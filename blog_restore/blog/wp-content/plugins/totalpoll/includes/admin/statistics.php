<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh

if ( ! class_exists( 'TP_Admin_Statistics' ) ) :

	/**
	 * Statistics Class
	 *
	 * @package TotalPoll/Classes/Admin/Statistics
	 * @since   3.0.0
	 */
	class TP_Admin_Statistics {

		public function __construct() {

		}

		public function data_array( $labels, $items, $limit = - 1 ) {
			$data = array(
				$labels,
			);
			foreach ( $items as $key => $value ):
				$key    = empty( $key ) ? __( 'N/A', TP_TD ) : $key;
				$data[] = array( (string) $key, $value );
				if ( -- $limit === 0 ):
					break;
				endif;
			endforeach;

			return $data;
		}

		public function analyzed_percentage( $id ) {
			$logs_count  = TotalPoll::instance( 'meta-pageable' )->count( 'logs', $id );
			$last_offset = (int) get_post_meta( $id, 'statistics_last_offset', true );
			if ( $logs_count == 0 || $last_offset == 0 || $last_offset > $logs_count ):
				return 100;
			endif;

			return ceil( ( $last_offset / $logs_count ) * 100 );
		}
		
		public function analyze( $id, $limit = - 1 ) {
			$logs_count      = TotalPoll::instance( 'meta-pageable' )->count( 'logs', $id );
			$last_offset     = (int) get_post_meta( $id, 'statistics_last_offset', true );
			$last_statistics = get_post_meta( $id, 'statistics', true );
			$statistics      = TotalPoll::instance( 'helpers' )->parse_args(
				empty( $last_statistics ) ? array() : $last_statistics,
				array(
					'status' => array(),
					'time'   => array(
						'days'   => array(),
						'months' => array(),
						'years'  => array(),
					),
					'ua'     => array(
						'browsers'  => array(),
						'platforms' => array(),
					),
					'fields' => array(),
				)
			);

			// Custom fields
			$custom_fields = TotalPoll::poll( $id )->settings( 'fields' );

			for ( $last_offset; $last_offset < $logs_count; $last_offset ++ ):
				$log = TotalPoll::instance( 'meta-pageable' )->item( 'logs', $id, $last_offset );

				if ( isset( $log['status'] ) ):
					$this->increment( $statistics['status'], $log['status'] );
				endif;

				if ( empty( $log ) || empty( $log['status'] ) ):
					continue;
				endif;

				// Time
				$this->increment( $statistics['time']['days'], date( 'm/d/Y', $log['time'] ) );
				$this->increment( $statistics['time']['weeks'], date( 'W/Y', $log['time'] ) );
				$this->increment( $statistics['time']['months'], date( 'm/Y', $log['time'] ) );
				$this->increment( $statistics['time']['years'], date( 'Y', $log['time'] ) );

				// UA
				$ua = TotalPoll::instance( 'helpers' )->parse_useragent( $log['useragent'] );
				$this->increment( $statistics['ua']['browsers'], $ua['browser'] );
				$this->increment( $statistics['ua']['platforms'], $ua['platform'] );

				// Custom fields
				if ( ! empty( $log['fields'] ) ):
					foreach ( $custom_fields as $field ):

						if ( empty( $field['statistics']['enabled'] ) ):

							unset( $log['fields'][ $field['name'] ] );

						elseif ( isset( $log['fields'][ $field['name'] ] ) ):

							if ( empty( $statistics['fields'][ $field['name'] ] ) ):
								$statistics['fields'][ $field['name'] ] = array();
							endif;

							foreach ( (array) $log['fields'][ $field['name'] ] as $value ):
								$this->increment( $statistics['fields'][ $field['name'] ], $value );
							endforeach;

						endif;

					endforeach;
				endif;

				if ( $limit -- === 0 ):
					break;
				endif;

			endfor;

			update_post_meta( $id, 'statistics_last_offset', $last_offset );
			update_post_meta( $id, 'statistics', $statistics );

			return $statistics;
		}

		private function increment( &$array, $key ) {
			if ( ! isset( $array[ $key ] ) ):
				$array[ $key ] = 1;
			else:
				++ $array[ $key ];
			endif;
		}

	}


endif;