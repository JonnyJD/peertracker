<?php

// License Information /////////////////////////////////////////////////////////////////////////////

/* 
 * PeerTracker - OpenSource BitTorrent Tracker
 * Revision - $Id: tracker.mysql.php 148 2009-11-16 23:18:28Z trigunflame $
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
	'db_prefix'         => 'pt_',         /* name prefixes for the PeerTracker tables */
	'db_persist'        => false,         /* use persistent connections if available. */
);

// Tracker Operations //////////////////////////////////////////////////////////////////////////////

// fatal error, stop execution
function tracker_error($error) 
{
	exit('d14:failure reason' . strlen($error) . ":{$error}e");
}

// MySQL Database API //////////////////////////////////////////////////////////////////////////////

// mysql core
class peertracker_mysql
{
	// database connection
	public $db;

	// connect to database
	public function __construct()
	{
		// attempt to connect
		if (
			// connection method
			!$this->db = (!$_SERVER['tracker']['db_persist'] ?
				// default connection
				mysql_connect(
					$_SERVER['tracker']['db_host'],
					$_SERVER['tracker']['db_user'],
					$_SERVER['tracker']['db_pass']
				) : 
				// persistent connection
				mysql_pconnect(
					$_SERVER['tracker']['db_host'],
					$_SERVER['tracker']['db_user'],
					$_SERVER['tracker']['db_pass']
				)
			// select database
			) OR !mysql_select_db(
				$_SERVER['tracker']['db_name'], $this->db
			)
		// error out if something happened
		) tracker_error(
			mysql_errno($this->db) . ' - ' . 
			mysql_error($this->db)
		);
	}

	// close database connection
	public function __destruct()
	{
		mysql_close($this->db);
	}
	
	// make sql safe
	public function escape_sql($sql)
	{
		return mysql_real_escape_string($sql, $this->db);
	}
	
	// query database
	public function query($sql)
	{
		return mysql_query($sql, $this->db);
	}
	
	// return one row
	public function fetch_once($sql)
	{
		// execute query
		$query = mysql_query($sql, $this->db) OR tracker_error(mysql_error($this->db));
		$result = mysql_fetch_row($query);

		// cleanup
		mysql_free_result($query);

		// return
		return $result;
	}

	// return compact peers
	public function peers_compact($sql, &$peers)
	{
		// fetch peers
		$query = mysql_query($sql, $this->db) OR tracker_error('failed to select compact peers');
		
		// build response
		while($peer = mysql_fetch_row($query)) $peers .= $peer[0];

		// cleanup
		mysql_free_result($query);
	}

	// return dictionary peers
	public function peers_dictionary($sql, &$response)
	{
		// fetch peers
		$query = mysql_query($sql, $this->db) OR tracker_error('failed to select peers');
		
		// dotted decimal string ip, 20-byte peer_id, integer port
		while($peer = mysql_fetch_row($query)) $response .= 'd2:ip' . strlen($peer[1]) . ":{$peer[1]}" . "7:peer id20:{$peer[0]}4:porti{$peer[2]}ee";

		// cleanup
		mysql_free_result($query);
	}

	// return dictionary peers without peer_id
	public function peers_dictionary_no_peer_id($sql, &$response)
	{
		// fetch peers
		$query = mysql_query($sql, $this->db) OR tracker_error('failed to select peers');
		
		// dotted decimal string ip, integer port
		while($peer = mysql_fetch_row($query)) $response .= 'd2:ip' . strlen($peer[0]) . ":{$peer[0]}4:porti{$peer[1]}ee";

		// cleanup
		mysql_free_result($query);
	}

	// full scrape of all torrents
	public function full_scrape($sql, &$response)
	{
		// fetch scrape
		$query = mysql_query($sql) OR tracker_error('unable to perform a full scrape');
		
		// 20-byte info_hash, integer complete, integer downloaded, integer incomplete
		while ($scrape = mysql_fetch_row($query)) $response .= "20:{$scrape[0]}d8:completei{$scrape[1]}e10:downloadedi0e10:incompletei{$scrape[2]}ee";

		// cleanup
		mysql_free_result($query);
	}
}

// MySQLi Database API /////////////////////////////////////////////////////////////////////////////

// mysqli core
class peertracker_mysqli
{
	// database connection
	public $db;

