<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh

if ( ! class_exists( 'TP_Admin_Tools' ) ) :

	/**
	 * Tools Class
	 *
	 * @package TotalPoll/Classes/Admin/Tools
	 * @since   3.0.0
	 */
	class TP_Admin_Tools {

		public function __construct() {

		}

		public function purge_cache() {
			@array_map( 'unlink', glob( WP_CONTENT_DIR . '/cache/totalpoll/css/*.css' ) );
			TotalPoll::instance( 'helpers' )->purge_cache();

			return true;
		}

		public function totalpoll_polls() {
			return get_posts(
				array(
					'post_type'      => 'poll',
					'posts_per_page' => - 1,
					'meta_key'       => '_tp_options',
					'fields'         => 'ids',
					'meta_query'     => array(
						array(
							'key'     => '_tp_options',
							'compare' => 'EXISTS',
						),
						array(
							'key'     => '_tp_migrated',
							'value'   => 'migrated',
							'compare' => 'NOT EXISTS',
						),
					),
				)
			);
		}

		public function migrate_totalpoll_polls() {
			$polls = $this->totalpoll_polls();

			foreach ( $polls as $poll ):
				$this->migrate_totalpoll_poll( $poll );
			endforeach;

		}

		public function migrate_totalpoll_poll( $poll_id ) {

			$old_poll = get_post_meta( $poll_id, '_tp_options', true );

			$presets = get_option( 'tp_presets' );

			if ( ! isset( $presets->{$old_poll['template']['name']} ) ):
				$old_poll['template']['name'] = 'default';
			endif;

			$preset = json_decode( json_encode( $presets->{$old_poll['template']['name']}->{$old_poll['template']['preset']['name']} ), true );

			if ( ! $old_poll ):
				return false;
			endif;

			$new_poll = array(
				'question' => '',
				'choices'  => array(),
				'settings' => TotalPoll::instance( 'admin/bootstrap' )->get_default_settings(),
			);

			$new_poll['question'] = $old_poll['question'];

			$available_choices = array( 'text', 'image', 'video', 'audio', 'html' );
			foreach ( $old_poll['choices'] as $choice ):
				$new_choice = array(
					'votes'   => $choice['votes'],
					'content' => array(
						'visible'   => true,
						'type'      => in_array( $choice['type'], $available_choices ) ? $choice['type'] : 'text',
						'label'     => isset( $choice['label'] ) ? $choice['label'] : ( isset( $choice['text'] ) ? $choice['text'] : 'No label' ),
						'video'     => array( 'url' => '' ),
						'image'     => array( 'url' => '' ),
						'thumbnail' => array( 'url' => '' ),
						'html'      => '',
					),
				);

				if ( isset( $choice['image'] ) ):
					$new_choice['content']['thumbnail'] = array( 'url' => $choice['image'] );
				endif;

				if ( isset( $choice['full'] ) ):
					$new_choice['content']['image'] = array( 'url' => $choice['full'] );
				endif;

				if ( isset( $choice['video'] ) ):
					$new_choice['content']['video'] = array( 'url' => $choice['video'] );
				endif;

				if ( isset( $choice['html'] ) ):
					$new_choice['content']['html'] = $choice['html'];
				endif;

				$new_poll['choices'][] = $new_choice;
			endforeach;

			if ( ! empty( $old_poll['limitations']['revote']['session'] ) || ! empty( $old_poll['limitations']['revote']['cookies'] ) ):
				$new_poll['settings']['limitations']['cookies']['enabled'] = 'yes';
				$new_poll['settings']['limitations']['cookies']['timeout'] = absint( $old_poll['limitations']['cookies']['timeout'] );
			endif;

			if ( ! empty( $old_poll['limitations']['revote']['ip'] ) ):
				$new_poll['settings']['limitations']['ip']['enabled'] = 'yes';
				$new_poll['settings']['limitations']['ip']['timeout'] = absint( $old_poll['limitations']['ip']['timeout'] );
			endif;

			if ( ! empty( $old_poll['limitations']['vote_for_results'] ) ):
				$new_poll['settings']['limitations']['results']['require_vote']['enabled'] = 'yes';
			endif;

			if ( ! empty( $old_poll['limitations']['multiselection'] ) ):
				$new_poll['settings']['limitations']['selection']['maximum'] = 0;
			endif;

			if ( ! empty( $old_poll['limitations']['limit_maximum_answers'] ) ):
				$new_poll['settings']['limitations']['selection']['maximum'] = absint( $old_poll['limitations']['maximum_answers'] );
			endif;

			if ( ! empty( $old_poll['limitations']['quota'] ) ):
				$new_poll['settings']['limitations']['quota']['enabled'] = 'yes';
				$new_poll['settings']['limitations']['quota']['votes']   = absint( $old_poll['limitations']['quota'] );
			endif;

			if ( ! empty( $old_poll['limitations']['date']['start_timestamp'] ) ):
				$new_poll['settings']['limitations']['date']['enabled'] = 'yes';
				$new_poll['settings']['limitations']['date']['start']   = $old_poll['limitations']['date']['start_timestamp'];
			endif;

			if ( ! empty( $old_poll['limitations']['date']['end_timestamp'] ) ):
				$new_poll['settings']['limitations']['date']['enabled'] = 'yes';
				$new_poll['settings']['limitations']['date']['end']     = $old_poll['limitations']['date']['end_timestamp'];
			endif;

			if ( ! empty( $old_poll['limitations']['private_results'] ) ):
				$new_poll['settings']['results']['hide']['enabled'] = 'yes';
			endif;

			if ( ! empty( $old_poll['limitations']['show_results_after'] ) ):
				$until = str_replace( 'date', 'end_date', $old_poll['limitations']['show_results_after'] );
				if ( $until != 'never' ):
					$new_poll['settings']['results']['hide']['until'][ $until ] = 'yes';
				endif;
			endif;

			if ( ! empty( $old_poll['limitations']['private_results_content'] ) ):
				$new_poll['settings']['results']['hide']['content'] = $old_poll['limitations']['private_results_content'];
			endif;

			if ( ! empty( $old_poll['limitations']['logged_users_only_vote'] ) ):
				$new_poll['settings']['limitations']['membership']['enabled'] = 'yes';
			endif;

			if ( ! empty( $old_poll['limitations']['logged_user_role'] ) ):
				$new_poll['settings']['limitations']['membership']['type'] = array_keys( $old_poll['limitations']['logged_user_role'] );
			endif;

			if ( ! empty( $old_poll['limitations']['user_fields'] ) ):
				$new_poll['settings']['fields'][] = array(
					'type'        => 'text',
					'name'        => 'name',
					'label'       => array( 'content' => 'Name', 'attributes' => array( 'class' => '' ) ),
					'class'       => '',
					'validations' => array(
						'filled' => array( 'enabled' => 'yes' ),
					),
					'template'    => '%label% %field%',
					'statistics'  => array(
						'enabled' => false,
					),
				);
			endif;

			if ( ! empty( $old_poll['limitations']['collect_emails'] ) || ! empty( $old_poll['limitations']['user_fields'] ) ):
				$new_poll['settings']['fields'][] = array(
					'type'        => 'text',
					'name'        => 'email',
					'label'       => array( 'content' => 'Email', 'attributes' => array( 'class' => '' ) ),
					'class'       => '',
					'validations' => array(
						'filled' => array( 'enabled' => 'yes' ),
						'email'  => array( 'enabled' => 'yes' ),
						'regex'  => array(
							'against' => '',
							'type'    => 'match',
						),
						'filter' => array(
							'list' => '',
						),
					),
					'template'    => '%label% %field%',
					'statistics'  => array(
						'enabled' => false,
					),
				);
			endif;

			if ( ! empty( $old_poll['misc']['orderby_votes'] ) ):
				$new_poll['settings']['results']['order']['enabled']   = 'yes';
				$new_poll['settings']['results']['order']['by']        = 'votes';
				$new_poll['settings']['results']['order']['direction'] = $old_poll['misc']['orderby_votes_direction'];
			endif;

			if ( ! empty( $old_poll['misc']['shuffle'] ) ):
				$new_poll['settings']['choices']['order']['enabled']   = 'yes';
				$new_poll['settings']['choices']['order']['by']        = 'random';
				$new_poll['settings']['choices']['order']['direction'] = 'desc';
			endif;

			if ( ! empty( $old_poll['misc']['pagination'] ) ):
				$new_poll['settings']['choices']['pagination']['per_page'] = absint( $old_poll['misc']['per_page'] );
			endif;

			if ( ! empty( $old_poll['misc']['custom_choice'] ) ):
				$new_poll['settings']['choices']['other']['enabled'] = 'yes';
			endif;

			if ( ! empty( $old_poll['misc']['show_results'] ) ):
				if ( $old_poll['misc']['show_results'] == 'number' ):
					$new_poll['settings']['results']['format']['votes'] = 'yes';
				elseif ( $old_poll['misc']['show_results'] == 'percentage' ):
					$new_poll['settings']['results']['format']['percentages'] = 'yes';
				elseif ( $old_poll['misc']['show_results'] == 'both' ):
					$new_poll['settings']['results']['format']['votes']       = 'yes';
					$new_poll['settings']['results']['format']['percentages'] = 'yes';
				endif;
			endif;

			if ( ! empty( $old_poll['logs'] ) ):
				$new_poll['settings']['logs']['enabled'] = 'yes';
			endif;

			$new_preset = array();

			if ( ! empty( $preset['general']['perRow'] ) ):
				$new_preset['general.tabs.other.fields.per-row.value'] = absint( $preset['general']['perRow'] );
			endif;

			if ( ! empty( $preset['general']['animationDuration'] ) ):
				$new_preset['general.tabs.other.fields.animation.value'] = absint( $preset['general']['animationDuration'] );
			endif;

			if ( ! empty( $preset['general']['borderRadius'] ) ):
				$new_preset['general.tabs.other.fields.border-radius.value'] = absint( $preset['general']['borderRadius'] ) . 'px';
			endif;

			if ( ! empty( $preset['general']['containerBackground'] ) ):
				$new_preset['general.tabs.container.fields.background.value'] = $preset['general']['containerBackground'];
			endif;

			if ( ! empty( $preset['general']['containerBorder'] ) ):
				$new_preset['general.tabs.container.fields.border.value'] = $preset['general']['containerBorder'];
			endif;

			if ( ! empty( $preset['general']['warningBackground'] ) ):
				$new_preset['general.tabs.messages.fields.background.value'] = $preset['general']['warningBackground'];
			endif;

			if ( ! empty( $preset['general']['warningBorder'] ) ):
				$new_preset['general.tabs.messages.fields.border.value'] = $preset['general']['warningBorder'];
			endif;

			if ( ! empty( $preset['general']['warningColor'] ) ):
				$new_preset['general.tabs.messages.fields.color.value'] = $preset['general']['warningColor'];
			endif;

			if ( ! empty( $preset['general']['choiceInputBackground'] ) ):
				$new_preset['choices.tabs.default.fields.background:normal.value'] = $preset['general']['choiceInputBackground'];
				$new_preset['choices.tabs.default.fields.background:hover.value']  = $preset['general']['choiceInputBackground'];
			endif;

			if ( ! empty( $preset['choices']['background'] ) ):
				$new_preset['choices.tabs.default.fields.background:normal.value'] = $preset['choices']['background'];
				$new_preset['choices.tabs.default.fields.background:hover.value']  = $preset['choices']['background'];
			endif;

			if ( ! empty( $preset['choices']['borderColor'] ) ):
				$new_preset['choices.tabs.default.fields.border:normal.value'] = $preset['choices']['borderColor'];
				$new_preset['choices.tabs.default.fields.border:hover.value']  = $preset['choices']['borderColor'];
			endif;

			if ( ! empty( $preset['general']['choiceColor'] ) ):
				$new_preset['choices.tabs.default.fields.color:normal.value'] = $preset['general']['choiceColor'];
				$new_preset['choices.tabs.default.fields.color:hover.value']  = $preset['general']['choiceColor'];
			endif;

			if ( ! empty( $preset['choices']['color'] ) ):
				$new_preset['choices.tabs.default.fields.color:normal.value'] = $preset['choices']['color'];
				$new_preset['choices.tabs.default.fields.color:hover.value']  = $preset['choices']['color'];
			endif;

			if ( ! empty( $preset['buttons']['background'] ) ):
				$new_preset['buttons.tabs.default.fields.background:normal.value'] = $preset['buttons']['background'];
			endif;

			if ( ! empty( $preset['buttons']['background:hover'] ) ):
				$new_preset['buttons.tabs.default.fields.background:hover.value'] = $preset['buttons']['background:hover'];
			endif;

			if ( ! empty( $preset['buttons']['borderColor'] ) ):
				$new_preset['buttons.tabs.default.fields.border:normal.value'] = $preset['buttons']['borderColor'];
			endif;

			if ( ! empty( $preset['buttons']['borderColor:hover'] ) ):
				$new_preset['buttons.tabs.default.fields.border:hover.value'] = $preset['buttons']['borderColor:hover'];
			endif;

			if ( ! empty( $preset['buttons']['color'] ) ):
				$new_preset['buttons.tabs.default.fields.color:normal.value'] = $preset['buttons']['color'];
			endif;

			if ( ! empty( $preset['buttons']['color:hover'] ) ):
				$new_preset['buttons.tabs.default.fields.color:hover.value'] = $preset['buttons']['color:hover'];
			endif;

			if ( ! empty( $preset['buttons']['primaryBackground'] ) ):
				$new_preset['buttons.tabs.primary.fields.background:normal.value'] = $preset['buttons']['primaryBackground'];
			endif;

			if ( ! empty( $preset['buttons']['primaryBackground:hover'] ) ):
				$new_preset['buttons.tabs.primary.fields.background:hover.value'] = $preset['buttons']['primaryBackground:hover'];
			endif;

			if ( ! empty( $preset['buttons']['primaryBorderColor'] ) ):
				$new_preset['buttons.tabs.primary.fields.border:normal.value'] = $preset['buttons']['primaryBorderColor'];
			endif;

			if ( ! empty( $preset['buttons']['primaryBorderColor:hover'] ) ):
				$new_preset['buttons.tabs.primary.fields.border:hover.value'] = $preset['buttons']['primaryBorderColor:hover'];
			endif;

			if ( ! empty( $preset['buttons']['primaryColor'] ) ):
				$new_preset['buttons.tabs.primary.fields.color:normal.value'] = $preset['buttons']['primaryColor'];
			endif;

			if ( ! empty( $preset['buttons']['primaryColor:hover'] ) ):
				$new_preset['buttons.tabs.primary.fields.color:hover.value'] = $preset['buttons']['primaryColor:hover'];
			endif;

			if ( ! empty( $preset['votesbar']['background'] ) ):
				$new_preset['votes-bar.tabs.text.fields.background.value'] = $preset['votesbar']['background'];
			endif;

			if ( ! empty( $preset['votesbar']['color:start'] ) ):
				$new_preset['votes-bar.tabs.bar.fields.color:start.value'] = $preset['votesbar']['color:start'];
			endif;

			if ( ! empty( $preset['votesbar']['color:end'] ) ):
				$new_preset['votes-bar.tabs.bar.fields.color:end.value'] = $preset['votesbar']['color:end'];
			endif;

			if ( ! empty( $preset['votesbar']['color'] ) ):
				$new_preset['votes-bar.tabs.bar.fields.color.value'] = $preset['votesbar']['color'];
			endif;

			if ( ! empty( $preset['typography']['lineHeight'] ) ):
				$new_preset['typography.tabs.general.fields.line-height.value'] = $preset['typography']['lineHeight'];
			endif;

			if ( ! empty( $preset['typography']['fontFamily'] ) ):
				$new_preset['typography.tabs.general.fields.font-family.value'] = $preset['typography']['fontFamily'];
			endif;

			if ( ! empty( $preset['typography']['fontSize'] ) ):
				$new_preset['typography.tabs.general.fields.font-size.value'] = $preset['typography']['fontSize'];
			endif;

			if ( $old_poll['template']['name'] === 'photo-contest' || $old_poll['template']['name'] === 'video-contest' ):
				$new_poll['settings']['design']['template']['name'] = 'media-contest';
			elseif ( $old_poll['template']['name'] === 'opinion' ):
				$new_poll['settings']['design']['template']['name'] = 'opinion';
			elseif ( $old_poll['template']['name'] === 'chartify' ):
				$new_poll['settings']['design']['template']['name'] = 'chartsome';
			elseif ( $old_poll['template']['name'] === 'facebook-like' ):
				$new_poll['settings']['design']['template']['name'] = 'facebook-like';
			elseif ( $old_poll['template']['name'] === 'this-or-that' ):
				$new_poll['settings']['design']['template']['name'] = 'versus';
			elseif ( $old_poll['template']['name'] === 'plaintext' ):
				$new_poll['settings']['design']['template']['name'] = 'default';
			endif;

			foreach ( $new_preset as $name => $value ):
				$steps     = explode( '.', $name );
				$preset    = &$new_poll['settings']['design']['preset'];
				$last_path = &$preset;
				foreach ( $steps as $step ):
					if ( empty( $last_path[ $step ] ) ):
						$last_path[ $step ] = array();
					endif;

					$last_path = &$last_path[ $step ];
				endforeach;

				$last_path = $value;

			endforeach;

			// Save the migration
			update_post_meta( $poll_id, 'question', $new_poll['question'] );
			$total_votes = 0;
			foreach ( $new_poll['choices'] as $choice_index => $choice ):

				$choice_prefix = "choice_{$choice_index}_";
				foreach ( $choice as $section => $section_details ):
					update_post_meta( $poll_id, "{$choice_prefix}{$section}", $section_details );
				endforeach;

				$total_votes += $choice['votes'];

			endforeach;
			update_post_meta( $poll_id, 'votes', $total_votes );
			update_post_meta( $poll_id, 'choices', count( $new_poll['choices'] ) );

			foreach ( $new_poll['settings'] as $section => $section_settings ):
				update_post_meta( $poll_id, "settings_$section", $section_settings );
			endforeach;

			update_post_meta( $poll_id, '_tp_migrated', 'migrated' );

			return true;
		}

		public function yop_polls() {
			global $wpdb;

			$yop_table_name = "{$wpdb->prefix}yop2_polls";

			if ( $wpdb->get_var( "SHOW TABLES LIKE '{$yop_table_name}'" ) == $yop_table_name ) :
				$polls = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM {$yop_table_name} WHERE poll_type = %s", 'poll' ) );
			endif;

			return ! empty( $polls ) ? array_diff( $polls, (array) get_option( 'yop_poll_migrated', array() ) ) : array();

		}

		public function migrate_yop_polls() {
			global $wpdb;
			$polls = $this->yop_polls();

			foreach ( $polls as $poll_id ):
				$poll = $wpdb->get_row( $wpdb->prepare( "SELECT poll_title, poll_start_date, poll_end_date FROM {$wpdb->prefix}yop2_polls WHERE ID = %d", $poll_id ), ARRAY_A );

				if ( ! $poll ):
					continue;
				endif;

				$poll             = array_merge( $poll, $wpdb->get_row( $wpdb->prepare( "SELECT meta_value AS options FROM {$wpdb->prefix}yop2_pollmeta WHERE yop_poll_id = %d AND meta_key = %s", $poll_id, 'options' ), ARRAY_A ) );
				$poll['question'] = $wpdb->get_var( $wpdb->prepare( "SELECT question FROM {$wpdb->prefix}yop2_poll_questions WHERE poll_id = %d", $poll_id ) );
				$poll['options']  = unserialize( $poll['options'] );
				$poll['choices']  = $wpdb->get_results( $wpdb->prepare( "SELECT answer, answer_status, votes, answer_date, answer_modified FROM {$wpdb->prefix}yop2_poll_answers WHERE poll_id = %d ORDER BY question_order", $poll_id ), ARRAY_A );
				$poll['fields']   = $wpdb->get_results( $wpdb->prepare( "SELECT ID, custom_field, required, status FROM {$wpdb->prefix}yop2_poll_custom_fields WHERE poll_id = %d", $poll_id ), ARRAY_A );
				$poll['id']       = $poll_id;

				$this->migrate_yop_poll( $poll );
			endforeach;

		}

		public function migrate_yop_poll( $poll ) {

			$new_poll = array(
				'question' => '',
				'choices'  => array(),
				'settings' => TotalPoll::instance( 'admin/bootstrap' )->get_default_settings(),
			);

			$new_poll['settings']['design']['preset'] = array(
				'votes-bar' => array(
					'tabs' => array(
						'bar' => array(
							'fields' => array(),
						),
					),
				),
			);

			$new_poll['question'] = $poll['question'];

			foreach ( $poll['choices'] as $choice ):
				$new_choice = array(
					'votes'   => $choice['votes'],
					'content' => array(
						'date'    => strtotime( empty( $choice['answer_modified'] ) ? $choice['answer_date'] : $choice['answer_modified'] ),
						'visible' => $choice['answer_status'] === 'active',
						'type'    => 'text',
						'label'   => $choice['answer'],
					),
				);

				$new_poll['choices'][] = $new_choice;
			endforeach;

			if ( $poll['options']['blocking_voters_interval_unit'] === 'days' ):
				$blocking_unit = 1440;
			elseif ( $poll['options']['blocking_voters_interval_unit'] === 'hours' ):
				$blocking_unit = 60;
			else:
				$blocking_unit = 1;
			endif;

			if ( ! empty( $poll['options']['blocking_voters'] ) && in_array( 'cookie', $poll['options']['blocking_voters'] ) ):
				$new_poll['settings']['limitations']['cookies']['enabled'] = 'yes';
				$new_poll['settings']['limitations']['cookies']['timeout'] = absint( $poll['options']['blocking_voters_interval_value'] ) * $blocking_unit;
			endif;

			if ( ! empty( $poll['options']['blocking_voters'] ) && in_array( 'ip', $poll['options']['blocking_voters'] ) ):
				$new_poll['settings']['limitations']['ip']['enabled'] = 'yes';
				$new_poll['settings']['limitations']['ip']['timeout'] = absint( $poll['options']['blocking_voters_interval_value'] ) * $blocking_unit;
			endif;

			if ( ! empty( $poll['options']['add_other_answers_to_default_answers'] ) && $poll['options']['add_other_answers_to_default_answers'] === 'yes' ):
				$new_poll['settings']['choices']['other']['enabled'] = 'yes';
			endif;

			if ( empty( $poll['options']['view_results_link'] ) ):
				$new_poll['settings']['limitations']['results']['require_vote']['enabled'] = 'yes';
			endif;

			if ( ! empty( $poll['options']['allow_multiple_answers'] ) ):
				$new_poll['settings']['limitations']['selection']['maximum'] = absint( $poll['options']['allow_multiple_answers_number'] );
				$new_poll['settings']['limitations']['selection']['minimum'] = absint( $poll['options']['allow_multiple_answers_min_number'] );
			endif;

			if ( ! empty( $poll['options']['poll_start_date'] ) ):
				$new_poll['settings']['limitations']['date']['enabled'] = 'yes';
				$new_poll['settings']['limitations']['date']['start']   = strtotime( $poll['options']['poll_start_date'] );
			endif;

			if ( ! empty( $poll['options']['poll_end_date'] ) ):
				$new_poll['settings']['limitations']['date']['enabled'] = 'yes';
				$new_poll['settings']['limitations']['date']['end']     = strtotime( $poll['options']['poll_end_date'] );
			endif;

			if ( ! empty( $poll['options']['view_results_type'] ) ):
				if ( $poll['options']['view_results_type'] === 'votes-number' || $poll['options']['view_results_type'] === 'votes-number-and-percentages' ):
					$new_poll['settings']['results']['format']['votes'] = 'yes';
				endif;
				if ( $poll['options']['view_results_type'] === 'percentages' || $poll['options']['view_results_type'] === 'votes-number-and-percentages' ):
					$new_poll['settings']['results']['format']['percentages'] = 'yes';
				endif;
			endif;

			if ( ! empty( $poll['options']['vote_permisions'] ) && in_array( 'registered', $poll['options']['vote_permisions'] ) ):
				$new_poll['settings']['limitations']['membership']['enabled'] = 'yes';
				foreach ( get_editable_roles() as $role => $details ):
					$new_poll['settings']['limitations']['membership']['type'][] = $role;
				endforeach;
			endif;

			if ( ! empty( $poll['options']['bar_background'] ) ):
				$new_poll['settings']['design']['preset']['votes-bar']['tabs']['bar']['fields'] = array(
					'color:start' => array( 'value' => '#' . $poll['options']['bar_background'] ),
					'color:end'   => array( 'value' => '#' . $poll['options']['bar_background'] ),
				);
			endif;

			if ( ! empty( $poll['options']['sorting_results'] ) ):
				$new_poll['settings']['results']['order']['enabled']   = 'yes';
				$new_poll['settings']['results']['order']['direction'] = $poll['options']['sorting_results_direction'];

				if ( $poll['options']['sorting_results'] === 'as_defined' ):
					$new_poll['settings']['results']['order']['by'] = 'date';
				elseif ( $poll['options']['sorting_results'] === 'alphabetical' ):
					$new_poll['settings']['results']['order']['by'] = 'label';
				elseif ( $poll['options']['sorting_results'] === 'votes' ):
					$new_poll['settings']['results']['order']['by'] = 'votes';
				endif;
			endif;

			if ( ! empty( $poll['fields'] ) ):
				foreach ( $poll['fields'] as $field ):
					if ( $field['status'] !== 'active' || empty( $field['custom_field'] ) ):
						continue;
					endif;

					$new_poll['settings']['fields'][] = array(
						'type'        => 'text',
						'name'        => sanitize_title_with_dashes( $field['custom_field'] ),
						'label'       => array( 'content' => $field['custom_field'], 'attributes' => array( 'class' => '' ) ),
						'class'       => '',
						'validations' => array(
							'filled' => $field['required'] === 'yes' ? array( 'enabled' => 'yes' ) : array(),
							'email'  => stripos( $field['custom_field'], 'email' ) !== false ? array( 'enabled' => 'yes' ) : array(),
						),
						'template'    => '%label% %field%',
					);
				endforeach;
			endif;

			$new_poll['settings']['logs']['enabled'] = 'yes';

			$poll_id = wp_insert_post( array(
				'post_title'   => wp_strip_all_tags( $poll['poll_title'] ),
				'post_content' => '',
				'post_status'  => 'pending',
				'post_type'    => 'poll',
			) );

			if ( ! $poll_id ):
				return false;
			endif;

			// Save the migration
			update_post_meta( $poll_id, 'question', $new_poll['question'] );
			$total_votes = 0;
			foreach ( $new_poll['choices'] as $choice_index => $choice ):

				$choice_prefix = "choice_{$choice_index}_";
				foreach ( $choice as $section => $section_details ):
					update_post_meta( $poll_id, "{$choice_prefix}{$section}", $section_details );
				endforeach;

				$total_votes += $choice['votes'];

			endforeach;
			update_post_meta( $poll_id, 'votes', $total_votes );
			update_post_meta( $poll_id, 'choices', count( $new_poll['choices'] ) );

			foreach ( $new_poll['settings'] as $section => $section_settings ):
				update_post_meta( $poll_id, "settings_$section", $section_settings );
			endforeach;

			add_option( 'yop_poll_migrated', $poll['id'] );

			return true;
		}

		public function wppolls_polls() {
			global $wpdb;

			$wppolls_table_name = "{$wpdb->prefix}pollsq";

			if ( $wpdb->get_var( "SHOW TABLES LIKE '{$wppolls_table_name}'" ) == $wppolls_table_name ) :
				$polls = $wpdb->get_col( "SELECT pollq_id FROM {$wppolls_table_name}" );
			endif;

			return ! empty( $polls ) ? array_diff( $polls, (array) get_option( 'wppolls_poll_migrated', array() ) ) : array();

		}

		public function migrate_wppolls_polls() {
			global $wpdb;
			$polls = $this->wppolls_polls();

			if ( ! empty( $polls ) ):

				$options      = array();
				$options_keys = array( 'poll_bar', 'poll_ans_sortby', 'poll_ans_sortorder', 'poll_ans_result_sortby', 'poll_ans_result_sortorder', 'poll_allowtovote', 'poll_cookielog_expiry' );
				foreach ( $options_keys as $option ):
					$options[ $option ] = get_option( $option, false );
				endforeach;

				foreach ( $polls as $poll_id ):
					$poll = $wpdb->get_row( $wpdb->prepare( "SELECT pollq_question AS question, pollq_timestamp AS start_date, pollq_expiry AS end_date,pollq_multiple AS multiple FROM {$wpdb->prefix}pollsq WHERE pollq_id = %d", $poll_id ), ARRAY_A );

					if ( ! $poll ):
						continue;
					endif;

					$poll['options'] = &$options;
					$poll['choices'] = $wpdb->get_results( $wpdb->prepare( "SELECT polla_answers AS label, polla_votes AS votes FROM {$wpdb->prefix}pollsa WHERE polla_qid = %d", $poll_id ), ARRAY_A );
					$poll['id']      = $poll_id;

					$this->migrate_wppolls_poll( $poll );
				endforeach;

			endif;

		}

		public function migrate_wppolls_poll( $poll ) {

			$new_poll = array(
				'question' => '',
				'choices'  => array(),
				'settings' => TotalPoll::instance( 'admin/bootstrap' )->get_default_settings(),
			);

			$new_poll['settings']['design']['preset'] = array(
				'votes-bar' => array(
					'tabs' => array(
						'bar' => array(
							'fields' => array(),
						),
					),
				),
			);

			$new_poll['question'] = $poll['question'];

			foreach ( $poll['choices'] as $choice ):
				$new_choice = array(
					'votes'   => (int) $choice['votes'],
					'content' => array(
						'date'    => current_time( 'timestamp' ),
						'visible' => true,
						'type'    => 'text',
						'label'   => $choice['label'],
					),
				);

				$new_poll['choices'][] = $new_choice;
			endforeach;

			if ( ! empty( $poll['options']['poll_cookielog_expiry'] ) ):
				$new_poll['settings']['limitations']['cookies']['enabled'] = 'yes';
				$new_poll['settings']['limitations']['cookies']['timeout'] = absint( $poll['options']['poll_cookielog_expiry'] );
			endif;

			if ( ! empty( $poll['start_date'] ) ):
				$new_poll['settings']['limitations']['date']['enabled'] = 'yes';
				$new_poll['settings']['limitations']['date']['start']   = $poll['start_date'];
			endif;

			if ( ! empty( $poll['end_date'] ) ):
				$new_poll['settings']['limitations']['date']['enabled'] = 'yes';
				$new_poll['settings']['limitations']['date']['end']     = $poll['end_date'];
			endif;

			if ( ! empty( $poll['options']['poll_allowtovote'] ) && $poll['options']['poll_allowtovote'] == 1 ):
				$new_poll['settings']['limitations']['membership']['enabled'] = 'yes';
				foreach ( get_editable_roles() as $role => $details ):
					$new_poll['settings']['limitations']['membership']['type'][] = $role;
				endforeach;
			endif;

			if ( ! empty( $poll['options']['poll_bar'] ) ):
				$new_poll['settings']['design']['preset']['votes-bar']['tabs']['bar']['fields'] = array(
					'color:start' => array( 'value' => '#' . $poll['options']['poll_bar']['background'] ),
					'color:end'   => array( 'value' => '#' . $poll['options']['poll_bar']['background'] ),
				);
			endif;

			if ( ! empty( $poll['options']['poll_ans_sortby'] ) ):
				$new_poll['settings']['choices']['order']['enabled']   = 'yes';
				$new_poll['settings']['choices']['order']['direction'] = $poll['options']['poll_ans_sortorder'];

				if ( $poll['options']['poll_ans_sortby'] === 'polla_aid' ):
					$new_poll['settings']['choices']['order']['by'] = 'date';
				elseif ( $poll['options']['poll_ans_sortby'] === 'polla_answers' ):
					$new_poll['settings']['choices']['order']['by'] = 'label';
				elseif ( $poll['options']['poll_ans_sortby'] === 'RAND()' ):
					$new_poll['settings']['choices']['order']['by'] = 'random';
				endif;
			endif;

			if ( ! empty( $poll['options']['poll_ans_result_sortby'] ) ):
				$new_poll['settings']['results']['order']['enabled']   = 'yes';
				$new_poll['settings']['results']['order']['direction'] = $poll['options']['poll_ans_result_sortorder'];

				if ( $poll['options']['poll_ans_result_sortby'] === 'polla_votes' ):
					$new_poll['settings']['results']['order']['by'] = 'votes';
				elseif ( $poll['options']['poll_ans_result_sortby'] === 'polla_aid' ):
					$new_poll['settings']['results']['order']['by'] = 'date';
				elseif ( $poll['options']['poll_ans_result_sortby'] === 'polla_answers' ):
					$new_poll['settings']['results']['order']['by'] = 'label';
				elseif ( $poll['options']['poll_ans_result_sortby'] === 'RAND()' ):
					$new_poll['settings']['results']['order']['by'] = 'random';
				endif;
			endif;

			$new_poll['settings']['logs']['enabled'] = 'yes';

			$poll_id = wp_insert_post( array(
				'post_title'   => wp_strip_all_tags( $poll['question'] ),
				'post_content' => '',
				'post_status'  => 'pending',
				'post_type'    => 'poll',
			) );

			if ( ! $poll_id ):
				return false;
			endif;

			// Save the migration
			update_post_meta( $poll_id, 'question', $new_poll['question'] );
			$total_votes = 0;
			foreach ( $new_poll['choices'] as $choice_index => $choice ):

				$choice_prefix = "choice_{$choice_index}_";
				foreach ( $choice as $section => $section_details ):
					update_post_meta( $poll_id, "{$choice_prefix}{$section}", $section_details );
				endforeach;

				$total_votes += $choice['votes'];

			endforeach;
			update_post_meta( $poll_id, 'votes', $total_votes );
			update_post_meta( $poll_id, 'choices', count( $new_poll['choices'] ) );

			foreach ( $new_poll['settings'] as $section => $section_settings ):
				update_post_meta( $poll_id, "settings_$section", $section_settings );
			endforeach;

			add_option( 'wppolls_poll_migrated', $poll['id'] );

			return true;
		}

	}


endif;