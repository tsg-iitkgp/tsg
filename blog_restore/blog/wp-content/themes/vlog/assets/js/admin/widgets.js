(function($) {
    $(document).ready(function (){
            	
            	/* Make some options sortable */
               vlog_opt_sortable();

                 $(document).on('widget-added', function(e){
                 	vlog_opt_sortable();
                 	 

                 });

                 $(document).on('widget-updated', function(e){
                 	vlog_opt_sortable();

                 });


                function vlog_opt_sortable(){
                	$( ".vlog-widget-content-sortable" ).sortable({
                  	revert: false,
                  	cursor: "move"
                	});
                }
			

    });
    
})(jQuery);