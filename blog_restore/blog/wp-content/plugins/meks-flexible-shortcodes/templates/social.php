<form id="mks_shortcode_social">
	<table class="form-table">
		<tbody>
			<tr>
		 		<th><h3><?php _e('Options','meks-flexible-shortcodes'); ?></h3></th><td>&nbsp;</td>
			</tr>
		<tr>
				<th>
					<?php _e('Shape','meks-flexible-shortcodes'); ?>:
				</th>
				<td><input type="radio" name="style" value="square" checked /> <?php _e('Square','meks-flexible-shortcodes'); ?><br/>
					<input type="radio" name="style" value="circle"/> <?php _e('Circle','meks-flexible-shortcodes'); ?><br/>
					<input type="radio" name="style" value="rounded"/> <?php _e('Rounded corners','meks-flexible-shortcodes'); ?>
				</td>
		</tr>
		
		<tr>
				<th><?php _e('Size','meks-flexible-shortcodes'); ?>:</th>
				<td><input type="text" name="size" class="small-text" value="48"/> px</td>
		</tr>
		
		<tr>
				<th><?php _e('URL','meks-flexible-shortcodes'); ?>:</th>
				<td><input type="text" name="url" class="widefat" value="http://"/></td>
		</tr>
		<tr>
				<th>
					<?php _e('Link Target','meks-flexible-shortcodes'); ?>:
				</th>
				<td>
					<input type="radio" name="target" value="_self" checked/> <?php _e('Open in same window','meks-flexible-shortcodes'); ?><br />
					<input type="radio" name="target" value="_blank"/> <?php _e('Open in new window/tab','meks-flexible-shortcodes'); ?>&nbsp;&nbsp;
				</td>
		</tr>
		
		<tr>
				<th><?php _e('Icon','meks-flexible-shortcodes'); ?>:</th>
				<td><?php mks_generate_social_icons_picker(); ?></td>	
		</tr>
		<tr>
				<th><input type="submit" class="button-primary" value="<?php _e('Insert Social Icon','meks-flexible-shortcodes'); ?>"></th> 
				<td>&nbsp;</td>
		</tr>
	</tbody>
	</table>
</form>

<script type="text/javascript">
	/* <![CDATA[ */
  (function($) {
    	$('#mks_shortcode_social').submit(function(e) {
    			e.preventDefault();
    			mks_shortcode_modal_obj.dialog('close');
    			var icon = $(this).find('input[name="icon"]').val();
    			var size = $(this).find('input[name="size"]').val();
    			var style = $(this).find('input[name="style"]:checked').val();
    			var url = $(this).find('input[name="url"]').val();
    			var target = $(this).find('input[name="target"]:checked').val();
    			var content = '[mks_social icon="'+icon+'" size="'+size+'" style="'+style+'" url="'+url+'" target="'+target+'"]';
    			mks_shortcode.setContent(content);
			});
			
			$('#mks_shortcode_social .mks_social_pick_button').click(function(e) {
    			e.preventDefault();
    			var holder = $(this).closest('.mks_social_pick_hold');
    			holder.find('.mks_icon_list').toggle();
			});
			
			$('#mks_shortcode_social ul.mks_icon_list li a').click(function(e) {
    			e.preventDefault();
    			var holder = $(this).closest('.mks_social_pick_hold');
    			holder.find('.mks_icon_data_preview').html($(this).parent().html());
    			holder.find('.mks_icon_data').val($(this).attr("data-icon"));
    			holder.find('.mks_icon_list').toggle();
			});
			
	})(jQuery);
	/* ]]> */
</script>