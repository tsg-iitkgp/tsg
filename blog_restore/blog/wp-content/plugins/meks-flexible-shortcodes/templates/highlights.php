<form id="mks_shortcode_highlights">
	<table class="form-table">
		<tbody>
			<tr>
		 		<th><h3><?php _e('Options','meks-flexible-shortcodes'); ?></h3></th><td>&nbsp;</td>
			</tr>
		<tr>
				<th><?php _e('Background Color','meks-flexible-shortcodes'); ?>:</th>
				<td><input id="mks_highlight_color" type="text" name="color" value="#ffffff"/></td>
		</tr>
		<tr>
				<th><input type="submit" class="button-primary" value="<?php _e('Insert Highlight','meks-flexible-shortcodes'); ?>"></th> 
				<td>&nbsp;</td>
		</tr>
	
	</tbody>
	</table>
</form>

<script type="text/javascript">
	/* <![CDATA[ */
  (function($) {
    	$('#mks_shortcode_highlights').submit(function(e) {
    			e.preventDefault();
    			mks_shortcode_modal_obj.dialog('close');
    			var color = $(this).find('input[name="color"]').val();
    			var content = '[mks_highlight color="'+color+'"][/mks_highlight]';
    			mks_shortcode.setContent(content);
			});
			
			if($('#mks_highlight_color').length && jQuery.isFunction(jQuery.fn.wpColorPicker)){
    		$('#mks_highlight_color').wpColorPicker(); 		
    	}
			
	})(jQuery);
	/* ]]> */
</script>