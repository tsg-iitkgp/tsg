<?php
/**
 * Main class for WP-Admin hooks
 *
 * This class loads all of our backend hooks and sets up admin interfaces
 *
 * @subpackage Admin interfaces
 * @author Sibin Grasic
 * @since 1.0
 * @var version - plugin version
 * @var opts - plugin options
 */
class Meks_TA_Admin {

	private $version;
	private $opts;

	/**
	 *  Meks TA Admin constructor
	 *
	 * Constructor first checks for the plugin version, and if this is the first activation, plugin adds version info in the DB with autoload option set to false.
	 * That way we can easily update across versions, if we decide to add options to the plugin, or change plugin settings and defaults
	 *
	 * @author Sibin Grasic
	 * @since 1.0
	 */
	public function __construct() {
		if ( $ta_ver = get_option( 'meks_ta_ver' ) ) {
			$this->version = $ta_ver;
		} else {
			$ta_ver = MEKS_TA_VER;
			add_option( 'meks_ta_ver', $ta_ver, '', 'no' );
		}

		$default_opts = array(
			'active'   => array( 'date' => true, 'time' => true, 'modified_date' => false, 'modified_time' => false ),
			'position' => 'after',
			'time'    => array( 'number' => '12', 'type'  => 'months' ),
			'ago_label' => ''
		);

		$default_opts = apply_filters( 'meks_ta_modify_default_opts', $default_opts );

		$this->opts = get_option( 'meks_ta_opts', $default_opts );

		$this->opts = $this->parse_args( $this->opts, $default_opts);

		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_filter( 'plugin_action_links_'.MEKS_TA_BASENAME, array( &$this, 'add_settings_link' ) );

	}

	/**
	 * Function that is hooked into the admin initialisation and registers settings
	 *
	 * @return void
	 * @author Sibin Grasic
	 * @since 1.0
	 */
	public function register_settings() {
		
		add_settings_section( 'meks_ta_opts', 'Meks Time Ago Options', array( &$this, 'settings_section_info' ), 'general' );
		
		add_settings_field( 'meks_ta_opts[active]', __( 'Apply "time ago" format to', 'meks-time-ago' ), array( &$this, 'active_callback' ), 'general', 'meks_ta_opts', $this->opts['active'] );
		add_settings_field( 'meks_ta_opts[time]', __( 'Apply to posts not older than', 'meks-time-ago' ), array( &$this, 'time_callback' ), 'general', 'meks_ta_opts', $this->opts['time'] );
		add_settings_field( 'meks_ta_opts[position]', __( 'Place "ago" word', 'meks-time-ago' ), array( &$this, 'position_callback' ), 'general', 'meks_ta_opts', $this->opts['position'] );
		add_settings_field( 'meks_ta_opts[ago_label]', __( 'Rewrite "ago" word', 'meks-time-ago' ), array( &$this, 'ago_label_callback' ), 'general', 'meks_ta_opts', $this->opts['ago_label'] );

		register_setting( 'general', 'meks_ta_opts', array( &$this, 'sanitize_opts' ) );
	}

	/**
	 * Function that displays the section heading information
	 *
	 * @author Sibin Grasic
	 * @since 1.0
	 */
	public function settings_section_info() {
		echo '<div id="mkstimeago"></div>';
	}

	/**
	 * Function that displays the plugin activation checkbox
	 *
	 * @author Sibin Grasic
	 * @since 1.0
	 */
	public function active_callback( $active ) {
	    $checked_date = checked( $active['date'], 1, false );
        $checked_time = checked( $active['time'], 1, false );
        $checked_modified_date = checked( $active['modified_date'], 1, false );
        $checked_modified_time = checked( $active['modified_time'], 1, false );

        echo "<input type=\"checkbox\" name=\"meks_ta_opts[active][date]\" ${checked_date}>".__( 'Date', 'meks-time-ago' ).'&nbsp;&nbsp;&nbsp;';
		echo "<input type=\"checkbox\" name=\"meks_ta_opts[active][time]\" ${checked_time}>".__( 'Time', 'meks-time-ago' ).'&nbsp;&nbsp;&nbsp;';
		echo "<input type=\"checkbox\" name=\"meks_ta_opts[active][modified_date]\" ${checked_modified_date}>".__( 'Date (modified)', 'meks-time-ago' ).'&nbsp;&nbsp;&nbsp;';
		echo "<input type=\"checkbox\" name=\"meks_ta_opts[active][modified_time]\" ${checked_modified_time}>".__( 'Time (modified)', 'meks-time-ago' );
	}

