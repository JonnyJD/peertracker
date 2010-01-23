<?php

// License Information /////////////////////////////////////////////////////////////////////////////

/* 
 * PeerTracker - OpenSource BitTorrent Tracker
 * Revision - $Id: tracker.sqlite3.php 148 2009-11-16 23:18:28Z trigunflame $
 * Copyright (C) 2009 PeerTracker Team
 *
 * PeerTracker is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * PeerTracker is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with PeerTracker.  If not, see <http://www.gnu.org/licenses/>.
 */

// Configuration ///////////////////////////////////////////////////////////////////////////////////

// tracker state
$_SERVER['tracker'] = array(
	// general tracker options
	'open_tracker'      => true,    /* track anything announced to it */
	'announce_interval' => 1800,    /* how often client will send requests */
	'min_interval'      => 900,     /* how often client can force requests */
	'default_peers'     => 50,      /* default # of peers to announce */
	'max_peers'         => 100,     /* max # of peers to announce */

	// advanced tracker options
	'external_ip'       => true,    /* allow client to specify ip address */
	'force_compact'     => false,   /* force compact announces only */
	'full_scrape'       => false,   /* allow scrapes without info_hash */
	'random_limit'      => 500,     /* if peers > #, use alternate SQL RANDOM() */
	'clean_idle_peers'  => 10,      /* tweaks % of time tracker attempts idle peer removal, */
	                                /* if you have a busy tracker, you may adjust this */
	                                /* example: 10 = 10%, 20 = 5%, 50 = 2%, 100 = 1% */
	// database options
	'db_path' => './db/tracker.db' /* file path to the SQLite3 database*/
);

// Tracker Operations //////////////////////////////////////////////////////////////////////////////

// fatal error, stop execution
function tracker_error($error) 
{
	exit('d14:failure reason' . strlen($error) . ":{$error}e");
}

// SQLite3 Tracker Database ////////////////////////////////////////////////////////////////////////

// peertracker core
class peertracker
{
	// database connection
	public static $db;

	// open database connection
	public static function open() 
	{
		// attempt to connect
		try { self::$db = new SQLite3($_SERVER['tracker']['db_path'], SQLITE3_OPEN_READWRITE); }
		// database not writable or does not exist
		catch (Exception $e)
		{
			// try and remove the file if one exist, it may be corrupted
			if (file_exists($_SERVER['tracker']['db_path'])) @unlink($_SERVER['tracker']['db_path']);

			// attempt creation
			try { self::$db = new SQLite3($_SERVER['tracker']['db_path']); }
			// insufficient write permissions are the most likely cause
			catch (Exception $e)
			{
				// relay the specific error message
				tracker_error($e->getMessage() . ' - verify chmod 0777 on db directory');
			}

			// create database tables
			self::$db->exec(
				// begin query transaction
				'BEGIN TRANSACTION; ' .
				// create peers table
				'CREATE TABLE IF NOT EXISTS peers ' .
					'(info_hash BLOB, peer_id BLOB, compact BLOB, ip TEXT, ' .
					'port INTEGER DEFAULT 0, state INTEGER DEFAULT 0, ' .
					'updated INTEGER DEFAULT 0); ' .
				// create tasks table
				'CREATE TABLE IF NOT EXISTS tasks' .
					'(name TEXT, value INTEGER DEFAULT 0); ' .
				// create index on info_hash & peer_id
				'CREATE UNIQUE INDEX IF NOT EXISTS i0 ' .
					'ON peers(info_hash, peer_id); ' .
				// end transaction and commit
				'COMMIT;'
			) OR tracker_error('failed to create database tables');
		}

		// tweak sqlite performance
		self::$db->exec(
			// disable synchronous updates
			'PRAGMA synchronous = OFF; ' . 
			// write journal files to memory
			'PRAGMA journal_mode = MEMORY; ' .
			// write temporary tables,indices to memory
			'PRAGMA temp_store = MEMORY;'
		) OR tracker_error('failed to set sqlite3 options');
	}

	// close database connection
	public static function close() 
	{
		self::$db->close(); 
	}

