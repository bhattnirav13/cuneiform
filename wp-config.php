<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'cuneiform' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'hSTEQ+$CZ{w|qp>tqnM@/tf@Kop0QrcHsOY{tl!91rnB;/A/Q(XAm?v:;Y8A^7hw' );
define( 'SECURE_AUTH_KEY',  'wzy#:uvuw92_tg<Mcu+v_J5VWKD)]&OeFnpf8t+$g&bxo?+ Bz<1rF/0]?l#~D5?' );
define( 'LOGGED_IN_KEY',    '6darT4Gi1 >$hCL_$RRWYo>aMd 6ZCmbqJ=_97f@)!y%dQP$*Y@[+FRF5dDX[4hh' );
define( 'NONCE_KEY',        'Ra*~I3|k`L$J&<7vjavgR}1-/d*_Xh,s:pt`s)Q8Ms{l rt6(L#X/8gKLrV#X|<B' );
define( 'AUTH_SALT',        'Qb1u:sE:f2R9eQxE(o=k,Tu~@Pj_u7;sPD3~SXl2ogRuxH|Fue4%[HobFsI<JV|9' );
define( 'SECURE_AUTH_SALT', 'u[imw`7dLeg*=Db73BJ!U[{u=f:^np3%r{g;Ql)3jtMjsz?n0aS]lSUCNO0TAHEl' );
define( 'LOGGED_IN_SALT',   ':I<Cf|v9C0#d6cEd9bm$5a5wZbG!kb}HZljg?wrS.c7$]i[qiD$TmH]|Jt136eDJ' );
define( 'NONCE_SALT',       'uGb7GUR/FM!]=/a-)MC{6k6}0CN8VMsL5BTL6Rj>ocH1pAey~!S5K2vqN;RE&rJd' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
