<?php
/*
Plugin Name: FBAlbum for Wordpress
Plugin URI: 
Description: Wordpress plugin for displaying facebook photo album on wordpress site.
Version: 1.0
Author: binnash
Author URI: http://binnash.blogspot.com
License : GPLv2
*/
//Direct access to this file is not permitted
if (realpath (__FILE__) === realpath ($_SERVER["SCRIPT_FILENAME"]))
    exit("Do not access this file directly.");

require_once ( ABSPATH . WPINC . '/pluggable.php' );
require_once ( ABSPATH . WPINC . '/registration.php' );
require_once  ('class-binnash-fbalbum.php');
define("FBALBUM4WP_VER", "1.0");
define('FBALBUM4WP_FOLDER', dirname(plugin_basename(__FILE__)));
define('FBALBUM4WP_URL', WP_PLUGIN_URL. '/'. FBALBUM4WP_FOLDER); 
define('FBALBUM4WP_DIR', WP_PLUGIN_DIR .'/'. FBALBUM4WP_FOLDER);
$FbAlbum4Wordpress = new FbAlbum4Wordpress();
