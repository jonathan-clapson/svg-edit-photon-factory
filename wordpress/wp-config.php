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
define('DB_USER', 'wordpress');

/** MySQL database password */
define('DB_PASSWORD', 'photon');

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
define('AUTH_KEY',         '(e3Y=I-YSvdGDY`gJ*#Ey`ZDG5|fU j6<uB3P@4?|{hDp!!boP}%l;U2CL&]{u]c');
define('SECURE_AUTH_KEY',  '{ff[%hndHT_W7u%Vt>MrC+<eP}dL*>niTxJ(<vcC6q/ozI>3ISj:2Z?9F!]hlHU1');
define('LOGGED_IN_KEY',    '(-a]#L~k]`[1CzW,sZWht6cP8qWL/cE~MDbXITG&g=-WiG]K#TXm<+~*;$`+XKSo');
define('NONCE_KEY',        '^()rpMN:|Q8,T+Nir=v42#Z^hn+C2gwL>3Yj)Q&XHsZGZ?f:v3P>iQb%m/TUedPa');
define('AUTH_SALT',        'E*+zMH)SC)yf .zJ5=gg2(_Dzue/pg7J!|)pBGSqV,_y@n|k+H5tddB-2cl&MzOr');
define('SECURE_AUTH_SALT', '|dVLc@Widm(YTKE|UJ1?g1Lxgn.ht+X >iuuOw>+n.||r=By<.&!sKQ3N`*{;KEl');
define('LOGGED_IN_SALT',   '-;|kPg<X8I:o,Rsz3y-IyEtOY:dS.QOay>X{(@zKMt/$pef,w1PI=z>{eM10]w>u');
define('NONCE_SALT',       'k_4$(aN`2X86Temxw1M#e,/sP@j1~.1~(40Az-)NXtbrYzc|Vizw#NreX+|dz>@(');

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

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