	// connect to database
	public function __construct()
	{
		// attempt to connect
		$this->db = new mysqli(
			// connection method
			(!$_SERVER['tracker']['db_persist'] ? 
				// default connection
				$_SERVER['tracker']['db_host'] : 
				// persistent connection
				'p:' . $_SERVER['tracker']['db_host']
			),
			$_SERVER['tracker']['db_user'],
			$_SERVER['tracker']['db_pass'],
			$_SERVER['tracker']['db_name']
		);

		// error out if something happened
		if ($this->db->connect_errno) tracker_error(
			$this->db->connect_errno . ' - ' . 
			$this->db->connect_error
		);
	}

	// close database connection
	public function __destruct()
	{
		$this->db->close();
	}

	// make sql safe
	public function escape_sql($sql)
	{
		return $this->db->real_escape_string($sql);
	}
	
	// query database
	public function query($sql)
	{
		return $this->db->query($sql);
	}

	// return one row
	public function fetch_once($sql)
	{
		// execute query
		$query = $this->db->query($sql) OR tracker_error($this->db->error);
		$result = $query->fetch_row();

		// cleanup
		$query->close();

		// return
		return $result;
	}

	// return compact peers
	public function peers_compact($sql, &$peers)
	{
		// fetch peers
		$query = $this->db->query($sql) OR tracker_error('failed to select compact peers');
		
		// build response
		while($peer = $query->fetch_row()) $peers .= $peer[0];

		// cleanup
		$query->close();
	}

	// return dictionary peers
	public function peers_dictionary($sql, &$response)
	{
		// fetch peers
		$query = $this->db->query($sql) OR tracker_error('failed to select peers');
		
		// dotted decimal string ip, 20-byte peer_id, integer port
		while($peer = $query->fetch_row()) $response .= 'd2:ip' . strlen($peer[1]) . ":{$peer[1]}" . "7:peer id20:{$peer[0]}4:porti{$peer[2]}ee";

		// cleanup
		$query->close();
	}

	// return dictionary peers without peer_id
	public function peers_dictionary_no_peer_id($sql, &$response)
	{
		// fetch peers
		$query = $this->db->query($sql) OR tracker_error('failed to select peers');
		
		// dotted decimal string ip, integer port
		while($peer = $query->fetch_row()) $response .= 'd2:ip' . strlen($peer[0]) . ":{$peer[0]}4:porti{$peer[1]}ee";

		// cleanup
		$query->close();
	}

	// full scrape of all torrents
	public function full_scrape($sql, &$response)
	{
		// fetch scrape
		$query = $this->db->query($sql) OR tracker_error('unable to perform a full scrape');
		
		// 20-byte info_hash, integer complete, integer downloaded, integer incomplete
		while ($scrape = $query->fetch_row()) $response .= "20:{$scrape[0]}d8:completei{$scrape[1]}e10:downloadedi0e10:incompletei{$scrape[2]}ee";

		// cleanup
		$query->close();
	}
}

// peertracker core
class peertracker
{
	// database api
	public static $api;

	// open database connection
	public static function open() 
	{
		// php version
		$php = PHP_VERSION;

		// establish database API
		self::$api = (
			// default connections
			!$_SERVER['tracker']['db_persist'] ? (
				// do we support mysqli?
				class_exists('mysqli') ? 
					// use the mysqli api
					new peertracker_mysqli() : 
					// use the mysql api
					new peertracker_mysql()
			// persistent connections
			) : (
				// do we support mysqli?
				class_exists('mysqli') && 
				// PHP is >= 5.3
				(($php[0]+0) > 5 || ($php[0]+0) == 5 && ($php[2]+0) >= 3) ?
					// use the mysqli api
					new peertracker_mysqli() : 
					// use the mysql api
					new peertracker_mysql()
			)
		);
	}

