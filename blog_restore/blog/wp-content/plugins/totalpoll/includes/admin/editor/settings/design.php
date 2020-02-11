<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>
<div class="totalpoll-tab-content settings-tab-content settings-design" data-tp-tab-content="design">

	<?php do_action( 'totalpoll/actions/admin/editor/settings/design/before', $design, $this->poll ); ?>

	<div class="settings-item">

		<div class="settings-field">
			<label class="settings-field-label" for="totalpoll-settings-design-template">
				<?php _e( 'Template', TP_TD ); ?>
				<span class="totalpoll-feature-details" title="<?php esc_attr_e( 'Select which template will be used to display this poll.', TP_TD ); ?>">?</span>
			</label>

			<input type="hidden" name="totalpoll[settings][design][template][last_used]" value="<?php echo esc_attr( $design['template']['name'] ); ?>">
			<select id="totalpoll-settings-design-template" name="totalpoll[settings][design][template][name]" class="settings-field-select widefat" data-tp-templates>
				<?php
				$templates = TotalPoll::instance( 'admin/templates' );
				foreach ( $templates->fetch() as $slug => $template ):
					?>
					<option value="<?php echo esc_attr( $slug ); ?>" <?php selected( $design['template']['name'], $slug ); ?> <?php disabled( $template['activated'] && $template['compatible'], false ); ?>>
						<?php echo $template['name'] ?>
						<?php echo $template['activated'] ? '' : __( '(Not Activated)', TP_TD ); ?>
						<?php echo $template['compatible'] ? '' : __( '(Not Compatible)', TP_TD ); ?>
					</option>
				<?php endforeach; ?>
			</select>

		</div>
		<?php do_action( 'totalpoll/actions/admin/editor/settings/design/template', $design, $this->poll ); ?>

		<div class="totalpoll-containables-container" data-tp-container>
			<ul class="totalpoll-containables" data-tp-containables>
				<?php
				$template = TotalPoll::module( 'template', $design['template']['name'], $this->poll );
				if ( $template === false ):
					$template = TotalPoll::module( 'template', 'default', $this->poll );
				endif;

				$sections = $template->settings();
				foreach ( $sections as $section => $section_content ):

					?>
					<li class="totalpoll-containable" data-tp-containable>

						<div class="totalpoll-containable-handle" data-tp-containable-handle>
							<?php echo $section_content['label']; ?>
						</div>

						<div class="totalpoll-containable-content with-tabs">
							<?php
							$first_tab = key( $section_content['tabs'] );
							$hide_tabs = count( $section_content['tabs'] ) === 1 && empty( $section_content['tabs'][ $first_tab ]['label'] );
							?>

							<div class="totalpoll-tabs-container">
								<?php if ( ! $hide_tabs ): ?>
									<div class="totalpoll-tabs" data-tp-tabs>
										<?php
										foreach ( $section_content['tabs'] as $tab => $tab_content ):
											?>
											<a href="#" class="<?php echo $tab === $first_tab ? 'active' : ''; ?>" data-tp-tab="design-<?php echo $tab; ?>"><?php echo $tab_content['label']; ?></a>
											<?php
										endforeach;
										?>
									</div>
								<?php endif; ?>
								<div class="totalpoll-tabs-content" data-tp-tabs-content>
									<?php foreach ( $section_content['tabs'] as $tab => $tab_content ): ?>
										<div class="totalpoll-tab-content <?php echo $tab === $first_tab ? 'active' : ''; ?>" data-tp-tab-content="design-<?php echo $tab; ?>">

											<?php foreach ( $tab_content['fields'] as $field_slug => $field_content ): ?>

												<div class="settings-item">

													<div class="settings-field">

														<?php

														if ( isset( $field_content['label'] ) && is_string( $field_content['label'] ) ):
															$field_content['label'] = array(
																'content'    => $field_content['label'],
																'attributes' => array(
																	'class' => 'settings-field-label',
																),
															);
														endif;

														if ( isset( $design['preset'][ $section ]['tabs'][ $tab ]['fields'][ $field_slug ] ) ):
															$field_content = TotalPoll::instance( 'helpers' )->parse_args( $design['preset'][ $section ]['tabs'][ $tab ]['fields'][ $field_slug ], $field_content );
														endif;

														$field = TotalPoll::instance(
															'field',
															array(
																TotalPoll::instance( 'helpers' )->parse_args(
																	array(
																		'type'       => $field_content['type'] === 'color' ? 'text' : $field_content['type'],
																		'field_name' => "totalpoll[settings][design][preset][{$section}][tabs][{$tab}][fields][{$field_slug}][value]",
																		'name'       => $field_slug,
																		'class'      => array( 'widefat', "{$field_content['type']}-field" ),
																		'attributes' => array(
																			"data-tp-field-{$field_content['type']}" => true,
																		),
																	),
																	$field_content
																),
															)
														);

														echo $field->render();
														?>

													</div>

												</div>

											<?php endforeach; ?>

										</div>
									<?php endforeach; ?>
								</div>
							</div>
						</div>

					</li>
					<?php
				endforeach;
				?>
			</ul>
		</div>

	</div>


	<div class="settings-item">

		<div class="settings-field">
			<label>
				<input type="checkbox" name="totalpoll[settings][design][template][default]">
				<?php _e( 'Set current settings as defaults', TP_TD ); ?>
			</label>
		</div>
		<?php do_action( 'totalpoll/actions/admin/editor/settings/design/set-as-defaults', $design, $this->poll ); ?>

		<div class="settings-field">
			<label>
				<input type="checkbox" name="totalpoll[settings][design][template][reset]">
				<?php _e( 'Reset settings to defaults', TP_TD ); ?>
			</label>
		</div>

		<?php do_action( 'totalpoll/actions/admin/editor/settings/design/reset-to-defaults', $design, $this->poll ); ?>

	</div>

	<div class="settings-item">

		<div class="settings-field">
			<label class="settings-field-label" for="totalpoll-settings-design-transition"><?php _e( 'Transition', TP_TD ); ?></label>
			<select id="totalpoll-settings-design-transition" name="totalpoll[settings][design][transition][type]" class="settings-field-select widefat">
				<option value="none" <?php selected( $design['transition']['type'], 'none' ); ?>>None</option>
				<option value="fade" <?php selected( $design['transition']['type'], 'fade' ); ?>>Fade</option>
				<option value="slide" <?php selected( $design['transition']['type'], 'slide' ); ?>>Slide</option>
			</select>
		</div>

		<?php do_action( 'totalpoll/actions/admin/editor/settings/design/transition', $design, $this->poll ); ?>
	</div>
	<div class="settings-item">

		<div class="settings-field">
			<label>
				<input type="checkbox" name="totalpoll[settings][design][scroll][enabled]" value="1" <?php checked( empty( $design['scroll']['enabled'] ), false ); ?>>
				<?php _e( 'Scroll up after vote submit', TP_TD ); ?>
			</label>
		</div>

		<?php do_action( 'totalpoll/actions/admin/editor/settings/design/scroll', $design, $this->poll ); ?>

	</div>
	<div class="settings-item">
		<div class="settings-field">
			<label>
				<input type="checkbox" name="totalpoll[settings][design][one_click][enabled]" value="1" <?php checked( empty( $design['one_click']['enabled'] ), false ); ?>>
				<?php _e( 'Enable one-click vote', TP_TD ); ?>
			</label>
		</div>

		<?php do_action( 'totalpoll/actions/admin/editor/settings/design/one_click', $design, $this->poll ); ?>
	</div>

	<?php do_action( 'totalpoll/actions/admin/editor/settings/design/after', $design, $this->poll ); ?>

</div>