	// database cleanup
	public static function clean() 
	{
		// run cleanup once per announce interval
		// check 'clean_idle_peers'% of the time to avoid excess queries
		if (mt_rand(1, $_SERVER['tracker']['clean_idle_peers']) == 1)
		{
			// unix timestamp
			$time = time();
			
			// fetch last cleanup time
			if (($last = self::$db->querySingle(
				// select last cleanup from tasks
				"SELECT value FROM tasks WHERE name='prune';"
			) + 0) == 0) 
			{
				self::$db->exec(
					// begin query transaction
					'BEGIN TRANSACTION; ' .
					// set tasks value prune to current unix timestamp
					"INSERT OR REPLACE INTO tasks VALUES('prune', {$time}); " .
					// delete peers that have been idle too long
					'DELETE FROM peers WHERE updated < ' .
					// idle length is announce interval x 2
					($time - ($_SERVER['tracker']['announce_interval'] * 2)) . '; ' .
					// end transaction and commit
					'COMMIT;'
				) OR tracker_error('could not perform maintenance');
			}
			// prune idle peers
			elseif (($last + $_SERVER['tracker']['announce_interval']) < $time)
			{
				self::$db->exec(
					// begin query transaction
					'BEGIN TRANSACTION; ' .
					// set tasks value prune to current unix timestamp
					"UPDATE tasks SET value={$time} WHERE name='prune'; " .
					// delete peers that have been idle too long
					'DELETE FROM peers WHERE updated < ' .
					// idle length is announce interval x 2
					($time - ($_SERVER['tracker']['announce_interval'] * 2)) . '; ' .
					// end transaction and commit
					'COMMIT;'
				) OR tracker_error('could not perform maintenance');
			}
		}
	}

	// insert new peer
	public static function new_peer()
	{
		// build peer query
		$peer = self::$db->prepare(
			// insert into the peers table
			'INSERT OR IGNORE INTO peers (info_hash, peer_id, compact, ip, port, state, updated) ' .
			// 20-byte info_hash, 20-byte peer_id, 6-byte compacted peer info
			'VALUES (:info_hash, :peer_id, :compact, ' .
			// dotted decimal string ip, integer port, integer state and unix timestamp updated
			"'{$_GET['ip']}', {$_GET['port']}, {$_SERVER['tracker']['seeding']}, " . time() . '); '
		);

		// assign binary data
		$peer->bindValue(':info_hash', $_GET['info_hash'], SQLITE3_BLOB);
		$peer->bindValue(':peer_id', $_GET['peer_id'], SQLITE3_BLOB);
		$peer->bindValue(':compact', pack('Nn', ip2long($_GET['ip']), $_GET['port']), SQLITE3_BLOB);

		// execute peer insert
		if ($peer->execute()) $peer->close(); else tracker_error('failed to add new peer data');
	}

	// full peer update
	public static function update_peer()
	{
		// build peer query
		$peer = self::$db->prepare(
			// update the 6-byte compacted peer info, dotted decimal string ip, integer port
			"UPDATE peers SET compact=:compact, ip='{$_GET['ip']}', port={$_GET['port']}, " .
			// integer state and unix timestamp updated
			"state={$_SERVER['tracker']['seeding']}, updated=" . time() .
			// that matches the given info_hash and peer_id
			" WHERE info_hash=:info_hash AND peer_id=:peer_id;"
		);

		// assign binary data
		$peer->bindValue(':info_hash', $_GET['info_hash'], SQLITE3_BLOB);
		$peer->bindValue(':peer_id', $_GET['peer_id'], SQLITE3_BLOB);
		$peer->bindValue(':compact', pack('Nn', ip2long($_GET['ip']), $_GET['port']), SQLITE3_BLOB);

		// execute peer update
		if ($peer->execute()) $peer->close(); else tracker_error('failed to update peer data');
	}

	// update peers last access time
	public static function update_last_access()
	{
		// build peer query
		$peer = self::$db->prepare(
			// set updated to the current unix timestamp
			'UPDATE peers SET updated=' . time() .
			// that matches the given info_hash and peer_id
			' WHERE info_hash=:info_hash AND peer_id=:peer_id;'
		);

		// assign binary data
		$peer->bindValue(':info_hash', $_GET['info_hash'], SQLITE3_BLOB);
		$peer->bindValue(':peer_id', $_GET['peer_id'], SQLITE3_BLOB);

		// execute peer update
		if ($peer->execute()) $peer->close(); else tracker_error('failed to update peers last access');
	}

	// remove existing peer
	public static function delete_peer()
	{
		// build peer query
		$peer = self::$db->prepare(
			// delete a peer from the peers table that matches the given info_hash and peer_id
			'DELETE FROM peers WHERE info_hash=:info_hash AND peer_id=:peer_id;'
		);

		// assign binary data
		$peer->bindValue(':info_hash', $_GET['info_hash'], SQLITE3_BLOB);
		$peer->bindValue(':peer_id', $_GET['peer_id'], SQLITE3_BLOB);

		// execute peer delete
		if ($peer->execute()) $peer->close(); else tracker_error('failed to remove peer data');
	}

