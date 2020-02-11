jQuery( document ).ready(function() {
	(function( jQuery ){
		if ( typeof ms_answer_init_params !== 'undefined' ) {
			jQuery.each( ms_answer_init_params, function( key, value ) {
				if ( jQuery( "#survey-results-" + key ).length == 0 ) {
					jQuery( ".entry-content" ).prepend( "<span id='survey-results-" + key + "' class='modal-survey-container' style='width:100%;'> </span>" );
				}
				if ( typeof value.max == undefined ) {
					value.max = 0;
				}
				jQuery( "#survey-results-" + key ).pmsresults({ "style": value.style, "datas": value.datas });
			})
		}
	})( jQuery );
});