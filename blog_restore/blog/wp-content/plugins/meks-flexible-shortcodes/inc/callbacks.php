<?php

/* Column shortcodes */
if ( !function_exists( 'mks_columns_sc' ) ) :
	function mks_columns_sc( $atts, $content = false, $tag ) {
		extract( shortcode_atts(  array( 'class' => '' ), $atts ) );
		$output = '<div class="'.$tag.'">' . do_shortcode( $content ) . '</div>';
		return $output;
	}
endif;

/* Button shortcode */
if ( !function_exists( 'mks_button_sc' ) ) :
	function mks_button_sc( $atts, $content = false, $tag ) {
		extract( shortcode_atts(  array( 'size' => 'large', 'style' => 'rounded', 'title' => '', 'url' => '#', 'target' => '_self', 'icon' => '', 'bg_color' => '#000000', 'txt_color' => '#ffffff', 'icon_type' => 'fa', 'nofollow' => 0 ), $atts ) );
		$inl_style = 'style="color: '.$txt_color.'; background-color: '.$bg_color.'"';
		$icon_type = ( $icon_type == 'fa' ) ? 'fa ' : '';
		$icon = $icon ? '<i class="'.$icon_type.$icon.'"></i>' : '';
		$nofollow = $nofollow ? 'rel="nofollow"' : '';
		$output = '<a class="mks_button mks_button_'.$size.' '.$style.'" href="'.$url.'" target="'.$target.'" '.$inl_style.' '.$nofollow.'>' . $icon . $title . '</a>';
		return $output;
	}
endif;

/* Dropcap shortcode */
if ( !function_exists( 'mks_dropcap_sc' ) ) :
	function mks_dropcap_sc( $atts, $content = false, $tag ) {
		extract( shortcode_atts(  array( 'style' => 'letter', 'size'=> 52, 'bg_color' => '#ffffff', 'txt_color' => '#000000' ), $atts ) );

		$apply_bg_color = true;
		switch ( $style ) {
		case 'letter': $class = 'mks_dropcap_letter'; $apply_bg_color = false; break;
		case 'square': $class = 'mks_dropcap'; break;
		case 'circle': $class = 'mks_dropcap mks_dropcap_circle'; break;
		case 'rounded': $class = 'mks_dropcap mks_dropcap_rounded'; break;
		default: $class = 'mks_dropcap_letter'; break;
		}

		$inl_style = 'style="font-size: '.absint( $size ).'px; color: '.$txt_color.'; ';
		if ( $apply_bg_color ) {
			$inl_style .= 'background-color: '.$bg_color.';';
		}
		$inl_style .= '"';

		$output = '<span class="'.$class.'" '.$inl_style.'>' . $content . '</span>';
		return $output;
	}
endif;

/* Pullquote shortcode */
if ( !function_exists( 'mks_pullquote_sc' ) ) :
	function mks_pullquote_sc( $atts, $content = false, $tag ) {
		extract( shortcode_atts(  array( 'align' => 'left', 'width' => 300, 'size' => 24, 'bg_color' => '#000000', 'txt_color' => '#ffffff' ), $atts ) );
		$output = '<div class="mks_pullquote mks_pullquote_'.$align.'" style="width:'.absint( $width ).'px; font-size: '.$size.'px; color: '.$txt_color.'; background-color:'.$bg_color.';">' . do_shortcode( $content ) . '</div>';
		return $output;
	}
endif;

/* Separator shortcode */
if ( !function_exists( 'mks_separator_sc' ) ) :
	function mks_separator_sc( $atts, $content = false, $tag ) {
		extract( shortcode_atts(  array( 'height' => 2, 'style' => 'solid' ), $atts ) );
		if ( $style == 'blank' ) {
			$inl_css = 'style="height: '.absint( $height ).'px;"';
		} else {
			$inl_css = 'style="border-bottom: '.absint( $height ).'px '.$style.';"';
		}
		$output = '<div class="mks_separator" '.$inl_css.'>' . $content . '</div>';
		return $output;
	}
endif;

/* Highlight shortcode */
if ( !function_exists( 'mks_highlight_sc' ) ) :
	function mks_highlight_sc( $atts, $content = false, $tag ) {
		extract( shortcode_atts(  array( 'color' => '#ffffff' ), $atts ) );
		$output = '<span class="mks_highlight" style="background-color: '.$color.'">' . $content . '</span>';
		return $output;
	}
endif;

