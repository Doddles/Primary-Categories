<?php
/**
 * Core plugin functionality.
 *
 * @package Primary Category
 */

namespace PrimaryCategory\Core;

use \WP_Error as WP_Error;

/**
 * Default setup routine
 *
 * @return void
 */
function setup() {
	$n = function( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	add_action( 'init', $n( 'i18n' ) );
	add_action( 'init', $n( 'init' ) );
	add_action( 'wp_enqueue_scripts', $n( 'scripts' ) );
	add_action( 'wp_enqueue_scripts', $n( 'styles' ) );
	add_action( 'admin_enqueue_scripts', $n( 'admin_scripts' ) );
	add_action( 'admin_enqueue_scripts', $n( 'admin_styles' ) );

	// Editor styles. add_editor_style() doesn't work outside of a theme.
	add_filter( 'mce_css', $n( 'mce_css' ) );
	// Hook to allow async or defer on asset loading.
	add_filter( 'script_loader_tag', $n( 'script_loader_tag' ), 10, 2 );

	do_action( 'primary_category_loaded' );

	$taxonomy = new Taxonomy;
	$taxonomy->init();

	$columns = new AdminColumn;
	$columns->init();

	$meta_box = new MetaBox;
	$meta_box->init();
}

/**
 * Registers the default textdomain.
 *
 * @return void
 */
function i18n() {
	$locale = apply_filters( 'plugin_locale', get_locale(), 'primary-category' );
	load_textdomain( 'primary-category', WP_LANG_DIR . '/primary-category/primary-category-' . $locale . '.mo' );
	load_plugin_textdomain( 'primary-category', false, plugin_basename( PRIMARY_CATEGORY_PATH ) . '/languages/' );
}

/**
 * Initializes the plugin and fires an action other plugins can hook into.
 *
 * @return void
 */
function init() {
	do_action( 'primary_category_init' );
//	require_once PRIMARY_CATEGORY_INC . 'classes/shadow-taxonomy.php';


//	$plugin = new \PrimaryCategory\Core\ShadowTaxonomy();
//	$plugin->run();

}


/**
 * Activate the plugin
 *
 * @return void
 */
function activate() {
	// First load the init scripts in case any rewrite functionality is being loaded
	init();
	flush_rewrite_rules();

}

/**
 * Deactivate the plugin
 *
 * Uninstall routines should be in uninstall.php
 *
 * @return void
 */
function deactivate() {

}


/**
 * The list of knows contexts for enqueuing scripts/styles.
 *
 * @return array
 */
function get_enqueue_contexts() {
	return [ 'admin', 'frontend', 'shared' ];
}

/**
 * Generate an URL to a script, taking into account whether SCRIPT_DEBUG is enabled.
 *
 * @param string $script Script file name (no .js extension)
 * @param string $context Context for the script ('admin', 'frontend', or 'shared')
 *
 * @return string|WP_Error URL
 */
function script_url( $script, $context ) {

	if ( ! in_array( $context, get_enqueue_contexts(), true ) ) {
		return new WP_Error( 'invalid_enqueue_context', 'Invalid $context specified in PrimaryCategory script loader.' );
	}

	return ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ?
		PRIMARY_CATEGORY_URL . "assets/js/${context}/{$script}.js" :
		PRIMARY_CATEGORY_URL . "dist/js/${script}.min.js";

}

/**
 * Generate an URL to a stylesheet, taking into account whether SCRIPT_DEBUG is enabled.
 *
 * @param string $stylesheet Stylesheet file name (no .css extension)
 * @param string $context Context for the script ('admin', 'frontend', or 'shared')
 *
 * @return string URL
 */
function style_url( $stylesheet, $context ) {

	if ( ! in_array( $context, get_enqueue_contexts(), true ) ) {
		return new WP_Error( 'invalid_enqueue_context', 'Invalid $context specified in PrimaryCategory stylesheet loader.' );
	}

	return ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ?
		PRIMARY_CATEGORY_URL . "assets/css/${context}/{$stylesheet}.css" :
		PRIMARY_CATEGORY_URL . "dist/css/${stylesheet}.min.css";

}

/**
 * Enqueue scripts for front-end.
 *
 * @return void
 */
function scripts() {
// none required
}

/**
 * Enqueue scripts for admin.
 *
 * @return void
 */
function admin_scripts() {

	$screen = get_current_screen();

	// limit our JavaScript files to only run on the new post and editing post pages
	if ( in_array( $screen->id, array( 'post', 'post-new' ) ) ) {

		wp_enqueue_script(
			'primary_category_admin',
			script_url( 'admin', 'admin' ),
			[],
			PRIMARY_CATEGORY_VERSION,
			true
		);

		// Localize the script with new data
		wp_localize_script( 'primary_category_admin', 'primaryCatLocal', array(
			'headingText' => __( 'Chose the Primary Category', 'primary-category' ),
			'selectText' => __( 'Select Primary Category', 'primary-category' )
		) );

	}

}

/**
 * Enqueue styles for front-end.
 *
 * @return void
 */
function styles() {

	if ( is_admin() ) {
		wp_enqueue_style(
			'primary_category_admin',
			style_url( 'admin-style', 'admin' ),
			[],
			PRIMARY_CATEGORY_VERSION
		);
	}

}

/**
 * Enqueue styles for admin.
 *
 * @return void
 */
function admin_styles() {

	wp_enqueue_style(
		'primary_category_admin',
		style_url( 'admin-style', 'admin' ),
		[],
		PRIMARY_CATEGORY_VERSION
	);

}

/**
 * Enqueue editor styles. Filters the comma-delimited list of stylesheets to load in TinyMCE.
 *
 * @param string $stylesheets Comma-delimited list of stylesheets.
 * @return string
 */
function mce_css( $stylesheets ) {
	if ( ! empty( $stylesheets ) ) {
		$stylesheets .= ',';
	}

	return $stylesheets . PRIMARY_CATEGORY_URL . ( ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ?
			'assets/css/frontend/editor-style.css' :
			'dist/css/editor-style.min.css' );
}

/**
 * Add async/defer attributes to enqueued scripts that have the specified script_execution flag.
 *
 * @link https://core.trac.wordpress.org/ticket/12009
 * @param string $tag    The script tag.
 * @param string $handle The script handle.
 * @return string
 */
function script_loader_tag( $tag, $handle ) {
	$script_execution = wp_scripts()->get_data( $handle, 'script_execution' );

	if ( ! $script_execution ) {
		return $tag;
	}

	if ( 'async' !== $script_execution && 'defer' !== $script_execution ) {
		return $tag; // _doing_it_wrong()?
	}

	// Abort adding async/defer for scripts that have this script as a dependency. _doing_it_wrong()?
	foreach ( wp_scripts()->registered as $script ) {
		if ( in_array( $handle, $script->deps, true ) ) {
			return $tag;
		}
	}

	// Add the attribute if it hasn't already been added.
	if ( ! preg_match( ":\s$script_execution(=|>|\s):", $tag ) ) {
		$tag = preg_replace( ':(?=></script>):', " $script_execution", $tag, 1 );
	}

	return $tag;
}
