(function( $ ) {
  $.widget( "custom.tooltipX", $.ui.tooltip, {
    options: {
        autoShow: true,
        autoHide: true
    },

    _create: function() {
      this._super();
      if(!this.options.autoShow){
        this._off(this.element, "mouseover focusin");
      }
    },

    _open: function( event, target, content ) {
      this._superApply(arguments);

      if(!this.options.autoHide){
        this._off(target, "mouseleave focusout");
      }
    }
  });

}( jQuery ) );
jQuery( window ).load( function () {
	function set_tutorial( tutorial ) {
		if ( tutorial == 'survey-form' ) {
			jQuery( "#modal_survey_settings .configuration_accordion:first" ).addClass( "tooltip-wizard wizard-step1" );
			jQuery( "#modal_survey_settings .configuration_accordion:first" ).attr( "data-title", "<p>Find the general options of the survey in the General Behavior section.</p><div class='wizard-controls'><a href='#' class='ms-wizard-close button button-secondary button-small'>Close</a><a href='#' class='ms-wizard button button-secondary button-small' data-step='2'>Next</a></div>" );
			
			jQuery( "#modal_survey_settings .expiry_time:first" ).addClass( "tooltip-wizard wizard-step2" );
			jQuery( "#modal_survey_settings .expiry_time:first" ).attr( "data-title", "<p>You can set start and expiry time for the survey to display it in a specified period.</p><div class='wizard-controls'><a href='#' class='ms-wizard-close button button-secondary button-small'>Close</a><a href='#' class='ms-wizard button button-secondary button-small' data-step='1'>Back</a><a href='#' class='ms-wizard button button-secondary button-small' data-step='3'>Next</a></div>" );
			
			jQuery( "#modal_survey_settings .survey_mode:first" ).addClass( "tooltip-wizard wizard-step3" );
			jQuery( "#modal_survey_settings .survey_mode:first" ).attr( "data-title", "<p>Set Mode to Default to use it globally on all of your pages or with shortcode on a specified page. Embed Start and End options automatically includes the survey to all of your posts. </p><div class='wizard-controls'><a href='#' class='ms-wizard-close button button-secondary button-small'>Close</a><a href='#' class='ms-wizard button button-secondary button-small' data-step='2'>Back</a><a href='#' class='ms-wizard button button-secondary button-small' data-step='4'>Next</a></div>" );
			
			jQuery( "#modal_survey_settings .global_survey:first" ).addClass( "tooltip-wizard wizard-step4" );
			jQuery( "#modal_survey_settings .global_survey:first" ).attr( "data-title", "<p>Check this option to ON to display the survey on all pages. Don't forget to uncheck this box, when you include the survey with shortcode.</p><div class='wizard-controls'><a href='#' class='ms-wizard-close button button-secondary button-small'>Close</a><a href='#' class='ms-wizard button button-secondary button-small' data-step='3'>Back</a><a href='#' class='ms-wizard button button-secondary button-small' data-step='5'>Next</a></div>" );
			
			jQuery( "#modal_survey_settings .shortcode_section:first" ).addClass( "tooltip-wizard wizard-step5" );
			jQuery( "#modal_survey_settings .shortcode_section:first" ).attr( "data-title", "<p>Copy and paste this shortcode to display the survey on a specified page only.</p><div class='wizard-controls'><a href='#' class='ms-wizard-close button button-secondary button-small'>Close</a><a href='#' class='ms-wizard button button-secondary button-small' data-step='4'>Back</a><a href='#' class='ms-wizard button button-secondary button-small' data-step='6'>Next</a></div>" );
			
			jQuery( ".floating-new-button" ).addClass( "tooltip-wizard wizard-step6" );
			jQuery( ".floating-new-button" ).attr( "data-title", "<p>Add new questions with this button.</p><div class='wizard-controls'><a href='#' class='ms-wizard-close button button-secondary button-small'>Close</a><a href='#' class='ms-wizard button button-secondary button-small' data-step='5'>Back</a><a href='#' class='ms-wizard button button-secondary button-small' data-step='7'>Next</a></div>" );
					
			jQuery( "#modal_survey_settings .add_answer:first img" ).addClass( "tooltip-wizard wizard-step7" );
			jQuery( "#modal_survey_settings .add_answer:first img" ).attr( "data-title", "<p>New Answers can be added by the green plus icon.</p><div class='wizard-controls'><a href='#' class='ms-wizard-close button button-secondary button-small'>Close</a><a href='#' class='ms-wizard button button-secondary button-small' data-step='6'>Back</a><a href='#' class='ms-wizard button button-secondary button-small' data-step='8'>Next</a></div>" );
			
			jQuery( "#modal_survey_settings .configuration_accordion:eq(1)" ).addClass( "tooltip-wizard wizard-step8" );
			jQuery( "#modal_survey_settings .configuration_accordion:eq(1)" ).attr( "data-title", "<p>Set the style of the survey under the Style and Appearance section.</p><div class='wizard-controls'><a href='#' class='ms-wizard-close button button-secondary button-small'>Close</a><a href='#' class='ms-wizard button button-secondary button-small' data-step='7'>Back</a><a href='#' class='ms-wizard button button-secondary button-small' data-step='9'>Next</a></div>" );
			
			jQuery( "#modal_survey_settings .question_options:first img" ).addClass( "tooltip-wizard wizard-step9" );
			jQuery( "#modal_survey_settings .question_options:first img" ).attr( "data-title", "<p>Click on the gear icon to show the Question Options.</p><div class='wizard-controls'><a href='#' class='ms-wizard-close button button-secondary button-small'>Close</a><a href='#' class='ms-wizard button button-secondary button-small' data-step='8'>Back</a><a href='#' class='ms-wizard button button-secondary button-small' data-step='10'>Next</a></div>" );
			
			jQuery( "#modal_survey_settings .answer_options:first img" ).addClass( "tooltip-wizard wizard-step10" );
			jQuery( "#modal_survey_settings .answer_options:first img" ).attr( "data-title", "<p>The answers also has individual section of options.</p><div class='wizard-controls'><a href='#' class='ms-wizard-close button button-secondary button-small'>Close</a><a href='#' class='ms-wizard button button-secondary button-small' data-step='9'>Back</a><a href='#' class='ms-wizard button button-secondary button-small' data-step='11'>Next</a></div>" );

			jQuery( ".floating-save-button" ).addClass( "tooltip-wizard wizard-step11" );
			jQuery( ".floating-save-button" ).attr( "data-title", "<p>Save the survey with the SAVE button. If the Survey already created, the button text will be change to UPDATE. Click on the RESET to clear all votes and allow users to participate in the survey again.</p><div class='wizard-controls'><a href='#' class='ms-wizard-close button button-secondary button-small'>Close</a><a href='#' class='ms-wizard button button-secondary button-small' data-step='10'>Back</a><a href='#' class='ms-wizard button button-secondary button-small' data-step='12'>Next</a></div>" );
			
			jQuery( "#modal_survey_settings .click-nav:first" ).addClass( "tooltip-wizard wizard-step12" );
			jQuery( "#modal_survey_settings .click-nav:first" ).attr( "data-title", "<p>Export the Survey to various formats. Create a backup using JSON format. This type of export can be imported in the Modal Survey Import Page.</p><div class='wizard-controls'><a href='#' class='ms-wizard-close button button-secondary button-small'>Close</a><a href='#' class='ms-wizard button button-secondary button-small' data-step='11'>Back</a><a href='#' class='ms-wizard button button-secondary button-small' data-step='13'>Next</a></div>" );
			
			jQuery( "#modal_survey_settings textarea.thankyou:first" ).addClass( "tooltip-wizard wizard-step13" );
			jQuery( "#modal_survey_settings textarea.thankyou:first" ).attr( "data-title", "<p>Type your message here to display at the end of the survey. It can even contains HTML, JavaScript or CSS codes.</p><div class='wizard-controls'><a href='#' class='ms-wizard-close button button-secondary button-small'>Close</a><a href='#' class='ms-wizard button button-secondary button-small' data-step='12'>Back</a><a href='#' class='ms-wizard button button-secondary button-small' data-step='14'>Next</a></div>" );
			
			jQuery( "#modal_survey_settings .participants_form:first h4" ).addClass( "tooltip-wizard wizard-step14" );
			jQuery( "#modal_survey_settings .participants_form:first h4" ).attr( "data-title", "<p>If you would like to ask the name and the email address of the participants, set it up in the Participants Form. It will automatically append the form at the end of the survey. Don't forget to set ON the <i>Save Participants Votes</i> in the General Settings.</p><div class='wizard-controls'><a href='#' class='ms-wizard-close button button-secondary button-small'>Close</a><a href='#' class='ms-wizard button button-secondary button-small' data-step='13'>Back</a><a href='#' class='ms-wizard button button-secondary button-small' data-step='15'>Next</a></div>" );
			
			jQuery( "#modal_survey_settings .ms_accordion_more_api:first h4" ).addClass( "tooltip-wizard wizard-step15" );
			jQuery( "#modal_survey_settings .ms_accordion_more_api:first h4" ).attr( "data-title", "<p>The plugin able to pass the participants email address to different newsletter providers, like MailChimp, Active Campaign, etc.</p><div class='wizard-controls'><a href='#' class='ms-wizard-close button button-secondary button-small'>Close</a><a href='#' class='ms-wizard button button-secondary button-small' data-step='14'>Back</a><a href='#' class='ms-wizard button button-secondary button-small' data-step='16'>Next</a></div>" );
			
			jQuery( "#modal_survey_settings .conditions:first h4" ).addClass( "tooltip-wizard wizard-step16" );
			jQuery( "#modal_survey_settings .conditions:first h4" ).attr( "data-title", "<p>Set actions based on the user votes with a simple conditions, like redirects, custom message or display individual chart at the end of the survey.</p><div class='wizard-controls'><a href='#' class='ms-wizard-close button button-secondary button-small'>Close</a><a href='#' class='ms-wizard button button-secondary button-small' data-step='15'>Back</a><a href='#' class='ms-wizard button button-secondary button-small' data-step='17'>Next</a></div>" );
			
			jQuery( ".floating-play-button" ).addClass( "tooltip-wizard wizard-step17" );
			jQuery( ".floating-play-button" ).attr( "data-title", "<p>Try out your survey with the play button, this mode doesn't save the votes.</p><div class='wizard-controls'><a href='#' class='ms-wizard-close button button-secondary button-small'>Close</a><a href='#' class='ms-wizard button button-secondary button-small' data-step='16'>Back</a><a href='#' class='ms-wizard button button-secondary button-small' data-step='18'>Next</a></div>" );
			
			jQuery( ".help_link a" ).addClass( "tooltip-wizard wizard-step18" );
			jQuery( ".help_link a" ).attr( "data-title", "<p>If you still have questions, read more informations in the documentation. If you need support, contact with the button below. High ratings are very important, please rate the plugin if you like it!</p><div class='wizard-controls'><a href='#' class='ms-wizard-close button button-secondary button-small'>Close</a><a target='_blank' href='http://codecanyon.net/item/modal-survey-wordpress-feedbacks-polls-plugin/6533863/support/contact' class='button button-secondary button-small'>Contact</a><a target='_blank' href='http://codecanyon.net/downloads' class='button button-secondary button-small'>Rate</a></div>" );
		}
		jQuery( ".tooltip-wizard" ).tooltipX({
			content: function () {
			  return jQuery( this ).attr( 'data-title' );
			},
			items: "[data-title]",
			show: { effect: "drop", duration: 300 },
			hide: { effect: "drop", duration: 100 },
			autoShow:false, 
			autoHide:false,
			position: {
			my: "center bottom-20",
			at: "center top",
			using: function( position, feedback ) {
			  jQuery( this ).css( position );
			  jQuery( "<div class='ms-tooltip-custom'>" )
				.addClass( "arrow" )
				.addClass( feedback.vertical )
				.addClass( feedback.horizontal )
				.appendTo( this );
			}
		  }
		});	
	}

	jQuery( document ).on( "click", ".ms-wizard-close", function() {
			jQuery( ".tooltip-wizard" ).tooltipX( "close" );		
			if ( jQuery( ".ms_accordion_more_api h4" ).hasClass( "ui-state-active" ) ) {
				jQuery( ".ms_accordion_more_api h4" ).trigger( "click" );
			}
			if ( jQuery( ".conditions h4" ).hasClass( "ui-state-active" ) ) {
				jQuery( ".conditions h4" ).trigger( "click" );
			}
			if ( ! jQuery( ".conditions h4" ).hasClass( "ui-state-active" ) ) {
				jQuery( ".conditions h4" ).trigger( "click" );
			}
			if ( jQuery( ".conditions h4" ).hasClass( "ui-state-active" ) ) {
				jQuery( ".conditions h4" ).trigger( "click" );
			}
			jQuery( ".floating-new-button" ).css( "marginLeft", "" );
			jQuery( ".floating-save-button" ).css( "marginLeft", "" );
			jQuery( ".floating-play-button" ).css( "marginLeft", "" );
	})
	jQuery( document ).on( "click", ".ms-wizard", function(event) {
		event.preventDefault();
		var thiselem = jQuery( this );
		var step = thiselem.attr( "data-step" );
		if ( step == 1 ) {
			set_tutorial( thiselem.attr( "data-tutorial" ) );
		}
		jQuery( ".tooltip-wizard" ).tooltipX( "close" );
		jQuery( "html, body" ).animate( { scrollTop: parseInt( jQuery( ".wizard-step" + step ).offset().top ) - 300 }, 500, 'swing', function() {
			jQuery( ".wizard-step" + thiselem.attr( "data-step" ) ).tooltipX( "open" );
		});
		if ( step == 6 ) {
			jQuery( ".floating-new-button" ).css( "marginLeft", "-120px" );
		}
		if ( step == 7 ) {
			jQuery( ".floating-new-button" ).css( "marginLeft", "" );
		}
		if ( step == 11 ) {
			jQuery( ".floating-save-button" ).css( "marginLeft", "-120px" );
		}
		if ( step == 12 ) {
			jQuery( ".floating-save-button" ).css( "marginLeft", "" );
		}
		if ( step == 17 ) {
			jQuery( ".floating-play-button" ).css( "marginLeft", "-120px" );
		}
		if ( step == 18 ) {
			jQuery( ".floating-play-button" ).css( "marginLeft", "" );
		}
		if ( step == 14 ) {
			if ( ! jQuery( ".participants_form h4" ).hasClass( "ui-state-active" ) ) {
				jQuery( ".participants_form h4" ).trigger( "click" );
			}
		}
		else {
			if ( jQuery( ".participants_form h4" ).hasClass( "ui-state-active" ) ) {
				jQuery( ".participants_form h4" ).trigger( "click" );
			}
		}
		if ( step == 15 ) {
			if ( ! jQuery( ".ms_accordion_more_api h4" ).hasClass( "ui-state-active" ) ) {
				jQuery( ".ms_accordion_more_api h4" ).trigger( "click" );
			}
		}
		else {
			if ( jQuery( ".ms_accordion_more_api h4" ).hasClass( "ui-state-active" ) ) {
				jQuery( ".ms_accordion_more_api h4" ).trigger( "click" );
			}
		}
		if ( step == 16 ) {
			if ( ! jQuery( ".conditions h4" ).hasClass( "ui-state-active" ) ) {
				jQuery( ".conditions h4" ).trigger( "click" );
			}
		}
		else {
			if ( jQuery( ".conditions h4" ).hasClass( "ui-state-active" ) ) {
				jQuery( ".conditions h4" ).trigger( "click" );
			}
		}
	}) 
})