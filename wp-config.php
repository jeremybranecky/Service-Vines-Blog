<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'thebram_servicevines');

/** MySQL database username */
define('DB_USER', 'thebram_service');

/** MySQL database password */
define('DB_PASSWORD', 'Vf@QCu@?}FuO');

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
define('AUTH_KEY',         'G4E}bD-6&q?>{u|lfvQO0umO~=Rd, 2|i8v+o7#pz{+E$t`7H|L>Q]2^_-D$a=TW');
define('SECURE_AUTH_KEY',  'fr=aOBfC:K{w|F(`Ix-!RA# R{={-q[FiSz##1lQS]5+Rhl+1%st+o7aM^R/7Fjh');
define('LOGGED_IN_KEY',    'i87.-;viD<z.sY~#q-bTeRS8:Dy4|MpgJK&<xzE8+{h 8$--jJM-PpeW,`T4~b><');
define('NONCE_KEY',        'Nh%56+%4b@GPZ<?<uZ%^rF.@e*Vfq|/,j$P;-p:[Rf1C3EZ>2lq&|kL.Ol]A1k^n');
define('AUTH_SALT',        ')9#s:GZnA&x|%e7[`p[;pdcOW7~+c1F%j$|4Y-TaVNzwTSO[g%;Q#SYy+ar|KCx=');
define('SECURE_AUTH_SALT', '~m-WqQdKxC)m.U13mt-SO,.+6 =JlqwsvR%Vt[_fc`Mz>q8->lK2tJ-H?_z;hfix');
define('LOGGED_IN_SALT',   '?2CdPVI4av#(09~N+kso:4WSE1UUO;6<GGXM>~E-9U`m?JJ @%18hbSp2b/;nGj!');
define('NONCE_SALT',       '5!3$xr%q(&DHq.vbAn:xayyHExQW)W]*aeOTrv5VErWVMt-A/AyH/;g5Jrb((/e%');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress.  A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de.mo to wp-content/languages and set WPLANG to 'de' to enable German
 * language support.
 */
define ('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
