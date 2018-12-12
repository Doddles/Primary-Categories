<?php
/**
 * Create utility functions for use as template_tags within the template files
 *
 * @package Primary Category
 */

/**
 * Display the current post Primary Category (if set)
 * @return void
 */
function primary_category() {

	global $post_id;
	$term = get_the_terms( $post_id, 'primary-category' );
	if ( ! $term ) {
		return;
	} else {
		echo '<a class="primary-category-link" href="' . get_home_url() . '/' . $term[0]->taxonomy . '/' . $term[0]->slug . '"><strong>' . $term[0]->name . '</strong></a>';
	}

}

/**
 * Return the current post Primary Category (if set)
 * @return {HTMLElement} returns Primary Catgory name and link
 */
function get_primary_category() {

	global $post_id;
	$term = get_the_terms( $post_id, 'primary-category' );
	if ( ! $term ) {
		return;
	} else {
		return '<a class="primary-category-link" href="' . get_home_url() . '/' . $term[0]->taxonomy . '/' . $term[0]->slug . '"><strong>' . $term[0]->name . '</strong></a>';
	}

}

/**
 * Display the current post Primary Category name (if set)
 * @return void
 */
function primary_category_name() {

	global $post_id;
	$term = get_the_terms( $post_id, 'primary-category' );
	if ( ! $term ) {
		return;
	} else {
		echo $term[0]->name;
	}

}

/**
 * Return the current post Primary Category name (if set)
 * @return {HTMLElement} returns Primary Catgory name
 */
function get_primary_category_name() {

	global $post_id;
	$term = get_the_terms( $post_id, 'primary-category' );
	if ( ! $term ) {
		return;
	} else {
		return $term[0]->name;
	}

}

/**
 * Display the current post Primary Category link (if set)
 * @return void
 */
function primary_category_link() {

	global $post_id;
	$term = get_the_terms( $post_id, 'primary-category' );
	if ( ! $term ) {
		return;
	} else {
		echo get_home_url() . '/' . $term[0]->taxonomy . '/' . $term[0]->slug;
	}

}

/**
 * Return the current post Primary Category link (if set)
 * @return {HTMLElement} returns Primary Catgory link
 */
function get_primary_category_link() {

	global $post_id;
	$term = get_the_terms( $post_id, 'primary-category' );
	if ( ! $term ) {
		return;
	} else {
		return get_home_url() . '/' . $term[0]->taxonomy . '/' . $term[0]->slug;
	}

}

/**
 * Return the current post Primary Category object (if set)
 * @return {Object} returns Primary Catgory object
 */
function get_primary_category_object() {

	global $post_id;
	$term = get_the_terms( $post_id, 'primary-category' );
	if ( ! $term ) {
		return;
	} else {
		return $term[0];
	}

}
