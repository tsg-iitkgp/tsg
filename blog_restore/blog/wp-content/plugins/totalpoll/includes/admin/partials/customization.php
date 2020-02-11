<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>
<style type="text/css">
    .totalpoll-customization {
        padding: 20px 10px;
        text-align: center;
        line-height: 1.5;
    }

    .totalpoll-customization img {
        max-width: 96px;
        margin-bottom: 20px;
    }

    .totalpoll-customization .title {
        font-size: 1.5em;
        margin: 15px 0;
    }

    .totalpoll-customization .copy {
        margin: 15px 0 20px;
        opacity: 0.9;
    }
</style>
<div class="totalpoll-customization">
    <img src="<?php echo TP_URL . 'assets/images/customization.gif'; ?>" alt="Customization">

    <div class="title"><?php _e( 'Customization?<br>We have got your back!', 'totalpoll' ); ?></div>
    <div class="copy"><?php _e( 'If you need custom feature just let us know we will be happy to serve you!', 'totalpoll' ); ?></div>

    <a href="<?php echo TP_SUPPORT; ?>?utm_campaign=customization&utm_medium=in-app&utm_source=totalpoll-pro" target="_blank" class="button button-primary button-large"><?php _e( 'Contact Us', 'totalpoll' ); ?></a>
</div>