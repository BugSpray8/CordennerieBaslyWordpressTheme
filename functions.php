<?php


define( 'BASLY_VERSION', '3.0.4' );
define( 'BASLY_VENDOR_VERSION', '1.0.2' );
define( 'BASLY_PHP_INCLUDE', trailingslashit( get_template_directory() ) . 'inc/' );
define( 'BASLY_CORE_DIR', BASLY_PHP_INCLUDE . 'core/' );

if ( ! defined( 'BASLY_DEBUG' ) ) {
	define( 'BASLY_DEBUG', false );
}

// Load hooks
require_once( BASLY_PHP_INCLUDE . 'hooks/hooks.php' );

// Load Helper Globally Scoped Functions
require_once( BASLY_PHP_INCLUDE . 'helpers/sanitize-functions.php' );
require_once( BASLY_PHP_INCLUDE . 'helpers/layout-functions.php' );

if ( class_exists( 'WooCommerce', false ) ) {
	require_once( BASLY_PHP_INCLUDE . 'compatibility/woocommerce/functions.php' );
}

if ( function_exists( 'max_mega_menu_is_enabled' ) ) {
	require_once( BASLY_PHP_INCLUDE . 'compatibility/max-mega-menu/functions.php' );
}

/**
 * Adds notice for PHP < 5.3.29 hosts.
 */
function basly_no_support_5_3() {
	$message = __( 'Hey, we\'ve noticed that you\'re running an outdated version of PHP which is no longer supported. Make sure your site is fast and secure, by upgrading PHP to the latest version.', 'hestia' );

	printf( '<div class="error"><p>%1$s</p></div>', esc_html( $message ) );
}


if ( version_compare( PHP_VERSION, '5.3.29' ) < 0 ) {
	/**
	 * Add notice for PHP upgrade.
	 */
	add_filter( 'template_include', '__return_null', 99 );
	switch_theme( WP_DEFAULT_THEME );
	unset( $_GET['activated'] );
	add_action( 'admin_notices', 'basly_no_support_5_3' );

	return;
}

/**
 * Begins execution of the theme core.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function basly_run() {

	require_once BASLY_CORE_DIR . 'class-basly-autoloader.php';
	$autoloader = new Basly_Autoloader();

	spl_autoload_register( array( $autoloader, 'loader' ) );

	new Basly_Core();

	$vendor_file = trailingslashit( get_template_directory() ) . 'vendor/composer/autoload_files.php';
	if ( is_readable( $vendor_file ) ) {
		$files = require_once $vendor_file;
		foreach ( $files as $file ) {
			if ( is_readable( $file ) ) {
				include_once $file;
			}
		}
	}
	add_filter( 'themeisle_sdk_products', 'basly_load_sdk' );

	if ( class_exists( 'Ti_White_Label', false ) ) {
		Ti_White_Label::instance( get_template_directory() . '/style.css' );
	}
}

/**
 * Loads products array.
 *
 * @param array $products All products.
 *
 * @return array Products array.
 */
function basly_load_sdk( $products ) {
	$products[] = get_template_directory() . '/style.css';

	return $products;
}

require_once( BASLY_CORE_DIR . 'class-basly-autoloader.php' );


basly_run();


function basly_upgrade_link( $link ) {

	$theme_name = wp_get_theme()->get_stylesheet();

	$_child_themes = array(
		'orfeo',
		'fagri',
		'tiny-basly',
		'christmas-basly',
		'jinsy-magazine',
	);

	if ( $theme_name === 'basly' ) {
		return $link;
	}

	if ( ! in_array( $theme_name, $basly_child_themes, true ) ) {
		return $link;
	}

	$link = add_query_arg(
		array(
			'theme' => $theme_name,
		),
		$link
	);

	return $link;
}

add_filter( 'basly_upgrade_link_from_child_theme_filter', 'basly_upgrade_link' );


function basly_check_passed_time( $no_seconds ) {
	$activation_time = get_option( 'basly_time_activated' );
	if ( ! empty( $activation_time ) ) {
		$current_time    = time();
		$time_difference = (int) $no_seconds;
		if ( $current_time >= $activation_time + $time_difference ) {
			return true;
		} else {
			return false;
		}
	}

	return true;
}

function basly_setup_theme() {
	return;
}

