<form id="mks_shortcode_toggles">
	
	<table class="form-table">
		<tbody>
			<tr>
		 		<th><h3><?php _e('Options','meks-flexible-shortcodes'); ?></h3></th><td>&nbsp;</td>
			</tr>
	   <tr>
		<tr>
				<th><?php _e('Title','meks-flexible-shortcodes'); ?>:</th>
				<td><input type="text" name="title" value="Example Title" class="widefat" /></td>
		</tr>
		<tr>
				<th>
					<?php _e('Default state','meks-flexible-shortcodes'); ?>:
				</th>
				<td>
					<input type="radio" name="state" value="open" checked/> <?php _e('Open','meks-flexible-shortcodes'); ?><br />
					<input type="radio" name="state" value="close "/> <?php _e('Close','meks-flexible-shortcodes'); ?>&nbsp;&nbsp;
				</td>
		</tr>
		<tr>
				<th><input type="submit" class="button-primary" value="<?php _e('Insert Toggle','meks-flexible-shortcodes'); ?>"></th> 
				<td>&nbsp;</td>
		</tr>
		
	</tbody>
	</table>
</form>

<script type="text/javascript">
	/* <![CDATA[ */
  (function($) {
    	$('#mks_shortcode_toggles').submit(function(e) {
    			e.preventDefault();
    			mks_shortcode_modal_obj.dialog('close');
    			var state = $(this).find('input[name="state"]:checked').val();
    			var title = $(this).find('input[name="title"]').val();
    			var content = '[mks_toggle title="'+title+'" state="'+state+'"]Toggle content goes here...[/mks_toggle]';
    			mks_shortcode.setContent(content);
			});
			
			
			
	})(jQuery);
	/* ]]> */
</script>