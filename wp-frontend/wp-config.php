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
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'tarix');

/** MySQL database password */
define('DB_PASSWORD', '12');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         'Kp.-eQFT7_*a&mv qKiO~ gEEK1df^@ 3#DdY=54&ESsbNt8_4)5M5q -87FtI+)');
define('SECURE_AUTH_KEY',  '?hAu-vWoG; $md`<@U,FQNth`Yx L>IxEgYo90iSxKh^TG*b/LfE!}-r/j}2N2@A');
define('LOGGED_IN_KEY',    '=R!OM-q)s~_A.x91)/-wg2*oTIm!|94R<D8A=t[}B]>^pO=U5DktJFs@bV#V8~PN');
define('NONCE_KEY',        ')C1*KfvnVe#+M}z83UC0MM8Xqy .pkP*w^Eah$vpU*4nD|PwS-OOLWuBEO.?Z4&_');
define('AUTH_SALT',        '},Yv}xB1qU;Y_oJ,zLViA-0jzb|vsuoED94}OJI1@wtGoI2J=)+lpU3gOn(?b @@');
define('SECURE_AUTH_SALT', 'k;rypwUV|?$i~Hgbch-BQ}&I6ubzis2CCiG9Fp,M RE=N;;Zr:uipoV<#c!gj[Vm');
define('LOGGED_IN_SALT',   '%~ MIuBV7Dd6Fs`K9?y!@cX3u9v[Mov] dRN pV=+6BF<A=BL{C|BfZ.-nRg,qd)');
define('NONCE_SALT',       'VE DW/,i2:)4LL0z`tjNN+X<0bAVVWF0gSE;yqVy-o(No)C0h=S6QCj6sL[m&]cn');

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
