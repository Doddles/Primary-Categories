<?php
/**
 * Plugin Name: Primary Categories
 * Plugin URI: https://bitbucket.org/doodles/primary-categories/ (Private)
 * Description: Select a Primary Category for each post - the selection maps to the 'Primary Category' taxonomy and displays posts in the standard WordPress way. This approach ensures performant queries when viewing Prmary Categories.
 * Version:     1.0.0
 * Author:      Doodles
 * Author URI:  https://www.doodles.com
 * Text Domain: primary-category
 * Domain Path: /languages
 *
 */

// Useful global constants.
define( 'PRIMARY_CATEGORY_VERSION', '1.0.0' );
define( 'PRIMARY_CATEGORY_URL', plugin_dir_url( __FILE__ ) );
define( 'PRIMARY_CATEGORY_PATH', plugin_dir_path( __FILE__ ) );
define( 'PRIMARY_CATEGORY_INC', PRIMARY_CATEGORY_PATH . 'includes/' );

// Include files.
require_once PRIMARY_CATEGORY_INC . 'functions/core.php';
require_once PRIMARY_CATEGORY_INC . 'functions/template-tags.php';
require_once PRIMARY_CATEGORY_INC . 'classes/taxonomy.php';
require_once PRIMARY_CATEGORY_INC . 'classes/metabox.php';
require_once PRIMARY_CATEGORY_INC . 'classes/admin-column.php';

// Activation/Deactivation.
register_activation_hook( __FILE__, '\PrimaryCategory\Core\activate' );
register_deactivation_hook( __FILE__, '\PrimaryCategory\Core\deactivate' );

// Bootstrap.
PrimaryCategory\Core\setup();

// Require Composer autoloader if it exists.
if ( file_exists( PRIMARY_CATEGORY_PATH . '/vendor/autoload.php' ) ) {
	require_once PRIMARY_CATEGORY_PATH . 'vendor/autoload.php';
}