	// tracker event handling
	public static function event()
	{
		// build peer query
		$peer = self::$db->prepare(
			// select a peer from the peers table that matches the given info_hash and peer_id
			'SELECT ip, port, state FROM peers WHERE info_hash=:info_hash AND peer_id=:peer_id;'
		);

		// assign binary data
		$peer->bindValue(':info_hash', $_GET['info_hash'], SQLITE3_BLOB);
		$peer->bindValue(':peer_id', $_GET['peer_id'], SQLITE3_BLOB);

		// execute peer select & cleanup 
		$success = $peer->execute() OR tracker_error('failed to select peer data');
		$pState = $success->fetchArray(SQLITE3_NUM);
		$success->finalize(); 
		$peer->close();

		// process tracker event
		switch ((isset($_GET['event']) ? $_GET['event'] : false))
		{
			// client gracefully exited
			case 'stopped':
				// remove peer
				if (isset($pState[2])) self::delete_peer();
				break;
			// client completed download
			case 'completed':
				// force seeding status
				$_SERVER['tracker']['seeding'] = 1;
			// client started download
			case 'started':
			// client continuing download
			default:
				// new peer
				if (!isset($pState[2])) self::new_peer();
				// peer status
				elseif (
					// check that ip addresses match
					$pState[0] != $_GET['ip'] || 
					// check that listening ports match
					($pState[1]+0) != $_GET['port'] ||
					// check whether seeding status match
					($pState[2]+0) != $_SERVER['tracker']['seeding']
				) self::update_peer();
				// update time
				else self::update_last_access();
		}
	}

	// tracker peer list
	public static function peers() 
	{
		// fetch peer total
		$peer = self::$db->prepare(
			// select a count of the number of peers that match the given info_hash
			'SELECT COUNT(*) FROM (SELECT 1 FROM peers WHERE info_hash=:info_hash);'
		);

		// assign binary data
		$peer->bindValue(':info_hash', $_GET['info_hash'], SQLITE3_BLOB);

		// execute peer row count & cleanup
		$success = $peer->execute() OR tracker_error('failed to select peer count');
		$total = $success->fetchArray(SQLITE3_NUM);
		$success->finalize(); 
		$peer->close();
		
		// prepare query
		$peer = self::$db->prepare(
			// select
			'SELECT ' . 
				// 6-byte compacted peer info
				($_GET['compact'] ? 'compact ' :
					// 20-byte peer_id
					(!$_GET['no_peer_id'] ? 'peer_id, ' : '') .
					// dotted decimal string ip, integer port
					'ip, port '
				) .
			// from peers table matching info_hash
			'FROM peers WHERE info_hash=:info_hash' .
			// less peers than requested, so return them all
			($total[0] <= $_GET['numwant'] ? ';' : 
				// if the total peers count is low, use SQL RANDOM
				($total[0] <= $_SERVER['tracker']['random_limit'] ?
					" ORDER BY RANDOM() LIMIT {$_GET['numwant']};" : 
					// use a more efficient but less accurate RANDOM
					" LIMIT {$_GET['numwant']} OFFSET " . 
					mt_rand(0, ($total[0]-$_GET['numwant'])) . ';'
				)
			)
		);
		
		// begin response
		$response = 'd8:intervali' . $_SERVER['tracker']['announce_interval'] . 
		            'e12:min intervali' . $_SERVER['tracker']['min_interval'] . 
		            'e5:peers';
					
		// assign binary data
		$peer->bindValue(':info_hash', $_GET['info_hash'], SQLITE3_BLOB);

		// execute peer selection
		$success = $peer->execute() OR tracker_error('failed to select peers');

		// compact announce
		if ($_GET['compact'])
		{
			// peers list
			$peers = '';
			
			// build response
			while ($p = $success->fetchArray(SQLITE3_NUM)) $peers .= $p[0];
			
			// 6-byte compacted peer info
			$response .= strlen($peers) . ':' . $peers;
		}
		// dictionary announce
		else
		{
			// list start
			$response .= 'l';

			// include peer_id
			if (!$_GET['no_peer_id'])
			{
				// dotted decimal string ip, 20-byte peer_id, integer port
				while ($p = $success->fetchArray(SQLITE3_NUM)) $response .= 'd2:ip' . strlen($p[1]) . ":{$p[1]}" . "7:peer id20:{$p[0]}4:porti{$p[2]}ee";
			}
			// omit peer_id
			else
			{
				// dotted decimal string ip, integer port
				while ($p = $success->fetchArray(SQLITE3_NUM)) $response .= 'd2:ip' . strlen($p[0]) . ":{$p[0]}4:porti{$p[1]}ee";
			}

			// list end
			$response .= 'e';
		}

		// send response
		echo $response . 'e';

		// cleanup
		$success->finalize(); 
		$peer->close();
		unset($peers);
	}

