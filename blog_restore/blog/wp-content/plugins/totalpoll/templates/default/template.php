<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh

/**
 * Template Name: Default
 * Template URI: http://totalpoll.com
 * Version: 1.0.5
 * Requires: 3.0.0
 * Description: The basic default template of TotalPoll
 * Author: MisqTech
 * Author URI: http://misqtech.com
 * Category: All
 * Type: built-in
 */

if ( ! class_exists( 'TP_Default_Template' ) && class_exists( 'TP_Template' ) ):

	class TP_Default_Template extends TP_Template {
		protected $textdomain = 'tp-default';
		protected $__FILE__ = __FILE__;

		public function assets() {
			wp_enqueue_style( 'tosrus-style', $this->asset( 'assets/css/jquery.tosrus' . ( WP_DEBUG ? '' : '.min' ) . '.css' ), array(), ( WP_DEBUG ? time() : TP_VERSION ) );
			wp_register_script( 'tosrus', $this->asset( 'assets/js/min/jquery.tosrus.js' ), 'jquery', TP_VERSION );
			wp_enqueue_script( 'tp-default', $this->asset( 'assets/js' . ( WP_DEBUG ? '' : '/min' ) . '/main.js' ), array (	'jquery', 'tosrus' ), ( WP_DEBUG ? time() : TP_VERSION ) );
		}

		public function settings() {
			return array(
				/**
				 * Sections
				 */
				'general'    => array(
					'label' => __( 'General', $this->textdomain ),
					/**
					 * Tabs
					 */
					'tabs'  => array(
						'container' => array(
							'label'  => __( 'Container', $this->textdomain ),
							/**
							 * Fields
							 */
							'fields' => array(
								'background' => array(
										'type'    => 'color',
										'label'   => __( 'Background', $this->textdomain ),
										'default' => '#FFFFFF',
								),
								'border'     => array(
										'type'    => 'color',
										'label'   => __( 'Border', $this->textdomain ),
										'default' => '#DDDDDD',
								),
							),
						),
						'messages'  => array(
								'label'  => __( 'Messages', $this->textdomain ),
							/**
							 * Fields
							 */
								'fields' => array(
										'background' => array(
												'type'    => 'color',
												'label'   => __( 'Background', $this->textdomain ),
												'default' => '#FFFAFB',
										),
										'border'     => array(
												'type'    => 'color',
												'label'   => __( 'Border', $this->textdomain ),
												'default' => '#F5BCC8',
										),
										'color'      => array(
												'type'    => 'color',
												'label'   => __( 'Foreground', $this->textdomain ),
												'default' => '#F44336',
										),
								),
						),
						'other'     => array(
								'label'  => __( 'Other', $this->textdomain ),
							/**
							 * Fields
							 */
								'fields' => array(
										'per-row'       => array(
												'type'       => 'number',
												'label'      => __( 'Choices per row', $this->textdomain ),
												'default'    => '1',
												'attributes' => array(
														'min'  => 1,
														'step' => 1,
												),
										),
										'animation'     => array(
												'type'    => 'text',
												'label'   => __( 'Animation duration (ms)', $this->textdomain ),
												'default' => '1000',
										),
										'border-radius' => array(
												'type'    => 'text',
												'label'   => __( 'Border radius', $this->textdomain ),
												'default' => '4px',
										),
								),

						),
					),
				),
				'choices'    => array(
						'label' => __( 'Choices', $this->textdomain ),
					/**
					 * Tabs
					 */
						'tabs'  => array(
								'default' => array(
										'label'  => __( 'Default', $this->textdomain ),
									/**
									 * Fields
									 */
										'fields' => array(
												'background:normal' => array(
														'type'    => 'color',
														'label'   => __( 'Background', $this->textdomain ),
														'default' => '#FFFFFF',
												),
												'background:hover'  => array(
														'type'    => 'color',
														'label'   => __( 'Background hover', $this->textdomain ),
														'default' => '#FAFAFA',
												),
												'border:normal'     => array(
														'type'    => 'color',
														'label'   => __( 'Border', $this->textdomain ),
														'default' => '#EEEEEE',
												),
												'border:hover'      => array(
														'type'  => 'color',
														'label' => __( 'Border hover', $this->textdomain ),
									'default' => '#DDDDDD',
								),
												'color:normal'      => array(
														'type'    => 'color',
														'label'   => __( 'Foreground', $this->textdomain ),
														'default' => 'inherit',
												),
												'color:hover'       => array(
														'type'    => 'color',
														'label'   => __( 'Foreground hover', $this->textdomain ),
														'default' => 'inherit',
												),
							),

						),
								'checked' => array(
										'label' => __( 'Checked', $this->textdomain ),
									/**
									 * Fields
									 */
							'fields' => array(
									'background:normal' => array(
											'type'    => 'color',
											'label'   => __( 'Background', $this->textdomain ),
											'default' => '#FAFAFA',
									),
									'background:hover'  => array(
											'type'    => 'color',
											'label'   => __( 'Background hover', $this->textdomain ),
											'default' => '#FAFAFA',
									),
									'border:normal'     => array(
											'type' => 'color',
									'label'   => __( 'Border', $this->textdomain ),
									'default' => '#DDDDDD',
								),
									'border:hover'      => array(
											'type'    => 'color',
											'label'   => __( 'Border hover', $this->textdomain ),
											'default' => '#DDDDDD',
									),
									'color:normal'      => array(
											'type'    => 'color',
											'label'   => __( 'Foreground', $this->textdomain ),
											'default' => 'inherit',
									),
									'color:hover'       => array(
											'type'    => 'color',
											'label'   => __( 'Foreground hover', $this->textdomain ),
											'default' => 'inherit',
									),
							),

						),
					),
				),
				'buttons'    => array(
					'label' => __( 'Buttons', $this->textdomain ),
					/**
					 * Tabs
					 */
					'tabs'  => array(
						'default' => array(
							'label'  => __( 'Default', $this->textdomain ),
							/**
							 * Fields
							 */
							'fields' => array(
								'background:normal' => array(
										'type'    => 'color',
										'label'   => __( 'Background', $this->textdomain ),
										'default' => '#F5F5F5',
								),
								'background:hover'  => array(
										'type'    => 'color',
										'label'   => __( 'Background hover', $this->textdomain ),
										'default' => '#EEEEEE',
								),
								'border:normal'     => array(
										'type'    => 'color',
										'label'   => __( 'Border', $this->textdomain ),
										'default' => '#EEEEEE',
								),
								'border:hover'      => array(
										'type'    => 'color',
										'label'   => __( 'Border hover', $this->textdomain ),
										'default' => '#EEEEEE',
								),
								'color:normal'      => array(
										'type'    => 'color',
										'label'   => __( 'Foreground', $this->textdomain ),
										'default' => 'inherit',
								),
								'color:hover'       => array(
										'type'    => 'color',
										'label'   => __( 'Foreground hover', $this->textdomain ),
										'default' => 'inherit',
								),
							),

						),
						'primary' => array(
							'label'  => __( 'Primary', $this->textdomain ),
							/**
							 * Fields
							 */
							'fields' => array(
								'background:normal' => array(
										'type'    => 'color',
										'label'   => __( 'Background', $this->textdomain ),
										'default' => '#2196F3',
								),
								'background:hover'  => array(
										'type'    => 'color',
										'label'   => __( 'Background hover', $this->textdomain ),
										'default' => '#1976D2',
								),
								'border:normal'     => array(
										'type'    => 'color',
										'label'   => __( 'Border', $this->textdomain ),
										'default' => '#2196F3',
								),
								'border:hover'      => array(
										'type'    => 'color',
										'label'   => __( 'Border hover', $this->textdomain ),
										'default' => '#1976D2',
								),
								'color:normal'      => array(
										'type'    => 'color',
										'label'   => __( 'Foreground', $this->textdomain ),
										'default' => '#FFFFFF',
								),
								'color:hover'       => array(
										'type'    => 'color',
										'label'   => __( 'Foreground hover', $this->textdomain ),
										'default' => '#FFFFFF',
								),
							),

						),
					),
				),
				'votes-bar'  => array(
					'label' => __( 'Votes bar', $this->textdomain ),
					/**
					 * Tabs
					 */
					'tabs'  => array(
							'bar'  => array(
									'label' => __( 'Bar', $this->textdomain ),
							/**
							 * Fields
							 */
							'fields' => array(
									'color:start' => array(
											'type'    => 'color',
											'label'   => __( 'Start color', $this->textdomain ),
											'default' => '#2196F3',
								),
									'color:end'   => array(
											'type'    => 'color',
											'label'   => __( 'End color', $this->textdomain ),
											'default' => '#1976D2',
								),
							),

							),
							'text' => array(
									'label'  => __( 'Text', $this->textdomain ),
								/**
								 * Fields
								 */
									'fields' => array(
											'background' => array(
													'type'  => 'color',
													'label' => __( 'Background', $this->textdomain ),
									'default' => '#DDDDDD',
								),
											'color'      => array(
													'type'  => 'color',
													'label' => __( 'Color', $this->textdomain ),
									'default' => '#DDDDDD',
								),
							),

						),
					),
				),
				'typography' => array(
					'label' => __( 'Typography', $this->textdomain ),
					/**
					 * Tabs
					 */
					'tabs'  => array(
						'general' => array(
							'label'  => false,
							/**
							 * Fields
							 */
							'fields' => array(
								'line-height' => array(
										'type'    => 'text',
										'label'   => __( 'Line height', $this->textdomain ),
										'default' => '1.5',
								),
								'font-family' => array(
									'type'    => 'text',
									'label'   => __( 'Font family', $this->textdomain ),
									'default' => 'inherit',
								),
								'font-size'   => array(
										'type'    => 'text',
										'label'   => __( 'Font size', $this->textdomain ),
										'default' => '14px',
								),
							),
						),
					),
				),
			);

		}
	}

	return 'TP_Default_Template';

endif;

