
<?php
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
?>
