<?php
/**
 * Add the Primary Category taxonomy. The taxonomy is fed by the metadata from the
 *
 * @package Primary Category
 */

namespace PrimaryCategory\Core;

class Taxonomy {

	public function init() {
		add_action( 'init', array( $this, 'register_primary_category_taxonomy')  );
		add_action('save_post', array( $this, 'primary_category_apply_taxonomy' ), 99, 3 );
	}

	/**
	 * Create the Primary Category taxonomy.
	 * This taxonomy is automatically fed by the meta value for Primary Categories on each save post.
	*/
	function register_primary_category_taxonomy() {
		$args = array(
			'hierarchical' 			=> true,
			'label' 				=> 'Primary Category',
			'show_in_nav_menus' 	=> false,
			'show_ui' 				=> false,
			'show_in_rest'          => true,
		);

		// the primary category is available to all post types
		register_taxonomy( 'primary-category', get_post_types(), $args );

	}

	/**
	 * Apply Primary Category meta value into 'Primary Category' taxonomy.
	 *
	 * @param int $post_id Post ID
	 */
	function primary_category_apply_taxonomy( $post_id ) {

		$meta_box = get_post_meta( $post_id, 'primary-category-meta', true );
		$term = get_cat_name( $meta_box );

		if ( ! term_exists( $term, 'primary-category' ) ) {
			wp_insert_term( $term, 'primary-category' );
		}

		$primary_cat_term = get_term_by( 'name', $term, 'primary-category', OBJECT );
		wp_set_post_terms( $post_id, [(int) $primary_cat_term->term_id], 'primary-category', false );
	}

}
