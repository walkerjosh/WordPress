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
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'free.d0m');

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
define('AUTH_KEY',         '#!f0qdd#fDqz)~DS<X?k+e:/u>-t89#itWPpOmXpapQ(1!O_VA^w+6QE h1.kv6p');
define('SECURE_AUTH_KEY',  'p#:O<DIeS3(vQHCA.4{--,VW>e-wDJ8#G`Pyt+F4ePPh,ZnHbuG^B;-.Dta1eX-T');
define('LOGGED_IN_KEY',    '#RbycVc2XYD-1c408+3>pgX$s0A|>Lh`2jg%JE%(FgaA^ymp-P?+b>;Pf}NQ.YJ8');
define('NONCE_KEY',        '<DSW|k^&/y#1zS(e3/1z^^wTeQ~d_[+Fwo.k[N0wqzZek@p9BAR/W8M%#sy*4wI8');
define('AUTH_SALT',        '!U8|Z&@-LWK^}Ktq% *0Ua=+^yC1h5${@q_fvpx7$]Uy,&<;jdWQIq/<KQ+t#9[P');
define('SECURE_AUTH_SALT', 'b9GOJ]+f0o+TNo @E~`u2jw6nl_bTF$1N,iJv~%0zhx~#;T5tZatI>Z9vk2rc3|7');
define('LOGGED_IN_SALT',   'UAsy_P5-duq4jGC^8KIRg,a.JnY6-qTMwn8EAHu%!f}B~5ob}>r&PdBPhUEIFh[A');
define('NONCE_SALT',       '00V/4`1cF<HJu/t:QhFuK02oPNa&`EAO%&{hh=:8=N{iVF0xO}gD:I!5fVO{.u6.');

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
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);
define('WP_ALLOW_MULTISITE', true);
define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', true);
$base = '/';
define('DOMAIN_CURRENT_SITE', 'orchardapp.com');
define('PATH_CURRENT_SITE', '/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);
define('WP_DEFAULT_THEME', 'xing');

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');


/** Activates domain mapping plugin */
define( 'SUNRISE', 'on' );

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
