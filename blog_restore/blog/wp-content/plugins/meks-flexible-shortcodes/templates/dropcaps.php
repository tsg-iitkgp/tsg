<form id="mks_shortcode_dropcaps">
	<table class="form-table">
		<tbody>
			<tr>
		 		<th><h3><?php _e('Options','meks-flexible-shortcodes'); ?></h3></th><td>&nbsp;</td>
			</tr>
	  <tr>
				<th>
					<?php _e('Style','meks-flexible-shortcodes'); ?>:
				</th>
				<td>
					<input type="radio" name="style" value="letter" checked /> <?php _e('Letter only (no background)','meks-flexible-shortcodes'); ?><br/>
					<input type="radio" name="style" value="square" /> <?php _e('Square','meks-flexible-shortcodes'); ?><br/>
					<input type="radio" name="style" value="circle" /> <?php _e('Circle','meks-flexible-shortcodes'); ?><br/>
					<input type="radio" name="style" value="rounded" /> <?php _e('Rounded Corners','meks-flexible-shortcodes'); ?>
				</td>
		</tr>
		<tr>
				<th><?php _e('Font size','meks-flexible-shortcodes'); ?>:</th>
				<td><input type="text" name="size" value="52" class="small-text"/> px </td>
		</tr>
		<tr>
				<th><?php _e('Background Color','meks-flexible-shortcodes'); ?>:</th>
				<td><input id="mks_dropcap_bg_color" type="text" name="bg_color" value="#ffffff"/></td>
		</tr>
		<tr>
				<th><?php _e('Text Color','meks-flexible-shortcodes'); ?>:</th>
				<td><input id="mks_dropcap_txt_color" type="text" name="txt_color" value="#000000"/></td>
		</tr>
		<tr>
				<th><input type="submit" class="button-primary" value="<?php _e('Insert Dropcap','meks-flexible-shortcodes'); ?>"></th> 
				<td>&nbsp;</td>
		</tr>
	</tbody>
	</table>
</form>

<script type="text/javascript">
	/* <![CDATA[ */
  (function($) {
    	$('#mks_shortcode_dropcaps').submit(function(e) {
    			e.preventDefault();
    			mks_shortcode_modal_obj.dialog('close');
    			var style = $(this).find('input[name="style"]:checked').val();
    			var size = $(this).find('input[name="size"]').val();
    			var bg_color = $(this).find('input[name="bg_color"]').val();
    			var txt_color = $(this).find('input[name="txt_color"]').val();
    			var content = '[mks_dropcap style="'+style+'" size="'+size+'" bg_color="'+bg_color+'" txt_color="'+txt_color+'"][/mks_dropcap]';
    			mks_shortcode.setContent(content);
			});
			
			if($('#mks_dropcap_bg_color').length && jQuery.isFunction(jQuery.fn.wpColorPicker)){
    		$('#mks_dropcap_bg_color').wpColorPicker(); 		
    	}
    	
    	if($('#mks_dropcap_txt_color').length && jQuery.isFunction(jQuery.fn.wpColorPicker)){
    		$('#mks_dropcap_txt_color').wpColorPicker();
    	}
	})(jQuery);
	/* ]]> */
</script>