PeerTracker - Simple, Efficient and Fast BitTorent Tracker
--------------------------------------------------------------------------------------


What Do You Need?
--------------------------------------------------------------------------------------
* Requirements:
  * HTTP Web Server. Apache, Nginx, lighttpd etc., anything that supports PHP.
  * PHP 5+, Highly recommend the latest release or 5.3+, as it comes with SQLite3 
    integrated.
    * Note: PHP 4 was discontinued in 07', PHP 5 has been around for 5+ years.
    * If your host has not upgraded by now; Get Another Host, because they are failing 
     to do their job in keeping their servers software updated, hence ripping you off. 
  * Database Support. Must use one of the following.
    * SQLite3 via PHP 5.3
    * MySQL 4.1+, Highly recommend the latest release or 5.1+. (Coming Soon)
      * Note: MySQL 4.1 was discontinued in 06', MySQL 5+ has been around for 4+ years. 
    * PostgreSQL 8.0+, recommend the latest release or 8.4+. (Coming Soon)
    * txtSQL, Written entirely in PHP; performance is reflected in that. (Coming Soon)
  * Optional:
    * .htaccess & mod_rewrite suppport.
      * If you want to use /announce & /scrape without the .php file type extensions.


Which Database System Should You Use?
--------------------------------------------------------------------------------------
* SQLite3
  * Pros:
    * Comes integrated with PHP 5.3.
    * Fantastic speed when using simple queries.
    * Single database file, no network connection overhead.
  * Cons:
    * Not designed for heavily loaded multi-user environments. I have not yet 
      tested this database system in a Real World Environment that experiences 
      several thousand Requests/s, but it should work fine for the typical tracker.
  * Summary:
    * Use this if you have a relatively small to medium sized tracker, and/or 
      don't have access to any of the other more robust database servers.

* MySQL (Recommended), at least 4.1+, suggest 5.1+
  * Pros:
    * Performance is great all around.
    * Has been proven reliable and is production stable in multi-user environments.
    * Most common database used on the average non-corporate based website.
  * Cons:
    * Nothing significant as relates to it's use in PeerTracker.
  * Summary:
    * Use this database system if available. It's the most widely support database
      in shared hosting environments and any decent host offers them.

* PostgreSQL (Coming Soon)
  * Pros: TODO
  * Cons: TODO
  * Summary: TODO


Quick Install Guide
--------------------------------------------------------------------------------------
PeerTracker is packaged in standalone versions. Each version is contained in it's 
own designated directory labeled by it's respectively used database system.

For example, if you wanted to use MySQL for your tracker's database:
 1. upload ./help.php to your tracker's document directory.
 2. followed by uploading all of the files 'inside' of the ./mysql/ directory as well.
 3. edit the configuration section at the top of the tracker.mysql.php file.
 4. run ./help.php from your browser and install the tracker database
 5. remove ./help.php and you have now installed the MySQL edition of PeerTracker.

These same steps should be followed for whichever database system you choose to use.

Step by Step Install Guide
--------------------------------------------------------------------------------------
1. upload ./help.php to your tracker's document directory.
2. run the uploaded script from your site.
   * example: 
     * http://tracker.yoursite.com/help.php
3. check out the information, this will let you know what your server supports.
4. after deciding which database system you would like to use, upload the files 'inside'
   of the respectively named directory to your trackers top web accessible directory.
   * example:
     * http://tracker.yoursite.com/announce.php
     * http://tracker.yoursite.com/scrape.php
     * etc... 
5. edit the configuration file. it will contain all of the settings needed to run 
   the tracker, such as path to the database, host, user, pass, port etc. it will 
   be named according to the database being used.
   * example:
     * http://tracker.yoursite.com/tracker.sqlite3.php
     * http://tracker.yoursite.com/tracker.mysql.php
     * etc... 
6. included are .htaccess files, they help PeerTracker to support the typical url 
   format ie. http://tracker.your.site/announce (notice, no .php extension). not 
   all webservers fully support these files, either because they don't recognize 
   them or because they have them disabled; if you notice them causing any problems 
   just remove them, they're not necessary for successful tracker operation.
7. run the ./help.php file again, and proceed to install the tracker database.
   * example:
     * http://tracker.yoursite.com/help.php
8. delete ./help.php from your tracker's document directory.
9. finished tracker setup.
   * now, you can use the following url for tracking:
     * http://tracker.yoursite.com/announce
   * or the extended url, if your server doesnt support .htaccess files:
     * http://tracker.yoursite.com/announce.php 


SQLite3 Installation Notes
--------------------------------------------------------------------------------------
Make sure to give write permissions 0777 to the 'DB' database directory or whichever 
directory you chose to store the SQLite3 Database file.

This is Required for PeerTracker? to automate installation of database tables upon 
first use or after suffering a corruption. 


Important Links:
--------------------------------------------------------------------------------------
Development Website: http://code.google.com/p/peertracker/
Issue Tracker: http://code.google.com/p/peertracker/issues/list
Source Code Repository: http://peertracker.googlecode.com/svn/trunk/


Misc Credits:
--------------------------------------------------------------------------------------
The project icon is (to my knowledge) licensed under the Creative Commons, 
Attribution-Noncommercial-No Derivative Works 3.0. Whomever designed it, 
feel free to contact me and I will give appropriate credit to said person.