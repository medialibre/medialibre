<?php
// Include local config
require( dirname( __FILE__ ) . '/wp-config-local.php' );

$table_prefix  = 'wp_';

define('WPLANG', '');

define('WP_DEBUG', false);

define('WP_POST_REVISIONS', 3);

if ( !defined('ABSPATH') )
    define('ABSPATH', dirname(__FILE__) . '/');

require_once(ABSPATH . 'wp-settings.php');
