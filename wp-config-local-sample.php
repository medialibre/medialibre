<?php
define('DB_NAME', '');
define('DB_USER', '');
define('DB_PASSWORD', '');
define('DB_HOST', '');
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', 'utf8-general-ci');

define('AUTH_KEY',         '');
define('SECURE_AUTH_KEY',  '');
define('LOGGED_IN_KEY',    '');
define('NONCE_KEY',        '');
define('AUTH_SALT',        '');
define('SECURE_AUTH_SALT', '');
define('LOGGED_IN_SALT',   '');
define('NONCE_SALT',       '');

define('WPCOM_API_KEY',    '');

define('WP_SITEURL', 'https://' . $_SERVER['SERVER_NAME'] . '/wordpress');
define('WP_HOME',    'https://' . $_SERVER['SERVER_NAME']);
define('WP_CONTENT_DIR', $_SERVER['DOCUMENT_ROOT'] . '/wp-content');
define('WP_CONTENT_URL', 'https://' . $_SERVER['SERVER_NAME'] . '/wp-content');
?>