	// scrape info_hash
	public function scrape()
	{
		// begin response
		$response = 'd5:filesd';

		// scrape info_hash
		if (isset($_GET['info_hash']))
		{
			// prepare query
			$query = self::$db->prepare(
				// total seeders and leechers
				'SELECT SUM(state=1), SUM(state=0) ' . 
				// that match info_hash
				'FROM peers WHERE info_hash=:info_hash;'
			);

			// assign binary data
			$query->bindValue(':info_hash', $_GET['info_hash'], SQLITE3_BLOB);

			// scrape
			$success = $query->execute() OR tracker_error('unable to scrape the requested torrent');
			$scrape = $success->fetchArray(SQLITE3_NUM);
			
			// 20-byte info_hash, integer complete, integer downloaded, integer incomplete
			$response .= "20:{$_GET['info_hash']}d8:completei" . ($scrape[0]+0) . 
			             'e10:downloadedi0e10:incompletei' . ($scrape[1]+0) . 'ee';

			// cleanup
			$success->finalize(); 
			$query->close();
		}
		// full scrape
		else
		{
			// scrape
			$query = self::$db->query(
				// select info_hash, total seeders and leechers
				'SELECT info_hash, SUM(state=1), SUM(state=0) ' .
				// from peers grouped by info_hash
				'FROM peers GROUP BY info_hash;'
			) OR tracker_error('unable to perform a full scrape');
			
			// 20-byte info_hash, integer complete, integer downloaded, integer incomplete
			while ($scrape = $query->fetchArray(SQLITE3_NUM)) $response .= "20:{$scrape[0]}d8:completei{$scrape[1]}e10:downloadedi0e10:incompletei{$scrape[2]}ee";
			
			// cleanup
			$query->finalize();
		}

		// send response
		echo $response . 'ee';
	}

	// tracker statistics
	public static function stats()
	{
		// get stats
		$query = self::$db->query(
			// select seeders and leechers
			'SELECT SUM(state=1), SUM(state=0), ' .
			// unique torrents from peers
			'COUNT(DISTINCT info_hash) FROM peers;'
		) OR tracker_error('failed to retrieve tracker statistics');
		$stats = $query->fetchArray(SQLITE3_NUM);

		// output format
		switch ($_GET['stats'])
		{
			// xml
			case 'xml':
				header('Content-Type: text/xml');
				echo '<?xml version="1.0" encoding="ISO-8859-1"?>' .
				     '<tracker version="$Id: tracker.sqlite3.php 148 2009-11-16 23:18:28Z trigunflame $">' .
				     '<peers>' . number_format($stats[0] + $stats[1]) . '</peers>' .
				     '<seeders>' . number_format($stats[0]) . '</seeders>' .
				     '<leechers>' . number_format($stats[1]) . '</leechers>' .
				     '<torrents>' . number_format($stats[2]) . '</torrents></tracker>';
				break;

			// json
			case 'json':
				header('Content-Type: text/javascript');
				echo '{"tracker":{"version":"$Id: tracker.sqlite3.php 148 2009-11-16 23:18:28Z trigunflame $",' .
				     '"peers": "' . number_format($stats[0] + $stats[1]) . '",' .
				     '"seeders":"' . number_format($stats[0]) . '",' .
				     '"leechers":"' . number_format($stats[1]) . '",' .
				     '"torrents":"' . number_format($stats[2]) . '"}}';
				break;

			// standard
			default:
				echo '<!doctype html><html><head><meta http-equiv="content-type" content="text/html; charset=UTF-8">' .
				     '<title>PeerTracker: $Id: tracker.sqlite3.php 148 2009-11-16 23:18:28Z trigunflame $</title>' .
				     '<body><pre>' . number_format($stats[0] + $stats[1]) . 
				     ' peers (' . number_format($stats[0]) . ' seeders + ' . number_format($stats[1]) .
				     ' leechers) in ' . number_format($stats[2]) . ' torrents</pre></body></html>';
		}

		// cleanup
		$query->finalize();
	}
}

?>