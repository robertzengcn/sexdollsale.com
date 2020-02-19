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
define('DB_NAME', 'sexdollsale');

/** MySQL database username */
define('DB_USER', 'sexdaer');

/** MySQL database password */
define('DB_PASSWORD', 'pI+#K{mdI3wQ');

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
define('AUTH_KEY',         '-XFAS_m|](B4<g/jM#oO]/P2{=C4^172e--?ynU`N/v09+E$OnGVN<+N1U^.Ac,8');
define('SECURE_AUTH_KEY',  '+%;J[vciH3++qLC-a19wAv[+ikI-T^M>ZB3JBPE8Sa{`8vjr0o-7u]X ^FP0f~/z');
define('LOGGED_IN_KEY',    '__%ZS+7{7]a-e*(E/X14{[t!  -X~jLv]-.S<y11qF)V-+FI|n{3!xEB|>6J+9)|');
define('NONCE_KEY',        'n7J}oc|M(duAaJP[4-Z5,o}|%].c2V8^C%>z=2{Du#WiQ)oYeg-!i_@wQidpM,RD');
define('AUTH_SALT',        '6gmM/)V~qQYD85,C&e`IY~+Zb.w[?)O=z8G(*[IS~l#s!vh}32kwBhXxr]_/k,LD');
define('SECURE_AUTH_SALT', '@LwSFU1$B&r+XxF`:zL+.$[|.Ey)v)Zs EH4Xk@g+OMuXg+[*)866-D%)|Pw|++l');
define('LOGGED_IN_SALT',   'lIv%|)(s0_K:L3#||q)CU3w%}>Z+X-Vpb/@H-cl|4(eFB_C+swEb[bNx(^^0sVUl');
define('NONCE_SALT',       'eX& m<LA`j~C[_|+V/8ul&=po|ar#(L0At;WRH#uDM0)n_F&yz<Kd.6[6csuVHhQ');

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