/* Social Icon Shortcode */
if ( !function_exists( 'mks_social_sc' ) ) :
	function mks_social_sc( $atts, $content = false, $tag ) {
		extract( shortcode_atts(  array( 'icon' => '', 'style'=> 'square', 'size' => 48, 'url' => '', 'target' => '_blank' ), $atts ) );

		$output = '<a href="'.$url.'" class="mks_ico '.$icon.'_ico '.$style.'" target="'.$target.'" style="width: '.absint( $size ).'px; height: '.absint( $size ).'px;">'.$icon.'</a>';
		return $output;
	}
endif;

/* Icon Shortcode */
if ( !function_exists( 'mks_icon_sc' ) ) :
	function mks_icon_sc( $atts, $content = false, $tag ) {
		extract( shortcode_atts(  array( 'icon' => '', 'type' => 'fa', 'color' => '#000000' ), $atts ) );
		$class = ( $type == 'fa' ) ? $type.' ' : '';
		$output = '<i class="'.$class.$icon.'" style="color: '.$color.'"></i>';
		return $output;
	}
endif;

/* Progress Bar Shortcode */
if ( !function_exists( 'mks_progressbar_sc' ) ) :
	function mks_progressbar_sc( $atts, $content = false, $tag ) {
		extract( shortcode_atts(  array( 'name' => '', 'level' => '', 'value' => 80, 'color' => '#000000', 'height' => 20, 'style' => '' ), $atts ) );
		$output = '<div class="mks_progress_bar">';
		if ( !empty( $name ) || !empty( $level ) ) {
			$output .= '<div class="mks_progress_label">'.$name.'<span class="mks_progress_name">'.$level.'</span></div>';
		}
		$output .= '<div class="mks_progress_level '.$style.'" style="height: '.absint( $height ).'px; background-color: '.mks_hex2rgba( $color, '0.5' ).';"><div class="mks_progress_level_set" style="width: '.absint( $value ).'%; background-color: '.$color.';"></div></div>';
		$output .= '</div>';

		return $output;
	}
endif;

/* Accordion Wrap Shortcode */
if ( !function_exists( 'mks_accordion_sc' ) ) :
	function mks_accordion_sc( $atts, $content = false, $tag ) {
		$output = '<div class="mks_accordion">'.do_shortcode( $content ).'</div>';
		return $output;
	}
endif;

/* Accordion Item Shortcode */
if ( !function_exists( 'mks_accordion_item_sc' ) ) :
	function mks_accordion_item_sc( $atts, $content = false, $tag ) {
		extract( shortcode_atts(  array( 'title' => 'Title' ), $atts ) );

		$output = '<div class="mks_accordion_item">
			<div class="mks_accordion_heading">'.$title.'<i class="fa fa-plus"></i><i class="fa fa-minus"></i></div>
				<div class="mks_accordion_content">'.do_shortcode( $content ).'</div>
			</div>';
		return $output;
	}
endif;

/* Toggle Shortcode */
if ( !function_exists( 'mks_toggle_sc' ) ) :
	function mks_toggle_sc( $atts, $content = false, $tag ) {
		extract( shortcode_atts(  array( 'title' => 'Title', 'state'=> '' ), $atts ) );

		$active = $state == 'open' ? ' mks_toggle_active': '';

		$output = '<div class="mks_toggle'.$active.'">
			<div class="mks_toggle_heading">'.$title.'<i class="fa fa-plus"></i><i class="fa fa-minus"></i></div>
				<div class="mks_toggle_content">'.do_shortcode( $content ).'</div>
			</div>';
		return $output;
	}
endif;

/* Tabs Wrap Shortcode */
if ( !function_exists( 'mks_tabs_sc' ) ) :
	function mks_tabs_sc( $atts, $content = false, $tag ) {
		extract( shortcode_atts(  array( 'nav' => 'horizontal', 'state'=> '' ), $atts ) );
		$output = '<div class="mks_tabs '.$nav.'"><div class="mks_tabs_nav"></div>'.do_shortcode( $content ).'</div>';
		return $output;
	}
endif;

/* Accordion Item Shortcode */
if ( !function_exists( 'mks_tab_item_sc' ) ) :
	function mks_tab_item_sc( $atts, $content = false, $tag ) {
		extract( shortcode_atts(  array( 'title' => 'Title' ), $atts ) );

		$output = '<div class="mks_tab_item"><div class="nav">'.$title.'</div>'.do_shortcode( $content ).'</div>';
		return $output;
	}
endif;


?>