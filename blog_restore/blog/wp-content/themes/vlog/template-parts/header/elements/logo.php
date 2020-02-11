<?php
global $vlog_h1_used;
$logo = vlog_get_option( 'logo' );
$brand = !empty( $logo ) ? '<img class="vlog-logo" src="'.esc_url( $logo ).'" alt="'.esc_attr( get_bloginfo( 'name' ) ).'" >' : get_bloginfo( 'name' );
$logo_only_class = !empty( $logo ) && !vlog_get_option( 'header_site_desc' ) ? 'vlog-logo-only' : '';
?>

<div class="vlog-site-branding <?php echo esc_attr( $logo_only_class ); ?>">

	<?php if ( is_front_page() && empty($vlog_h1_used) ) : ?>
		<h1 class="site-title h1"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php echo $brand; ?></a></h1>
	<?php else : ?>
		<span class="site-title h1"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php echo $brand; ?></a></span>
	<?php endif; ?>

	<?php if ( vlog_get_option( 'header_site_desc' ) ): ?>
		<?php get_template_part( 'template-parts/header/elements/site-desc' ); ?>
	<?php endif; ?>
</div>

<?php $vlog_h1_used = true; ?>