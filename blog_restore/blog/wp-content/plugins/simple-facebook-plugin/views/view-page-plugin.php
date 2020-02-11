<?php 
// Check if shortcode return string and not bool 
if ( is_string( $hide_cover ) ) 	$hide_cover 	= ( $hide_cover 	== 'true') ? true : false;
if ( is_string( $show_facepile 	) ) $show_facepile 	= ( $show_facepile 	== 'true') ? true : false;
if ( is_string( $small_header 	) ) $small_header 	= ( $small_header 	== 'true') ? true : false;
if ( is_string( $timeline ) ) 		$timeline 		= ( $timeline 		== 'true') ? true : false;
if ( is_string( $events ) ) 		$events 		= ( $events 		== 'true') ? true : false;
if ( is_string( $messages ) ) 		$messages 		= ( $messages 		== 'true') ? true : false;

$like_box_classes = array( "sfp-container" );
$like_box_classes = apply_filters( "sfp_like_box_classes", $like_box_classes, $instance );
$like_box_classes = implode( " ", $like_box_classes );

$tabs = array();

if ( $timeline === true ) 	$tabs[] = "timeline";
if ( $events === true ) 	$tabs[] = "events";
if ( $messages === true ) 	$tabs[] = "messages";   

?>
<div id="fb-root"></div>
<script>
	(function(d){
		var js, id = 'facebook-jssdk';
		if (d.getElementById(id)) {return;}
		js = d.createElement('script');
		js.id = id;
		js.async = true;
		js.src = "//connect.facebook.net/<?php echo $locale; ?>/all.js#xfbml=1";
		d.getElementsByTagName('head')[0].appendChild(js);
	}(document));
</script>
<!-- SFPlugin by topdevs.net -->
<!-- Page Plugin Code START -->
<div class="<?php echo $like_box_classes; ?>">
	<div class="fb-page"
		data-href="<?php echo $url; ?>"
		data-width="<?php echo $width; ?>"
		data-height="<?php echo $height; ?>"
		data-hide-cover="<?php echo ( $hide_cover ) ? 'true' : 'false'; ?>"
		data-show-facepile="<?php echo ( $show_facepile ) ? 'true' : 'false' ;?>"
		data-small-header="<?php echo ( $small_header ) ? 'true' : 'false' ;?>"
		data-tabs="<?php echo implode(",", $tabs) ;?>">
	</div>
</div>
<!-- Page Plugin Code END -->