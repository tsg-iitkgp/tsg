<?php
if (defined('ABSPATH') === false) :
	exit;
endif; // Shhh

/**
 * Template Name: Rainbow
 * Template URI: http://totalpoll.com
 * Version: 1.0.1
 * Requires: 3.0.0
 * Description: Lovely colorful simple results
 * Author: MisqTech
 * Author URI: http://misqtech.com
 * Category: All
 * Type: text
 */

if ( ! class_exists('TP_Rainbow_Template') && class_exists('TP_Template')):

	class TP_Rainbow_Template extends TP_Template
	{
		protected $textdomain = 'tp-rainbow';
		protected $__FILE__ = __FILE__;

		public function assets()
		{
			wp_enqueue_script('tp-rainbow', $this->asset('assets/js' . (WP_DEBUG ? '' : '/min') . '/main.js'), array ('jquery'), (WP_DEBUG ? time() : TP_VERSION));
		}

		public function settings()
		{
			$options = array (
				/**
				 * Sections
				 */
				'general'    => array (
					'label' => __('General', $this->textdomain),
					/**
					 * Tabs
					 */
					'tabs'  => array (
						'container' => array (
							'label'  => __('Container', $this->textdomain),
							/**
							 * Fields
							 */
							'fields' => array (
								'padding'      => array (
									'type'    => 'text',
									'label'   => __('Padding', $this->textdomain),
									'default' => '1em',
								),
								'border-color' => array (
									'type'    => 'color',
									'label'   => __('Pagination Container Border', $this->textdomain),
									'default' => '#F1F1F1',
								),
							),
						),
						'messages'  => array (
							'label'  => __('Messages', $this->textdomain),
							/**
							 * Fields
							 */
							'fields' => array (
								'background' => array (
									'type'    => 'color',
									'label'   => __('Background', $this->textdomain),
									'default' => '#FFFAFB',
								),
								'border'     => array (
									'type'    => 'color',
									'label'   => __('Border', $this->textdomain),
									'default' => '#F5BCC8',
								),
								'color'      => array (
									'type'    => 'color',
									'label'   => __('Foreground', $this->textdomain),
									'default' => '#F44336',
								),
							),
						),
						'question'  => array (
							'label'  => __('Question', $this->textdomain),
							/**
							 * Fields
							 */
							'fields' => array (
								'font-size'     => array (
									'type'    => 'text',
									'label'   => __('Font Size', $this->textdomain),
									'default' => '1.25em',
								),
								'margin-bottom' => array (
									'type'    => 'text',
									'label'   => __('Margin Below', $this->textdomain),
									'default' => '30px',
								),
							),
						),
						'other'     => array (
							'label'  => __('Other', $this->textdomain),
							/**
							 * Fields
							 */
							'fields' => array (
								'animation'     => array (
									'type'    => 'text',
									'label'   => __('Animation duration (ms)', $this->textdomain),
									'default' => '1000',
								),
								'border-radius' => array (
									'type'    => 'text',
									'label'   => __('Border Radius', $this->textdomain),
									'default' => '0px',
								),
							),

						),
					),
				),
				'choices'    => array (
					'label' => __('Choices', $this->textdomain),
					/**
					 * Tabs
					 */
					'tabs'  => array (
						'default' => array (
							'label'  => __('Default', $this->textdomain),
							/**
							 * Fields
							 */
							'fields' => array (
								'background-odd'   => array (
									'type'    => 'color',
									'label'   => __('Odd Choice Background', $this->textdomain),
									'default' => '#F7F7F7',
								),
								'background-even'  => array (
									'type'    => 'color',
									'label'   => __('Even Choice Background', $this->textdomain),
									'default' => '#FFFFFF',
								),
								'background:hover' => array (
									'type'    => 'color',
									'label'   => __('Background hover', $this->textdomain),
									'default' => '#EEEEEE',
								),
								'border'           => array (
									'type'    => 'color',
									'label'   => __('Border', $this->textdomain),
									'default' => 'rgba(0,0,0,0)',
								),
								'checkbox-border'  => array (
									'type'    => 'color',
									'label'   => __('Checkbox Border', $this->textdomain),
									'default' => '#EAEAEA',
								),
								'color:normal'     => array (
									'type'    => 'color',
									'label'   => __('Foreground', $this->textdomain),
									'default' => 'inherit',
								),
								'color:hover'      => array (
									'type'    => 'color',
									'label'   => __('Foreground hover', $this->textdomain),
									'default' => 'inherit',
								),
							),

						),
						'checked' => array (
							'label'  => __('Checked', $this->textdomain),
							/**
							 * Fields
							 */
							'fields' => array (
								'background:normal' => array (
									'type'    => 'color',
									'label'   => __('Background', $this->textdomain),
									'default' => 'inherit',
								),
								'background:hover'  => array (
									'type'    => 'color',
									'label'   => __('Background hover', $this->textdomain),
									'default' => '#EEEEEE',
								),
								'color:normal'      => array (
									'type'    => 'color',
									'label'   => __('Foreground', $this->textdomain),
									'default' => '#077EE6',
								),
								'color:hover'       => array (
									'type'    => 'color',
									'label'   => __('Foreground hover', $this->textdomain),
									'default' => '#077EE6',
								),
							),
						),
						'colors'  => array (
							'label'  => __('Colors', $this->textdomain),
							/**
							 * Fields
							 */
							'fields' => array (),
						),
					),
				),
				'buttons'    => array (
					'label' => __('Buttons', $this->textdomain),
					/**
					 * Tabs
					 */
					'tabs'  => array (
						'general' => array (
							'label'  => __('General', $this->textdomain),
							/**
							 * Fields
							 */
							'fields' => array (
								'padding'     => array (
									'type'    => 'text',
									'label'   => __('Padding', $this->textdomain),
									'default' => '0.8em',
								),
								'font-weight' => array (
									'type'    => 'text',
									'label'   => __('Font Weight', $this->textdomain),
									'default' => '600',
								),
								'align'       => array (
									'type'    => 'select',
									'label'   => __('Align', $this->textdomain),
									'extra'   => array (
										'options' => array (
											'left'   => 'Left',
											'center' => 'Center',
											'right'  => 'Right'
										)
									),
									'default' => 'center'
								)
							),

						),
						'default' => array (
							'label'  => __('Default', $this->textdomain),
							/**
							 * Fields
							 */
							'fields' => array (
								'background:normal' => array (
									'type'    => 'color',
									'label'   => __('Background', $this->textdomain),
									'default' => '#FAFAFA',
								),
								'background:hover'  => array (
									'type'    => 'color',
									'label'   => __('Background hover', $this->textdomain),
									'default' => '#F5F5F5',
								),
								'border:normal'     => array (
									'type'    => 'color',
									'label'   => __('Border', $this->textdomain),
									'default' => '#F1F1F1',
								),
								'border:hover'      => array (
									'type'    => 'color',
									'label'   => __('Border hover', $this->textdomain),
									'default' => '#DEDEDE',
								),
								'color:normal'      => array (
									'type'    => 'color',
									'label'   => __('Foreground', $this->textdomain),
									'default' => '#676767',
								),
								'color:hover'       => array (
									'type'    => 'color',
									'label'   => __('Foreground hover', $this->textdomain),
									'default' => '#5A5A5A',
								),
							),

						),
						'primary' => array (
							'label'  => __('Primary', $this->textdomain),
							/**
							 * Fields
							 */
							'fields' => array (
								'background:normal' => array (
									'type'    => 'color',
									'label'   => __('Background', $this->textdomain),
									'default' => '#269EE3',
								),
								'background:hover'  => array (
									'type'    => 'color',
									'label'   => __('Background hover', $this->textdomain),
									'default' => '#2090D0',
								),
								'border:normal'     => array (
									'type'    => 'color',
									'label'   => __('Border', $this->textdomain),
									'default' => '#1A7FB9',
								),
								'border:hover'      => array (
									'type'    => 'color',
									'label'   => __('Border hover', $this->textdomain),
									'default' => '#106BC5',
								),
								'color:normal'      => array (
									'type'    => 'color',
									'label'   => __('Foreground', $this->textdomain),
									'default' => '#FFFFFF',
								),
								'color:hover'       => array (
									'type'    => 'color',
									'label'   => __('Foreground hover', $this->textdomain),
									'default' => '#FFFFFF',
								),
							),

						),
					),
				),
				'results'    => array (
					'label' => __('Results', $this->textdomain),
					/**
					 * Tabs
					 */
					'tabs'  => array (
						'bar'  => array (
							'label'  => __('Bar', $this->textdomain),
							/**
							 * Fields
							 */
							'fields' => array (),
						),
						'text' => array (
							'label'  => __('Text', $this->textdomain),
							/**
							 * Fields
							 */
							'fields' => array (),

						),
					),
				),
				'typography' => array (
					'label' => __('Typography', $this->textdomain),
					/**
					 * Tabs
					 */
					'tabs'  => array (
						'general' => array (
							'label'  => false,
							/**
							 * Fields
							 */
							'fields' => array (
								'line-height' => array (
									'type'    => 'text',
									'label'   => __('Line height', $this->textdomain),
									'default' => '1.5',
								),
								'font-family' => array (
									'type'    => 'text',
									'label'   => __('Font family', $this->textdomain),
									'default' => 'inherit',
								),
								'font-size'   => array (
									'type'    => 'text',
									'label'   => __('Font size', $this->textdomain),
									'default' => '14px',
								),
							),
						),
					),
				),
			);

			$choices_count = count($this->poll->choices()) === 0 ? 8 : count($this->poll->choices());
			$colors        = array ('1e73be', 'dd3333', '81d742', '8224e3', 'dd9933', '7f8c8d', 'd35400', '1abc9c');
			for ($i = 1; $i <= $choices_count; $i ++) :
				$color = current($colors);
				if ( ! $color):
					reset($colors);
					$color = current($colors);
				endif;

				$options['choices']['tabs']['colors']['fields'][ 'color-' . $i ] = array (
					'type'    => 'color',
					'label'   => sprintf(__('Color %d', $this->textdomain), $i),
					'default' => '#' . $color,
				);
				next($colors);
			endfor;

			return $options;

		}
	}

	return 'TP_Rainbow_Template';

endif;

