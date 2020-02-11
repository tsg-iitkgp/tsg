<div class="wrap">
	<h2>Simple Facebook Menu</h2>
	
	<form method="POST" action="">
		<select name="locale">
		<?php foreach ( $this->locales as $code => $name ) : ?>
			<option <?php selected(( $options['locale'] == $code ) ? 1 : 0 ); ?> value="<?php echo $code; ?>" ><?php echo $name; ?></option>
		<?php endforeach; ?>
		</select>

		<p class="description">
		Some Description
		</p>

		<?php submit_button('Save', 'primary', 'sfp_options_saved'); ?>
	</form>

	<?php var_dump( $options ); ?>
</div>