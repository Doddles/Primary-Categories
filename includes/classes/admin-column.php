<?php
/**
 * Primary Category Admin Column
 *
 * Add a new 'Primary Category' admin column to the 'All Posts' display
 *
 * @package Primary Category
 */

namespace PrimaryCategory\Core;

class AdminColumn {

	public function init() {
		add_filter('manage_posts_columns', array( $this, 'primary_category_column_position' ), 50, 2 );
		add_filter( 'manage_post_posts_columns', array( $this, 'set_primary_category_column' ) );
		add_action( 'manage_post_posts_custom_column' , array( $this, 'primary_category_column_constructor' ), 10, 2 );
	}

	/**
	 * Add Primary Category admin column
	 *
	 * @param array $columns - array of admin columns
	 */
	function set_primary_category_column( $columns ) {
		$columns['primary_category'] = __( 'Primary Category', 'primary-category' );
		return $columns;
	}

	/**
	 * Construct Primary Category column
	 * @param array $columns - array of admin columns
	 * @param int $post_id Post ID
	 */
	function primary_category_column_constructor( $columns, $post_id ) {
		$term = get_the_terms( $post_id, 'primary-category' );
		if ( $term ) {
			echo '<a href="' . get_home_url() . '/' . $term[0]->taxonomy . '/' . $term[0]->slug . '"><strong>' . $term[0]->name . '</strong></a>';
		} else {
			echo '<i>' . __( 'none', 'primary-category' ) . '</i>';
		}
	}

	/**
	 * Construct Primary Category column
	 *
	 * @param array $columns - array of admin columns
	 */
	function primary_category_column_position( $columns ) {

		foreach( $columns as $key=>$value ) {
			if ( 'tags' == $key ) {
			$new['primary_category'] = $tags;
			}
			$new[$key] = $value;
		}

		return $new;
	}

}
