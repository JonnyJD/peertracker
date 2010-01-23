<?php

// License Information /////////////////////////////////////////////////////////////////////////////

/* 
 * PeerTracker - OpenSource BitTorrent Tracker
 * Revision - $Id: tracker.postgresql.php 149 2009-11-16 23:20:06Z trigunflame $
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
	'open_tracker'      => true,          /* track anything announced to it */
	'announce_interval' => 1800,          /* how often client will send requests */
	'min_interval'      => 900,           /* how often client can force requests */
	'default_peers'     => 50,            /* default # of peers to announce */
	'max_peers'         => 100,           /* max # of peers to announce */

	// advanced tracker options
	'external_ip'       => true,          /* allow client to specify ip address */
	'force_compact'     => false,         /* force compact announces only */
	'full_scrape'       => false,         /* allow scrapes without info_hash */
	'random_limit'      => 500,           /* if peers > #, use alternate SQL RAND() */
	'clean_idle_peers'  => 10,            /* tweaks % of time tracker attempts idle peer removal */
	                                      /* if you have a busy tracker, you may adjust this */
	                                      /* example: 10 = 10%, 20 = 5%, 50 = 2%, 100 = 1% */
	// database options
	'db_host'           => 'localhost',   /* ip or hostname to mysql server */
	'db_user'           => 'root',        /* username used to connect to mysql */
	'db_pass'           => '',            /* password used to connect to mysql */
	'db_name'           => 'peertracker', /* name of the PeerTracker database */

	// advanced database options
	'db_prefix'         => ''          /* name prefixes for the PeerTracker tables */
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
		self::$db = pg_connect(
			"host='{$_SERVER['tracker']['db_host']}' dbname='{$_SERVER['tracker']['db_name']}' " . 
			"user='{$_SERVER['tracker']['db_user']}' password='{$_SERVER['tracker']['db_pass']}'"
		) OR tracker_error('database error - ' . pg_last_error(self::$db));
	}
	
	// close database connection
	public static function close() 
	{
		pg_close(self::$db);
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
			$last = pg_fetch_row(pg_query(self::$db,
				// select last cleanup from tasks
				"SELECT value FROM {$_SERVER['tracker']['db_prefix']}tasks WHERE name='prune';"
			));
			
			// first clean cycle?
			if (($last[0] + 0) == 0) 
			{
				pg_query(self::$db, 
					// set tasks value prune to current unix timestamp
					"INSERT INTO {$_SERVER['tracker']['db_prefix']}tasks VALUES('prune', {$time})"
				) OR tracker_error('could not perform maintenance');

				pg_query(self::$db, 
					// delete peers that have been idle too long
					"DELETE FROM {$_SERVER['tracker']['db_prefix']}peers WHERE updated < " .
					// idle length is announce interval x 2
					($time - ($_SERVER['tracker']['announce_interval'] * 2)) . ';'
				) OR tracker_error('could not perform maintenance');
			}
			// prune idle peers
			elseif (($last[0] + $_SERVER['tracker']['announce_interval']) < $time)
			{
				pg_query(self::$db, 
					// set tasks value prune to current unix timestamp
					"UPDATE {$_SERVER['tracker']['db_prefix']}tasks SET value={$time} WHERE name='prune';"
				) OR tracker_error('could not perform maintenance');

				pg_query(self::$db, 
					// delete peers that have been idle too long
					"DELETE FROM {$_SERVER['tracker']['db_prefix']}peers WHERE updated < " .
					// idle length is announce interval x 2
					($time - ($_SERVER['tracker']['announce_interval'] * 2)) . ';'
				) OR tracker_error('could not perform maintenance');
			}
		}
	}
	
	// insert new peer
	public static function new_peer()
	{
		// insert peer
		pg_query(self::$db, 
			// insert into the peers table
			"INSERT INTO {$_SERVER['tracker']['db_prefix']}peers " .
			// table columns
			'(info_hash, peer_id, compact, ip, port, state, updated) ' .
			// 20-byte info_hash, 20-byte peer_id
			"VALUES (E'{$_GET['info_hash']}'::bytea, E'{$_GET['peer_id']}'::bytea, " .
			// 6-byte compacted peer info
			"E'" . pg_escape_bytea(self::$db, pack('Nn', ip2long($_GET['ip']), $_GET['port'])) .
			// dotted decimal string ip, integer port
			"'::bytea, '{$_GET['ip']}', {$_GET['port']}, {$_SERVER['tracker']['seeding']}, " . time() . '); '
		) OR tracker_error('failed to add new peer data');
	}
	
	// full peer update
	public static function update_peer()
	{
		// update peer
		pg_query(self::$db, 
			// update the peers table
			"UPDATE {$_SERVER['tracker']['db_prefix']}peers " . 
			// set the 6-byte compacted peer info
			"SET compact=E'" . pg_escape_bytea(self::$db, pack('Nn', ip2long($_GET['ip']), $_GET['port'])) .
			// dotted decimal string ip, integer port
			"'::bytea, ip='{$_GET['ip']}', port={$_GET['port']}, " .
			// integer state and unix timestamp updated
			"state={$_SERVER['tracker']['seeding']}, updated=" . time() .
			// that matches the given info_hash and peer_id
			"WHERE info_hash=E'{$_GET['info_hash']}'::bytea AND peer_id=E'{$_GET['peer_id']}'::bytea;"
		) OR tracker_error('failed to update peer data');
	}
	
	// update peers last access time
	public static function update_last_access()
	{
		// update peer
		pg_query(self::$db, 
			// update the peers table
			"UPDATE {$_SERVER['tracker']['db_prefix']}peers " . 
			// set updated to the current unix timestamp
			'SET updated=' . time() .
			// that matches the given info_hash and peer_id
			" WHERE info_hash=E'{$_GET['info_hash']}'::bytea AND peer_id=E'{$_GET['peer_id']}'::bytea;"
		) OR tracker_error('failed to update peers last access');
	}
	
	// remove existing peer
	public static function delete_peer()
	{
		// delete peer
		pg_query(self::$db, 
			// delete a peer from the peers table
			"DELETE FROM {$_SERVER['tracker']['db_prefix']}peers " .
			// that matches the given info_hash and peer_id
			"WHERE info_hash=E'{$_GET['info_hash']}'::bytea AND peer_id=E'{$_GET['peer_id']}'::bytea;"
		) OR tracker_error('failed to remove peer data');
	}
	
	// tracker event handling
	public static function event()
	{
		// execute peer select
		$pState = pg_fetch_row(pg_query(self::$db,
			// select a peer from the peers table
			"SELECT ip, port, state FROM {$_SERVER['tracker']['db_prefix']}peers " .
			// that matches the given info_hash and peer_id
			"WHERE info_hash=E'{$_GET['info_hash']}'::bytea AND peer_id=E'{$_GET['peer_id']}'::bytea;"
		));
		
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
		$total = pg_fetch_row(pg_query(self::$db,
			// select a count of the number of peers that match the given info_hash
			"SELECT COUNT(*) FROM {$_SERVER['tracker']['db_prefix']}peers WHERE info_hash=E'{$_GET['info_hash']}'::bytea;"
		)) OR tracker_error('failed to select peer count');
		
		// select
		$sql = 'SELECT ' . 
			// 6-byte compacted peer info
			($_GET['compact'] ? 'compact ' :
			// 20-byte peer_id
			(!$_GET['no_peer_id'] ? 'peer_id, ' : '') .
			// dotted decimal string ip, integer port
			'ip, port '
			) .
			// from peers table matching info_hash
			"FROM {$_SERVER['tracker']['db_prefix']}peers WHERE info_hash=E'{$_GET['info_hash']}'::bytea" .
			// less peers than requested, so return them all
			($total[0] <= $_GET['numwant'] ? ';' : 
				// if the total peers count is low, use SQL RAND
				($total[0] <= $_SERVER['tracker']['random_limit'] ?
					" ORDER BY RANDOM() LIMIT {$_GET['numwant']};" : 
					// use a more efficient but less accurate RAND
					" LIMIT {$_GET['numwant']} OFFSET " . 
					mt_rand(0, ($total[0]-$_GET['numwant'])) . ';'
				)
			);
			
		// begin response
		$response = 'd8:intervali' . $_SERVER['tracker']['announce_interval'] . 
		            'e12:min intervali' . $_SERVER['tracker']['min_interval'] . 
		            'e5:peers';
					
		// execute peer selection
		$query = pg_query(self::$db, $sql) OR tracker_error('failed to select peers');

		// compact announce
		if ($_GET['compact'])
		{
			// peers list
			$peers = '';
			
			// build response
			while ($peer = pg_fetch_row($query)) $peers .= pg_unescape_bytea($peer[0]);

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
				while ($p = pg_fetch_row($query)) $response .= 'd2:ip' . strlen($p[1]) . ":{$p[1]}" . "7:peer id20:{$p[0]}4:porti{$p[2]}ee";
			}
			// omit peer_id
			else
			{
				// dotted decimal string ip, integer port
				while ($p = pg_fetch_row($query)) $response .= 'd2:ip' . strlen($p[0]) . ":{$p[0]}4:porti{$p[1]}ee";
			}

			// list end
			$response .= 'e';
		}
		
		// send response
		echo $response . 'e';

		// cleanup
		pg_free_result($query);
		unset($peers);
	}
	
	// scrape info_hash
	public function scrape()
	{
		// scrape response
		$response = 'd5:filesd';

		// scrape info_hash
		if (isset($_GET['info_hash']))
		{
			// scrape
			$scrape = pg_fetch_row(pg_query(self::$db,
				// select total seeders
				'SELECT SUM(CASE WHEN state=1 THEN 1 ELSE 0 END), ' .
				// and leechers
				'SUM(CASE WHEN state=0 THEN 1 ELSE 0 END) ' .
				// from peers
				"FROM {$_SERVER['tracker']['db_prefix']}peers " . 
				// that match info_hash
				"WHERE info_hash=E'" . pg_escape_bytea(self::$db, ($_GET['info_hash'])) . "'::bytea;"
			));

			// 20-byte info_hash, integer complete, integer downloaded, integer incomplete
			$response .= "20:{$_GET['info_hash']}d8:completei" . ($scrape[0]+0) . 
			             'e10:downloadedi0e10:incompletei' . ($scrape[1]+0) . 'ee';
		}
		// full scrape
		else
		{
			// scrape
			$query = pg_query(self::$db,
				// select total seeders
				'SELECT info_hash, SUM(CASE WHEN state=1 THEN 1 ELSE 0 END), ' .
				// and leechers
				'SUM(CASE WHEN state=0 THEN 1 ELSE 0 END) ' .
				// from peers grouped by info_hash
				"FROM {$_SERVER['tracker']['db_prefix']}peers GROUP BY info_hash;"
			) OR tracker_error('unable to perform a full scrape');
			
			// 20-byte info_hash, integer complete, integer downloaded, integer incomplete
			while ($scrape = pg_fetch_row($query)) $response .= "20:{$scrape[0]}d8:completei{$scrape[1]}e10:downloadedi0e10:incompletei{$scrape[2]}ee";
			
			// cleanup
			pg_free_result($query);
		}

		// send response
		echo $response . 'ee';
	}
	
	// tracker statistics
	public static function stats()
	{
		// statistics
		$stats = pg_fetch_row(pg_query(self::$db,
			// select total seeders
			'SELECT SUM(CASE WHEN state=1 THEN 1 ELSE 0 END), ' .
			// and leechers
			'SUM(CASE WHEN state=0 THEN 1 ELSE 0 END), ' .
			// unique torrents
			'COUNT(DISTINCT info_hash) ' .
			// from peers
			"FROM {$_SERVER['tracker']['db_prefix']}peers;"
		)) OR tracker_error('failed to retrieve tracker statistics');
		
		// output format
		switch ($_GET['stats'])
		{
			// xml
			case 'xml':
				header('Content-Type: text/xml');
				echo '<?xml version="1.0" encoding="ISO-8859-1"?>' .
				     '<tracker version="$Id: tracker.postgresql.php 149 2009-11-16 23:20:06Z trigunflame $">' .
				     '<peers>' . number_format($stats[0] + $stats[1]) . '</peers>' .
				     '<seeders>' . number_format($stats[0]) . '</seeders>' .
				     '<leechers>' . number_format($stats[1]) . '</leechers>' .
				     '<torrents>' . number_format($stats[2]) . '</torrents></tracker>';
				break;

			// json
			case 'json':
				header('Content-Type: text/javascript');
				echo '{"tracker":{"version":"$Id: tracker.postgresql.php 149 2009-11-16 23:20:06Z trigunflame $",' .
				     '"peers": "' . number_format($stats[0] + $stats[1]) . '",' .
					 '"seeders":"' . number_format($stats[0]) . '",' .
					 '"leechers":"' . number_format($stats[1]) . '",' .
				     '"torrents":"' . number_format($stats[2]) . '"}}';
				break;

			// standard
			default:
				echo '<!doctype html><html><head><meta http-equiv="content-type" content="text/html; charset=UTF-8">' .
				     '<title>PeerTracker: $Id: tracker.postgresql.php 149 2009-11-16 23:20:06Z trigunflame $</title>' .
					 '<body><pre>' . number_format($stats[0] + $stats[1]) . 
				     ' peers (' . number_format($stats[0]) . ' seeders + ' . number_format($stats[1]) .
				     ' leechers) in ' . number_format($stats[2]) . ' torrents</pre></body></html>';
		}
	}
}

?>