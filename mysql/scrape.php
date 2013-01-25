<?php

// License Information /////////////////////////////////////////////////////////////////////////////

/* 
 * PeerTracker - OpenSource BitTorrent Tracker
 * Revision - $Id: scrape.php 125 2009-10-29 01:02:21Z trigunflame $
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

// Enviroment Runtime //////////////////////////////////////////////////////////////////////////////

// error level
error_reporting(E_ERROR | E_PARSE);
//error_reporting(E_ALL & ~E_WARNING);
//error_reporting(E_ALL | E_STRICT | E_DEPRECATED);

// ignore disconnects
ignore_user_abort(true);

// load tracker core
require './tracker.mysql.php';

// Verify Request //////////////////////////////////////////////////////////////////////////////////

// tracker statistics
if (isset($_GET['stats'])) 
{
	// display stats
	peertracker::open();
	peertracker::stats();
	peertracker::close();
	exit;
}

// 20-bytes - info_hash
// sha-1 hash of torrent being tracked
if (!isset($_GET['info_hash']) || strlen($_GET['info_hash']) != 20)
{
	// full scrape disabled
	if (!$_SERVER['tracker']['full_scrape']) exit;
	// full scrape enabled
	else unset($_GET['info_hash']);
}

// Handle Request //////////////////////////////////////////////////////////////////////////////////

// strip info_hash & peer_id
if (get_magic_quotes_gpc()) stripslashes($_GET['info_hash']); 

// scrape
peertracker::open();
peertracker::scrape();
peertracker::close();

?>