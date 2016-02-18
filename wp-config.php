<?php
// Include local config
require( dirname( __FILE__ ) . '/wp-config-local.php' );

$table_prefix  = 'wp_';

define('WPLANG', '');

define('WP_DEBUG', false);

define('WP_SITEURL', 'https://' . $_SERVER['SERVER_NAME'] . '/wordpress');
define('WP_HOME',    'https://' . $_SERVER['SERVER_NAME']);
define('WP_CONTENT_DIR', dirname(__FILE__) . '/wp-content');
define('WP_CONTENT_URL', 'http://' . $_SERVER['SERVER_NAME'] . '/wp-content');
define('WP_PLUGIN_DIR', dirname(__FILE__) . '/wp-content/plugins' );
define('WP_PLUGIN_URL', 'http://' . $_SERVER['SERVER_NAME'] . '/wp-content/plugins');
define('UPLOADS', dirname(__FILE__) . '/wp-content/uploads');

if ( !defined('ABSPATH') )
    define('ABSPATH', dirname(__FILE__) . '/');

require_once(ABSPATH . 'wp-settings.php');
