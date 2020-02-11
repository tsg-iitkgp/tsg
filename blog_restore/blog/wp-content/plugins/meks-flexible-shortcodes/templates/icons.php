<form id="mks_shortcode_icons">
	
	<table class="form-table">
		<tbody>
			<tr>
		 		<th><h3><?php _e('Options','meks-flexible-shortcodes'); ?></h3></th><td>&nbsp;</td>
			</tr>
	   <tr>
		<tr>
				<th><?php _e('Color','meks-flexible-shortcodes'); ?>:</th>
				<td><input id="mks_icon_color" type="text" name="color" value="#000000"/></td>
		</tr>
		<tr>
				<th>
					<?php _e('Icon','meks-flexible-shortcodes'); ?>:
				</th>
				<td>
					<?php mks_generate_fontawesome_icons_picker(); ?>
				</td>
		</tr>
	
		<tr>
				<th><input type="submit" class="button-primary" value="<?php _e('Insert Icon','meks-flexible-shortcodes'); ?>"></th> 
				<td>&nbsp;</td>
		</tr>
	
	</tbody>
	</table>
</form>

<script type="text/javascript">
	/* <![CDATA[ */
  (function($) {
    	$('#mks_shortcode_icons').submit(function(e) {
    			e.preventDefault();
    			mks_shortcode_modal_obj.dialog('close');
    			var icon = $(this).find('input[name="icon"]').val();
    			var icon_type = $(this).find('input[name="icon_type"]').val();
    			var color = $(this).find('input[name="color"]').val();
    			var content = '[mks_icon icon="'+icon+'" color="'+color+'" type="'+icon_type+'"]';
    			mks_shortcode.setContent(content);
			});
			
			if($('#mks_icon_color').length && jQuery.isFunction(jQuery.fn.wpColorPicker)){
    		$('#mks_icon_color').wpColorPicker(); 		
    	}
    	
			$('#mks_shortcode_icons .mks_icon_pick_button').click(function(e) {
    			e.preventDefault();
    			var holder = $(this).closest('.mks_icon_pick_hold');
    			
    			holder.find('.mks_icon_list').toggle();
			});
			
			$('#mks_shortcode_icons ul.mks_icon_list li a').click(function(e) {
    			e.preventDefault();
    			var holder = $(this).closest('.mks_icon_pick_hold');
    			holder.find('.mks_icon_data_preview').html($(this).html());
    			holder.find('.mks_icon_data').val($(this).attr("mks-data-icon"));
    			holder.find('.mks_icon_type').val($(this).attr("mks-icon-type"));
    			holder.find('.mks_icon_list').toggle();
			});
			
	})(jQuery);
	/* ]]> */
</script>