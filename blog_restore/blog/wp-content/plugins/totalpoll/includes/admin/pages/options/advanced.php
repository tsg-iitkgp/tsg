<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>
<div class="totalpoll-tab-content settings-tab-content settings-sharing" data-tp-tab-content="advanced">

    <div class="settings-field">
        <label>
            <input type="checkbox" name="totalpoll[options][advanced][css_cache_alt][enabled]" <?php checked( empty( $this->options['advanced']['css_cache_alt']['enabled'] ), false ); ?>>
			<?php _e( 'Always embed CSS with poll HTML', TP_TD ); ?>
        </label>

        <p class="totalpoll-feature-tip"><?php _e( "Useful for websites with custom file system.", TP_TD ); ?></p>
    </div>

</div>