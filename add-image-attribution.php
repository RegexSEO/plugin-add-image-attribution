<?php
/*
Plugin Name: Add Image Attribution 
Plugin URI: https://regexseo.com/
Description: Plugin to attribution in images. 
Version: 1.2
Author: Shubh Sheth
Author URI: https://regexseo.com/
Text Domain: regexseo
*/


// Media Fields Handler
include(plugin_dir_path(__FILE__) . 'includes/attribution-image-fields.php');

// Frontend Content Updater
include(plugin_dir_path(__FILE__) . 'includes/attribution-update-content.php');
