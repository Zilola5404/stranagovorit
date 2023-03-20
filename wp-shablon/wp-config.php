<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'stranagovorit');

/** MySQL database username */
define('DB_USER', 'stranagovorit');

/** MySQL database password */
define('DB_PASSWORD', 'vO3aQ3zC1j');

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
define('AUTH_KEY',         'V#OhWP%bTsYp710&W5sejgx@bbq0ie85rln)xyw15Ls0n)(oGBEAJEovVG1i1R3P');
define('SECURE_AUTH_KEY',  '@rpKHEmjb2s^qsWwfnbPppN&jRJ@ulilyE5lDL#LH9uac)lr7Q)ZWFER6kYp#fQZ');
define('LOGGED_IN_KEY',    '#i!E5eKEeN1jd!@K7t8c8Uug6&LgtSt3xFhY4Ga%UJDBkcPT)VC@O3fBln&O2hLI');
define('NONCE_KEY',        'kiYV1CN8r4Yt@n)K91cuIqOI*s@iZgZRcflvYm72BW&5#KC#dF38L0L^5)q6D07C');
define('AUTH_SALT',        '@Nj^e(yP9kAz#eIjevNXBNp&ol*I0Czt!F2FH1v918PT(I(oy%8!1@2&mQWbrwKa');
define('SECURE_AUTH_SALT', 'fYhI5kXRMC8MTE0B2WTbo4S(IUXKA&zRN6sGXuKCxJulbprU8fzYtcp)c0jovcuD');
define('LOGGED_IN_SALT',   'hpQ!%9(Dd502*9(#HxSEW89h8JcM!wqUN6A^yLtHDBSo6LHrDo@cqgtFu7L6H#V9');
define('NONCE_SALT',       'PiA7cq8CxIlr!rd(EpIIyzP4aX25jrO4GA%h0v*yF#TXUo@L!Ff&Jgd3OfcUjuli');
/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

define( 'WP_ALLOW_MULTISITE', true );

define ('FS_METHOD', 'direct');
