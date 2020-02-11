<input type="hidden" name="old_wplp_page" value="<?php echo $wplp_page; ?>"/>
    <label class="selectit"><input type="radio" id="wplp_page_none" name="wplp_page" value="none"<?php checked($wplp_page, 'none');?>/>&nbsp;<?php _e('Show normally everywhere.', 'wp-hide-post');?></label>
    <br />
    <br />
    <label class="selectit"><input type="radio" id="wplp_page_front" name="wplp_page" value="front"<?php checked($wplp_page, 'front');?>/>&nbsp;<?php _e('Hide when listing pages on the front page.', 'wp-hide-post');?></label>
    <br />
    <br />
    <label class="selectit"><input type="radio" id="wplp_page_all" name="wplp_page" value="all"<?php checked($wplp_page, 'all');?>/>&nbsp;<?php _e('Hide everywhere pages are listed.', 'wp-hide-post');?><sup>*</sup></label>
    <div style="height:18px;margin-left:20px">
        <div id="wplp_page_search_show_div">
            <label class="selectit"><input type="checkbox" id="wplp_page_search_show" name="wplp_page_search_show" value="1"<?php checked($wplp_page_search_show, 1);?>/>&nbsp;<?php _e('Keep in search results.', 'wp-hide-post');?></label>
            <input type="hidden" name="old_wplp_page_search_show" value="<?php echo $wplp_page_search_show; ?>"/>
        </div>
    </div>
    <br />
    <div style="float:right;clear:both;font-size:x-small;">* Will still show up in sitemap.xml if you generate one automatically. See <a href="http://www.scriptburn.com/wp-low-profiler/">details</a>.</div>
    <br />
    <br />
    <br />
    <div style="float:right;font-size: xx-small;"><a href="http://www.scriptburn.com/posts/wp-hide-post/#comments"><?php _e("Leave feedback and report bugs...", 'wp-hide-post');?></a></div>
    <br />
    <div style="float:right;clear:both;font-size:xx-small;"><a href="http://wordpress.org/extend/plugins/wp-hide-post/"><?php _e("Give 'WP Hide Post' a good rating...", 'wp-hide-post');?></a></div>
    <br />
    <script type="text/javascript">
    <!--
        // toggle the wplp_page_search_show checkbox
        var wplp_page_search_show_callback = function () {
            if(jQuery("#wplp_page_all").is(":checked"))
                jQuery("#wplp_page_search_show_div").show();
            else
                jQuery("#wplp_page_search_show_div").hide();
        };
        jQuery("#wplp_page_all").change(wplp_page_search_show_callback);
        jQuery("#wplp_page_front").change(wplp_page_search_show_callback);
        jQuery("#wplp_page_none").change(wplp_page_search_show_callback);
        jQuery(document).ready( wplp_page_search_show_callback );
    //-->
    </script>