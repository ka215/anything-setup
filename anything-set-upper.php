<?php
/*
  Plugin Name: Anything Set Upper
  Plugin URI: http://ka2.org/
  Description: Anything setup is a plugin that can add your own settings page for using any themes and plugins in the admin panel.
  Version: 0.1.0
  Author: ka2
  Author URI: http://ka2.org
  Copyright: 2014 monauralsound (email : ka2@ka2.org)
  License: GPL2 - http://www.gnu.org/licenses/gpl.txt
  Text Domain: anything-set-upper
  Domain Path: /langs
*/
define('ATSU_PLUGIN_VERSION', '0.1.0');
define('ATSU_DB_VERSION', (float)1.0);
define('ATSU_PLUGIN_SLUG', 'anything-set-upper');

define('ATSU_DS', DIRECTORY_SEPARATOR);
define('ATSU_PLUGIN_DIR', dirname(__FILE__));
define('ATSU_PLUGIN_LIB_DIR', ATSU_PLUGIN_DIR . ATSU_DS . 'lib');
//define('ATSU_PLUGIN_TMPL_DIR', ATSU_PLUGIN_DIR . ATSU_DS . 'templates');

require_once ATSU_PLUGIN_LIB_DIR . ATSU_DS . 'atsu.class.php';
require_once ATSU_PLUGIN_DIR . ATSU_DS . 'functions.php';

global $atsu;
$atsu = new AnythingSetUpper();

register_activation_hook(__FILE__, array($atsu, 'activate'));
register_deactivation_hook(__FILE__, array($atsu, 'deactivation'));
