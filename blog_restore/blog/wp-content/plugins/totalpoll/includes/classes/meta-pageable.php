<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh

if ( ! class_exists( 'TP_Meta_Pageable' ) ) :

	/**
	 * Meta Pageable Class
	 *
	 * @package TotalPoll/Classes/MetaPageable
	 * @since   3.0.0
	 */
	class TP_Meta_Pageable {

		/**
		 * @var array Items bag.
		 * @access private
		 * @since  3.0.0
		 */
		private $bag = array();

		/**
		 * Meta Pageable constructor.
		 *
		 * @since 3.0.0
		 */
		public function __construct() {
			// Setup hooks
			add_action( 'shutdown', array( $this, 'save' ) );
		}

		/**
		 * Add item.
		 *
		 * @param string $meta    Meta name.
		 * @param int    $id      Poll ID.
		 * @param mixed  $content Content.
		 *
		 * @since 3.0.0
		 * @return void
		 */
		public function add( $meta, $id, $content ) {
			if ( ! empty( $content ) ):
				$this->bag[] = array( 'meta' => $meta, 'id' => $id, 'content' => $content );
			endif;
		}

		/**
		 * Count items.
		 *
		 * @param string $meta Meta name.
		 * @param int    $id   Poll ID.
		 *
		 * @since 3.0.0
		 * @return int Items count.
		 */
		public function count( $meta, $id ) {
			return (int) get_post_meta( $id, "_mp_{$meta}", true );
		}

		/**
		 * @param string $meta     Meta name.
		 * @param string $id       Poll ID.
		 * @param int    $page     Page.
		 * @param int    $per_page Per page.
		 *
		 * @since 3.0.0
		 * @return array Items.
		 */
		public function paginate( $meta, $id, $page = 1, $per_page = 10 ) {
			$total = $this->count( $meta, $id );
			$items = array();

			if ( $total > 0 ):

				$per_page = ( $per_page === - 1 ) ? $total : absint( $per_page );
				$page     = ( $per_page === - 1 ) ? 1 : absint( $page );

				if ( $per_page < 1 || $per_page > $total ):
					$per_page = 10;
				endif;

				if ( $page < 1 || $page > $total ):
					$page = 1;
				endif;


				$start_offset = ( $page - 1 ) * $per_page;
				$end_offset   = $start_offset + $per_page;
				for ( $start_offset; $start_offset < $end_offset; $start_offset ++ ):
					$item = $this->item( $meta, $id, $start_offset );
					if ( ! empty( $item ) ):
						$items[] = $item;
					endif;
				endfor;

			endif;

			return $items;
		}

		/**
		 * Get a specific item.
		 *
		 * @param string $meta   Meta name
		 * @param int    $id     Poll ID.
		 * @param int    $offset Offset.
		 *
		 * @since 3.0.0
		 * @return mixed Item.
		 */
		public function item( $meta, $id, $offset ) {
			return get_post_meta( $id, "_mp_{$meta}_{$offset}", true );
		}

		/**
		 * Save items.
		 *
		 * @since 3.0.0
		 * @return void
		 */
		public function save() {
			if ( ! empty( $this->bag ) ):
				// Counters
				$counters = array();

				// Add meta
				foreach ( $this->bag as $item ):
					if ( ! isset( $counters[ $item['meta'] ] ) ):
						$counters[ $item['meta'] ] = array();
					endif;
					if ( ! isset( $counters[ $item['meta'] ][ $item['id'] ] ) ):
						$counters[ $item['meta'] ][ $item['id'] ] = $this->count( $item['meta'], $item['id'] );
					endif;

					if ( add_post_meta( $item['id'], "_mp_{$item['meta']}_{$counters[ $item['meta'] ][ $item['id'] ]}", $item['content'] ) ) :
						$counters[ $item['meta'] ][ $item['id'] ] ++;
					endif;
				endforeach;

				// Update counters
				foreach ( $counters as $meta => $ids ):
					foreach ( $ids as $id => $new_count ):
						update_post_meta( $id, "_mp_{$meta}", $new_count );
					endforeach;
				endforeach;

			endif;
		}

		/**
		 * Reset.
		 *
		 * @param $meta Meta name.
		 * @param $id   Poll ID.
		 */
		public function reset( $meta, $id ) {
			global $wpdb, $wp_version;

			$wpdb->query(
					$wpdb->prepare(
							"DELETE FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key LIKE %s",
							$id,
							version_compare($wp_version, '4.0', '>=') ? $wpdb->esc_like( "_mp_{$meta}" ) . '%' : like_escape( "_mp_{$meta}" ) . '%'
					)
			);

		}
	}


endif;
