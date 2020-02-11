<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>
<div class="totalpoll-tab-content settings-tab-content settings-expressions" data-tp-tab-content="expressions">
	<div class="settings-item">
		<div class="totalpoll-containables-container" data-tp-container>
			<ul class="totalpoll-containables" data-tp-containables>
				<li class="totalpoll-containable active" data-tp-containable>
					<div class="totalpoll-containable-handle" data-tp-containable-handle>
						<?php _e( 'Global', TP_TD ); ?>
					</div>

					<div class="totalpoll-containable-content with-tabs">
						<?php
						$tabs      = array(
							'votes'       => array(
								'label'       => __( 'Votes', TP_TD ),
								'expressions' =>
									array(
										'%s Vote' => array(
											'translations' => array(
												__( '%s Vote', TP_TD ),
												__( '%s Votes', TP_TD ),
											),
										),
									),
							),
							'buttons'     => array(
								'label'       => __( 'Buttons', TP_TD ),
								'expressions' =>
									array(
										'Previous page' => array(
											'translations' => array(
												__( 'Previous page', TP_TD ),
											),
										),
										'Next page'     => array(
											'translations' => array(
												__( 'Next page', TP_TD ),
											),
										),
										'Results'       => array(
											'translations' => array(
												__( 'Results', TP_TD ),
											),
										),
										'Vote'          => array(
											'translations' => array(
												__( 'Vote', TP_TD ),
											),
										),
										'Back'          => array(
											'translations' => array(
												__( 'Back', TP_TD ),
											),
										),
										'Proceed'       => array(
											'translations' => array(
												__( 'Proceed', TP_TD ),
											),
										),
									),
							),
							'errors'      => array(
								'label'       => __( 'Errors', TP_TD ),
								'expressions' =>
									array(
										'You cannot vote again in this poll.'                                                                    =>
											array(
												'translations' => array(
													__( 'You cannot vote again in this poll.', TP_TD ),
												),
											),
										'You have to vote for at least one choice.'                                                              =>
											array(
												'translations' => array(
													__( 'You have to vote for at least one choice.', TP_TD ),
													__( 'You have to vote for at least %d choices.', TP_TD ),
												),
											),
										'You cannot vote for more than one choice.'                                                              =>
											array(
												'translations' => array(
													__( 'You cannot vote for more than one choice.', TP_TD ),
													__( 'You cannot vote for more than %d choices.', TP_TD ),
												),
											),
										'You have entered an invalid captcha code.'                                                              =>
											array(
												'translations' => array(
													__( 'You have entered an invalid captcha code.', TP_TD ),
												),
											),
										'You cannot vote because the quota has been exceeded.'                                                   =>
											array(
												'translations' => array(
													__( 'You cannot vote because the quota has been exceeded.', TP_TD ),
												),
											),
										'You cannot see results before voting.'                                                                  =>
											array(
												'translations' => array(
													__( 'You cannot see results before voting.', TP_TD ),
												),
											),
										'You cannot vote because this poll has not started yet.'                                                 =>
											array(
												'translations' => array(
													__( 'You cannot vote because this poll has not started yet.', TP_TD ),
												),
											),
										'You cannot vote because this poll has been completed.'                                                  =>
											array(
												'translations' => array(
													__( 'You cannot vote because this poll has been completed.', TP_TD ),
												),
											),
										'You cannot vote because this poll is not available in your region.'                                     =>
											array(
												'translations' => array(
													__( 'You cannot vote because this poll is not available in your region.', TP_TD ),
												),
											),
										'You cannot vote because you have insufficient rights.'                                                  =>
											array(
												'translations' => array(
													__( 'You cannot vote because you have insufficient rights.', TP_TD ),
												),
											),
										'You cannot vote because you are a guest, please <a href="%s">sign in</a> or <a href="%s">register</a>.' =>
											array(
												'translations' => array(
													__( 'You cannot vote because you are a guest, please <a href="%s">sign in</a> or <a href="%s">register</a>.', TP_TD ),
												),
											),
										'Voting via links has been disabled for this poll.'                                                      =>
											array(
												'translations' => array(
													__( 'Voting via links has been disabled for this poll.', TP_TD ),
												),
											),
									),
							),
							'validations' => array(
								'label'       => __( 'Validations', TP_TD ),
								'expressions' =>
									array(
										'%label% field does not contain a valid email.'    => array(
											'translations' => array(
												__( '%label% field does not contain a valid email.', TP_TD ),
											),
										),
										'%label% field is required.'                       => array(
											'translations' => array(
												__( '%label% field is required.', TP_TD ),
											),
										),
										'%label% field does not contain a valid value.'    => array(
											'translations' => array(
												__( '%label% field does not contain a valid value.', TP_TD ),
											),
										),
										'%label% field does not support multiple values.'  => array(
											'translations' => array(
												__( '%label% field does not support multiple values.', TP_TD ),
											),
										),
										'%label% has been used before.'                    => array(
											'translations' => array(
												__( '%label% has been used before.', TP_TD ),
											),
										),
										'%label% field is not valid.'                      => array(
											'translations' => array(
												__( '%label% field is not valid.', TP_TD ),
											),
										),
										'You cannot vote because %s field is not allowed.' => array(
											'translations' => array(
												__( 'You cannot vote because %s field is not allowed.', TP_TD ),
											),
										),
									),
							),
						);
						$first_tab = key( $tabs );
						?>
						<div class="totalpoll-tabs-container">
							<div class="totalpoll-tabs" data-tp-tabs>
								<?php foreach ( $tabs as $tab => $tab_content ): ?>
									<a href="#" class="<?php echo $first_tab == $tab ? 'active' : ''; ?>" data-tp-tab="expressions-<?php echo $tab; ?>"><?php echo $tab_content['label']; ?></a>
								<?php endforeach; ?>
							</div>
							<div class="totalpoll-tabs-content" data-tp-tabs-content>
								<?php foreach ( $tabs as $tab => $tab_content ): ?>
									<div class="totalpoll-tab-content <?php echo $first_tab == $tab ? 'active' : ''; ?>" data-tp-tab-content="expressions-<?php echo $tab; ?>">
										<?php foreach ( $tab_content['expressions'] as $expression => $expression_content ): ?>
											<div class="settings-item">
												<div class="settings-field">
													<?php
													foreach ( $expression_content['translations'] as $translation_index => $translation ):
														$field = TotalPoll::instance(
															'field',
															array(
																TotalPoll::instance( 'helpers' )->parse_args(
																	array(
																		'type'       => 'text',
																		'field_name' => "totalpoll[options][expressions][$expression][translations][]",
																		'name'       => md5( $expression ),
																		'value'      => empty( $this->options['expressions'][ $expression ]['translations'][ $translation_index ] ) ? '' : $this->options['expressions'][ $expression ]['translations'][ $translation_index ],
																		'class'      => array( 'widefat', 'text-field' ),
																		'attributes' => array(
																			'placeholder' => __( $translation, TP_TD ),
																		),
																		'extra'      => array(
																			'ignore_dynamic_name' => true,
																		),
																	),
																	array(
																		'label' => array(
																			'content'    => $translation_index == 0 ? esc_attr( $expression ) : false,
																			'attributes' => array(
																				'class' => 'settings-field-label',
																			),
																		),
																	)
																),
															)
														);

														echo $field->render();
													endforeach;
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
			</ul>
		</div>

	</div>
</div>