<form id="mks_shortcode_progressbars">
	<table class="form-table">
		<tbody>
			<tr>
		 		<th><h3><?php _e('Options','meks-flexible-shortcodes'); ?></h3></th><td>&nbsp;</td>
			</tr>
		 <tr>
		 	<th><?php _e('Name label','meks-flexible-shortcodes'); ?>:</th>
		 	<td><input type="text" name="name" value="<?php _e('WordPress','meks-flexible-shortcodes'); ?>" class="widefat"/></td>
		 </tr>
	   <tr>
				<th>
					<?php _e('Level label','meks-flexible-shortcodes'); ?>:
				</th>
				<td><input type="text" name="level" value="<?php _e('Pro','meks-flexible-shortcodes'); ?>" class="widefat"/></td>
		</tr>
		<tr>
		 	<th><?php _e('Value','meks-flexible-shortcodes'); ?>:</th>
		 	<td>
		 		<select name="value">
		 		<?php for($i = 5; $i<= 100; $i+=5): ?>
		 			<option value="<?php echo $i;?>" <?php selected($i,80);?>><?php echo $i;?></option>
		 		<?php endfor; ?>
		 	</select> %
		 	</td>
		</tr>
		<tr>
		 		<th><h3><?php _e('Style','meks-flexible-shortcodes'); ?></h3></th><td>&nbsp;</td>
		</tr>
		<tr>
				<th>
					<?php _e('Height (thickness)','meks-flexible-shortcodes'); ?>:
				</th>
				<td><input type="text" name="height" value="20" class="small-text"/> px</td>
		</tr>
		<tr>
				<th>
					<?php _e('Shape','meks-flexible-shortcodes'); ?>:
				</th>
				<td>
					<input type="radio" name="style" value="squared" checked /> <?php _e('Squared','meks-flexible-shortcodes'); ?>&nbsp;&nbsp;
					<input type="radio" name="style" value="rounded"/> <?php _e('Rounded corners','meks-flexible-shortcodes'); ?>&nbsp;&nbsp;
				</td>
		</tr>
		
		<tr>
				<th><?php _e('Color','meks-flexible-shortcodes'); ?>:</th>
				<td><input id="mks_progressbar_color" type="text" name="color" value="#000000"/></td>
		</tr>
		
		<tr>
				<th><input type="submit" class="button-primary" value="<?php _e('Insert Progress Bar','meks-flexible-shortcodes'); ?>"></th> 
				<td>&nbsp;</td>
		</tr>
	
	</tbody>
	</table>
</form>

<script type="text/javascript">
	/* <![CDATA[ */
  (function($) {
    	$('#mks_shortcode_progressbars').submit(function(e) {
    			e.preventDefault();
    			mks_shortcode_modal_obj.dialog('close');
    			var name = $(this).find('input[name="name"]').val();
    			var level = $(this).find('input[name="level"]').val();
    			var color = $(this).find('input[name="color"]').val();
    			var height = $(this).find('input[name="height"]').val();
    			var style = $(this).find('input[name="style"]:checked').val();
    			var value = $(this).find('select[name="value"]').val();
    			var content = '[mks_progressbar name="'+name+'" level="'+level+'" value="'+value+'" height="'+height+'" color="'+color+'" style="'+style+'"]';
    			mks_shortcode.setContent(content);
			});
			
			if($('#mks_progressbar_color').length && jQuery.isFunction(jQuery.fn.wpColorPicker)){
    		$('#mks_progressbar_color').wpColorPicker(); 		
    	}
	})(jQuery);
	/* ]]> */
</script>