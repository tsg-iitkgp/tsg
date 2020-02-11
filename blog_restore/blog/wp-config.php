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
define('DB_NAME', 'gymkhana');

/** MySQL database username */
define('DB_USER', 'gymkhana');

/** MySQL database password */
define('DB_PASSWORD', 'gymkhana@4321');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');


/* Configure proxy Server */
define('WP_PROXY_HOST', '172.16.2.30');
define('WP_PROXY_PORT', '8080');
define('WP_PROXY_USERNAME', '');
define('WP_PROXY_PASSWORD', '');
define('WP_PROXY_BYPASS_HOSTS', 'localhost');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'oVKN5PPzEdNY p(?k%?VUxXR!n0by;]!o3dx?ziUs+E|hf&)PmQF=v.R[fTAz9^i');
define('SECURE_AUTH_KEY',  '{S2PvE00rVom~0GIzSWEh(Gy|M,p)XCtGCtZlIx_p` fS!(7C_Rg1&4:v#fjM_Kw');
define('LOGGED_IN_KEY',    'BaDsiB[m7m}M@wOJcQ%T pD5B4B3YptTGNvD_I(c`d(!/mf@i4EvRDPnI.wN05+K');
define('NONCE_KEY',        '()]P=Fk6{id]>jf6r$`K>C&ifb>Jg5,=x6,4YeQ!q6s/Sevskjx).887^p<p*E9<');
define('AUTH_SALT',        '&:R2#lMVkrL{m__D+K]zT~8zh#[_fJ5 ?N.aMI5vZHan1.E!T49sO{ &rtXN>VyA');
define('SECURE_AUTH_SALT', 'fF2?$/mAm$/mx}Sr*yhSeMY8wxYDzZ[OJ2K{#/KxWQ<d6kyBSo=P-!Qx.yN0W{r!');
define('LOGGED_IN_SALT',   '%6vW 0fKQo=hid$[p9|u=d;IN}j|8-f1|^`~.0n,N,]s3~<A^Z4W4OF)=5&(`gtD');
define('NONCE_SALT',       '<Ow`Ch[HC> LgpTE4G>a$-X,T/rIzQ70B*Uf4d+<&.qzG*_<Jm_%p:J$v@$1<Q(y');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wpblog_';

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