	// close database connection
	public static function close() 
	{
		// trigger __destruct()
		self::$api = null;
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
			$last = self::$api->fetch_once(
				// select last cleanup from tasks
				"SELECT value FROM `{$_SERVER['tracker']['db_prefix']}tasks` WHERE name='prune'"
			);

			// first clean cycle?
			if (($last[0] + 0) == 0) 
			{
				self::$api->query(
					// set tasks value prune to current unix timestamp
					"REPLACE INTO `{$_SERVER['tracker']['db_prefix']}tasks` VALUES('prune', {$time})"
				) OR tracker_error('could not perform maintenance');

				self::$api->query(
					// delete peers that have been idle too long
					"DELETE FROM `{$_SERVER['tracker']['db_prefix']}peers` WHERE updated < " .
					// idle length is announce interval x 2
					($time - ($_SERVER['tracker']['announce_interval'] * 2))
				) OR tracker_error('could not perform maintenance');
			}
			// prune idle peers
			elseif (($last[0] + $_SERVER['tracker']['announce_interval']) < $time)
			{
				self::$api->query(
					// set tasks value prune to current unix timestamp
					"UPDATE `{$_SERVER['tracker']['db_prefix']}tasks` SET value={$time} WHERE name='prune'"
				) OR tracker_error('could not perform maintenance');

				self::$api->query(
					// delete peers that have been idle too long
					"DELETE FROM `{$_SERVER['tracker']['db_prefix']}peers` WHERE updated < " .
					// idle length is announce interval x 2
					($time - ($_SERVER['tracker']['announce_interval'] * 2))
				) OR tracker_error('could not perform maintenance');
			}
		}
	}

	// insert new peer
	public static function new_peer()
	{
		// insert peer
		self::$api->query(
			// insert into the peers table
			"INSERT IGNORE INTO `{$_SERVER['tracker']['db_prefix']}peers` " .
			// table columns
			'(info_hash, peer_id, compact, ip, port, state, updated) ' .
			// 20-byte info_hash, 20-byte peer_id
			"VALUES ('{$_GET['info_hash']}', '{$_GET['peer_id']}', '" .
			// 6-byte compacted peer info
			self::$api->escape_sql(pack('Nn', ip2long($_GET['ip']), $_GET['port'])) . "', " .
			// dotted decimal string ip, integer port, integer state and unix timestamp updated
			"'{$_GET['ip']}', {$_GET['port']}, {$_SERVER['tracker']['seeding']}, " . time() . '); '
		) OR tracker_error('failed to add new peer data');
		exit;
	}

	// full peer update
	public static function update_peer()
	{
		// update peer
		self::$api->query(
			// update the peers table
			"UPDATE `{$_SERVER['tracker']['db_prefix']}peers` " . 
			// set the 6-byte compacted peer info
			"SET compact='" . self::$api->escape_sql(pack('Nn', ip2long($_GET['ip']), $_GET['port'])) .
			// dotted decimal string ip, integer port
			"', ip='{$_GET['ip']}', port={$_GET['port']}, " .
			// integer state and unix timestamp updated
			"state={$_SERVER['tracker']['seeding']}, updated=" . time() .
			// that matches the given info_hash and peer_id
			" WHERE info_hash='{$_GET['info_hash']}' AND peer_id='{$_GET['peer_id']}'"
		) OR tracker_error('failed to update peer data');
	}
	
	// update peers last access time
	public static function update_last_access()
	{
		// update peer
		self::$api->query(
			// set updated to the current unix timestamp
			"UPDATE `{$_SERVER['tracker']['db_prefix']}peers` SET updated=" . time() .
			// that matches the given info_hash and peer_id
			" WHERE info_hash='{$_GET['info_hash']}' AND peer_id='{$_GET['peer_id']}'"
		) OR tracker_error('failed to update peers last access');
	}

	// remove existing peer
	public static function delete_peer()
	{
		// delete peer
		self::$api->query(
			// delete a peer from the peers table
			"DELETE FROM `{$_SERVER['tracker']['db_prefix']}peers` " .
			// that matches the given info_hash and peer_id
			"WHERE info_hash='{$_GET['info_hash']}' AND peer_id='{$_GET['peer_id']}'"
		) OR tracker_error('failed to remove peer data');
	}

	// tracker event handling
	public static function event()
	{
		// execute peer select
		$pState = self::$api->fetch_once(
			// select a peer from the peers table
			"SELECT ip, port, state FROM `{$_SERVER['tracker']['db_prefix']}peers` " .
			// that matches the given info_hash and peer_id
			"WHERE info_hash='{$_GET['info_hash']}' AND peer_id='{$_GET['peer_id']}'"
		);

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
		$total = self::$api->fetch_once(
			// select a count of the number of peers that match the given info_hash
			"SELECT COUNT(*) FROM `{$_SERVER['tracker']['db_prefix']}peers` WHERE info_hash='{$_GET['info_hash']}'"
		) OR tracker_error('failed to select peer count');

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
			"FROM `{$_SERVER['tracker']['db_prefix']}peers` WHERE info_hash='{$_GET['info_hash']}'" .
			// less peers than requested, so return them all
			($total[0] <= $_GET['numwant'] ? ';' : 
				// if the total peers count is low, use SQL RAND
				($total[0] <= $_SERVER['tracker']['random_limit'] ?
					" ORDER BY RAND() LIMIT {$_GET['numwant']};" : 
					// use a more efficient but less accurate RAND
					" LIMIT {$_GET['numwant']} OFFSET " . 
					mt_rand(0, ($total[0]-$_GET['numwant']))
				)
			);
			
		// begin response
		$response = 'd8:intervali' . $_SERVER['tracker']['announce_interval'] . 
		            'e12:min intervali' . $_SERVER['tracker']['min_interval'] . 
		            'e5:peers';

		// compact announce
		if ($_GET['compact'])
		{
			// peers list
			$peers = '';
			
			// build response
			self::$api->peers_compact($sql, $peers);

			// 6-byte compacted peer info
			$response .= strlen($peers) . ':' . $peers;
		}
		// dictionary announce
		else
		{
			// list start
			$response .= 'l';

			// include peer_id
			if (!$_GET['no_peer_id']) self::$api->peers_dictionary($sql, $response); 
			// omit peer_id
			else self::$api->peers_dictionary_no_peer_id($sql, $response);

			// list end
			$response .= 'e';
		}

		// send response
		echo $response . 'e';

		// cleanup
		unset($peers);
	}

	// tracker scrape
	public static function scrape()
	{
		// scrape response
		$response = 'd5:filesd';

		// scrape info_hash
		if (isset($_GET['info_hash']))
		{
			// scrape
			$scrape = self::$api->fetch_once(
				// select total seeders and leechers
				'SELECT SUM(state=1), SUM(state=0) ' .
				// from peers
				"FROM `{$_SERVER['tracker']['db_prefix']}peers` " . 
				// that match info_hash
				"WHERE info_hash='" . self::$api->escape_sql($_GET['info_hash']) . "'"
			) OR tracker_error('unable to scrape the requested torrent');

			// 20-byte info_hash, integer complete, integer downloaded, integer incomplete
			$response .= "20:{$_GET['info_hash']}d8:completei" . ($scrape[0]+0) . 
			             'e10:downloadedi0e10:incompletei' . ($scrape[1]+0) . 'ee';
		}
		// full scrape
		else
		{
			// scrape
			$sql = 'SELECT ' .
				// info_hash, total seeders and leechers
				'info_hash, SUM(state=1), SUM(state=0) ' .
				// from peers
				"FROM `{$_SERVER['tracker']['db_prefix']}peers` " .
				// grouped by info_hash
				'GROUP BY info_hash';

			// build response
			self::$api->full_scrape($sql, $response);
		}

		// send response
		echo $response . 'ee';
	}

	// tracker statistics
	public static function stats()
	{
		// statistics
		$stats = self::$api->fetch_once(
			// select seeders and leechers
			'SELECT SUM(state=1), SUM(state=0), ' .
			// unique torrents
			'COUNT(DISTINCT info_hash) ' .
			// from peers
			"FROM `{$_SERVER['tracker']['db_prefix']}peers` "
		) OR tracker_error('failed to retrieve tracker statistics');
 
		// output format
		switch ($_GET['stats'])
		{
			// xml
			case 'xml':
				header('Content-Type: text/xml');
				echo '<?xml version="1.0" encoding="ISO-8859-1"?>' .
				     '<tracker version="$Id: tracker.mysql.php 148 2009-11-16 23:18:28Z trigunflame $">' .
				     '<peers>' . number_format($stats[0] + $stats[1]) . '</peers>' .
				     '<seeders>' . number_format($stats[0]) . '</seeders>' .
				     '<leechers>' . number_format($stats[1]) . '</leechers>' .
				     '<torrents>' . number_format($stats[2]) . '</torrents></tracker>';
				break;

			// json
			case 'json':
				header('Content-Type: text/javascript');
				echo '{"tracker":{"version":"$Id: tracker.mysql.php 148 2009-11-16 23:18:28Z trigunflame $",' .
				     '"peers": "' . number_format($stats[0] + $stats[1]) . '",' .
					 '"seeders":"' . number_format($stats[0]) . '",' .
					 '"leechers":"' . number_format($stats[1]) . '",' .
				     '"torrents":"' . number_format($stats[2]) . '"}}';
				break;

			// standard
			default:
				echo '<!doctype html><html><head><meta http-equiv="content-type" content="text/html; charset=UTF-8">' .
				     '<title>PeerTracker: $Id: tracker.mysql.php 148 2009-11-16 23:18:28Z trigunflame $</title>' .
					 '<body><pre>' . number_format($stats[0] + $stats[1]) . 
				     ' peers (' . number_format($stats[0]) . ' seeders + ' . number_format($stats[1]) .
				     ' leechers) in ' . number_format($stats[2]) . ' torrents</pre></body></html>';
		}
	}
}

?>