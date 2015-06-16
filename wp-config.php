<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link http://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'militiat_wp18');

/** MySQL database username */
define('DB_USER', 'militiat_wp18');

/** MySQL database password */
define('DB_PASSWORD', 'SBaP.z-676');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'crg25jm0ocwcjashbxa3tcbus723wb85g2vo1db3lqwgaxke25mzmaleev1glkqs');
define('SECURE_AUTH_KEY',  'licafbfilujr0amqezia7cjlrtillz1e2eeuga8f4jqdtqoygnefljgunq1tvjeg');
define('LOGGED_IN_KEY',    'uz4vzuyzspdzwy0bjnnyzp6kbwtbafgmn7jod4jjl7r43fbwthnxrzngnwsyh4mo');
define('NONCE_KEY',        'nplkdag0n4nij0c8dvd2bqfzcdpon7sbfbhm1sb14h7oczhsewmwhjrclwb6g5u9');
define('AUTH_SALT',        'npnqdmxff0o4j0sb3yukaidupbtr6auz5szp8dsw8gumf0kjpvajsrfascpswbgi');
define('SECURE_AUTH_SALT', 'wc5xqlwhp3ia2jmbtzfdlusxfc7ltstr7fbdljfupuh6frjzqcoawssrrks0rzoy');
define('LOGGED_IN_SALT',   'd76zllokakwdi2ujzrqtjry7r4xfeer8vxlrqs2efqs3qroqmioyakcsvriwecrs');
define('NONCE_SALT',       'fhzbgpr0uwinw65aoxbqvn2bthbhgh5dak5jvas9zmqhrsbdm6cacaeolaoxdwod');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);
define('WP_DEBUG_DISPLAY', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
	

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
