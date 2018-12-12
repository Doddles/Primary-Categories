<?php
/**
 * Primary Category Meta Box
 *
 * The metabox is used to store the Primary Category value which is then added to the Primary Category taxonomy
 *
 * @package Primary Category
 */

namespace PrimaryCategory\Core;

class MetaBox {

	public function init() {
		add_action( 'save_post', array( $this, 'save_primary_category_meta_box' ), 10, 3 );
		add_action( 'add_meta_boxes', array( $this, 'add_primary_category_meta_box' ) );
	}

	/**
	 * Register Primary Category meta box.
	 * This value is stored and used on `save_post` to inject the primary category
	 * into the 'primary-category' shadow taxonomy
	 */
	function add_primary_category_meta_box() {
	add_meta_box( 'primary-category-meta-box', 'Primary Category Meta Value', array( $this, 'primary_category_meta_box' ), 'post', 'side', 'high', null );
	}

	/**
	 * Primary Category meta box display callback.
	 *
	 * @param WP_Post $post Current post object.
	 */
	function primary_category_meta_box( $object ) {
		wp_nonce_field(basename(__FILE__), 'meta-box-nonce');

		?>
			<div>
				<input id="primary-category-meta" name="primary-category-meta" type="text" value="<?php echo get_post_meta( $object->ID, 'primary-category-meta', true ); ?>">
			</div>
		<?php
	}

	/**
	 * Save Primary Category meta box.
	 *
	 * @param int $post_id Post ID
	 */
	function save_primary_category_meta_box( $post_id, $post, $update ) {
		if ( ! isset($_POST['meta-box-nonce']) || !wp_verify_nonce($_POST['meta-box-nonce'], basename(__FILE__) ) )
			return $post_id;

		if ( ! current_user_can('edit_post', $post_id ) )
			return $post_id;

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return $post_id;

		$slug = 'post';
		if ( $slug != $post->post_type )
			return $post_id;

		$meta_box_text_value = '';

		if ( isset( $_POST['primary-category-meta'] ) ) {
			$meta_box_text_value = $_POST['primary-category-meta'];
		}
		update_post_meta( $post_id, 'primary-category-meta', $meta_box_text_value );
	}

}