	/**
	 * Function that displays the string position select box
	 *
	 * @author Sibin Grasic
	 * @since 1.0
	 */
	public function position_callback( $position ) {
		
		$positions = apply_filters( 'meks_ta_position_opts', array( 'after' => __( 'After (1 hour ago)', 'meks-time-ago' ), 'before' => __( 'Before (ago 1 hour)', 'meks-time-ago' ) ) );

		$html = '<select name="meks_ta_opts[position]">';

		foreach ( $positions as $value => $label ) {

			$selected = selected( $position, $value, false );
			$html.= '<option value="'.$value.'" '.$selected.'>'.$label.'</option>';

		}

		$html .= '</select>';

		echo $html;
	}

	/**
	 * Function that displays the hours input
	 *
	 * @author Sibin Grasic
	 * @since 1.0
	 */
	public function time_callback( $time ) {
		$type = $time['type'];
		$time = $time['number'];
		$minutes = $hours = $days = $months = '';
		$$type = 'selected';

		echo "<input type=\"number\" name=\"meks_ta_opts[time][number]\" value=\"${time}\" class=\"small-text\" style=\"height: 28px; vertical-align: top;\">";
		echo sprintf( '<select name="meks_ta_opts[time][type]"><option value="minutes" %1$s>%2$s</option><option value="hours" %3$s>%4$s</option><option value="days" %5$s>%6$s</option><option value="months" %7$s>%8$s</option>',
			$minutes,
			__( 'Minutes', 'meks-time-ago' ),
			$hours,
			__( 'Hours', 'meks-time-ago' ),
			$days,
			__( 'Days', 'meks-time-ago' ),
			$months,
			__( 'Months', 'meks-time-ago' )
		);

	}

	/**
	 * Display ago label setting
	 *
	 * @since 1.1
	 */
	
	public function ago_label_callback( $ago_label ) {
	
		echo '<input type="text" name="meks_ta_opts[ago_label]" value="'.esc_attr($ago_label).'"/>';
	}

	/**
	 * Function that sanitizes plugin options on save
	 *
	 * @author Sibin Grasic
	 * @since 1.0
	 * @param array   $opts Meks Time Ago options
	 * @return array $opts Sanitized options array
	 *
	 */
	public function sanitize_opts( $opts ) {

	    $options_active = array('date', 'time', 'modified_date', 'modified_time');

        foreach ($options_active as $option_active) {
            if ( isset( $opts['active'][$option_active] )) {
                $opts['active'][$option_active] = true;
            } else {
                $opts['active'][$option_active] = false;
            }
	    }

		$opts['ago_label'] = esc_html( $opts['ago_label'] );
		return $opts;
	}

	public function add_settings_link( $links ) {
		$admin_url = admin_url();
		$link = array( '<a href="'.$admin_url.'options-general.php#mkstimeago">Settings</a>' );

		return array_merge( $links, $link );
	}


	/**
	 * Parse args ( merge arrays )
	 *
	 * Similar to wp_parse_args() but extended to also merge multidimensional arrays
	 *
	 * @param array   $a - set of values to merge
	 * @param array   $b - set of default values
	 * @return array Merged set of elements
	 * @since  1.1.3
	 */

	function parse_args( &$a, $b ) {
		$a = (array) $a;
		$b = (array) $b;
		$r = $b;
		foreach ( $a as $k => &$v ) {
			if ( is_array( $v ) && isset( $r[ $k ] ) ) {
				$r[ $k ] = $this->parse_args( $v, $r[ $k ] );
			} else {
				$r[ $k ] = $v;
			}
		}
		return $r;
	}
}

?>