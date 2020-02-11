(function( jQuery ){
	if ( typeof ms_init_params !== 'undefined' ) {
		jQuery.each( ms_init_params, function( key, value ) {
			if ( jQuery( "#survey-" + key ).length == 0 ) {
				var sopts = JSON.parse( value.survey_options );
				if ( sopts.style != "flat" ) {
					jQuery( "body" ).prepend( "<div id='survey-" + key + "' class='modal-survey-container' style='width:100%;'> </div>" );
				}
				else {
					delete ms_init_params[ key ]; 
				}
			}
			jQuery( "#survey-" + key ).modalsurvey({ "unique_key": + value.unique_key, "survey_options": value.survey_options });
		})
	}
})( jQuery );