(function( jQuery ){
	jQuery(window).load(function() {
	jQuery("#wpbody-content .wrap").css("visibility","visible");
	jQuery("#screen_preloader").fadeOut("slow",function(){jQuery(this).remove();});
	var rmdni = false, exportcharts = 2, thisexpelement = '', actionfl = false,	survey_id, lastScrollTop = 0, surveysystem = jQuery.noConflict(), buttonspan_global = "", active_survey, random, played_question = 0, delprsurv = "", exporttimer, ziptimer, msChartData = {}, labs = [], dset = [], msChartBGcolor = [], msChartBGcolor2 = [];
	var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9\+\/\=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/\r\n/g,"\n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}}
	survey_id = surveysystem( "#modal_survey_settings #modal_survey_accordion>div" ).attr("id")
	surveysystem(function() {
		if ( jQuery( "#modal_survey_tabs" ).length != 0 ) {
			surveysystem("#modal_survey_tabs").tabs();
			surveysystem('.open-tab').click(function (event) {
				surveysystem( "#modal_survey_tabs" ).tabs( "option", "active", 0 );
			});
		}
		surveysystem("#survey_name").focus();
		 surveysystem(function() {
		surveysystem( ".conditions" ).accordion({
			collapsible: true,
			active: false,
			heightStyle: "content"
		});
		surveysystem( ".endcontent_accordion" ).accordion({
			collapsible: true,
			active: false,
			heightStyle: "content"
		});
		surveysystem( ".participants_form" ).accordion({
			collapsible: true,
			active: false,
			heightStyle: "content"
		});
		surveysystem( ".ms_accordion_more_api" ).accordion({
			collapsible: true,
			active: false,
			heightStyle: "content"
		});
		surveysystem( ".configuration_accordion" ).accordion({
			collapsible: false,
			heightStyle: "auto"
		});
	  });
	initialize_sliders();
	sortable_conditions();
	function sortable_conditions() {
		surveysystem( ".added_conditions" ).sortable({
		stop: function (event, ui) {
			surveysystem( ".one_condition_line" ).each( function( index ) {
				surveysystem( this ).children( "a" ).attr( "data-cond", index );
				})
		}});
		surveysystem( "#one_condition_line" ).draggable({
			connectToSortable: ".added_conditions",
			helper: "clone",
			revert: "invalid"
		});		
	}
	function toHex( n ) {
		n = parseInt( n, 10 );
		if ( isNaN( n ) ) {
			return "00";
		}
		n = Math.max( 0, Math.min( n, 255 ) );
		return "0123456789ABCDEF".charAt( ( n - n % 16 ) / 16 ) + "0123456789ABCDEF".charAt( n % 16 );
	}

	function rgbToHex( RGB ) { 
		var rgb_colors = RGB.replace( "rgb(", "" ).replace( ")", "" ).split( "," );
		return "#" + toHex( rgb_colors[ 0 ] ) + toHex( rgb_colors[ 1 ] ) + toHex( rgb_colors[ 2 ] );
	}

	surveysystem( document ).on( "click", ".answer_status", function(event) {
		event.preventDefault();
		if ( surveysystem( this ).children( "img" ).attr( "src" ).indexOf( "inactive" ) >= 0 ) {
			surveysystem( this ).children( "img" ).attr( "src", sspa_params.plugin_url + "/templates/assets/img/active.png" );
			surveysystem( this ).children( "img" ).attr( "title", sspa_params.languages.activeanswer );
			surveysystem( this ).parent().find( ".aopts_status" ).val( "0" );
		}
		else {
			surveysystem( this ).children( "img" ).attr( "src", sspa_params.plugin_url + "/templates/assets/img/inactive.png" );			
			surveysystem( this ).children( "img" ).attr( "title", sspa_params.languages.inactiveanswer );
			surveysystem( this ).parent().find( ".aopts_status" ).val( "1" );
		}
	});
	
	surveysystem( document ).on( "click", ".remove_condition", function(event) {
		event.preventDefault();
		surveysystem( "#dialog-confirm10" ).data( 'id', surveysystem( this ).attr( "data-cond" ) ).dialog( "open" );
	})

	surveysystem( document ).on( "click", ".add_condition", function( event ) {
		event.preventDefault();
		var newline = [ 
			surveysystem( this ).parent().find( ".conds" ).val(),
			surveysystem( this ).parent().find( ".relation" ).val(),
			surveysystem( this ).parent().find( ".condvalue" ).val(),
			surveysystem( this ).parent().find( ".action" ).val(),
			surveysystem( this ).parent().find( ".actionvalue" ).val().replace( /'/g, "|" )
		];
		if ( newline[ 0 ] == "time" ) {
			newline[ 2 ] = "-";
		}
		if ( newline[ 0 ] == "" || newline[ 1 ] == "" || newline[ 2 ] == "" || newline[ 3 ] == "" || newline[ 4 ] == "" ) {
			if ( ( newline[ 3 ] == "displayirchart" && newline[ 4 ] == "" ) || ( newline[ 3 ] == "displayischart" && newline[ 4 ] == "" ) || ( newline[ 3 ] == "displayicchart" && newline[ 4 ] == "" ) ) {}
			else {
				showmessage( sspa_params.languages.conditionsmust );
				return;
			}
		}
		var breakcond = 0;
		surveysystem( ".packed_cond" ).each( function( index ) {
			if ( surveysystem( this ).val().indexOf( "chart" ) > 0 && newline[ 3 ].indexOf( "chart" ) > 0 ) {
				breakcond = 1;
			}
		})
		if ( breakcond == 1 && ( surveysystem( "#cond_ed_field" ).val() == "" ) ) {
			showmessage( sspa_params.languages.chartalready );
			return true;			
		}
		var newline_str = "<input type='hidden' class='packed_cond' value='" + JSON.stringify( newline ) + "'>If ";
			if ( newline[ 0 ] == "time" ) newline_str += sspa_params.languages.finaltime + " ";
			if ( newline[ 0 ] == "score" ) newline_str += sspa_params.languages.finalscore + " ";
			if ( newline[ 0 ] == "correct" ) newline_str += sspa_params.languages.correctanswers + " ";
			if ( newline[ 0 ].indexOf( "questionscore_" ) >= 0 ) {
				var qsexp = newline[ 0 ].split( "_" );
				newline_str += sspa_params.languages.question + " " + qsexp[ 1 ] + " " + sspa_params.languages.score + " ";
			}
			if ( newline[ 0 ].indexOf( "questioncatscore_" ) >= 0 ) {
				var qsexp = newline[ 0 ].split( "_" );
				newline_str += sspa_params.languages.category + " " + qsexp[ 1 ] + " " + sspa_params.languages.score + " ";
			}
			if ( newline[ 0 ] != "time" ) {
					if ( newline[ 1 ] == "higher" ) newline_str += sspa_params.languages.higherthan + " ";
					if ( newline[ 1 ] == "equal" ) newline_str += sspa_params.languages.equalwith + " ";
					if ( newline[ 1 ] == "lower" ) newline_str += sspa_params.languages.lowerthan + " ";
				newline_str += newline[ 2 ];
			}
			newline_str += " " + sspa_params.languages.then + " ";
			if ( newline[ 3 ] == "redirect" ) {
				if ( ! isUrlValid( newline[ 4 ] ) ) {
					showmessage( sspa_params.languages.invalidurl );
					return;
				}
				newline_str += sspa_params.languages.redirectto + " ";
			}
			if ( newline[ 3 ] == "display" ) newline_str += sspa_params.languages.dmes + ": ";
			if ( newline[ 3 ] == "soctitle" ) newline_str += sspa_params.languages.dsoct + ": ";
			if ( newline[ 3 ] == "socdesc" ) newline_str += sspa_params.languages.dsocd + ": ";
			if ( newline[ 3 ] == "socimg" ) newline_str += sspa_params.languages.dsoci + ": ";
			if ( newline[ 3 ] == "displayirchart" ) newline_str += sspa_params.languages.dindr + " ";
			if ( newline[ 3 ] == "displayischart" ) newline_str += sspa_params.languages.dinds + " ";
			if ( newline[ 3 ] == "displayicchart" ) newline_str += sspa_params.languages.dindc + " ";
			newline_str += surveysystem( this ).parent().find(".actionvalue").val();
			if ( surveysystem( "#cond_ed_field" ).val() != "" && parseInt( surveysystem( "#cond_ed_field" ).val() ) >= 0 ) {
				surveysystem( ".one_condition_line" ).eq( surveysystem( "#cond_ed_field" ).val() ).replaceWith( "<div class='one_condition_line'><a href='#' class='edit_condition' data-cond='" + surveysystem( "#cond_ed_field" ).val() + "'><img class='modal_survey_tooltip' title='" + sspa_params.languages.ed_cond + "' src='"+sspa_params.plugin_url+"/templates/assets/img/cond-edit.png'></a><a href='#' class='remove_condition' data-cond='" + surveysystem( "#cond_ed_field" ).val() + "'><img class='modal_survey_tooltip' title='" + sspa_params.languages.rem_cond + "' src='"+sspa_params.plugin_url+"/templates/assets/img/delete.png'></a>" + newline_str + "</div>" );
			}
			else {
				surveysystem( this ).parent().parent().find(".added_conditions").append( "<div class='one_condition_line'><a href='#' class='edit_condition' data-cond='" + surveysystem( '.one_condition_line' ).length + "'><img class='modal_survey_tooltip' title='" + sspa_params.languages.ed_cond + "' src='"+sspa_params.plugin_url+"/templates/assets/img/cond-edit.png'></a><a href='#' class='remove_condition' data-cond='" + surveysystem( '.one_condition_line' ).length + "'><img class='modal_survey_tooltip' title='" + sspa_params.languages.rem_cond + "' src='"+sspa_params.plugin_url+"/templates/assets/img/delete.png'></a>" + newline_str + "</div>" );
			}
			surveysystem( "#cond_ed_field" ).val( "" );
			surveysystem( ".new_condition_line .conds" ).val( "time" );
			surveysystem( ".new_condition_line .relation" ).val( "higher" );
			surveysystem( ".new_condition_line .condvalue" ).val( "" );
			surveysystem( ".new_condition_line .action" ).val( "redirect" );
			surveysystem( ".new_condition_line .actionvalue" ).val( "" );
			surveysystem( ".new_condition_line .add_condition" ).text( sspa_params.languages.add_cond );
	});

	function escapeHtml( text ) {
	  var map = {
		'&': '&amp;',
		'<': '&lt;',
		'>': '&gt;',
		'"': '&quot;',
		"'": '&#039;'
	  };
	return text.replace(/[&<>"']/g, function(m) { return map[m]; });
	}
	
	surveysystem( document ).on( "click", ".edit_condition", function( event ) {
		event.preventDefault();
		var conds_array = surveysystem.parseJSON( surveysystem( ".one_condition_line" ).eq( surveysystem( this ).attr( "data-cond" ) ).children( ".packed_cond" ).val() );
		surveysystem( "#cond_ed_field" ).val( surveysystem( this ).attr( "data-cond" ) );
		surveysystem( ".new_condition_line .conds" ).val( conds_array[ 0 ] );
		surveysystem( ".new_condition_line .relation" ).val( conds_array[ 1 ] );
		surveysystem( ".new_condition_line .condvalue" ).val( conds_array[ 2 ] );
		surveysystem( ".new_condition_line .action" ).val( conds_array[ 3 ] );
		surveysystem( ".new_condition_line .actionvalue" ).val( conds_array[ 4 ] );
		surveysystem( ".new_condition_line .add_condition" ).text( sspa_params.languages.ed_cond );
	})
	
	surveysystem( document ).on( "click", ".answer-image-upload", function() {
		surveysystem( this ).wpmediauploader({
				"button": surveysystem( this ),
				"target": surveysystem( this ).parent( ".uploaded_image" ),
				"container": "<div class='image_container'><img src='[content]'><input type='hidden' class='upl_image upl-photo' value='[objImageUrl]'><div><input class='remove_answerimage_button button remove-button' type='button' value='" + sspa_params.languages.remove + "' /></div></div>",
				"mode": "insert",
				"type": "single",
				"ajax": false,
				"callback": function() {
				}
		})
	})
	
	surveysystem(document).on( 'click', '.remove_answerimage_button', function() {
		surveysystem( this ).parents( '.uploaded_image' ).html( '<input class="answer-image-upload button add-button" type="button" value="' + sspa_params.languages.addimage + '" />' );
		surveysystem( this ).parents( '.uploaded_image' ).find( '.image_container' ).remove();
    })

	function isUrlValid( url ) {
		return /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test( url );
	}
	
	function initialize_sliders()
	{
	initialize_tooltips();
	surveysystem( ".open_answers_accordion" ).accordion({
		collapsible: true,
		active: false,
		heightStyle: "content"
	});
	surveysystem( ".ao-accordion" ).accordion({
		collapsible: true,
		active: false,
		heightStyle: "content"		
	});
	surveysystem( ".qo-accordion" ).accordion({
		collapsible: true,
		active: false,
		heightStyle: "content"		
	});
	surveysystem( document ).on( "click", ".answer_options", function( event ) {
		event.preventDefault();
        surveysystem( this ).parent().find( '.ao-accordion h4' ).trigger( 'click' );
    });
	
	surveysystem( document ).on( "click", ".floating-save-button", function( event ) {
		event.preventDefault();
        surveysystem( '.save_survey' ).trigger( 'click' );
    });
	
	surveysystem( document ).on( "click", ".floating-reset-button", function( event ) {
		event.preventDefault();
        surveysystem( '.reset_survey' ).trigger( 'click' );
    });
	
	surveysystem( document ).on( "click", ".floating-delete-button", function( event ) {
		event.preventDefault();
        surveysystem( '.delete_survey' ).trigger( 'click' );
    });
	
	surveysystem( document ).on( "click", ".floating-play-button", function( event ) {
		event.preventDefault();
        surveysystem( '.play_button' ).trigger( 'click' );
    });
	
	surveysystem( document ).on( "click", ".floating-new-button", function( event ) {
		event.preventDefault();
		if ( rmdni == false ) {
			rmdni = true;
			surveysystem( ".floating-new-button" ).children( "img" ).attr( "src", sspa_params.plugin_url + "/templates/assets/img/floating-loader.svg" );
			surveysystem( "html, body" ).animate({
				scrollTop: surveysystem( "#modal_survey_settings" ).height() - surveysystem( window ).height() + 200
			}, 500 ).promise().then(function() {
				surveysystem( '.add_question:first' ).trigger( 'click' );
				setTimeout( function() {
					surveysystem( ".addednow" ).css( "opacity", "1" );
					rmdni = false;
					surveysystem( ".floating-new-button" ).children( "img" ).attr( "src", sspa_params.plugin_url + "/templates/assets/img/new-question-icon.png" );
				}, 100 );
			});
		}
    });	
	
	surveysystem( document ).on( "click", ".question_options", function( event ) {
		event.preventDefault();
        surveysystem( this ).parent().find( '.qo-accordion h4' ).trigger( 'click' );
    });
	surveysystem("#modal_survey_accordion .modal_survey_line_height").each(function( index ) {
	initialize_question_accordions(survey_id);
	surveysystem('#'+survey_id+' .datepicker').datetimepicker({
			dateFormat: 'yy-mm-dd',
			minDate: getFormattedDate(new Date()),
			showAnim: 'slide'
		});
	create_graph( survey_id, 1, "true" );
	var thisvalue = surveysystem( "#"+survey_id+" .modal_survey_line_height_value" ).val().replace( /[^\d.]/g, '' );
	  	//initialize the line-height property slider
			surveysystem( "#"+survey_id+" .modal_survey_line_height" ).slider({
			range: "min",
			value: thisvalue,
			min: 0,
			max: 40,
			slide: function( event, ui ) {
				surveysystem( "#"+survey_id+" .modal_survey_line_height_value" ).val( function( index, value ) {
				   return value.replace( surveysystem( "#"+survey_id+" .modal_survey_line_height_value" ).val().replace( /[^\d.]/g, '' ), ui.value );
				});
				surveysystem(".modal-survey-container .survey_element").css("line-height",ui.value + "px");
			}
			});
	})
	
	surveysystem("#modal_survey_accordion .modal_survey_cookie_expiration").each(function( index ) {
	var thisvalue = surveysystem( "#"+survey_id+" .modal_survey_cookie_expiration_value" ).val().replace( /[^\d.]/g, '' );
	  	//initialize the font-size property slider
			surveysystem( "#"+survey_id+" .modal_survey_cookie_expiration" ).slider({
			range: "min",
			value: thisvalue,
			step: 0.5,
			min: 0,
			max: 9999,
			slide: function( event, ui ) {
				surveysystem( "#"+survey_id+" .modal_survey_cookie_expiration_value" ).val( function( index, value ) {
				   return value.replace( surveysystem( "#"+survey_id+" .modal_survey_cookie_expiration_value" ).val().replace( /[^\d.]/g, '' ), ui.value );
				});
			}
			});
	})

	surveysystem("#modal_survey_accordion .modal_survey_font_size").each(function( index ) {
	var thisvalue = surveysystem( "#"+survey_id+" .modal_survey_font_size_value" ).val().replace( /[^\d.]/g, '' );
	  	//initialize the font-size property slider
			surveysystem( "#"+survey_id+" .modal_survey_font_size" ).slider({
			range: "min",
			value: thisvalue,
			min: 6,
			max: 40,
			slide: function( event, ui ) {
				surveysystem( "#"+survey_id+" .modal_survey_font_size_value" ).val( function( index, value ) {
				   return value.replace( surveysystem( "#"+survey_id+" .modal_survey_font_size_value" ).val().replace( /[^\d.]/g, '' ), ui.value );
				});
				surveysystem(".modal-survey-container .survey_element").css("font-size",ui.value + "px");
			}
			});
	})
	
	surveysystem("#modal_survey_accordion .modal_survey_padding").each(function( index ) {
	var thisvalue = surveysystem( "#"+survey_id+" .modal_survey_padding_value" ).val().replace( /[^\d.]/g, '' );
	  	//initialize the padding property slider
			surveysystem( "#"+survey_id+" .modal_survey_padding" ).slider({
			range: "min",
			value: thisvalue,
			min: 0,
			max: 40,
			slide: function( event, ui ) {
				surveysystem( "#"+survey_id+" .modal_survey_padding_value" ).val( function( index, value ) {
				   return value.replace( surveysystem( "#"+survey_id+" .modal_survey_padding_value" ).val().replace( /[^\d.]/g, '' ), ui.value );
				});
				surveysystem(".modal-survey-container .survey_element").css("padding",ui.value + "px");
			}
			});
	})
	surveysystem("#modal_survey_accordion .modal_survey_shadowh").each(function( index ) {
	var thisvalue = surveysystem( "#"+survey_id+" .modal_survey_shadowh_value" ).val().replace( /[^\d.-]/g, '' );
	  	//initialize the border-width property slider
			surveysystem( "#"+survey_id+" .modal_survey_shadowh" ).slider({
			range: "min",
			value: thisvalue,
			min: -20,
			max: 20,
			slide: function( event, ui ) {
				surveysystem( "#"+survey_id+" .modal_survey_shadowh_value" ).val( function( index, value ) {
				   return value.replace( surveysystem( "#"+survey_id+" .modal_survey_shadowh_value" ).val().replace( /[^\d.-]/g, '' ), ui.value );
				});
				surveysystem(".modal-survey-container .survey_element").css( "box-shadow", ui.value + "px " + surveysystem( "#" + survey_id + " .modal_survey_shadowv_value" ).val().replace( /[^\d.-]/g, '' ) + "px " + surveysystem( "#" + survey_id + " .modal_survey_shadowb_value" ).val().replace( /[^\d.]/g, '' ) + "px " + surveysystem( "#" + survey_id + " .modal_survey_shadows_value" ).val().replace( /[^\d.]/g, '' ) + "px " + surveysystem( "#" + survey_id + " .modal_survey_preview1004" ).css("background-color"));
			}
			});
	})

	surveysystem("#modal_survey_accordion .modal_survey_shadowv").each(function( index ) {
	var thisvalue = surveysystem( "#"+survey_id+" .modal_survey_shadowv_value" ).val().replace( /[^\d.-]/g, '' );
	  	//initialize the border-width property slider
			surveysystem( "#"+survey_id+" .modal_survey_shadowv" ).slider({
			range: "min",
			value: thisvalue,
			min: -20,
			max: 20,
			slide: function( event, ui ) {
				surveysystem( "#"+survey_id+" .modal_survey_shadowv_value" ).val( function( index, value ) {
				   return value.replace( surveysystem( "#"+survey_id+" .modal_survey_shadowv_value" ).val().replace( /[^\d.-]/g, '' ), ui.value );
				});
				surveysystem(".modal-survey-container .survey_element").css( "box-shadow", surveysystem( "#" + survey_id + " .modal_survey_shadowh_value" ).val().replace( /[^\d.-]/g, '' ) + "px " + ui.value + "px " + surveysystem( "#" + survey_id + " .modal_survey_shadowb_value" ).val().replace( /[^\d.]/g, '' ) + "px " + surveysystem( "#" + survey_id + " .modal_survey_shadows_value" ).val().replace( /[^\d.]/g, '' ) + "px " + surveysystem( "#" + survey_id + " .modal_survey_preview1004" ).css("background-color"));
			}
			});
	})

	surveysystem("#modal_survey_accordion .modal_survey_shadowb").each(function( index ) {
	var thisvalue = surveysystem( "#"+survey_id+" .modal_survey_shadowb_value" ).val().replace( /[^\d.]/g, '' );
	  	//initialize the border-width property slider
			surveysystem( "#"+survey_id+" .modal_survey_shadowb" ).slider({
			range: "min",
			value: thisvalue,
			min: 0,
			max: 20,
			slide: function( event, ui ) {
				surveysystem( "#"+survey_id+" .modal_survey_shadowb_value" ).val( function( index, value ) {
				   return value.replace( surveysystem( "#"+survey_id+" .modal_survey_shadowb_value" ).val().replace( /[^\d.]/g, '' ), ui.value );
				});
				surveysystem(".modal-survey-container .survey_element").css( "box-shadow", surveysystem( "#" + survey_id + " .modal_survey_shadowh_value" ).val().replace( /[^\d.-]/g, '' ) + "px " + surveysystem( "#" + survey_id + " .modal_survey_shadowv_value" ).val().replace( /[^\d.-]/g, '' ) + "px " + ui.value + "px " + surveysystem( "#" + survey_id + " .modal_survey_shadows_value" ).val().replace( /[^\d.]/g, '' ) + "px " + surveysystem( "#" + survey_id + " .modal_survey_preview1004" ).css("background-color"));
			}
			});
	})

	surveysystem("#modal_survey_accordion .modal_survey_shadows").each(function( index ) {
	var thisvalue = surveysystem( "#"+survey_id+" .modal_survey_shadows_value" ).val().replace( /[^\d.]/g, '' );
	  	//initialize the border-width property slider
			surveysystem( "#"+survey_id+" .modal_survey_shadows" ).slider({
			range: "min",
			value: thisvalue,
			min: 0,
			max: 20,
			slide: function( event, ui ) {
				surveysystem( "#"+survey_id+" .modal_survey_shadows_value" ).val( function( index, value ) {
				   return value.replace( surveysystem( "#"+survey_id+" .modal_survey_shadows_value" ).val().replace( /[^\d.]/g, '' ), ui.value );
				});
				surveysystem(".modal-survey-container .survey_element").css( "box-shadow", surveysystem( "#" + survey_id + " .modal_survey_shadowh_value" ).val().replace( /[^\d.-]/g, '' ) + "px " + surveysystem( "#" + survey_id + " .modal_survey_shadowv_value" ).val().replace( /[^\d.-]/g, '' ) + "px " + surveysystem( "#" + survey_id + " .modal_survey_shadowb_value" ).val().replace( /[^\d.]/g, '' ) + "px " + ui.value + "px " + surveysystem( "#" + survey_id + " .modal_survey_preview1004" ).css("background-color"));
			}
			});
	})

	surveysystem("#modal_survey_accordion .modal_survey_border_width").each(function( index ) {
	var thisvalue = surveysystem( "#"+survey_id+" .modal_survey_border_width_value" ).val().replace( /[^\d.]/g, '' );
	  	//initialize the border-width property slider
			surveysystem( "#"+survey_id+" .modal_survey_border_width" ).slider({
			range: "min",
			value: thisvalue,
			min: 0,
			max: 20,
			slide: function( event, ui ) {
				surveysystem( "#"+survey_id+" .modal_survey_border_width_value" ).val( function( index, value ) {
				   return value.replace( surveysystem( "#"+survey_id+" .modal_survey_border_width_value" ).val().replace( /[^\d.]/g, '' ), ui.value );
				});
				surveysystem(".modal-survey-container .survey_element").css("border",ui.value+"px solid "+surveysystem("#"+survey_id+" .modal_survey_preview1003").css("background-color"));
			}
			});
	})

	surveysystem("#modal_survey_accordion .modal_survey_border_radius").each(function( index ) {
	var thisvalue = surveysystem( "#"+survey_id+" .modal_survey_border_radius_value" ).val().replace( /[^\d.]/g, '' );
	  	//initialize the border-radius property slider
			surveysystem( "#"+survey_id+" .modal_survey_border_radius" ).slider({
			range: "min",
			value: thisvalue,
			min: 0,
			max: 100,
			slide: function( event, ui ) {
				surveysystem( "#"+survey_id+" .modal_survey_border_radius_value" ).val( function( index, value ) {
				   return value.replace( surveysystem( "#"+survey_id+" .modal_survey_border_radius_value" ).val().replace( /[^\d.]/g, '' ), ui.value );
				});
				surveysystem(".modal-survey-container .survey_element").css("border-radius",ui.value+"px "+ui.value+"px "+ui.value+"px "+ui.value+"px");
				surveysystem(".modal-survey-container .survey_answers").css("border-radius",ui.value+"px "+ui.value+"px "+ui.value+"px "+ui.value+"px");
			}
			});
	})

	surveysystem("#modal_survey_accordion .modal_survey_animation_speed").each(function( index ) {
	var thisvalue = surveysystem( "#"+survey_id+" .modal_survey_animation_speed_value" ).val().replace( /[^\d.]/g, '' );
	  	//initialize the animation speed property slider
			surveysystem( "#"+survey_id+" .modal_survey_animation_speed" ).slider({
			range: "min",
			step: 0.1,
			value: thisvalue,
			min: 0,
			max: 10,
			slide: function( event, ui ) {
				surveysystem( "#"+survey_id+" .modal_survey_animation_speed_value" ).val( function( index, value ) {
				   return value.replace( surveysystem( "#"+survey_id+" .modal_survey_animation_speed_value" ).val().replace( /[^\d.]/g, '' ), ui.value );
				});
			}
			});
	})

	surveysystem("#modal_survey_accordion .modal_survey_end_delay").each(function( index ) {
	var thisvalue = surveysystem( "#"+survey_id+" .modal_survey_end_delay_value" ).val().replace( /[^\d.]/g, '' );
	  	//initialize the end delay property slider
			surveysystem( "#"+survey_id+" .modal_survey_end_delay" ).slider({
			range: "min",
			step: 0.1,
			value: thisvalue,
			min: 0,
			max: 300,
			slide: function( event, ui ) {
				surveysystem( "#"+survey_id+" .modal_survey_end_delay_value" ).val( function( index, value ) {
				   return value.replace( surveysystem( "#"+survey_id+" .modal_survey_end_delay_value" ).val().replace( /[^\d.]/g, '' ), ui.value );
				});
			}
			});
	})

	surveysystem("#modal_survey_accordion .modal_survey_display_timer").each(function( index ) {
	var thisvalue = surveysystem( "#"+survey_id+" .modal_survey_display_timer_value" ).val().replace( /[^\d.]/g, '' );
	  	//initialize the end delay property slider
			surveysystem( "#"+survey_id+" .modal_survey_display_timer" ).slider({
			range: "min",
			step: 0.1,
			value: thisvalue,
			min: 0,
			max: 300,
			slide: function( event, ui ) {
				surveysystem( "#"+survey_id+" .modal_survey_display_timer_value" ).val( function( index, value ) {
				   return value.replace( surveysystem( "#"+survey_id+" .modal_survey_display_timer_value" ).val().replace( /[^\d.]/g, '' ), ui.value );
				});
			}
			});
	})

	surveysystem("#modal_survey_accordion .modal_survey_quiz_timer").each(function( index ) {
	var thisvalue = surveysystem( "#" + survey_id + " .modal_survey_quiz_timer_value" ).val().replace( /[^\d.]/g, '' );
	  	//initialize the end delay property slider
			surveysystem( "#" + survey_id + " .modal_survey_quiz_timer" ).slider({
			range: "min",
			step: 1,
			value: thisvalue,
			min: 0,
			max: 9999,
			slide: function( event, ui ) {
				surveysystem( "#" + survey_id + " .modal_survey_quiz_timer_value" ).val( function( index, value ) {
				   return value.replace( surveysystem( "#" + survey_id + " .modal_survey_quiz_timer_value" ).val().replace( /[^\d.]/g, '' ), ui.value );
				});
			}
			});
	})

	surveysystem("#modal_survey_accordion .modal_survey_preview1001").each(function( index ) {
	var colors = surveysystem(this).children().children("input.bgcolor").val().split(')",');
	var ecolors = colors[0].split(';');
	var rcolors = parseRGB(ecolors[0]);
	var rpercent = parsePercentage(ecolors[0]);
	var key;
	var ctype;
	var cdir;
	if (ecolors[0].indexOf("circle")>=0) {
	ctype="circle";
	var cutcolor = ecolors[0].split("(");
	var cutc = cutcolor[1].split(" , rgb");
	cdir = cutc[0];	
	}
	if (ecolors[0].indexOf("ellipse")>=0) {
	ctype="ellipse";
	var cutcolor = ecolors[0].split("(");
	var cutc = cutcolor[1].split(" , rgb");
	cdir = cutc[0];
	}
	if (ecolors[0].indexOf("linear")>=0) {
	ctype="linear";
	var cutcolor = ecolors[0].split("(");
	var cutc = cutcolor[1].split(" , rgb");
	cdir = cutc[0];
	}
	if (cdir==undefined) cdir = 'center , circle cover';
	var gradxcolors = [];
	for (key in rcolors) {
    if (arrayHasOwnIndex(rcolors, key)) {
	var gradxparams = {};
			gradxparams.color = rgbToHex(rcolors[key]);
			if (arrayHasOwnIndex(rpercent, key)) gradxparams.position = rpercent[key].replace("%","");
			else gradxparams.position = '0';
			gradxcolors.push(gradxparams);
		}
	}
	surveysystem("#ms_preview_inner"+survey_id).click(function() {
	surveysystem("#gradX").css("display","block");
            gradX("#gradX", {
                targets: [".modal-survey-container .survey_element","#"+survey_id+" .inner"],
				type: ctype,
				direction: cdir.replace(" , circle cover","").replace(" , ellipse cover",""),
				sliders: gradxcolors
            });
		})
	surveysystem(".modal-survey-container .survey_row").css('background', '');
	})
	
	surveysystem("#modal_survey_accordion .modal_survey_preview1002").each(function( index ) {
		surveysystem("#"+survey_id+" .modal_survey_preview1002").spectrum({
                move: function(color) {
					var rgba = color.toRgbString();
					surveysystem("#"+survey_id+" .modal_survey_preview1002").css('background-color', rgba);
					surveysystem(".modal-survey-container .survey_element").css('color', rgba);
                },
                change: function(color) {
					var rgba = color.toRgbString();
					surveysystem("#"+survey_id+" .modal_survey_preview1002").css('background-color', rgba);
					surveysystem(".modal-survey-container .survey_element").css('color', rgba);
                },
                showAlpha: true,
                color: surveysystem(this).css("background-color"),
                clickoutFiresChange: true,
                showInput: true,
                showButtons: true
            });	
	})
	surveysystem("#modal_survey_accordion .modal_survey_preview1003").each(function( index ) {
	surveysystem("#"+survey_id+" .modal_survey_preview1003").spectrum({
                move: function(color) {
					var rgba = color.toRgbString();
					surveysystem("#"+survey_id+" .modal_survey_preview1003").css('background-color', rgba);
					surveysystem(".modal-survey-container .survey_element").css('border-color', rgba);
                },
                change: function(color) {
					var rgba = color.toRgbString();
					surveysystem("#"+survey_id+" .modal_survey_preview1003").css('background-color', rgba);
					surveysystem(".modal-survey-container .survey_element").css('border-color', rgba);
                },
                showAlpha: true,
                color: surveysystem(this).css("background-color"),
                clickoutFiresChange: true,
                showInput: true,
                showButtons: true
            });	
	})
	surveysystem("#modal_survey_accordion .modal_survey_preview1004").each(function( index ) {
	surveysystem("#"+survey_id+" .modal_survey_preview1004").spectrum({
                move: function(color) {
					var rgba = color.toRgbString();
					surveysystem("#"+survey_id+" .modal_survey_preview1004").css('background-color', rgba);
					surveysystem(".modal-survey-container .survey_element").css( "box-shadow", surveysystem( "#" + survey_id + " .modal_survey_shadowh_value" ).val().replace( /[^\d.-]/g, '' ) + "px " + surveysystem( "#" + survey_id + " .modal_survey_shadowv_value" ).val().replace( /[^\d.-]/g, '' ) + "px " + surveysystem( "#" + survey_id + " .modal_survey_shadowb_value" ).val().replace( /[^\d.]/g, '' ) + "px "  + surveysystem( "#" + survey_id + " .modal_survey_shadows_value" ).val().replace( /[^\d.]/g, '' ) + "px " + surveysystem( "#" + survey_id + " .modal_survey_preview1004" ).css("background-color"));
                },
                change: function(color) {
					var rgba = color.toRgbString();
					surveysystem("#"+survey_id+" .modal_survey_preview1004").css('background-color', rgba);
					surveysystem(".modal-survey-container .survey_element").css( "box-shadow", surveysystem( "#" + survey_id + " .modal_survey_shadowh_value" ).val().replace( /[^\d.-]/g, '' ) + "px " + surveysystem( "#" + survey_id + " .modal_survey_shadowv_value" ).val().replace( /[^\d.-]/g, '' ) + "px " + surveysystem( "#" + survey_id + " .modal_survey_shadowb_value" ).val().replace( /[^\d.]/g, '' ) + "px "  + surveysystem( "#" + survey_id + " .modal_survey_shadows_value" ).val().replace( /[^\d.]/g, '' ) + "px " + surveysystem( "#" + survey_id + " .modal_survey_preview1004" ).css("background-color"));
                },
                showAlpha: true,
                color: surveysystem(this).css("background-color"),
                clickoutFiresChange: true,
                showInput: true,
                showButtons: true
            });	
	})
			//bind event to change font family
		surveysystem(".font_family").on("change", function(){
		var protocol = ('https:' == window.location.protocol ? 'https://' : 'http://');
			if (surveysystem(this).val()=="") surveysystem(".modal-survey-container").css("font-family","");
			else
			{
				if (!surveysystem("link[href='" + protocol + "fonts.googleapis.com/css?family="+surveysystem(this).val()+"']").length) {
					surveysystem('head').append('<link rel="stylesheet" href="' + protocol + 'fonts.googleapis.com/css?family='+surveysystem(this).val()+'" type="text/css" />');
				}
				if (surveysystem(".modal-survey-container").length!=0) {
					surveysystem(".modal-survey-container").css("font-family",surveysystem(this).val()+", serif");
				}
			}
		});
		//bind event to change text align
		surveysystem(".text_align").on("change", function(){
			if ( surveysystem( this ).val() != "" ) surveysystem(".modal-survey-container .survey_element").css("text-align", surveysystem(this).val());
		});
	}

	function parseRGB ( string ) {
    var rgbRegex = /(rgb\([^)]*\))/gi;
    var rgbArray = string.match(rgbRegex); 

    return rgbArray;
	}

	function parsePercentage ( string ) {
    var prcRegex = /[0-9]*\.?[0-9]+%/gi;
    var prcArray = string.match(prcRegex); 

    return prcArray;
	}

	
	function arrayHasOwnIndex(array, prop) {
		if (array!=null) return array.hasOwnProperty(prop) && /^0$|^[1-9]\d*$/.test(prop) && prop <= 4294967294; // 2^32 - 2
	}	
	
	function play_survey() {
		surveysystem( "#survey-" + random + "-1" ).remove();
		random = Math.floor(Math.random()*(1000000-1000+1)+1000);
		var cond_array = [], answers_array = [], thissurvey = [], qoptions = [], aoptions = [], ao = {};
		surveysystem( "#" + survey_id + " .survey_error_span" ).html( "" );
			surveysystem( "#answers_" + survey_id + " .answer" ).each(function( index ) {
				answers_array[ index ] = surveysystem( this ).val();
			});
		surveysystem( "#" + survey_id + " .packed_cond" ).each(function( index ) {
			cond_array.push( surveysystem( this ).val() );
		})
		surveysystem( "#" + survey_id + " .question_text" ).each(function( index ) {
			var qa = {};
			qa[ 0 ] = surveysystem( this ).val();
			var thisquestion = surveysystem( this ).parent().parent().parent().attr( "id" );
			var qimageurl = "";
			if ( surveysystem( this ).parent().parent().find( ".qo-accordion .upl-photo" ).length > 0 ) {
				qimageurl = surveysystem( this ).parent().parent().find( ".qo-accordion .upl-photo" ).val();
			}
		surveysystem( "#"+survey_id+" #"+thisquestion+" div input.answer" ).each(function( index2 ) {
		var answer_type = "default", answer_status = "0", converttextarea = "0";
		var answer_unique = (Math.floor(Math.random() * 26) + Date.now());
		var autocomplete = "0", imageurl = "", score = "0", correct = "0", imgwidth = "", imgheight = "", hidelabel = "0";
		if ( surveysystem( this ).parent().find( ".ao-accordion .upl-photo" ).length > 0 ) {
			imageurl = surveysystem( this ).parent().find( ".ao-accordion .upl-photo" ).val();
		}
		if ( surveysystem( this ).parent().find( ".ao-accordion .aopts_score" ).length > 0 ) {
			score = surveysystem( this ).parent().find( ".ao-accordion .aopts_score" ).val();
		}
		if ( surveysystem( this ).parent().find( ".ao-accordion .aopts_correct_answer" ).length >0 ) {
			correct = surveysystem( this ).parent().find( ".ao-accordion .aopts_correct_answer" ).val();
		}
		if ( surveysystem( this ).parent().find( ".ao-accordion .aopts_hide_label" ).length >0 ) {
			hidelabel = surveysystem( this ).parent().find( ".ao-accordion .aopts_hide_label" ).val();
		}
		if ( surveysystem( this ).parent().find( ".ao-accordion .aopts_width" ).length > 0 ) {
			imgwidth = surveysystem( this ).parent().find( ".ao-accordion .aopts_width" ).val();
		}
		if ( surveysystem( this ).parent().find( ".ao-accordion .aopts_height" ).length > 0 ) {
			imgheight = surveysystem( this ).parent().find( ".ao-accordion .aopts_height" ).val();
		}

		var thiscount = surveysystem("#"+survey_id+" #"+thisquestion+" div span#answer_count"+(index2+1)).text().split(" - ");
			qa[(index2+1)] = surveysystem(this).val();
			if ( typeof surveysystem( this ).attr( "data-answertype" ) != "undefined" ) {
				answer_type = surveysystem( this ).attr( "data-answertype" );
			}
			if ( typeof surveysystem( this ).attr( "data-unique" ) != "undefined" ) {
				if ( surveysystem( this ).attr( "data-unique" ) != "" ) {
					answer_unique = surveysystem( this ).attr( "data-unique" );
				}
			}
			if ( surveysystem( "#" + survey_id + " .autocomplete" + answer_unique ).length > 0 ) {
				if ( surveysystem( "#" + survey_id + " .autocomplete" + answer_unique ).val() == "1" ) {
						autocomplete = "1";
				}
			}
			if ( surveysystem( "#" + survey_id + " .converttextarea" + answer_unique ).length > 0 ) {
				if ( surveysystem( "#" + survey_id + " .converttextarea" + answer_unique ).val() == "1" ) {
						converttextarea = "1";
				}
			}
			if ( surveysystem( this ).parent().find( ".ao-accordion .aopts_status" ).val() != "1" ) {
				answer_status = "0";
			}
			else {
				answer_status = "1";
			}
			ao[ ( index + 1 ) + "_" + ( index2 + 1 ) ] = [answer_type, answer_unique, autocomplete, imageurl, score, correct, imgwidth, imgheight,answer_status, converttextarea,  surveysystem( this ).parent().find( ".ao-accordion .answer_tooltip_text" ).val(), surveysystem( this ).parent().find( ".ao-accordion .answer_redirection" ).val(), surveysystem( this ).parent().find( ".ao-accordion .answer_category" ).val(), hidelabel];
			})
			qoptions.push( [ surveysystem( "#" + survey_id + " #" + thisquestion + " input.choices" ).val(), surveysystem( "#" + survey_id + " #" + thisquestion + " input.minchoices" ).val(), qimageurl, surveysystem( "#" + survey_id + " #" + thisquestion + " .qopts_rating" ).val(), surveysystem( "#" + survey_id + " #" + thisquestion + " .question_tooltip_text" ).val(), surveysystem( "#" + survey_id + " #" + thisquestion + " .question_category" ).val(), surveysystem( "#" + survey_id + " #" + thisquestion + " .qopts_width" ).val(), surveysystem( "#" + survey_id + " #" + thisquestion + " .qopts_height" ).val() ] );
			thissurvey.push(qa);
		})
		aoptions.push( ao );
		var aopts = aoptions.reduce(function(o, v, i) {
		  o[i] = v;
		  return o;
		}, {});
		var customfsarray = new Array();
		var customfs = {};
		surveysystem( "#" + survey_id + " .one-custom-field" ).each( function( index ) {
			if ( surveysystem( this ).children( ".cfid" ).val() != '' && surveysystem( this ).children( ".cfname" ).val() ) {
			customfs = {};
			if ( surveysystem( this ).children( ".cfrequired" ).val() == '1' ) {
				var thisreq = 'true';
			}
			else {
				var thisreq = 'false';
			}
			customfs.id = surveysystem( this ).children( ".cfid" ).val();
			customfs.name = surveysystem( this ).children( ".cfname" ).val();
			customfs.required = thisreq;
			customfs.type = surveysystem( this ).children( ".cfid" ).attr( "data-type" );
			if ( customfs.type == "html" ) {
				customfs.name = surveysystem( this ).find( "body" ).val();
			}
			customfs.warning = surveysystem( this ).children( ".cfwarning" ).val();
			customfs.minlength = surveysystem( this ).children( ".cfminlength" ).val();
				customfsarray.push( customfs );
			}
		});
		var msurvey_admin_options = JSON.stringify({
			options : JSON.stringify([
			surveysystem( "#" + survey_id + " .display_style" ).val(),
			surveysystem( "#" + survey_id + " .animation_easing" ).val(),
			surveysystem( "#" + survey_id + " .font_family" ).val(),
			surveysystem( "#" + survey_id + " .bgcolor" ).val(),
			surveysystem( "#" + survey_id + " .modal_survey_preview1002" ).css( "background-color" ),
			surveysystem( "#" + survey_id + " .modal_survey_preview1003" ).css( "background-color" ),
			surveysystem( "#" + survey_id + " .modal_survey_border_width_value" ).val().replace( /[^\d.]/g, '' ),
			surveysystem( "#" + survey_id + " .modal_survey_border_radius_value" ).val().replace( /[^\d.]/g, '' ),
			surveysystem( "#" + survey_id + " .modal_survey_font_size_value" ).val().replace( /[^\d.]/g, '' ),
			surveysystem( "#" + survey_id + " .modal_survey_padding_value" ).val().replace( /[^\d.]/g, '' ),
			surveysystem( "#" + survey_id + " .modal_survey_line_height_value" ).val().replace( /[^\d.]/g, '' ),
			(surveysystem( "#" + survey_id + " .modal_survey_animation_speed_value" ).val().replace( /[^\d.]/g, '' ) ) * 1000,
			surveysystem( "#" + survey_id + " .thankyou textarea" ).val(),
			surveysystem( "#" + survey_id + " .lock_bg" ).val(),
			'1',
			surveysystem( "#" + survey_id + " .atbottom" ).val(),
			surveysystem( "#" + survey_id + " .text_align" ).val(),
			'modal',
			surveysystem( "#" + survey_id + " .loggedin" ).val(),
			surveysystem( "#" + survey_id + " .redirecturl" ).val(),
			surveysystem( "#" + survey_id + " .progressbar" ).val(),
			cond_array,
			surveysystem("#"+survey_id+" .listlayout").val(),
			(surveysystem( "#" + survey_id + " .modal_survey_end_delay_value" ).val().replace( /[^\d.]/g, '' ) ) * 1000,
			surveysystem( "#" + survey_id + " .addmoreapi_activecampaign" ).val(),
			surveysystem( "#" + survey_id + " .activecampaign_url" ).val(),
			surveysystem( "#" + survey_id + " .activecampaign_apikey" ).val(),
			surveysystem( "#" + survey_id + " .activecampaign_listid" ).val(),
			surveysystem( "#" + survey_id + " .addmoreapi_aweber" ).val(),
			surveysystem( "#" + survey_id + " .aweber_authorizationcode" ).val(),
			surveysystem( "#" + survey_id + " .aweber_consumerkey" ).val(),
			surveysystem( "#" + survey_id + " .aweber_consumersecret" ).val(),
			surveysystem( "#" + survey_id + " .aweber_accesskey" ).val(),
			surveysystem( "#" + survey_id + " .aweber_accesssecret" ).val(),
			surveysystem( "#" + survey_id + " .aweber_listid" ).val(),
			surveysystem( "#" + survey_id + " .addmoreapi_benchmark" ).val(),
			surveysystem( "#" + survey_id + " .benchmark_doubleoptin" ).val(),
			surveysystem( "#" + survey_id + " .benchmark_apikey" ).val(),
			surveysystem( "#" + survey_id + " .benchmark_listid" ).val(),
			surveysystem( "#" + survey_id + " .addmoreapi_campaignmonitor" ).val(),
			surveysystem( "#" + survey_id + " .campaignmonitor_apikey" ).val(),
			surveysystem( "#" + survey_id + " .campaignmonitor_listid" ).val(),
			surveysystem( "#" + survey_id + " .addmoreapi_campayn" ).val(),
			surveysystem( "#" + survey_id + " .campayn_domain" ).val(),
			surveysystem( "#" + survey_id + " .campayn_apikey" ).val(),
			surveysystem( "#" + survey_id + " .campayn_listid" ).val(),
			surveysystem( "#" + survey_id + " .addmoreapi_constantcontact" ).val(),
			surveysystem( "#" + survey_id + " .constantcontact_apikey" ).val(),
			surveysystem( "#" + survey_id + " .constantcontact_accesstoken" ).val(),
			surveysystem( "#" + survey_id + " .constantcontact_listid" ).val(),
			surveysystem( "#" + survey_id + " .addmoreapi_freshmail" ).val(),
			surveysystem( "#" + survey_id + " .freshmail_apikey" ).val(),
			surveysystem( "#" + survey_id + " .freshmail_apisecret" ).val(),
			surveysystem( "#" + survey_id + " .freshmail_listhash" ).val(),
			surveysystem( "#" + survey_id + " .addmoreapi_getresponse" ).val(),
			surveysystem( "#" + survey_id + " .getresponse_apikey" ).val(),
			surveysystem( "#" + survey_id + " .getresponse_campaignid" ).val(),
			surveysystem( "#" + survey_id + " .addmoreapi_icontact" ).val(),
			surveysystem( "#" + survey_id + " .icontact_appid" ).val(),
			surveysystem( "#" + survey_id + " .icontact_apiusername" ).val(),
			surveysystem( "#" + survey_id + " .icontact_apipassword" ).val(),
			surveysystem( "#" + survey_id + " .icontact_listid" ).val(),
			surveysystem( "#" + survey_id + " .addmoreapi_infusionsoft" ).val(),
			surveysystem( "#" + survey_id + " .infusionsoft_apikey" ).val(),
			surveysystem( "#" + survey_id + " .infusionsoft_campaignid" ).val(),
			surveysystem( "#" + survey_id + " .infusionsoft_groupid" ).val(),
			surveysystem( "#" + survey_id + " .addmoreapi_interspire" ).val(),
			surveysystem( "#" + survey_id + " .interspire_username" ).val(),
			surveysystem( "#" + survey_id + " .interspire_usertoken" ).val(),
			surveysystem( "#" + survey_id + " .interspire_listid" ).val(),
			surveysystem( "#" + survey_id + " .addmoreapi_madmimi" ).val(),
			surveysystem( "#" + survey_id + " .madmimi_username" ).val(),
			surveysystem( "#" + survey_id + " .madmimi_apikey" ).val(),
			surveysystem( "#" + survey_id + " .madmimi_listname" ).val(),
			surveysystem( "#" + survey_id + " .addmoreapi_mailchimp" ).val(),
			surveysystem( "#" + survey_id + " .mailchimp_apikey" ).val(),
			surveysystem( "#" + survey_id + " .mailchimp_listid" ).val(),
			surveysystem( "#" + survey_id + " .addmoreapi_mailerlite" ).val(),
			surveysystem( "#" + survey_id + " .mailerlite_apikey" ).val(),
			surveysystem( "#" + survey_id + " .mailerlite_listid" ).val(),
			surveysystem( "#" + survey_id + " .addmoreapi_mailigen" ).val(),
			surveysystem( "#" + survey_id + " .mailigen_apikey" ).val(),
			surveysystem( "#" + survey_id + " .mailigen_listid" ).val(),
			surveysystem( "#" + survey_id + " .mailigen_doubleoptin" ).val(),
			surveysystem( "#" + survey_id + " .addmoreapi_mailjet" ).val(),
			surveysystem( "#" + survey_id + " .mailjet_apikey" ).val(),
			surveysystem( "#" + survey_id + " .mailjet_secretkey" ).val(),
			surveysystem( "#" + survey_id + " .mailjet_listid" ).val(),
			surveysystem( "#" + survey_id + " .addmoreapi_mailpoet" ).val(),
			surveysystem( "#" + survey_id + " .mailpoet_listid" ).val(),
			surveysystem( "#" + survey_id + " .addmoreapi_emma" ).val(),
			surveysystem( "#" + survey_id + " .emma_accountid" ).val(),
			surveysystem( "#" + survey_id + " .emma_publickey" ).val(),
			surveysystem( "#" + survey_id + " .emma_privatekey" ).val(),
			surveysystem( "#" + survey_id + " .addmoreapi_mymail" ).val(),
			surveysystem( "#" + survey_id + " .mymail_listid" ).val(),
			surveysystem( "#" + survey_id + " .addmoreapi_ontraport" ).val(),
			surveysystem( "#" + survey_id + " .ontraport_appid" ).val(),
			surveysystem( "#" + survey_id + " .ontraport_key" ).val(),
			surveysystem( "#" + survey_id + " .ontraport_tagid" ).val(),
			surveysystem( "#" + survey_id + " .ontraport_sequenceid" ).val(),
			surveysystem( "#" + survey_id + " .addmoreapi_pinpointe" ).val(),
			surveysystem( "#" + survey_id + " .pinpointe_username" ).val(),
			surveysystem( "#" + survey_id + " .pinpointe_usertoken" ).val(),
			surveysystem( "#" + survey_id + " .pinpointe_listid" ).val(),
			surveysystem( "#" + survey_id + " .addmoreapi_sendinblue" ).val(),
			surveysystem( "#" + survey_id + " .sendinblue_accesskey" ).val(),
			surveysystem( "#" + survey_id + " .sendinblue_listid" ).val(),
			surveysystem( "#" + survey_id + " .addmoreapi_sendreach" ).val(),
			surveysystem( "#" + survey_id + " .sendreach_key" ).val(),
			surveysystem( "#" + survey_id + " .sendreach_secret" ).val(),
			surveysystem( "#" + survey_id + " .sendreach_userid" ).val(),
			surveysystem( "#" + survey_id + " .sendreach_listid" ).val(),
			surveysystem( "#" + survey_id + " .addmoreapi_sendy" ).val(),
			surveysystem( "#" + survey_id + " .sendy_installationurl" ).val(),
			surveysystem( "#" + survey_id + " .sendy_apikey" ).val(),
			surveysystem( "#" + survey_id + " .sendy_listid" ).val(),
			surveysystem( "#" + survey_id + " .addmoreapi_simplycast" ).val(),
			surveysystem( "#" + survey_id + " .simplycast_publickey" ).val(),
			surveysystem( "#" + survey_id + " .simplycast_secretkey" ).val(),
			surveysystem( "#" + survey_id + " .simplycast_listid" ).val(),
			surveysystem( "#" + survey_id + " .addmoreapi_ymlp" ).val(),
			surveysystem( "#" + survey_id + " .ymlp_username" ).val(),
			surveysystem( "#" + survey_id + " .ymlp_apikey" ).val(),
			surveysystem( "#" + survey_id + " .ymlp_groupid" ).val(),
			surveysystem( "#" + survey_id + " .msform_status" ).val(),
			surveysystem( "#" + survey_id + " .msform_name_field" ).val(),
			surveysystem( "#" + survey_id + " .msform_email_field" ).val(),
			surveysystem( "#" + survey_id + " .modal_survey_shadowh_value" ).val().replace( /[^\d.-]/g, '' ),
			surveysystem( "#" + survey_id + " .modal_survey_shadowv_value" ).val().replace( /[^\d.-]/g, '' ),
			surveysystem( "#" + survey_id + " .modal_survey_shadowb_value" ).val().replace( /[^\d.]/g, '' ),
			surveysystem( "#" + survey_id + " .modal_survey_shadows_value" ).val().replace( /[^\d.]/g, '' ),
			surveysystem( "#" + survey_id + " .modal_survey_preview1004" ).css( "background-color" ),
			surveysystem( "#" + survey_id + " .survey_preloader" ).val(),
			surveysystem( "#" + survey_id + " .survey_hover" ).val(),
			(surveysystem( "#" + survey_id + " .modal_survey_display_timer_value" ).val().replace( /[^\d.]/g, '' ) ) * 1000,
			surveysystem( "#" + survey_id + " .endchart_status" ).val(),
			surveysystem( "#" + survey_id + " .endchart_style" ).val(),
			surveysystem( "#" + survey_id + " .endchart_type" ).val(),
			surveysystem( "#" + survey_id + " .endchart_datatype" ).val(),
			surveysystem( "#" + survey_id + " .endchart_advancedchart" ).val(),
			surveysystem( "#" + survey_id + " .survey_closeicon" ).val(),
			surveysystem( "#" + survey_id + " .grid_items" ).val(),
			surveysystem( "#" + survey_id + " .modal_survey_cookie_expiration_value" ).val().replace( /[^\d.-]/g, '' ),
			surveysystem( "#" + survey_id + " .notificationemail" ).val(),
			surveysystem( "#" + survey_id + " .survey_closeiconsize" ).val(),
			surveysystem( "#" + survey_id + " .msform_email_validate_field" ).val(),
			surveysystem( "#" + survey_id + " #ms_autoresponse" ).val(),
			surveysystem( "#" + survey_id + " .autoresponse_sendername input" ).val(),
			surveysystem( "#" + survey_id + " .autoresponse_senderemail input" ).val(),
			surveysystem( "#" + survey_id + " .autoresponse_subject input" ).val(),
			surveysystem( "#" + survey_id + " .next_button_style" ).val(),
			surveysystem( "#" + survey_id + " .alwaysnext").val(),
			surveysystem( "#" + survey_id + " .rating_question_style" ).val(),
			surveysystem( "#" + survey_id + " .enableback").val(),
			surveysystem( "#" + survey_id + " .remandcont" ).val(),
			(surveysystem( "#" + survey_id + " .modal_survey_quiz_timer_value" ).val().replace( /[^\d.]/g, '' ) ) * 1000,
			surveysystem( "#" + survey_id + " .qtimer" ).val(),
			surveysystem( "#" + survey_id + " .animation_type" ).val(),
			customfsarray,
			surveysystem( "#" + survey_id + " .msform_confirmation" ).val(),
			surveysystem( "#" + survey_id + " .msform_wosignup" ).val()			
			]),
			plugin_url : sspa_params.plugin_url,
			grid_items: surveysystem( "#" + survey_id + " .grid_items" ).val(),
			admin_url : sspa_params.admin_url,
			survey_id : random,
			style : 'modal',
			expired : "false",
			debug : "true",
			questions: thissurvey,
			ao : aopts[0],
			qo : qoptions,
			display_once: "",
			preview: "true",
			social:[0]
			});
		if ( surveysystem( "#survey-" + random + "-1" ).length == 0 ) {
			surveysystem( "body" ).append( '<div id="survey-' + random + '-1" class="modal-survey-container" style="width:100%;"></div>' );
		}
		surveysystem( "#survey-" + random + "-1" ).modalsurvey({ "unique_key": "1", "survey_options": msurvey_admin_options });
	}
	
	surveysystem("body").on( "click", ".play_button",function() {
	var s_id = surveysystem(this).parent().parent().attr("id");
	active_survey = s_id;
	played_question=1;
	if (surveysystem("#"+active_survey+" .lock_bg").val()==1) 
	{
		if (surveysystem("#bglock").length==0) surveysystem("body").append("<div id='bglock'></div>");
		surveysystem("#bglock").fadeIn(1000);
	}
	play_survey();
	})
	
function add_survey()
{
	if (rmdni==false)
		{
		var survey_id = Math.abs( surveysystem( "#survey_name" ).val().split("").reduce(function(a,b){a=((a<<5)-a)+b.charCodeAt(0);return a&a},0));
		var data = {
			action: 'ajax_survey',
			sspcmd: 'add',
			survey_name: surveysystem( "#survey_name" ).val(),
			survey_id: survey_id
			};
		rmdni = true;
		surveysystem( "#button-container" ).html( '<img width="20" src="' + sspa_params.plugin_url + '/templates/assets/img/preloader.gif">' );
		checker = setTimeout( function() {
			if ( surveysystem( "#button-container" ).html() == '<img width="20" src="' + sspa_params.plugin_url + '/templates/assets/img/preloader.gif">' ) {
				surveysystem( "#button-container" ).html('<a id="add_new_survey" class="button button-secondary button-small">' + sspa_params.languages.newsurvey + '</a>' );
				surveysystem( "#error_log" ).html( sspa_params.languages.saveerror );
			}
		}, 15000 );
		surveysystem.post( sspa_params.admin_url, data, function( response ) {
			if ( response.toLowerCase().indexOf( "success" ) >= 0 || response.toLowerCase().indexOf( "updated" ) >= 0 ) {
			clearTimeout( checker );
			surveysystem( "#error_log" ).html( sspa_params.languages.successcreate );
			window.location = response.replace( "success", "" );
			}
			else {
				surveysystem( "#button-container" ).html('<a id="add_new_survey" class="button button-secondary button-small">' + sspa_params.languages.newsurvey + '</a>');
				surveysystem( "#error_log" ).html( sspa_params.languages.saveerror + ": " + response );
			}
			rmdni = false;
		});
		}
}
function initialize_question_accordions(survey_id)
{
	surveysystem( "#"+survey_id+" #new_questions" ).accordion({
      collapsible: true,
	  heightStyle: "content",
	  header: "> div > h3",
	  beforeActivate: function( event, ui ) {
		if ( surveysystem( "#new_questions .ui-accordion-header" ).length > 0 ) {
			var questionid = surveysystem( ui.newPanel ).children( ".left_half" ).children( "div" ).attr( "id" );
			if ( questionid != undefined ) {
				questionid = questionid.replace( "question_", "" )
			}
			var canvas = document.getElementById( "modal_survey_pro_graph_" + survey_id + '_' + questionid );
			canvas.width = canvas.width;
		}
	  },
	  activate: function( event, ui ) {
		if ( surveysystem( "#new_questions .ui-accordion-header" ).length > 0 ) {
			var questionid = surveysystem( ui.newPanel ).children( ".left_half" ).children( "div" ).attr( "id" ).replace( "question_", "" );
			var canvas = document.getElementById("modal_survey_pro_graph_" + survey_id + '_' + questionid );
			canvas.width = canvas.width;
			create_graph( survey_id, questionid, "true" );
		}
	  }
    }).sortable({
        axis: "y",
        handle: "h3",
        stop: function( event, ui ) {
          ui.item.children( "h3" ).triggerHandler( "focusout" );
		  surveysystem( "#" + survey_id + " #new_questions h3" ).each( function( index ) {
			surveysystem( this ).html( '<span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-e"></span>' + ( index + 1 ) + '. ' + sspa_params.languages.question + '<span class="question-subheader">' + surveysystem( this ).parent().find( "textarea.question_text" ).val() + '</span>' );
		  })
        }
      });
};
	surveysystem( "#survey_name" ).keypress(function( event ) {
	if ( event.which == 13 ) {
	event.preventDefault();
	add_survey();
	}
	});
  
  	surveysystem( document ).on( "click", "#add_new_survey", function (event) {
		add_survey();
	});
	});
	window.numbersandminusonly = function( e ){ 
		var unicode=e.charCode? e.charCode : e.keyCode
		if ( unicode != 8 ) { //if the key isn't the backspace key (which we should allow)
			if ( ( unicode < 48 || unicode > 57 ) && ( unicode != 45 ) ) {//if not a number
				return false; //disable key press
			}
		}
	}

	function remove_condition( cond ) {
		surveysystem( ".one_condition_line" ).eq( cond ).remove();		
	}

	function add_normal_answer( thisanswer ) {
		var answer_area = surveysystem( thisanswer ).parent().parent().attr( "id" );
		var question_num = surveysystem( thisanswer ).parent().parent().parent().attr( "id" ).replace( "question_", "" );
		var answer_num = surveysystem( "#" + answer_area.replace( "answers_", "" ) + " #question_" + question_num + " .answer" ).length + 1;
		surveysystem( "#" + answer_area.replace( "answers_", "" ) + " #question_" + question_num ).append( '<div class="added_answers" id="answer_element_' + answer_area.replace( "answers_", "" ) + '_' + answer_num + '"><a href="#" class="answer_options" data-aid="' + answer_num + '"><img class="modal_survey_tooltip" title="' + sspa_params.languages.answeroptions + '" src="' + sspa_params.plugin_url + '/templates/assets/img/options.png"></a><a href="#" class="answer_status answer_status' + answer_num + '" data-aid="' + answer_num + '"><img class="modal_survey_tooltip" title="' + sspa_params.languages.activeanswer + '" src="' + sspa_params.plugin_url + '/templates/assets/img/active.png"></a><span class="ans_nmo">'+answer_num+'.</span> ' + sspa_params.languages.answer + ': <input type="text" data-unique="' + (Math.floor(Math.random() * 26) + Date.now()) + '" id="answer'+answer_num+'" name="answer[]" style="width:40%;" class="answer" value="" /><span id="answer_count'+answer_num+'" class="answer_count">0 - 0%</span><a class="remove_answer" id="remove_'+answer_area+'_'+answer_num+'"><img class="modal_survey_tooltip" title="' + sspa_params.languages.removeanswer + '" src="' + sspa_params.plugin_url + '/templates/assets/img/delete.png"></a><div class="ao-accordion oaa' + answer_num + ' oaa' + answer_area.replace( "answers_", "" ) + '_' + answer_num + '"><h4>' + sspa_params.languages.answeroptions + '</h4><div><div class="left_aopts"><div class="modal_survey_tooltip optscontainer" title="' + sspa_params.languages.imgwidthhint + '">' + sspa_params.languages.imgwidth + ': <input type="text" size="10" maxlength="10" placeholder="' + sspa_params.languages.imgwidthp + '" class="aopts_width optsinput" value=""></div><div class="modal_survey_tooltip optscontainer" title="' + sspa_params.languages.imgheighthint + '">' + sspa_params.languages.imgheight + ': <input type="text" size="10" maxlength="10" placeholder="' + sspa_params.languages.imgheightp + '" class="aopts_height optsinput" value=""></div><div class="imageelement"><div class="uploaded_image"><input class="answer-image-upload button add-button" type="button" value="' + sspa_params.languages.addimage + '" /></div></div><div class="modal_survey_tooltip optscontainer" title="' + sspa_params.languages.hidelabel_hint + '"><label><input type="checkbox" class="aopts_hide_label ms-checkbox" value=""><span>' + sspa_params.languages.hidelabel + '</span></label></div><div class="img-pos-cont"><div class="optscontainer">' + sspa_params.languages.imgpos + '</div><label><input type="radio" name="img-align" checked class="img-align" value=""> ' + sspa_params.languages.imgdef + '</label><label><input type="radio" name="img-align" class="img-align" value="1"> ' + sspa_params.languages.imgtop + '</label><label><input type="radio" name="img-align" class="img-align" value="2"> ' + sspa_params.languages.imgbot + '</label><label><input type="radio" name="img-align" class="img-align" value="3"> ' + sspa_params.languages.imgontop + '</label><label><input type="radio" name="img-align" class="img-align" value="3"> ' + sspa_params.languages.imgontop + '</label></div></div><div class="right_aopts"><div class="modal_survey_tooltip optscontainer" title="' + sspa_params.languages.scorehint + '">' + sspa_params.languages.answerscore + ': <input type="number" size="10" maxlength="10" onkeypress="return numbersandminusonly(event)" placeholder="' + sspa_params.languages.number + '" class="aopts_score optsinput" value=""></div><div class="answer_tooltip optscontainer"><span>' + sspa_params.languages.customtooltip + '</span><textarea maxlength="300" class="answer_tooltip_text" placeholder="' + sspa_params.languages.customtooltipph + '"></textarea></div><div class="answer_redirection_container optscontainer modal_survey_tooltip" title="' + sspa_params.languages.redirecttooltip + '"><span>' + sspa_params.languages.clredirection + '</span><input class="answer_redirection optsinput" placeholder="' + sspa_params.languages.redplaceholder + '" onkeypress="return numbersandminusonly(event)" value=""></div><div class="answer_category_container optscontainer modal_survey_tooltip" title="' + sspa_params.languages.category_tooltip + '"><span>' + sspa_params.languages.category + '</span><input class="answer_category optsinput" placeholder="' + sspa_params.languages.category_placeholder + '" value=""></div><div class="modal_survey_tooltip optscontainer" title="' + sspa_params.languages.setcorrect + '"><label><input type="checkbox" class="aopts_correct_answer ms-checkbox" value="0"><span>' + sspa_params.languages.setcorrect2 + '</span></label></div><input type="hidden" class="aopts_status" value="0"></div></div></div></div>' );
		create_graph( answer_area.replace( "answers_", "" ), question_num, "true" );
		initialize_tooltips();
		//surveysystem( "#answer_element_" + answer_area.replace( "answers_", "" ) + "_" + answer_num + " input" ).focus();
		surveysystem( ".oaa" + answer_area.replace( "answers_", "" ) + "_" + answer_num ).accordion({
			collapsible: true,
			active: false,
			heightStyle: "content"		
		});		
	}
	
  	surveysystem("body").on( "click", ".add_answer", function( event ) {
		event.preventDefault();
		surveysystem( "#dialog-help4" ).data( 'this', this ).dialog( "open" );
	});
	
	surveysystem( "body" ).on( "click", ".converttextarea_container input", function() {
		if ( surveysystem( this ).prop( "checked" ) == true ) {
			surveysystem( this ).parent().parent().parent().parent().find( ".autocomplete_container input" ).prop( "checked", false );
			surveysystem( this ).parent().parent().parent().parent().find( ".autocomplete_container input" ).val( "0" );
		}
	});
	
	surveysystem( "body" ).on( "click", ".autocomplete_container input", function() {
		if ( surveysystem( this ).prop( "checked" ) == true ) {
			surveysystem( this ).parent().parent().parent().parent().find( ".converttextarea_container input" ).prop( "checked", false );
			surveysystem( this ).parent().parent().parent().parent().find( ".converttextarea_container input" ).val( "0" );
		}
	});

  	surveysystem( document ).on( "click", ".add_custom_fields", function () {
	var s_id = surveysystem( this ).parent().parent().attr( "id" );
	surveysystem( "#" + s_id + " .custom_field_section" ).append( "<div class='one-custom-field'><input type='text' data-type='text' class='cfid modal_survey_tooltip' title='" + sspa_params.languages.customtext_hint + "' value='' onkeyup=\"this.value = this.value.replace(/[^a-zA-Z0-9]/g,\'\');\" placeholder='ID'><input type='text' class='cfname modal_survey_tooltip' value='' title='" + sspa_params.languages.customtext_name_hint + "' placeholder='" + sspa_params.languages.customtext_name_placeholder + "'><input type='text' class='cfwarning modal_survey_tooltip' title='" + sspa_params.languages.customtext_warning + "' value='' placeholder='" + sspa_params.languages.customtext_warning_placeholder + "'><input type='text' class='cfminlength modal_survey_tooltip' title='" + sspa_params.languages.customtext_min + "' value='' placeholder='0'><input type='checkbox' class='cfrequired modal_survey_tooltip' title='" + sspa_params.languages.customtext_required + "' value='0'><img class='remove_cfield modal_survey_tooltip' title='" + sspa_params.languages.customtext_remove + "' src='" + sspa_params.plugin_url + "/templates/assets/img/delete.png'></div>" );
			initialize_tooltips();
	});
  	surveysystem( document ).on( "click", ".add_custom_fields_radio", function () {
	var s_id = surveysystem( this ).parent().parent().attr( "id" );
	surveysystem( "#" + s_id + " .custom_field_section" ).append( "<div class='one-custom-field'><input type='text' data-type='radio' class='cfid modal_survey_tooltip' title='" + sspa_params.languages.customradio_hint + "' value='' onkeyup=\"this.value = this.value.replace(/[^a-zA-Z0-9]/g,\'\');\" placeholder='ID'><input type='text' class='cfname modal_survey_tooltip longinput' value='' title='" + sspa_params.languages.customradio_name_hint + "' placeholder='" + sspa_params.languages.customradio_name_placeholder + "'><input type='checkbox' class='cfrequired modal_survey_tooltip' title='" + sspa_params.languages.customradio_required + "' value='0'><img class='remove_cfield modal_survey_tooltip' title='" + sspa_params.languages.customradio_remove + "' src='"+sspa_params.plugin_url+"/templates/assets/img/delete.png'></div>" );
			initialize_tooltips();
	});
  	surveysystem( document ).on( "click", ".add_custom_fields_textarea", function () {
	var s_id = surveysystem( this ).parent().parent().attr( "id" );
	surveysystem( "#" + s_id + " .custom_field_section" ).append( "<div class='one-custom-field'><input type='text' data-type='textarea' class='cfid modal_survey_tooltip' title='" + sspa_params.languages.customtextarea_hint + "' onkeyup=\"this.value = this.value.replace(/[^a-zA-Z0-9]/g,\'\');\" value='' placeholder='ID'><input type='text' class='cfname modal_survey_tooltip' value='' title='" + sspa_params.languages.customtextarea_name_hint + "' placeholder='" + sspa_params.languages.customtextarea_name_placeholder + "'><input type='text' class='cfwarning modal_survey_tooltip' title='" + sspa_params.languages.customtextarea_warning + "' value='' placeholder='" + sspa_params.languages.customtextarea_warning_placeholder + "'><input type='text' class='cfminlength modal_survey_tooltip' title='" + sspa_params.languages.customtextarea_min + "' value='' placeholder='0'><input type='checkbox' class='cfrequired modal_survey_tooltip' title='" + sspa_params.languages.customtextarea_required + "' value='0'><img class='remove_cfield modal_survey_tooltip' title='" + sspa_params.languages.customtextarea_remove + "' src='" + sspa_params.plugin_url + "/templates/assets/img/delete.png'></div>" );
			initialize_tooltips();
	});
  	surveysystem( document ).on( "click", ".add_custom_fields_select", function () {
	var s_id = surveysystem( this ).parent().parent().attr( "id" );
	surveysystem( "#" + s_id + " .custom_field_section").append( "<div class='one-custom-field'><input type='text' data-type='select' class='cfid modal_survey_tooltip' title='" + sspa_params.languages.customselect_hint + "' value='' onkeyup=\"this.value = this.value.replace(/[^a-zA-Z0-9]/g,\'\');\" placeholder='ID'><input type='text' class='cfname modal_survey_tooltip longinput' value='' title='" + sspa_params.languages.customselect_name_hint + "' placeholder='" + sspa_params.languages.customselect_name_placeholder + "' class='longinput'><input type='checkbox' class='cfrequired modal_survey_tooltip' title='" + sspa_params.languages.customselect_required + "' value='0'><img class='remove_cfield modal_survey_tooltip' title='" + sspa_params.languages.customselect_remove + "' src='" + sspa_params.plugin_url + "/templates/assets/img/delete.png'></div>");
			initialize_tooltips();
	});
  	surveysystem( document ).on( "click", ".add_custom_fields_hidden", function () {
	var s_id = surveysystem( this ).parent().parent().attr( "id" );
	surveysystem("#" + s_id + " .custom_field_section" ).append( "<div class='one-custom-field'><input type='text' data-type='hidden' class='cfid modal_survey_tooltip' title='" + sspa_params.languages.customhidden_hint + "' value='' onkeyup=\"this.value = this.value.replace(/[^a-zA-Z0-9]/g,\'\');\" placeholder='ID'><input type='text' class='cfname modal_survey_tooltip longinput' value='' title='" + sspa_params.languages.customhidden_name_hint + "' placeholder='" + sspa_params.languages.customhidden_name_placeholder + "' class='longinput'><div class='emptycheckbox'></div><img class='remove_cfield modal_survey_tooltip' title='" + sspa_params.languages.customhidden_remove + "' src='" + sspa_params.plugin_url + "/templates/assets/img/delete.png'></div>" );
			initialize_tooltips();
	});
  	surveysystem( document ).on( "click", ".add_custom_fields_checkbox", function () {
	var s_id = surveysystem( this ).parent().parent().attr( "id" );
	surveysystem( "#" + s_id + " .custom_field_section" ).append( "<div class='one-custom-field'><input type='text' data-type='checkbox' class='cfid modal_survey_tooltip' title='" + sspa_params.languages.customcheckbox_hint + "' value='' onkeyup=\"this.value = this.value.replace(/[^a-zA-Z0-9]/g,\'\');\" placeholder='ID'><input type='text' class='cfname modal_survey_tooltip longinput' value='' title='" + sspa_params.languages.customcheckbox_name_hint + "' placeholder='" + sspa_params.languages.customcheckbox_name_placeholder + "'><input type='checkbox' class='cfrequired modal_survey_tooltip' title='" + sspa_params.languages.customcheckbox_required + "' value='0'><img class='remove_cfield modal_survey_tooltip' title='" + sspa_params.languages.customcheckbox_remove + "' src='"+sspa_params.plugin_url+"/templates/assets/img/delete.png'></div>" );
			initialize_tooltips();
	});
  	surveysystem( document ).on( "click", ".add_custom_fields_html", function () {
	var s_id = surveysystem( this ).parent().parent().attr( "id" );
	var unihtmlid = Math.floor(Math.random() * 26) + Date.now();
	surveysystem( "#" + s_id + " .custom_field_section" ).append( "<div class='one-custom-field custom_field_section_html' data-type='html' data-id='" + unihtmlid + "'><textarea data-type='html' class='cfid' id='customhtml_" + unihtmlid + "'></textarea><div class='emptycheckbox'></div><img class='remove_cfield modal_survey_tooltip' title='" + sspa_params.languages.customhtml_remove + "' src='"+sspa_params.plugin_url+"/templates/assets/img/delete.png'><div class='customhtml-tip'>" + sspa_params.languages.customhtml_tip + "</div><div class='customhtml_position'><label><input type='radio' name='customhtml_pos_" + unihtmlid + "' class='customhtml_pos' value='1'>" + sspa_params.languages.customhtml_pos1 + "</label><label><input type='radio' name='customhtml_pos_" + unihtmlid + "' class='customhtml_pos' checked value='2'>" + sspa_params.languages.customhtml_pos2 + "</label><label><input type='radio' name='customhtml_pos_" + unihtmlid + "' class='customhtml_pos' value='3'>" + sspa_params.languages.customhtml_pos3 + "</label></div></div>" );
		initialize_tooltips();
	});

 	surveysystem( document ).on( "click", ".remove_cfield", function () {
		surveysystem( this ).parent().remove();
	})
	
 	function add_open_answer( thisanswer ) {
		var answer_area = surveysystem( thisanswer ).parent().parent().attr( "id" );
		var question_num = surveysystem( thisanswer ).parent().parent().parent().attr( "id" ).replace( "question_", "" );
		var answer_num = surveysystem( "#" + answer_area.replace( "answers_", "" ) + " #question_" + question_num + " .answer" ).length + 1;
		if ( surveysystem( "#" + answer_area.replace( "answers_", "" ) + " #question_" + question_num + " .open_answer_style").length > 0 ) {
			showmessage( sspa_params.languages.alreadyopena );
			return;
		}
		open_unique_id = ( Math.floor( Math.random() * 26 ) + Date.now() );
		surveysystem( "#" + answer_area.replace( "answers_", "" ) + " #question_" + question_num ).append( '<div class="added_answers" id="answer_element_' + answer_area.replace( "answers_", "" ) + '_' + answer_num + '"><a href="#" class="answer_options" data-aid="' + answer_num + '"><img class="modal_survey_tooltip" title="' + sspa_params.languages.answeroptions + '" src="' + sspa_params.plugin_url + '/templates/assets/img/options.png"></a><a href="#" class="answer_status answer_status' + answer_num + '" data-aid="' + answer_num + '"><img class="modal_survey_tooltip" title="' + sspa_params.languages.activeanswer + '" src="' + sspa_params.plugin_url + '/templates/assets/img/active.png"></a><span class="ans_nmo">'+answer_num+'.</span> ' + sspa_params.languages.answer + ': <input type="text" data-answertype="open" data-unique="' + open_unique_id + '" id="answer'+answer_num+'" name="answer[]" class="answer open_answer_style" value="' + sspa_params.languages.typea + '" /><span id="answer_count'+answer_num+'" class="answer_count">0 - 0%</span><a class="remove_answer" id="remove_'+answer_area+'_'+answer_num+'"><img class="modal_survey_tooltip" title="' + sspa_params.languages.removeanswer + '" src="'+sspa_params.plugin_url+'/templates/assets/img/delete.png"></a><div class="ao-accordion oaa' + answer_num + ' oaa' + answer_area.replace( "answers_", "" ) + '_' + answer_num + '"><h4>' + sspa_params.languages.answeroptions + '</h4><div><div class="left_aopts"><div class="modal_survey_tooltip optscontainer" title="' + sspa_params.languages.imgwidthhint + '">' + sspa_params.languages.imgwidth + ': <input type="text" size="10" maxlength="10" placeholder="' + sspa_params.languages.imgwidthp + '" class="aopts_width optsinput" value=""></div><div class="modal_survey_tooltip optscontainer" title="' + sspa_params.languages.imgheighthint + '">' + sspa_params.languages.imgheight + ': <input type="text" size="10" maxlength="10" placeholder="' + sspa_params.languages.imgheightp + '" class="aopts_height optsinput" value=""></div><div class="imageelement"><div class="uploaded_image"><input class="answer-image-upload button add-button" type="button" value="' + sspa_params.languages.addimage + '" /></div></div><div class="modal_survey_tooltip optscontainer" title="' + sspa_params.languages.hidelabel_hint + '"><label><input type="checkbox" class="aopts_hide_label ms-checkbox" value=""><span>' + sspa_params.languages.hidelabel + '</span></label></div><div class="img-pos-cont"><div class="optscontainer">' + sspa_params.languages.imgpos + '</div><label><input type="radio" name="img-align" checked class="img-align" value=""> ' + sspa_params.languages.imgdef + '</label><label><input type="radio" name="img-align" class="img-align" value="1"> ' + sspa_params.languages.imgtop + '</label><label><input type="radio" name="img-align" class="img-align" value="2"> ' + sspa_params.languages.imgbot + '</label><label><input type="radio" name="img-align" class="img-align" value="3"> ' + sspa_params.languages.imgontop + '</label></div></div><div class="right_aopts"><div class="modal_survey_tooltip optscontainer" title="' + sspa_params.languages.scorehint + '">' + sspa_params.languages.answerscore + ': <input type="number" size="10" maxlength="10" onkeypress="return numbersandminusonly(event)" placeholder="' + sspa_params.languages.number + '" class="aopts_score optsinput" value=""></div><div class="answer_tooltip optscontainer"><span>' + sspa_params.languages.customtooltip + '</span><textarea maxlength="300" class="answer_tooltip_text" placeholder="' + sspa_params.languages.customtooltipph + '"></textarea></div><div class="answer_redirection_container optscontainer modal_survey_tooltip" title="' + sspa_params.languages.redirecttooltip + '"><span>' + sspa_params.languages.clredirection + '</span><input class="answer_redirection optsinput" placeholder="' + sspa_params.languages.redplaceholder + '" onkeypress="return numbersandminusonly(event)" value=""></div><div class="answer_category_container optscontainer modal_survey_tooltip" title="' + sspa_params.languages.category_tooltip + '"><span>' + sspa_params.languages.category + '</span><input class="answer_category optsinput" placeholder="' + sspa_params.languages.category_placeholder + '" value=""></div><div class="optscontainer"><span class="autocomplete_container modal_survey_tooltip" title="' + sspa_params.languages.autocomplete + '"><label><input type="checkbox" name="autocomplete' + open_unique_id + '" class="autocomplete' + open_unique_id + ' ms-checkbox" value="0"> ' + sspa_params.languages.autocomplete + '</label></span><span class="converttextarea_container modal_survey_tooltip" title="' + sspa_params.languages.converttextarea + '"><label><input type="checkbox" name="converttextarea' + open_unique_id + '" class="converttextarea' + open_unique_id + ' ms-checkbox" value="0"> ' + sspa_params.languages.converttextarea + '</label></span></div><input type="hidden" class="aopts_status" value="0"></div></div></div>');
		create_graph( answer_area.replace( "answers_", "" ), question_num, "true" );
		initialize_tooltips();
		//surveysystem( "#answer_element_" + answer_area.replace( "answers_", "" ) + "_" + answer_num + " input" ).focus();
		surveysystem( ".oaa" + answer_area.replace( "answers_", "" ) + "_" + answer_num ).accordion({
			collapsible: true,
			active: false,
			heightStyle: "content"		
		});
	};
  	surveysystem("body").on( "click", ".duplicate_question",function() {
		var question_num = surveysystem( this ).attr( "data-qid" );
		var answer_area = surveysystem( ".nquestion" ).parent().parent().attr( "id" );
		var question_next = surveysystem( "#" + answer_area + " .question_text" ).length + 1;
		var duplicated = surveysystem( this ).closest( ".group" ).clone();
		surveysystem( duplicated ).children( "h3" ).html( ( question_next ) + '. ' + sspa_params.languages.question );
		surveysystem( duplicated ).children( "#question_section" + question_num ).attr( "id", "question_section_" + question_next );
		surveysystem( duplicated ).children().children( ".left_half" ).children().attr( "id", "question_" + question_next );
		surveysystem( duplicated ).find( ".right_half" ).attr( "id", "chart" + question_next );
		surveysystem( duplicated ).find( ".question_options" ).attr( "data-qid", question_next );
		surveysystem( duplicated ).find( ".question-area" ).children( "textarea" ).attr( "id", "question" + question_next );
		surveysystem( duplicated ).find( ".remove_question" ).attr( "id", "remove_question_" + answer_area + "_" + question_next );
		surveysystem( duplicated ).find( ".duplicate_question" ).attr( "id", "duplicate_question_" + answer_area + "_" + question_next );
		surveysystem( duplicated ).find( ".qo-accordion" ).addClass( "qaa" + question_next ).addClass( "qaa" + question_next + "_" + question_next ).removeClass( "qaa" + question_num ).removeClass( "qaa" + question_num + "_" + question_num );
		surveysystem( duplicated ).find( ".answer_count" ).html( "0 - 0%" );
		surveysystem( duplicated ).find( ".open_answers_accordion" ).remove();
		surveysystem( "#" + answer_area + " #new_questions" ).append( duplicated );	
		surveysystem( "#" + answer_area + " .ao-accordion" ).accordion({
			collapsible: true,
			active: false,
			heightStyle: "content"		
		});
		surveysystem( "#" + answer_area + " .qo-accordion" ).accordion({
			collapsible: true,
			active: false,
			heightStyle: "content"		
		});
		
		surveysystem( duplicated ).find( '.answer' ).each( function( index, value ) {
			if ( surveysystem( this ).attr( "data-unique" ) != "" ) {
				surveysystem( this ).attr( "data-unique", (Math.floor(Math.random() * 26) + Date.now()) ); 
			}
		})
		
		surveysystem( "#" + answer_area + " #chart" + question_next ).html( '<canvas id="modal_survey_pro_graph_' + answer_area + '_' + question_next + '" class="canvas_graph" height="250" width="250"></canvas>');
		surveysystem( "#" + answer_area + " .left_half " + "#question_" + question_next + ">span").attr( "id", "answers_" + answer_area );
		create_graph( answer_area, question_next, "true" );
		surveysystem( "#" + answer_area + " #new_questions" ).accordion( "refresh" );
		surveysystem( "#" + answer_area + " #new_questions" ).accordion({ active: question_next - 1 });
		initialize_tooltips();
		surveysystem( "#" + answer_area + " #question_" + question_next ).select();
		manage_conds();
		
	});
  	surveysystem("body").on( "click", ".add_question",function() {
		var answer_area = surveysystem(this).parent().parent().attr("id");
		var question_num = (surveysystem("#"+answer_area+" .question_text").length+1);
		var question_options = '<div class="qo-accordion qaa' + question_num + ' qaa' + answer_area + '_1"><h4>' + sspa_params.languages.questionoptions + '</h4><div><div class="left_qopts"><div class="modal_survey_tooltip optscontainer" title="' + sspa_params.languages.qoptwidth + '">' + sspa_params.languages.qimgwidth + ' <input type="text" size="10" maxlength="10" placeholder="' + sspa_params.languages.qimgwidth_pl + '" class="qopts_width optsinput" value=""></div><div class="modal_survey_tooltip optscontainer" title="' + sspa_params.languages.qoptheight + '">' + sspa_params.languages.qimgheight + ' <input type="text" size="10" maxlength="10" placeholder="' + sspa_params.languages.qimgheight_pl + '" class="qopts_height optsinput" value=""></div><div class="imageelement"><div class="uploaded_image"><input class="answer-image-upload button add-button" type="button" value="' + sspa_params.languages.addimage + '" /></div></div><div class="img-pos-cont"><div class="optscontainer">' + sspa_params.languages.imgpos + '</div><label><input type="radio" name="img-align" checked class="img-align" value=""> ' + sspa_params.languages.imgdef + '</label><label><input type="radio" name="img-align" class="img-align" value="1"> ' + sspa_params.languages.imgtop + '</label><label><input type="radio" name="img-align" class="img-align" value="2"> ' + sspa_params.languages.imgbot + '</label><label><input type="radio" name="img-align" class="img-align" value="3"> ' + sspa_params.languages.imgontop + '</label></div></div><div class="right_qopts"><div class="optscontainer"><span class="modal_survey_tooltip" title="' + sspa_params.languages.specopta + '">' + sspa_params.languages.optionalanswers + ':</span> <input name="choices[]" size="3" maxlength="3" type="number" min="1" class="choices optsinput" value="0"></div><div class="optscontainer"><span class="modal_survey_tooltip optscontainer" title="' + sspa_params.languages.specreqa + '">' + sspa_params.languages.requireda + ':</span> <input name="choices[]" size="3" type="number" min="1" maxlength="3" class="minchoices optsinput" value="0"></div><div class="question_tooltip optscontainer"><span>' + sspa_params.languages.customtooltip + '</span><textarea maxlength="300" class="question_tooltip_text" placeholder="' + sspa_params.languages.customtooltipphq + '"></textarea></div><div class="question_category_container optscontainer modal_survey_tooltip" title="' + sspa_params.languages.qcategory_tooltip + '"><span>' + sspa_params.languages.qcategory + '</span><input class="question_category optsinput" placeholder="' + sspa_params.languages.qcategory_pl + '" value=""></div><div class="modal_survey_tooltip optscontainer" title="' + sspa_params.languages.setrating + '"><label><input type="checkbox" class="qopts_rating ms-checkbox" value="0"><span>' + sspa_params.languages.setratingq + '</span></label></div></div></div></div>';
		var answer_options = '<div class="ao-accordion oaa1 oaa' + answer_area + '_1"><h4>' + sspa_params.languages.answeroptions + '</h4><div><div class="left_aopts"><div class="modal_survey_tooltip optscontainer" title="' + sspa_params.languages.imgwidthhint + '">' + sspa_params.languages.imgwidth + ': <input type="text" size="10" maxlength="10" placeholder="' + sspa_params.languages.imgwidthp + '" class="aopts_width optsinput" value=""></div><div class="modal_survey_tooltip optscontainer" title="' + sspa_params.languages.imgheighthint + '">' + sspa_params.languages.imgheight + ': <input type="text" size="10" maxlength="10" placeholder="' + sspa_params.languages.imgheightp + '" class="aopts_height optsinput" value=""></div><div class="imageelement"><div class="uploaded_image"><input class="answer-image-upload button add-button" type="button" value="' + sspa_params.languages.addimage + '" /></div></div><div class="modal_survey_tooltip optscontainer" title="' + sspa_params.languages.hidelabel_hint + '"><label><input type="checkbox" class="aopts_hide_label ms-checkbox" value=""><span>' + sspa_params.languages.hidelabel + '</span></label></div><div class="img-pos-cont"><div class="optscontainer">' + sspa_params.languages.imgpos + '</div><label><input type="radio" name="img-align" class="img-align" checked value=""> ' + sspa_params.languages.imgdef + '</label><label><input type="radio" name="img-align" class="img-align" value="1"> ' + sspa_params.languages.imgtop + '</label><label><input type="radio" name="img-align" class="img-align" value="2"> ' + sspa_params.languages.imgbot + '</label><label><input type="radio" name="img-align" class="img-align" value="3"> ' + sspa_params.languages.imgontop + '</label></div></div><div class="right_aopts"><div class="modal_survey_tooltip optscontainer" title="' + sspa_params.languages.scorehint + '">' + sspa_params.languages.answerscore + ': <input type="number" size="10" maxlength="10" onkeypress="return numbersandminusonly(event)" placeholder="' + sspa_params.languages.number + '" class="aopts_score optsinput" value=""></div><div class="answer_tooltip optscontainer"><span>' + sspa_params.languages.customtooltip + '</span><textarea maxlength="300" class="answer_tooltip_text" placeholder="' + sspa_params.languages.customtooltipph + '"></textarea></div><div class="answer_redirection_container optscontainer modal_survey_tooltip" title="' + sspa_params.languages.redirecttooltip + '"><span>' + sspa_params.languages.clredirection + '</span><input class="answer_redirection optsinput" placeholder="' + sspa_params.languages.redplaceholder + '" onkeypress="return numbersandminusonly(event)" value=""></div><div class="answer_category_container optscontainer modal_survey_tooltip" title="' + sspa_params.languages.category_tooltip + '"><span>' + sspa_params.languages.category + '</span><input class="answer_category optsinput" placeholder="' + sspa_params.languages.category_placeholder + '" value=""></div><div class="modal_survey_tooltip optscontainer" title="' + sspa_params.languages.setcorrect + '"><label><input type="checkbox" class="aopts_correct_answer ms-checkbox" value="0"><span>' + sspa_params.languages.setcorrect2 + '</span></label></div><input type="hidden" class="aopts_status" value="0"></div></div></div>';
		surveysystem("#"+answer_area+" #new_questions").append('<div class="group addednow" style="opacity: 0;"><h3>'+question_num+'. ' + sspa_params.languages.question + '<span class="question-subheader"></span></h3><div class="one_question" id="question_section'+question_num+'"><div class="left_half"><div id="question_'+question_num+'" class="questions_block"><div><a href="#" class="question_options" data-qid="' + question_num + '"><img class="modal_survey_tooltip" title="' + sspa_params.languages.questionoptions + '" src="'+sspa_params.plugin_url+'/templates/assets/img/options.png"></a><span class="question-area">' + sspa_params.languages.question + ':&nbsp; <textarea name="question[]" id="question'+question_num+'" style="width: 70%;" class="question_text"></textarea><a class="add_question"><img class="remove_question modal_survey_tooltip" title="' + sspa_params.languages.removequestion + '" id="remove_question_'+answer_area+'_'+question_num+'" src="'+sspa_params.plugin_url+'/templates/assets/img/delete.png"></a><a class="dup_question"><img class="duplicate_question modal_survey_tooltip" title="' + sspa_params.languages.duplicatequestion + '" id="duplicate_question_'+answer_area+'_'+question_num+'" data-qid="'+question_num+'" src="' + sspa_params.plugin_url + '/templates/assets/img/list-duplicate.png"></a></span>' + question_options + '</div><span><div class="default_answer"><a href="#" class="answer_options" data-aid="1"><img class="modal_survey_tooltip" title="' + sspa_params.languages.answeroptions + '" src="' + sspa_params.plugin_url + '/templates/assets/img/options.png"></a><a href="#" class="answer_status answer_status1" data-aid="1"><img class="modal_survey_tooltip" title="' + sspa_params.languages.activeanswer + '" src="' + sspa_params.plugin_url + '/templates/assets/img/active.png"></a><span class="ans_nmo">1.</span> ' + sspa_params.languages.answer + ': <input type="text" name="answer[]" class="answer" id="answer1" style="width: 40%;" value="' + sspa_params.languages.no + '" placeholder="' + sspa_params.languages.no + '" /><span id="answer_count1" class="answer_count">0 - 0%</span><a class="add_answer"><img class="modal_survey_tooltip" title="' + sspa_params.languages.addanswer + '" src="'+sspa_params.plugin_url+'/templates/assets/img/add.png"></a>' + answer_options + '</div></span></div></div><div id="chart'+question_num+'" class="right_half"></div></div>');
		surveysystem( "#" + answer_area + " .ao-accordion" ).accordion({
			collapsible: true,
			active: false,
			heightStyle: "content"		
		});
		surveysystem( "#" + answer_area + " .qo-accordion" ).accordion({
			collapsible: true,
			active: false,
			heightStyle: "content"		
		});
		surveysystem( "#" + answer_area + " #chart" + question_num ).html( '<canvas id="modal_survey_pro_graph_' + answer_area + '_' + question_num + '" class="canvas_graph" height="250" width="250"></canvas>' );
		surveysystem("#" + answer_area + " .left_half " + "#question_" + question_num + ">span" ).attr( "id", "answers_" + answer_area );
		create_graph( answer_area, question_num, "true" );
		surveysystem( "#"+answer_area+" #new_questions" ).accordion("refresh" );
		surveysystem( "#"+answer_area+" #new_questions" ).accordion({ active: question_num-1 });
		var demo_questions = [ sspa_params.languages.demoq1, sspa_params.languages.demoq2, sspa_params.languages.demoq3, sspa_params.languages.demoq4, sspa_params.languages.demoq5, sspa_params.languages.demoq6, sspa_params.languages.demoq7, sspa_params.languages.demoq8];
		initialize_tooltips();
		var random_question = Math.floor( Math.random() * demo_questions.length );
		surveysystem( "#" + answer_area + " #question" + question_num ).val( demo_questions[ random_question ] );
		surveysystem( "#question_section" + question_num ).parent().find( "h3" ).children( ".question-subheader" ).html( demo_questions[ random_question ] );
		surveysystem( "#" + answer_area + " #question" + question_num ).select();
		manage_conds();
	});
	
	surveysystem( "body" ).on( "keyup", ".question_text", function() {
		var thisq = surveysystem( this ).attr( "id" ).replace( "question", "" );
		surveysystem( "#question_section" + thisq ).parent().find( "h3" ).children( ".question-subheader" ).html( surveysystem( this ).val().replace(/(<([^>]+)>)/ig,"") );
	});
	function manage_conds() {
		surveysystem( '.conds option' ).each( function( index, value ) {
			if ( surveysystem( value ).val().indexOf( "questionscore_" ) >= 0 ) {
				surveysystem( this ).remove();
			}
		})
		surveysystem( ".one_question" ).each(function( index ) {
			if ( surveysystem( '.conds option[value="questionscore_' + ( index + 1 ) + '"]' ).length < 1 ) {
				surveysystem( '.conds' ).append( '<option value="questionscore_' + ( index + 1 ) + '">' + sspa_params.languages.question + ' ' + ( index + 1 ) + ' ' + sspa_params.languages.score + '</option>' );
			}
		})
	}
  	surveysystem("body").on( "click", ".remove_answer",function() {
	var remove_id = surveysystem(this).attr("id");
	var msurvey_id = remove_id.split("_");
	var question_id = surveysystem(this).parent().parent().attr("id").replace("question_","");
	surveysystem("#"+msurvey_id[2]+" #question_"+question_id+" #answer_element_"+remove_id.replace("remove_answers_","")).remove();
	surveysystem("#"+msurvey_id[2]+" #question_"+question_id+" .oaa"+remove_id.replace("remove_answers_","")).remove();
	surveysystem("#"+msurvey_id[2]+" #question_"+question_id+" .open_answers_container"+remove_id.replace("remove_answers_","")).remove();
	surveysystem( "#"+msurvey_id[2]+" #question_"+question_id+" .added_answers" ).each(function( index ) {
		surveysystem(this).children("span.ans_nmo").text(index+2+'.');
		surveysystem(this).children("a.answer_options").attr("data-aid", parseInt( index + 2 ) );
		surveysystem(this).children("span.answer_count").attr( "id", "answer_count" + ( index + 2 ) );
	});
	create_graph( msurvey_id[ 2 ], question_id, "true" );
	});
  	surveysystem("body").on( "click", ".delete_open_answer",function() {
	var remove_id = surveysystem(this).attr("data-id");
	var msurvey_id = remove_id.split("_");
	var thisopenanswer = surveysystem(this).parent().parent();
	var data = {
				action: 'ajax_survey',
				sspcmd: 'delete_open_answer',
				survey_id: msurvey_id[ 0 ],
				unique_id: msurvey_id[ 1 ],
				text: surveysystem(this).parent().text()
				};
				surveysystem.post(sspa_params.admin_url, data, function(response) {
					if (response.toLowerCase().indexOf("success") >= 0) 
					{
						thisopenanswer.remove();
					}
				});
	})
  	surveysystem("body").on( "click", ".remove_question",function() {
		var remove_id = surveysystem(this).attr("id");
		var msurvey_id = remove_id.split("_");
		surveysystem(this).parent().parent().parent().parent().parent().parent().parent().fadeOut('slow',
		function(){
		surveysystem(this).remove();
		surveysystem( "#"+msurvey_id[2]+" h3" ).each(function( index ) {
			surveysystem( this ).text( (index + 1) + " ." + sspa_params.languages.question );
		});
		manage_conds();		
		});
	});
	
 	surveysystem("body").on( "click", ".global_survey, .lock_bg, .closeable, .atbottom, .loggedin, .ms-checkbox, .participants-select, #participants-select-all, .admincheckbox, .cfrequired",function() {
		if ( surveysystem( this ).val() == "0" || surveysystem( this ).val() == "" ) {
			surveysystem( this ).val( "1" );
			surveysystem( this ).attr( "checked", "checked" );
		}
		else {
			surveysystem( this ).val( "0" );
			surveysystem( this ).removeAttr( "checked", "" );
		}
	})
		
 	surveysystem("body").on( "click", "#gradx_close",function() {
	surveysystem("#gradX").css("display","none");
	})
	
	function getFormattedDate(date) {
		var day = date.getDate();
		var month = date.getMonth() + 1;
		var year = date.getFullYear().toString().slice(2);
		return year + '-' + month + '-' + day;
	}

 	surveysystem("body").on( "click", ".save_survey",function() {
		if ( rmdni == false ) {
			surveysystem( ".floating-save-button" ).children( "img" ).attr( "src", sspa_params.plugin_url + "/templates/assets/img/floating-loader.svg" );
			var buttonspan = surveysystem( this ).parent();
			var error = false;
			var checker;
			var answers_array = [];
			var cond_array = [];
			var qsc_cond_array = "";
			var qscexp = "";
			surveysystem( "#" + survey_id + " .survey_error_span" ).html('');
				surveysystem( "#answers_" + survey_id + " .answer" ).each( function( index ) {
					answers_array[ index ] = surveysystem( this ).val();
				});
			surveysystem( "#" + survey_id + " .packed_cond" ).each(function( index ) {
				qsc_cond_array = JSON.parse( surveysystem( this ).val() );
				if ( qsc_cond_array[0].indexOf( "questionscore_" ) >= 0 ) {
					qscexp = qsc_cond_array[0].split( "_" );
					if ( qscexp[ 1 ] <= surveysystem( ".one_question" ).length ) {
						cond_array.push( surveysystem( this ).val() );
					}
				}
				else {
					cond_array.push( surveysystem( this ).val() );
				}
			})
			var customfsarray = new Array();
			var customfs = {};
			tinyMCE.triggerSave();
			surveysystem( "#" + survey_id + " .one-custom-field" ).each( function( index ) {
				if ( surveysystem( this ).children( ".cfid" ).val() != '' && surveysystem( this ).children( ".cfname" ).val() ) {
				customfs = {};
				if ( surveysystem( this ).children( ".cfrequired" ).val() == '1' ) {
					var thisreq = 'true';
				}
				else {
					var thisreq = 'false';
				}
				customfs.id = surveysystem( this ).children( ".cfid" ).val();
				customfs.name = surveysystem( this ).children( ".cfname" ).val();
				customfs.required = thisreq;
				customfs.type = surveysystem( this ).children( ".cfid" ).attr( "data-type" );
				customfs.warning = surveysystem( this ).children( ".cfwarning" ).val();
				customfs.minlength = surveysystem( this ).children( ".cfminlength" ).val();
					customfsarray.push( customfs );
				}
				if ( surveysystem( this ).hasClass( "custom_field_section_html" ) ) {
					customfs = {};
					customfs.id = surveysystem( this ).attr( "data-id" );
					customfs.name = surveysystem( this ).find( "#customhtml_" + customfs.id ).val();
					customfs.type = surveysystem( this ).attr( "data-type" );
					customfs.required = false;
					customfs.position = surveysystem( this ).find( ".customhtml_pos:checked" ).val();
						customfsarray.push( customfs );					
				}
			});
			var options = [
				surveysystem( "#" + survey_id + " .display_style" ).val(),
				surveysystem( "#" + survey_id + " .animation_easing" ).val(),
				surveysystem( "#" + survey_id + " .font_family" ).val(),
				surveysystem( "#" + survey_id + " .bgcolor" ).val(),
				surveysystem( "#" + survey_id + " .modal_survey_preview1002" ).css( "background-color" ),
				surveysystem( "#" + survey_id + " .modal_survey_preview1003" ).css( "background-color" ),
				surveysystem( "#" + survey_id + " .modal_survey_border_width_value" ).val().replace( /[^\d.]/g, '' ),
				surveysystem( "#" + survey_id + " .modal_survey_border_radius_value" ).val().replace( /[^\d.]/g, '' ),
				surveysystem( "#" + survey_id + " .modal_survey_font_size_value" ).val().replace( /[^\d.]/g, '' ),
				surveysystem( "#" + survey_id + " .modal_survey_padding_value" ).val().replace( /[^\d.]/g, '' ),
				surveysystem( "#" + survey_id + " .modal_survey_line_height_value" ).val().replace( /[^\d.]/g, '' ),
				(surveysystem( "#" + survey_id + " .modal_survey_animation_speed_value" ).val().replace( /[^\d.]/g, '' ) ) * 1000,
				surveysystem( "#" + survey_id + " .thankyou textarea" ).val(),
				surveysystem( "#" + survey_id + " .lock_bg" ).val(),
				surveysystem( "#" + survey_id + " .closeable" ).val(),
				surveysystem( "#" + survey_id + " .atbottom" ).val(),
				surveysystem( "#" + survey_id + " .text_align" ).val(),
				surveysystem( "#" + survey_id + " .survey_mode" ).val(),
				surveysystem( "#" + survey_id + " .loggedin" ).val(),
				surveysystem( "#" + survey_id + " .redirecturl" ).val(),
				surveysystem( "#" + survey_id + " .progressbar" ).val(),
				cond_array, 
				surveysystem( "#" + survey_id + " .listlayout").val(),
				(surveysystem( "#" + survey_id + " .modal_survey_end_delay_value" ).val().replace( /[^\d.]/g, '' ) ) * 1000,
				surveysystem( "#" + survey_id + " .addmoreapi_activecampaign" ).val(),
				surveysystem( "#" + survey_id + " .activecampaign_url" ).val(),
				surveysystem( "#" + survey_id + " .activecampaign_apikey" ).val(),
				surveysystem( "#" + survey_id + " .activecampaign_listid" ).val(),
				surveysystem( "#" + survey_id + " .addmoreapi_aweber" ).val(),
				surveysystem( "#" + survey_id + " .aweber_authorizationcode" ).val(),
				surveysystem( "#" + survey_id + " .aweber_consumerkey" ).val(),
				surveysystem( "#" + survey_id + " .aweber_consumersecret" ).val(),
				surveysystem( "#" + survey_id + " .aweber_accesskey" ).val(),
				surveysystem( "#" + survey_id + " .aweber_accesssecret" ).val(),
				surveysystem( "#" + survey_id + " .aweber_listid" ).val(),
				surveysystem( "#" + survey_id + " .addmoreapi_benchmark" ).val(),
				surveysystem( "#" + survey_id + " .benchmark_doubleoptin" ).val(),
				surveysystem( "#" + survey_id + " .benchmark_apikey" ).val(),
				surveysystem( "#" + survey_id + " .benchmark_listid" ).val(),
				surveysystem( "#" + survey_id + " .addmoreapi_campaignmonitor" ).val(),
				surveysystem( "#" + survey_id + " .campaignmonitor_apikey" ).val(),
				surveysystem( "#" + survey_id + " .campaignmonitor_listid" ).val(),
				surveysystem( "#" + survey_id + " .addmoreapi_campayn" ).val(),
				surveysystem( "#" + survey_id + " .campayn_domain" ).val(),
				surveysystem( "#" + survey_id + " .campayn_apikey" ).val(),
				surveysystem( "#" + survey_id + " .campayn_listid" ).val(),
				surveysystem( "#" + survey_id + " .addmoreapi_constantcontact" ).val(),
				surveysystem( "#" + survey_id + " .constantcontact_apikey" ).val(),
				surveysystem( "#" + survey_id + " .constantcontact_accesstoken" ).val(),
				surveysystem( "#" + survey_id + " .constantcontact_listid" ).val(),
				surveysystem( "#" + survey_id + " .addmoreapi_freshmail" ).val(),
				surveysystem( "#" + survey_id + " .freshmail_apikey" ).val(),
				surveysystem( "#" + survey_id + " .freshmail_apisecret" ).val(),
				surveysystem( "#" + survey_id + " .freshmail_listhash" ).val(),
				surveysystem( "#" + survey_id + " .addmoreapi_getresponse" ).val(),
				surveysystem( "#" + survey_id + " .getresponse_apikey" ).val(),
				surveysystem( "#" + survey_id + " .getresponse_campaignid" ).val(),
				surveysystem( "#" + survey_id + " .addmoreapi_icontact" ).val(),
				surveysystem( "#" + survey_id + " .icontact_appid" ).val(),
				surveysystem( "#" + survey_id + " .icontact_apiusername" ).val(),
				surveysystem( "#" + survey_id + " .icontact_apipassword" ).val(),
				surveysystem( "#" + survey_id + " .icontact_listid" ).val(),
				surveysystem( "#" + survey_id + " .addmoreapi_infusionsoft" ).val(),
				surveysystem( "#" + survey_id + " .infusionsoft_apikey" ).val(),
				surveysystem( "#" + survey_id + " .infusionsoft_campaignid" ).val(),
				surveysystem( "#" + survey_id + " .infusionsoft_groupid" ).val(),
				surveysystem( "#" + survey_id + " .addmoreapi_interspire" ).val(),
				surveysystem( "#" + survey_id + " .interspire_username" ).val(),
				surveysystem( "#" + survey_id + " .interspire_usertoken" ).val(),
				surveysystem( "#" + survey_id + " .interspire_listid" ).val(),
				surveysystem( "#" + survey_id + " .addmoreapi_madmimi" ).val(),
				surveysystem( "#" + survey_id + " .madmimi_username" ).val(),
				surveysystem( "#" + survey_id + " .madmimi_apikey" ).val(),
				surveysystem( "#" + survey_id + " .madmimi_listname" ).val(),
				surveysystem( "#" + survey_id + " .addmoreapi_mailchimp" ).val(),
				surveysystem( "#" + survey_id + " .mailchimp_apikey" ).val(),
				surveysystem( "#" + survey_id + " .mailchimp_listid" ).val(),
				surveysystem( "#" + survey_id + " .addmoreapi_mailerlite" ).val(),
				surveysystem( "#" + survey_id + " .mailerlite_apikey" ).val(),
				surveysystem( "#" + survey_id + " .mailerlite_listid" ).val(),
				surveysystem( "#" + survey_id + " .addmoreapi_mailigen" ).val(),
				surveysystem( "#" + survey_id + " .mailigen_apikey" ).val(),
				surveysystem( "#" + survey_id + " .mailigen_listid" ).val(),
				surveysystem( "#" + survey_id + " .mailigen_doubleoptin" ).val(),
				surveysystem( "#" + survey_id + " .addmoreapi_mailjet" ).val(),
				surveysystem( "#" + survey_id + " .mailjet_apikey" ).val(),
				surveysystem( "#" + survey_id + " .mailjet_secretkey" ).val(),
				surveysystem( "#" + survey_id + " .mailjet_listid" ).val(),
				surveysystem( "#" + survey_id + " .addmoreapi_mailpoet" ).val(),
				surveysystem( "#" + survey_id + " .mailpoet_listid" ).val(),
				surveysystem( "#" + survey_id + " .addmoreapi_emma" ).val(),
				surveysystem( "#" + survey_id + " .emma_accountid" ).val(),
				surveysystem( "#" + survey_id + " .emma_publickey" ).val(),
				surveysystem( "#" + survey_id + " .emma_privatekey" ).val(),
				surveysystem( "#" + survey_id + " .addmoreapi_mymail" ).val(),
				surveysystem( "#" + survey_id + " .mymail_listid" ).val(),
				surveysystem( "#" + survey_id + " .addmoreapi_ontraport" ).val(),
				surveysystem( "#" + survey_id + " .ontraport_appid" ).val(),
				surveysystem( "#" + survey_id + " .ontraport_key" ).val(),
				surveysystem( "#" + survey_id + " .ontraport_tagid" ).val(),
				surveysystem( "#" + survey_id + " .ontraport_sequenceid" ).val(),
				surveysystem( "#" + survey_id + " .addmoreapi_pinpointe" ).val(),
				surveysystem( "#" + survey_id + " .pinpointe_username" ).val(),
				surveysystem( "#" + survey_id + " .pinpointe_usertoken" ).val(),
				surveysystem( "#" + survey_id + " .pinpointe_listid" ).val(),
				surveysystem( "#" + survey_id + " .addmoreapi_sendinblue" ).val(),
				surveysystem( "#" + survey_id + " .sendinblue_accesskey" ).val(),
				surveysystem( "#" + survey_id + " .sendinblue_listid" ).val(),
				surveysystem( "#" + survey_id + " .addmoreapi_sendreach" ).val(),
				surveysystem( "#" + survey_id + " .sendreach_key" ).val(),
				surveysystem( "#" + survey_id + " .sendreach_secret" ).val(),
				surveysystem( "#" + survey_id + " .sendreach_userid" ).val(),
				surveysystem( "#" + survey_id + " .sendreach_listid" ).val(),
				surveysystem( "#" + survey_id + " .addmoreapi_sendy" ).val(),
				surveysystem( "#" + survey_id + " .sendy_installationurl" ).val(),
				surveysystem( "#" + survey_id + " .sendy_apikey" ).val(),
				surveysystem( "#" + survey_id + " .sendy_listid" ).val(),
				surveysystem( "#" + survey_id + " .addmoreapi_simplycast" ).val(),
				surveysystem( "#" + survey_id + " .simplycast_publickey" ).val(),
				surveysystem( "#" + survey_id + " .simplycast_secretkey" ).val(),
				surveysystem( "#" + survey_id + " .simplycast_listid" ).val(),
				surveysystem( "#" + survey_id + " .addmoreapi_ymlp" ).val(),
				surveysystem( "#" + survey_id + " .ymlp_username" ).val(),
				surveysystem( "#" + survey_id + " .ymlp_apikey" ).val(),
				surveysystem( "#" + survey_id + " .ymlp_groupid" ).val(),
				surveysystem( "#" + survey_id + " .msform_status" ).val(),
				surveysystem( "#" + survey_id + " .msform_name_field" ).val(),
				surveysystem( "#" + survey_id + " .msform_email_field" ).val(),
				surveysystem( "#" + survey_id + " .modal_survey_shadowh_value" ).val().replace( /[^\d.-]/g, '' ),
				surveysystem( "#" + survey_id + " .modal_survey_shadowv_value" ).val().replace( /[^\d.-]/g, '' ),
				surveysystem( "#" + survey_id + " .modal_survey_shadowb_value" ).val().replace( /[^\d.]/g, '' ),
				surveysystem( "#" + survey_id + " .modal_survey_shadows_value" ).val().replace( /[^\d.]/g, '' ),
				surveysystem( "#" + survey_id + " .modal_survey_preview1004" ).css( "background-color" ),
				surveysystem( "#" + survey_id + " .survey_preloader" ).val(),
				surveysystem( "#" + survey_id + " .survey_hover" ).val(),
				(surveysystem( "#" + survey_id + " .modal_survey_display_timer_value" ).val().replace( /[^\d.]/g, '' ) ) * 1000,
				surveysystem( "#" + survey_id + " .endchart_status" ).val(),
				surveysystem( "#" + survey_id + " .endchart_style" ).val(),
				surveysystem( "#" + survey_id + " .endchart_type" ).val(),
				surveysystem( "#" + survey_id + " .endchart_datatype" ).val(),
				surveysystem( "#" + survey_id + " .endchart_advancedchart" ).val(),
				surveysystem( "#" + survey_id + " .survey_closeicon" ).val(),
				surveysystem( "#" + survey_id + " .grid_items" ).val(),
				surveysystem( "#" + survey_id + " .modal_survey_cookie_expiration_value" ).val().replace( /[^\d.-]/g, '' ),
				surveysystem( "#" + survey_id + " .notificationemail" ).val(),
				surveysystem( "#" + survey_id + " .survey_closeiconsize" ).val(),
				surveysystem( "#" + survey_id + " .msform_email_validate_field" ).val(),
				surveysystem( "#" + survey_id + " #ms_autoresponse" ).val(),
				surveysystem( "#" + survey_id + " .autoresponse_sendername input" ).val(),
				surveysystem( "#" + survey_id + " .autoresponse_senderemail input" ).val(),
				surveysystem( "#" + survey_id + " .autoresponse_subject input" ).val(),
				surveysystem( "#" + survey_id + " .next_button_style" ).val(),
				surveysystem( "#" + survey_id + " .alwaysnext" ).val(),
				surveysystem( "#" + survey_id + " .rating_question_style" ).val(),
				surveysystem( "#" + survey_id + " .enableback" ).val(),
				surveysystem( "#" + survey_id + " .remandcont" ).val(),
				(surveysystem( "#" + survey_id + " .modal_survey_quiz_timer_value" ).val().replace( /[^\d.]/g, '' ) ) * 1000,
				surveysystem( "#" + survey_id + " .qtimer" ).val(),
				surveysystem( "#" + survey_id + " .animation_type" ).val(),
				customfsarray,
				surveysystem( "#" + survey_id + " .msform_confirmation" ).val(),
				surveysystem( "#" + survey_id + " .msform_wosignup" ).val()			
			];
				if ( surveysystem( "#" + survey_id + " .modal_survey_end_delay_value" ).val() == "End Delay: 0sec" ) {
					showmessage( sspa_params.languages.redirectioninfo );
				}

			if ( error == false ) {
				rmdni = true;
				surveysystem(buttonspan).html('<img width="20" style="margin-left:50px;" src="'+sspa_params.plugin_url+'/templates/assets/img/preloader.gif">');
				var thissurvey = [], qoptions = [], aoptions = [], ao = {};
				surveysystem( "#"+survey_id+" .question_text" ).each(function( index ) {
					var qa = {};
					qa[0] = surveysystem(this).val();
					var thisquestion = surveysystem( this ).parent().parent().parent().attr("id");
					var qimageurl = "";
					if ( surveysystem( this ).parent().parent().find( ".qo-accordion .upl-photo" ).length > 0 ) {
						qimageurl = surveysystem( this ).parent().parent().find( ".qo-accordion .upl-photo" ).val();
					}
					surveysystem( "#"+survey_id+" #"+thisquestion+" div input.answer" ).each(function( index2 ) {
					var answer_type = "default";
					var answer_unique = (Math.floor(Math.random() * 26) + Date.now());
					var autocomplete = "0", converttextarea = "0", imageurl = "", score = "0", correct = "0", imgwidth = "", imgheight = "", answer_status = "0", hidelabel = "0";
					if ( surveysystem( this ).parent().find( ".ao-accordion .upl-photo" ).length > 0 ) {
						imageurl = surveysystem( this ).parent().find( ".ao-accordion .upl-photo" ).val();
					}
					if ( surveysystem( this ).parent().find( ".ao-accordion .aopts_score" ).length > 0 ) {
						score = surveysystem( this ).parent().find( ".ao-accordion .aopts_score" ).val();
					}
					if ( surveysystem( this ).parent().find( ".ao-accordion .aopts_correct_answer" ).length >0 ) {
						correct = surveysystem( this ).parent().find( ".ao-accordion .aopts_correct_answer" ).val();
					}
					if ( surveysystem( this ).parent().find( ".ao-accordion .aopts_hide_label" ).length >0 ) {
						hidelabel = surveysystem( this ).parent().find( ".ao-accordion .aopts_hide_label" ).val();
					}
					if ( surveysystem( this ).parent().find( ".ao-accordion .aopts_width" ).length > 0 ) {
						imgwidth = surveysystem( this ).parent().find( ".ao-accordion .aopts_width" ).val();
					}
					if ( surveysystem( this ).parent().find( ".ao-accordion .aopts_height" ).length > 0 ) {
						imgheight = surveysystem( this ).parent().find( ".ao-accordion .aopts_height" ).val();
					}
					if ( surveysystem( this ).parent().find( ".ao-accordion .aopts_status" ).val() != "1" ) {
						answer_status = "0";
					}
					else {
						answer_status = "1";
					}

					var thiscount = surveysystem("#"+survey_id+" #"+thisquestion+" div span#answer_count"+(index2+1)).text().split(" - ");
					qa[(index2+1)] = surveysystem(this).val()+'->'+thiscount[0];
					if ( typeof surveysystem( this ).attr( "data-answertype" ) != "undefined" ) {
						answer_type = surveysystem( this ).attr( "data-answertype" );
					}
					if ( typeof surveysystem( this ).attr( "data-unique" ) != "undefined" ) {
						if ( surveysystem( this ).attr( "data-unique" ) != "" ) {
							answer_unique = surveysystem( this ).attr( "data-unique" );
						}
					}
					if ( surveysystem( "#" + survey_id + " .autocomplete" + answer_unique ).length > 0 ) {
						if ( surveysystem( "#" + survey_id + " .autocomplete" + answer_unique ).val() == "1" ) {
								autocomplete = "1";
						}
					}
					if ( surveysystem( "#" + survey_id + " .converttextarea" + answer_unique ).length > 0 ) {
						if ( surveysystem( "#" + survey_id + " .converttextarea" + answer_unique ).val() == "1" ) {
								converttextarea = "1";
						}
					}
					ao[ ( index + 1 ) + "_" + ( index2 + 1 ) ] = [ answer_type, answer_unique, autocomplete, imageurl, score, correct, imgwidth, imgheight, answer_status, converttextarea, surveysystem( this ).parent().find( ".ao-accordion .answer_tooltip_text" ).val(), surveysystem( this ).parent().find( ".ao-accordion .answer_redirection" ).val(), surveysystem( this ).parent().find( ".ao-accordion .answer_category" ).val(), hidelabel, surveysystem( this ).parent().find( ".ao-accordion .img-align:checked" ).val() ];
					})
					qoptions.push( [ surveysystem( "#" + survey_id + " #" + thisquestion + " input.choices" ).val(), surveysystem( "#" + survey_id + " #" + thisquestion + " input.minchoices" ).val(), qimageurl, surveysystem( "#" + survey_id + " #" + thisquestion + " .qopts_rating" ).val(), surveysystem( "#" + survey_id + " #" + thisquestion + " .question_tooltip_text" ).val(), surveysystem( "#" + survey_id + " #" + thisquestion + " .question_category" ).val(), surveysystem( "#" + survey_id + " #" + thisquestion + " .qopts_width" ).val(), surveysystem( "#" + survey_id + " #" + thisquestion + " .qopts_height" ).val(), surveysystem( "#" + survey_id + " #" + thisquestion + " .img-align:checked" ).val() ] );
					thissurvey.push(qa);
				})
				aoptions.push( ao );

				var data = {
					action: 'ajax_survey',
					sspcmd: 'save',
					survey_id: survey_id,
					survey_name: surveysystem(".header_"+survey_id).text(),
					start_time: surveysystem("#"+survey_id+" .start_time").val(),
					expiry_time: surveysystem("#"+survey_id+" .expiry_time").val(),
					global_use: surveysystem("#"+survey_id+" .global_survey").val(),
					options: JSON.stringify( options ),
					qa: JSON.stringify( thissurvey ),
					qo: JSON.stringify( qoptions ),
					ao: JSON.stringify( aoptions )
					};
					checker = setTimeout( function() {
						if ( surveysystem( buttonspan ).html() != '<input type="submit" name="save_survey" class="save_survey button" value="' + sspa_params.languages.save + '">' || surveysystem( buttonspan ).html() != '<input type="submit" name="save_survey" class="save_survey button" value="' + sspa_params.languages.update + '">' ) {
							surveysystem( buttonspan ).html('<input type="submit" name="save_survey" class="save_survey button" value="' + sspa_params.languages.tryagain + '"><span style="margin-left: 35px;line-height:25px;color: #FC0303;">' + sspa_params.languages.saveerror + '</span>')
						}
					},15000);
					surveysystem.post( sspa_params.admin_url, data, function( response ) {
					if ( response.toLowerCase().indexOf( "success" ) >= 0 || response.toLowerCase().indexOf( "updated" ) >= 0 ) {
						clearTimeout( checker );
						if ( response.toLowerCase().indexOf( "success" ) >= 0) {
							var buttontext = sspa_params.languages.saved;
							var buttontext2 = sspa_params.languages.save;
						}
						else {
							var buttontext = sspa_params.languages.updated;
							var buttontext2 = sspa_params.languages.update;
						}
						surveysystem( buttonspan ).html( '<span style="margin-left: 35px;line-height:25px;"><strong>' + buttontext + '</strong></span>' );
						setTimeout( function() {
							surveysystem( buttonspan ).html( '<input type="submit" name="save_survey" class="save_survey button" value="' + buttontext2 + '">' )
						}, 2000 );
					}
					else {
						surveysystem( ".floating-save-button" ).children( "img" ).attr( "src", sspa_params.plugin_url + "/templates/assets/img/error-icon.png" );
					}
						rmdni = false;
						surveysystem( ".floating-save-button img" ).attr( "src", sspa_params.plugin_url + "/templates/assets/img/save-icon.png" );
					}).fail(function() {
						surveysystem( ".floating-save-button img" ).attr( "src", sspa_params.plugin_url + "/templates/assets/img/save-icon.png" );
						rmdni = false;
						showmessage( sspa_params.languages.saveerror );
				  });
			};
		}
	});
		
		surveysystem("body").on( "click", ".getapiinfo",function( event ) {
		event.preventDefault();
		if (rmdni==false)
			{
			rmdni = true;
			var thiselement = surveysystem(this);
			var parentelement = surveysystem(this).parent();
			var thisbutton = surveysystem(this).parent().html();
		surveysystem(this).parent().html('<img width="20" style="margin-left:50px;" src="'+sspa_params.plugin_url+'/templates/assets/img/preloader.gif">');
		var field1 = '', field2 = '', field3 = '', field4 = '';
		if ( surveysystem( thiselement ).attr( "data-apiid" ) == "aweberlists" ) {
			field1 = surveysystem( ".aweber_accesskey" ).val();
			field2 = surveysystem( ".aweber_accesssecret" ).val();
			field3 = surveysystem( ".aweber_consumerkey" ).val();
			field4 = surveysystem( ".aweber_consumersecret" ).val();
		}
		if ( surveysystem( thiselement ).attr( "data-apiid" ) == "benchmarklists" ) {
			field1 = surveysystem( ".benchmark_apikey" ).val();
		}
		if ( surveysystem( thiselement ).attr( "data-apiid" ) == "campaynlists" ) {
			field1 = surveysystem( ".campayn_domain" ).val();
			field2 = surveysystem( ".campayn_apikey" ).val();
		}
		if ( surveysystem( thiselement ).attr( "data-apiid" ) == "constantcontactlists" ) {
			field1 = surveysystem( ".constantcontact_apikey" ).val();
			field2 = surveysystem( ".constantcontact_accesstoken" ).val();
		}
		if ( surveysystem( thiselement ).attr( "data-apiid" ) == "getresponselists" ) {
			field1 = surveysystem( ".getresponse_apikey" ).val();
		}
		if ( surveysystem( thiselement ).attr( "data-apiid" ) == "ymlplists" ) {
			field1 = surveysystem( ".ymlp_username" ).val();
			field2 = surveysystem( ".ymlp_apikey" ).val();
		}
		var data = {
				action: 'ajax_survey',
				sspcmd: 'getapiinfo',
				field1: field1,
				field2: field2,
				field3: field3,
				field4: field4,
				id: surveysystem( thiselement ).attr( "data-apiid" )
				};
				surveysystem.post( sspa_params.admin_url, data, function( response ) {
				if ( response.indexOf( "success" ) >= 0 ) {
					surveysystem( parentelement ).html( '<span><strong>SUCCESS</strong></span>' );
					setTimeout( function() {
						surveysystem( parentelement ).html( thisbutton );
					}, 2000 );
				}
				else {
					if ( response.indexOf( "Error" ) == -1 ) {
						if ( surveysystem( "." + surveysystem( thiselement ).attr( "data-apiid" ) + "_container" ).length > 0 ) {
							if ( response.indexOf( "Error" ) == -1 ) {
								surveysystem( "." + surveysystem( thiselement ).attr( "data-apiid" ) + "_container" ).html( response ); 
								surveysystem( parentelement ).html( thisbutton );
								surveysystem( "body" ).on( "click", "." + surveysystem( thiselement ).attr( "data-apiid" ) + "_container .getid", function() {
									surveysystem( "." + surveysystem( this ).attr( "data-target" ) ).val( surveysystem( this ).attr( "data-value" ) );
								}) 
							}
							else {
								surveysystem( parentelement ).html( thisbutton + '<span><strong>' + response + '</strong></span>' );
							}
						}
					}
					else {
						surveysystem( parentelement ).html( thisbutton + '<span><strong>' + response + '</strong></span>' );
					}
					rmdni = false;
				}
				});
			
			};
			
		});		
		
    surveysystem( "#dialog-confirm" ).dialog({
      resizable: false,
      height:220,
	  autoOpen: false,
      modal: true,
      buttons: [
		{
			text: sspa_params.languages.deletesurvey,
			click : function() {
				remove_survey();
				surveysystem( ".floating-delete-button" ).children( "img" ).attr( "src", sspa_params.plugin_url + "/templates/assets/img/floating-loader.svg" );
				surveysystem( this ).dialog( "close" );
			}
		},
		{
			text: sspa_params.languages.cancel,
			class: 'ui-cancel',
			click : function() {
				surveysystem( this ).dialog( "close" );
			}
		}
      ]
    });
	
	function showmessage( message ) {
		surveysystem( "#dialog-message .content" ).html( message );
		surveysystem( "#dialog-message" ).dialog( "open" );
	}

	surveysystem( "#dialog-message" ).dialog({
		resizable: false,
		width: 'auto',
		height: 115,
		autoOpen: false,
		resize: 'auto',
		modal: true,
		show: 'fade',
		hide: 'fade',
        open: function() {
            surveysystem( '.ui-widget-overlay' ).bind( 'click', function() {
                surveysystem( '#dialog-message' ).dialog( 'close' );
            })
        }		
	});
	
	surveysystem( '.help-dialogs' ).each( function( index ) {
		surveysystem( this ).dialog({
			resizable: false,
			width: 550,
			height: 350,
			autoOpen: false,
			modal: true,
			show: 'fade',
			hide: 'fade',
			open: function() {
				surveysystem( '.ui-widget-overlay' ).bind( 'click', function() {
					surveysystem( '.help-dialogs' ).dialog( 'close' );
				})
			}		
		});
	})
 	
    surveysystem( "#dialog-confirm2" ).dialog({
      resizable: false,
      height:220,
	  autoOpen: false,
      modal: true,
      buttons: [
		{
			text: sspa_params.languages.reset,
			click : function() {
				reset_survey();
				surveysystem( this ).dialog( "close" );
			}
		},
		{
			text: sspa_params.languages.cancel,
			class: 'ui-cancel',
			click : function() {
				surveysystem( this ).dialog( "close" );
			}
		}
      ]
    });
 
    surveysystem( "#dialog-confirm3" ).dialog({
      resizable: false,
      height:220,
	  width: 500,
	  autoOpen: false,
      modal: true,
      buttons: [
		{
			text: sspa_params.languages.exportcharts,
			click : function() {
				exportcharts = 2;
				export_survey();
				surveysystem( this ).dialog( "close" );
			}
		},
		{
			text: sspa_params.languages.exportwcharts,
			click : function() {
				exportcharts = 1;
				export_survey();		
				surveysystem( this ).dialog( "close" );
			}
		}
      ]
    });
			
    surveysystem( "#dialog-confirm4" ).on( "submit", function( e ) {
      e.preventDefault();
      duplicate_survey();
    });	
	
    surveysystem( "#dialog-confirm4" ).dialog({
      resizable: false,
      height: 250,
	  width: 500,
	  autoOpen: false,
      modal: true,
      buttons: [
		{
			text: sspa_params.languages.duplicate,
			click : function( e ) {
				e.preventDefault();
				duplicate_survey();
				surveysystem( this ).dialog( "close" );
			}
		},
		{
			text: sspa_params.languages.cancel,
			class: 'ui-cancel',
			click : function() {
				surveysystem( this ).dialog( "close" );
			}
		}
      ]
    });

    surveysystem( "#dialog-confirm5" ).dialog({
      resizable: false,
      height:220,
	  width: 400,
	  autoOpen: false,
      modal: true,
      buttons: [
		{
			text: sspa_params.languages.cdelete,
			click : function() {
				delete_selected_participants();
				surveysystem( this ).dialog( "close" );
			}
		},
		{
			text: sspa_params.languages.cancel,
			class: 'ui-cancel',
			click : function() {
				surveysystem( this ).dialog( "close" );
			}
		}
      ]
    });
    surveysystem( "#dialog-confirm7" ).dialog({
      resizable: false,
      height:220,
	  width: 400,
	  autoOpen: false,
      modal: true,
      buttons: [
		{
			text: sspa_params.languages.cdelete,
			click : function() {
				surveysystem( ".delete_samesession" + delprsurv ).submit();
				surveysystem( this ).dialog( "close" );
			}
		},
		{
			text: sspa_params.languages.cancel,
			class: 'ui-cancel',
			click : function() {
				surveysystem( this ).dialog( "close" );
			}
		}
      ]
    });
	
    surveysystem( "#dialog-confirm8" ).dialog({
      resizable: false,
      height:220,
	  width: 950,
	  autoOpen: false,
      modal: true,
      buttons: [
		{
			text: sspa_params.languages.cexporttocsv,
			click : function() {
				export_selected_participants( 'csv' );
				surveysystem( this ).dialog( "close" );
			}
		},
		{
			text: sspa_params.languages.cexporttojson,
			click : function() {
				export_selected_participants( 'json' );
				surveysystem( this ).dialog( "close" );
			}
		},
		{
			text: sspa_params.languages.cexporttopdf,
			click : function() {
				export_selected_participants( 'pdf' );
				surveysystem( this ).dialog( "close" );
			}
		},
		{
			text: sspa_params.languages.cexporttoxml,
			click : function() {
				export_selected_participants( 'xml' );
				surveysystem( this ).dialog( "close" );
			}
		},
		{
			text: sspa_params.languages.cexporttoxls,
			click : function() {
				export_selected_participants( 'xls' );
				surveysystem( this ).dialog( "close" );
			}
		},
		{
			text: sspa_params.languages.cexporttotxt,
			click : function() {
				export_selected_participants( 'txt' );
				surveysystem( this ).dialog( "close" );
			}
		},
		{
			text: sspa_params.languages.cancel,
			class: 'ui-cancel',
			click : function() {
				surveysystem( this ).dialog( "close" );
			}
		}
      ]
    });

     surveysystem( "#dialog-confirm9" ).dialog({
      resizable: false,
      height: 300,
	  width: 400,
	  autoOpen: false,
      modal: true,
      buttons: [
		{
			text: sspa_params.languages.cdelete,
			click : function() {
				surveysystem( "#incomplete-deletion" + delprsurv ).submit();
				surveysystem( this ).dialog( "close" );
			}
		},
		{
			text: sspa_params.languages.cancel,
			class: 'ui-cancel',
			click : function() {
				surveysystem( this ).dialog( "close" );
			}
		}
      ]
    });

     surveysystem( "#dialog-confirm10" ).dialog({
      resizable: false,
      height: 200,
	  width: 400,
	  autoOpen: false,
      modal: true,
      buttons: [
		{
			text: sspa_params.languages.cdelete,
			click : function() {
				remove_condition( surveysystem( "#dialog-confirm10" ).data( 'id' ) );
				surveysystem( this ).dialog( "close" );
			}
		},
		{
			text: sspa_params.languages.cancel,
			class: 'ui-cancel',
			click : function() {
				surveysystem( this ).dialog( "close" );
			}
		}
      ]
    });

	surveysystem( "#dialog-help4" ).dialog({
      resizable: false,
      height: 170,
	  width: 'auto',
	  autoOpen: false,
      modal: true,
      buttons: [
		{
			text: sspa_params.languages.addanswer_normal,
			click : function() {
				var this2 = surveysystem( "#dialog-help4" ).data( 'this' )
				add_normal_answer( this2 );
				surveysystem( this ).dialog( "close" );
			}
		},
		{
			text: sspa_params.languages.addanswer_open,
			click : function() {
				var this2 = surveysystem( "#dialog-help4" ).data( 'this' )
				add_open_answer( this2 );
				surveysystem( this ).dialog( "close" );
			}
		},
		{
			text: sspa_params.languages.cancel,
			class: 'ui-cancel',
			click : function() {
				surveysystem( this ).dialog( "close" );
			}
		}
      ]
    });
	
    surveysystem( "#dialog-confirm6" ).dialog({
      resizable: false,
      height:220,
	  width: 500,
	  autoOpen: false,
      modal: true,
      buttons: [
		{
			text: sspa_params.languages.exportcharts,
			click : function() {
				exportcharts = 2;
				export_personal_survey();
				surveysystem( this ).dialog( "close" );
			}
		},
		{
			text: sspa_params.languages.exportwcharts,
			click : function() {
				exportcharts = 1;
				export_personal_survey();		
				surveysystem( this ).dialog( "close" );
			}
		}
      ]
    });
	
	function delete_selected_participants() {
		var delparts = [];
		surveysystem( '.participants-select' ).each( function( index ) {
			if ( surveysystem( this ).val() == '1' ) {
				var thisid = surveysystem( this ).attr( "id" ).split( "-" );
				if ( typeof thisid[ 1 ] != undefined && typeof thisid[ 2 ] != undefined ) {
					delparts.push( thisid[ 1 ] + '-' + thisid[ 2 ] );
				}
			}
		})
		surveysystem( "body" ).append( "<form id='delete_participants_form' method='post' name='delete_participants_form'><input type='hidden' name='delete_participants' value='" + JSON.stringify( delparts ) + "'></form>" );
		surveysystem( "#delete_participants_form" ).submit();
	}
	
	function quickexport( filetype, mode, survey_id, session ) {
		var data = {
			action: 'ajax_survey',
			sspcmd: 'export',
			type: filetype,
			mode: mode,
			survey_id: survey_id,
			samesession: session
		};
		surveysystem.post( sspa_params.admin_url, data, function( response ) {
			if ( response.toLowerCase().indexOf( "success" ) >= 0 ) {
				return true;
			}
			else {
				return false;
			}
		});
		return true;
	}
	
	function export_selected_participants( filetype ) {
		var bulkexpparts = [], step = 0;
		surveysystem( ".modal-survey_page_modal_survey_participants .export-progress" ).css( "display", "block" );
		surveysystem( ".export-progress .process_text" ).text( "" );
		surveysystem( ".export-progress .survey_global_percent" ).css( "width", "0%" );
		surveysystem( ".export-progress .perc" ).text( "0%" )
		surveysystem( '.participants-select' ).each( function( index ) {
			if ( surveysystem( this ).val() == '1' ) {
				var thisid = surveysystem( this ).attr( "id" ).split( "-" );
				if ( typeof thisid[ 1 ] != undefined && typeof thisid[ 2 ] != undefined ) {
					var expbtn = surveysystem( this );
					var session_cut = expbtn.attr( "data-sessions" ).split( "," );
					surveysystem.each( session_cut, function( index, value ) {
						bulkexpparts.push( [ thisid[ 2 ], value, expbtn.attr( "data-uid" ), filetype ] );
					});
				}
			}
		})
		step = 100 / bulkexpparts.length;
		surveysystem.each( bulkexpparts, function( index, value ) {
			exporttimer = setTimeout( function(){
				if ( quickexport( filetype, 'personal', value[ 2 ] + "-" + value[ 0 ] + "-" + value[ 1 ], value[ 1 ] ) == true ) {
					var crtposhtml = index * step;
					var crtpos = Math.round( index * step * 100 ) / 100;
					surveysystem( ".export-progress .process_text" ).text( value[ 2 ] + "-" + value[ 0 ] + "-" + value[ 1 ] + "." + value[ 3 ] );
					surveysystem( ".export-progress .survey_global_percent" ).css( "width", crtposhtml + "%" );
					var percent_number_step = surveysystem.animateNumber.numberStepFactories.append( "%" );
					surveysystem( ".export-progress .perc" ).prop( 'number', surveysystem( ".export-progress .perc" ).text().replace( "%", "" ) ).animateNumber({
						number: crtpos,
						numberStep: percent_number_step
					},
					  1000
					);
				}
				else {
					surveysystem( ".export-progress .process_text" ).text( sspa_params.languages.failedtoexport );					
				}
			}, 1000 * index );
		})
			ziptimer = setTimeout( function(){
					var data = {
					action: 'ajax_survey',
					sspcmd: 'zip',
					survey_id: '1234',
					files: JSON.stringify( bulkexpparts )
				};
				clearTimeout( exporttimer );
				surveysystem.post( sspa_params.admin_url, data, function( response ) {
				clearTimeout( ziptimer );
					if ( response.toLowerCase().indexOf( "success" ) >= 0 ) {
						crtposhtml = 100;
						crtpos = 100;
						var unid = response.split( ":" );
						surveysystem( ".export-progress .process_text" ).html( "<a href='" + sspa_params.plugin_url + "/exports/modalsurvey-export-" + unid[ 1 ] + ".zip" + "'>ZIP Complete - Click here to Download</a>" );
						surveysystem( ".export-progress .survey_global_percent" ).css( "width", crtposhtml + "%" );
						var percent_number_step = surveysystem.animateNumber.numberStepFactories.append( "%" );
						surveysystem( ".export-progress .perc" ).prop( 'number', surveysystem( ".export-progress .perc" ).text().replace( "%", "" ) ).animateNumber({
							number: crtpos,
							numberStep: percent_number_step
						},
						  1000
						);
						return true;
					}
					else {
						crtposhtml = 100;
						crtpos = 100;
						surveysystem( ".export-progress .process_text" ).text( sspa_params.languages.failedzip );
						surveysystem( ".export-progress .survey_global_percent" ).css( "width", crtposhtml + "%" );
						var percent_number_step = surveysystem.animateNumber.numberStepFactories.append( "%" );
						surveysystem( ".export-progress .perc" ).prop( 'number', surveysystem( ".export-progress .perc" ).text().replace( "%", "" ) ).animateNumber({
							number: crtpos,
							numberStep: percent_number_step
						},
						  1000
						);						
						return false;
					}
				});
			}, 1000 * parseInt( bulkexpparts.length + 1 ) );
	}
		
		/*survey_id = surveysystem( ".ms-user-panel" ).attr( "id" ).replace( "msps-", "" );

		thiselement.parentsUntil( ".click-download" ).parent( ".click-download" ).remove();
		checker = thiselement.parentsUntil( ".click-nav" ).parent( ".click-nav" ).html();
		thiselement.parentsUntil( ".click-nav" ).parent( ".click-nav" ).html( '<img width="20" style="margin-left:50px;" src="' + sspa_params.plugin_url + '/templates/assets/img/preloader.gif">' );
		*/		

	surveysystem( "body" ).on( "click", ".help-dialog", function( e ) {
		e.preventDefault();
		var helpid = surveysystem( this ).attr( "data-helpid" );
		if (typeof helpid !== typeof undefined && helpid !== false) {
			surveysystem( "#dialog-" + helpid ).dialog( "open" );
		}
	})

	surveysystem( "body" ).on( "click", ".help-dialog", function( e ) {
		e.preventDefault();
		var helpid = surveysystem( this ).attr( "data-helpid" );
		if (typeof helpid !== typeof undefined && helpid !== false) {
			surveysystem( "#dialog-" + helpid ).dialog( "open" );
		}
	})

	surveysystem("body").on( "click", ".duplicate_survey",function(e) {
		e.preventDefault();
		var attr = surveysystem(this).attr("data-sid");
		if (typeof attr !== typeof undefined && attr !== false) {
			actionfl = attr;attr = "";
			var c = 2;
			var newname = surveysystem(this).attr("data-sname")+" - "+c;
			while (surveysystem("#"+Math.abs(newname.split("").reduce(function(a,b){a=((a<<5)-a)+b.charCodeAt(0);return a&a},0))).length > 0) {
				newname = surveysystem(this).attr("data-sname")+" - "+c;
				c++;
			}
			surveysystem("#dsurvey_name").val(newname);
		}
		surveysystem( "#dialog-confirm4" ).dialog( "open" );
	})
	
	surveysystem("body").on( "click", ".import_survey-submit",function(e) {
		e.preventDefault();
		if (surveysystem("#"+Math.abs(surveysystem("#survey_name").val().split("").reduce(function(a,b){a=((a<<5)-a)+b.charCodeAt(0);return a&a},0))).length > 0) {
			surveysystem( ".import-notice" ).text( " " + sspa_params.languages.surveyexists1 + " " );
			return true;
		}
		if ( surveysystem("#survey_name").val() == "" ) {
			surveysystem( ".import-notice" ).text( " " + sspa_params.languages.surveyexists2 + " " );
			return true;
		}
		surveysystem( "#import_modal_survey_id" ).val(Math.abs(surveysystem("#survey_name").val().split("").reduce(function(a,b){a=((a<<5)-a)+b.charCodeAt(0);return a&a},0)));
		setTimeout( function() {
			surveysystem( "#modal_survey_import_form" ).submit();
		}, 150 );
	})
	
	surveysystem("body").on( "click", ".delete_survey",function(e) {
		e.preventDefault();
		var attr = surveysystem(this).attr("data-sid");
		if (typeof attr !== typeof undefined && attr !== false) {
			actionfl = attr;attr = "";
		}
		else {
			buttonspan_global = surveysystem(this).parent();
		}
		surveysystem( "#dialog-confirm" ).dialog( "open" );
	})

	surveysystem("body").on( "click", ".reset_survey",function(e) {
		e.preventDefault();
		var attr = surveysystem(this).attr("data-sid");
		if (typeof attr !== typeof undefined && attr !== false) {
			actionfl = attr;attr = "";
		}
		else {
			buttonspan_global = surveysystem(this).parent();
		}
		surveysystem( "#dialog-confirm2" ).dialog( "open" );
	})

	surveysystem("body").on( "click", ".delete_samesession_link",function(e) {
		e.preventDefault();
		var attr = surveysystem( this ).attr( "data-session" );
		if ( typeof attr !== typeof undefined && attr !== false ) {
			delprsurv = attr;attr = "";
		}
		else {
			delprsurv = "";
		}
		surveysystem( "#dialog-confirm7" ).dialog( "open" );
	})

	surveysystem("body").on( "click", "#delete_incomplete",function(e) {
		e.preventDefault();
		surveysystem( "#dialog-confirm9" ).dialog( "open" );
	})

  	surveysystem( "body" ).on( "click", ".pdfexportlink", function( e ) {
	e.preventDefault();
		surveysystem( "body" ).append("<div id='modal_survey_pro_pdf_export' style='height:0px;width:300px;overflow:hidden;'></div>");
		surveysystem( '#' + survey_id + ' canvas' ).each( function( index ) {
			surveysystem( "#modal_survey_pro_pdf_export" ).append( '<canvas id="modal_survey_pro_pdf_container_' + survey_id + '_' + ( index + 1 ) + '" class="canvas_graph" height="250" width="250"></canvas>' );
			create_graph( survey_id, index + 1, "pdf" );
		})
		surveysystem( "#dialog-confirm3" ).dialog( "open" );
		thisexpelement = surveysystem( this );
	})
  	surveysystem("body").on( "click", ".pdfexportlink_personal",function(e) {
		e.preventDefault();
		surveysystem( "#dialog-confirm6" ).dialog( "open" );
		thisexpelement = surveysystem(this);
	})
  	surveysystem("body").on( "click", ".exportlink",function(e) {
		e.preventDefault();
		thisexpelement = surveysystem(this);
		export_survey();
	})

  	surveysystem("body").on( "click", ".exportlink_personal",function(e) {
		e.preventDefault();
		thisexpelement = surveysystem(this);
		export_personal_survey();
	})

  	surveysystem("body").on( "click", ".exportalink",function(e) {
	e.preventDefault();
	thisexpelement = surveysystem(this);
	export_open_answers();
	})

function hasOwnProperty(obj, prop) {
    var proto = obj.__proto__ || obj.constructor.prototype;
    return (prop in obj) &&
        (!(prop in proto) || proto[prop] !== obj[prop]);
}

function export_open_answers() {
	var thiselement = thisexpelement;
	var container = surveysystem( thiselement ).closest( ".click-nav2" );
	if (thiselement == '') return true;
      container.find('.js ul').css("display","none");
		var data = {
		action: 'ajax_survey',
		sspcmd: 'aexport',
		type: surveysystem( thiselement ).attr( "href" ),
		sid: surveysystem( thiselement ).attr( "data-sid" ),
		qid: surveysystem( thiselement ).attr( "data-qid" ),
		auid: surveysystem( thiselement ).attr( "data-auid" )
		};
		surveysystem(".click-download").remove();
		checker = container.html();
		container.html('<img width="20" style="margin-left:50px;" src="'+sspa_params.plugin_url+'/templates/assets/img/preloader.gif">');
		surveysystem.post( sspa_params.admin_url, data, function( response ) {
		if ( response.toLowerCase().indexOf( "success" ) >= 0 ) {
			container.html( checker + "<div class='click-download'>" + surveysystem(thiselement).text() + " " + sspa_params.languages.isready + ", <a target='_blank' href='"+sspa_params.plugin_url+"/exports/" + surveysystem( thiselement ).attr( "data-sid" ) + "_" + surveysystem( thiselement ).attr( "data-auid" ) + "." + surveysystem( thiselement ).attr( "href" ) + "'>" + sspa_params.languages.dhere + "</a></div>");
		}
			rmdni = false;
		});
}
	
function export_survey() {
	var thiselement = thisexpelement;
	if ( thiselement == '' ) {
		return true;
	}
    surveysystem( '.click-nav .js ul' ).css( "display", "none" );
	var filetype = surveysystem( thiselement ).attr( "href" );
	var filename = surveysystem( thiselement ).text();
	var chartimg = [], legenddatas = [];
	if ( filetype == 'pdf' ) {
		if ( exportcharts == 2 ) {
			surveysystem( '#modal_survey_pro_pdf_export canvas' ).each( function( index ) {
				chartimg.push( surveysystem( this )[ 0 ].toDataURL() );
				var legends = surveysystem( "#legend_" + surveysystem( this ).attr( "id" ) ).html();
				var cleg = [];
				surveysystem( legends ).each( function( index2 ) {
					cleg.push( surveysystem( this )[0].outerHTML );
				});
				legenddatas.push( cleg );
			})
		}
		surveysystem("#modal_survey_pro_pdf_export").remove();
	}
				var data = {
				action: 'ajax_survey',
				sspcmd: 'export',
				type: filetype,
				survey_id: survey_id,
				legend: JSON.stringify( legenddatas ),
				chart: JSON.stringify( chartimg )
				};
				surveysystem( ".click-download" ).remove();
				checker = surveysystem( "#" + survey_id + " .click-nav" ).html();
				surveysystem( "#" + survey_id + " .click-nav" ).html( '<img width="20" style="margin-left:50px;" src="' + sspa_params.plugin_url + '/templates/assets/img/preloader.gif">' );
				surveysystem.post( sspa_params.admin_url, data, function( response ) {
				if ( response.toLowerCase().indexOf( "success" ) >= 0 ) {
					surveysystem( "#" + survey_id + " .click-nav" ).html( checker + "<div class='click-download'>" + filename + " " + sspa_params.languages.isready + ", <a target='_blank' href='" + sspa_params.plugin_url + "/exports/" + survey_id + "." + filetype + "'>" + sspa_params.languages.dhere + "</a></div>" );
				}
					rmdni = false;
				});
}

function export_personal_survey() {
		var thiselement = thisexpelement;
		if ( thiselement == '' ) {
			return true;
		}
		thiselement.parentsUntil( 'ul' ).parent( 'ul' ).css( "display", "none" );
		var filetype = surveysystem( thiselement ).attr( "href" );
		var filename = surveysystem( thiselement ).text();
		var samesession = surveysystem( thiselement ).attr( "data-session" );
		var container = thiselement.parentsUntil( ".click-nav" ).parent( ".click-nav" );
		var chartimg = [];
		if ( filetype == 'pdf' ) {
			if ( exportcharts == 2 ) {
				surveysystem( '.ms-chart canvas' ).each( function( index ) {
					chartimg.push( surveysystem( this )[ 0 ].toDataURL() );
				})
			}
			surveysystem( "#modal_survey_pro_pdf_export" ).remove();
		}
		survey_id = surveysystem( ".ms-user-panel" ).attr( "id" ).replace( "msps-", "" );
		var data = {
			action: 'ajax_survey',
			sspcmd: 'export',
			type: filetype,
			mode: 'personal',
			survey_id: survey_id,
			pchart: JSON.stringify( chartimg ),
			samesession: samesession
		};
		thiselement.parentsUntil( ".click-download" ).parent( ".click-download" ).remove();
		checker = thiselement.parentsUntil( ".click-nav" ).parent( ".click-nav" ).html();
		thiselement.parentsUntil( ".click-nav" ).parent( ".click-nav" ).html( '<img width="20" style="margin-left:50px;" src="' + sspa_params.plugin_url + '/templates/assets/img/preloader.gif">' );
		surveysystem.post( sspa_params.admin_url, data, function( response ) {
			if ( response.toLowerCase().indexOf( "success" ) >= 0 ) {
				container.html( checker + "<div class='click-download'>" + filename + " " + sspa_params.languages.isready + ", <a target='_blank' href='" + sspa_params.plugin_url + "/exports/" + survey_id + "-" + samesession + "." + filetype + "'>" + sspa_params.languages.dhere + "</a></div>" );
			}
			thiselement.parentsUntil( 'ul' ).parent( 'ul' ).css( "display", "" );
			rmdni = false;
		});
}

function duplicate_survey() {
		if (surveysystem("#"+Math.abs(surveysystem("#dsurvey_name").val().split("").reduce(function(a,b){a=((a<<5)-a)+b.charCodeAt(0);return a&a},0))).length>0) {
			surveysystem(".duplicate-notice").css("display","block");
		}
		else {
			if ( actionfl != false ) {
				if (surveysystem("#keep_votes").prop("checked")) {
					var keep_votes = 1;
				}
				else {
					var keep_votes = 0;						
				}
				var data = {
					action: 'ajax_survey',
					sspcmd: 'duplicate',
					survey_id: actionfl,
					keep_votes: keep_votes,
					survey_name: surveysystem("#dsurvey_name").val(),
					survey_nid : Math.abs(surveysystem("#dsurvey_name").val().split("").reduce(function(a,b){a=((a<<5)-a)+b.charCodeAt(0);return a&a},0))
					};
					surveysystem.post( sspa_params.admin_url, data, function( response ) {
						if ( response.toLowerCase().indexOf( "duplicated" ) >= 0 ) {
							window.location.href = sspa_params.adminpage_url + "&result=duplicated";
						}
						else {
							window.location.href = sspa_params.adminpage_url + "&result=fail&reason=" + response;
						}
					});			
			}
		}
}
	
function reset_survey() {
		if ( actionfl != false ) {
			var data = {
				action: 'ajax_survey',
				sspcmd: 'reset',
				survey_id: actionfl
				};
				surveysystem.post(sspa_params.admin_url, data, function(response) {
					if ( response.toLowerCase().indexOf( "reset" ) >= 0 ) {
						window.location.href = sspa_params.adminpage_url + "&result=reset";
					}
					else {
						window.location.href = sspa_params.adminpage_url + "&result=fail";
					}
				});			
		}
		else {
			var parent = surveysystem("#"+survey_id);
			surveysystem("#"+survey_id+" .answer_count").text("0 - 0%");
			var head = parent.prev('h3');
						rmdni = true;
				var data = {
					action: 'ajax_survey',
					sspcmd: 'reset',
					survey_id: survey_id
					};
					surveysystem.post(sspa_params.admin_url, data, function(response) {
						rmdni = false;
					});
		}
}
	
	
function remove_survey() {
		if ( actionfl != false ) {
			var data = {
				action: 'ajax_survey',
				sspcmd: 'delete',
				survey_id: actionfl
				};
				surveysystem.post(sspa_params.admin_url, data, function(response) {
					if ( response.toLowerCase().indexOf( "deleted" ) >= 0 ) {
						window.location.href = sspa_params.adminpage_url + "&result=deleted";
					}
					else {
						window.location.href = sspa_params.adminpage_url + "&result=fail";
					}
				});			
		}
		else {
			var parent = surveysystem("#"+survey_id);
			var head = parent.prev('h3');
						rmdni = true;
				var data = {
					action: 'ajax_survey',
					sspcmd: 'delete',
					survey_id: survey_id
					};
					surveysystem.post(sspa_params.admin_url, data, function(response) {
						rmdni = false;
						if ( response.toLowerCase().indexOf( "deleted" ) >= 0 ) {
						window.location.href = sspa_params.adminpage_url + "&result=deleted";
						}
						else {
							window.location.href = sspa_params.adminpage_url + "&result=fail";
						}
					});
		}
}
surveysystem( '#participants-select-all' ).click(function (event) {
	if ( surveysystem( this ).val() != "1" ) {
		surveysystem( '.participants-select' ).prop( 'checked', true );
		surveysystem( '.participants-select' ).val( '1' );
	}
	else {
		surveysystem( '.participants-select' ).prop( 'checked', false );
		surveysystem( '.participants-select' ).val( '0' );
	}
})

surveysystem( '#delete_allp' ).click( function ( event ) {
	if( surveysystem( ".participants-select").is( ':checked' ) ) {
		surveysystem( "#dialog-confirm5" ).dialog( "open" );
	}
	else {
		showmessage( sspa_params.languages.noselected );
		return false;
	}
})

surveysystem( '#bulk_export' ).click( function ( event ) {
	if( surveysystem( ".participants-select").is( ':checked' ) ) {
		surveysystem( "#dialog-confirm8" ).dialog( "open" );
	}
	else {
		showmessage( sspa_params.languages.noselected );
		return false;
	}
})

function create_graph( survey_id, question_id, animation ) {
					var msChartOptions = {
					tooltips: {
						fontSize: '75.4%'
					}
				};
	labs = [];dset = [];msChartBGcolor = [];msChartBGcolor2 = [];labs = [];
	var answer_counter = 0;
		surveysystem( "#" + survey_id + " #question_" + question_id + " .answer" ).each( function( index ) {
		var thisid = surveysystem( this ).attr( "id" ).replace( "answer", "" );
		var thisval = surveysystem( "#" + survey_id + " #question_" + question_id + " #answer_count" + thisid ).text().split( "-" );
		if ( thisval != "" ) {
			answer_counter += parseInt( thisval[ 1 ].replace( "%", "" ).trim() );
		}
	})

	surveysystem( "#" + survey_id + " #question_" + question_id + " .answer" ).each( function( index ) {
		var thisid = surveysystem( this ).attr( "id" ).replace( "answer", "" );
		var thisval = surveysystem( "#" + survey_id + " #question_" + question_id + " #answer_count" + thisid ).text().split( "-" );
		if (answer_counter==0) {
			labs.push( surveysystem( this ).val().replace(/(\[.*?\]) */g, "") );
			dset.push( Math.floor( 100/surveysystem( "#"+survey_id+" #question_"+question_id+" .answer" ).length ) );
			msChartBGcolor.push( get_random_color() );
			msChartBGcolor2.push( get_random_color() );
		}
		else
		{
			labs.push( surveysystem( this ).val().replace(/(\[.*?\]) */g, "") );
			dset.push( parseInt( thisval[ 0 ].replace( "%", "" ).trim() ) );
			msChartBGcolor.push( get_random_color() );
			msChartBGcolor2.push( get_random_color() );
		}
	})
	Chart.defaults.global.legend = {
		display: false
	}
		msChartData = {
			labels : labs,
			datasets : [
				{
					backgroundColor : msChartBGcolor,
					borderColor : "rgba(0, 0, 0, 0.2)",
					hoverBackgroundColor: msChartBGcolor2,
					hoverBorderColor: "rgba(0, 0, 0, 0.2)",
					pointBackgroundColor: "rgba(0, 0, 0, 0.2)",
					pointBorderColor: "rgba(0, 0, 0, 0.2)",
					pointHoverBackgroundColor: "rgba(0, 0, 0, 0.2)",
					pointHoverBorderColor: "rgba(0, 0, 0, 0.2)",
					data : dset
				}
			]
		}
	if ( animation == 'true' ) {
			if ( document.getElementById( "modal_survey_pro_graph_" + survey_id + '_' + question_id ) != undefined ) {
			modalSurveyChart = new Chart(
				document.getElementById( "modal_survey_pro_graph_" + survey_id + '_' + question_id ).getContext( "2d" ),
				{
					type: 'pie',
					data: msChartData,
					options: msChartOptions
				}
			);
		}
	}
	else {
		if ( document.getElementById( "modal_survey_pro_pdf_container_" + survey_id + '_' + question_id ) != undefined ) {
			Chart.defaults.global.legend = {
				display: false
			}
			msChartOptions.animation = {
				animation: false
			}
			modalSurveyChart = new Chart(
				document.getElementById( "modal_survey_pro_pdf_container_" + survey_id + '_' + question_id ).getContext( "2d" ),
				{
					type: 'pie',
					data: msChartData,
					options: msChartOptions
				}
			);
			var legend = modalSurveyChart.generateLegend();
			surveysystem( "#modal_survey_pro_pdf_container_" + survey_id + '_' + question_id ).parent().append( '<div id="legend_modal_survey_pro_pdf_container_' + survey_id + '_' + question_id +'">' + legend.replace(/(\[.*?\]) */g, "") + '</div>' );
			surveysystem( '#legend_modal_survey_pro_pdf_container_' + survey_id + '_' + question_id + ' span' ).css({ "width": "20px", "height": "20px", "margin-right": "10px" });
			surveysystem( '#legend_modal_survey_pro_pdf_container_' + survey_id + '_' + question_id + ' span' ).html( '&nbsp;&nbsp;&nbsp;&nbsp;' );
			surveysystem( '#legend_modal_survey_pro_pdf_container_' + survey_id + '_' + question_id ).html( surveysystem( '#legend_modal_survey_pro_pdf_container_' + survey_id + '_' + question_id + ' span' ) );
		}
	}		
}
	
	function initialize_tooltips() {
		surveysystem(".modal_survey_tooltip").tooltip({
			  content: function () {
				  return surveysystem(this).prop('title');
			  },
			  show: { effect: "drop", duration: 300 },
			  hide: { effect: "drop", duration: 100 }
		  });	
	}

	function getFromHex( color, transparency ) {
		var patt = /^#([\da-fA-F]{2})([\da-fA-F]{2})([\da-fA-F]{2})$/;
		var matches = patt.exec( color );
		if ( matches == null ) {
			var matches = patt.exec( colourNameToHex( color ) );
		}
		var rgba = "rgba(" + parseInt( matches[ 1 ], 16 ) + "," + parseInt( matches[ 2 ], 16 ) + "," + parseInt( matches[ 3 ], 16 ) + ", " + transparency + ")";
		return rgba;
	}
		
	function get_random_color() {
		var letters = '0123456789ABCDEF'.split('');
		var color = '#';
		for (var i = 0; i < 6; i++ ) {
			color += letters[Math.round(Math.random() * 15)];
		}
		return color;
	}
	
	surveysystem( document ).on( "click", ".campaign-disconnect", function( event ) {
		event.preventDefault();
		var campaign_container = surveysystem( this ).attr( "data-show" );
		surveysystem( "." + campaign_container ).removeClass( "dnone" );
		surveysystem( ".aweber_connect_container" ).removeClass( "dnone" );
		surveysystem( this ).parent().remove();
	});
	
	surveysystem( document ).on( "click", ".connect-campaign", function( event ) {
		event.preventDefault();
		var campaign_ex = surveysystem( this ).attr( "id" ).split( "-" ), campaign = "", field1 = "";
		if ( campaign_ex[ 0 ] != undefined ) {
			campaign = campaign_ex[ 0 ];
			if ( rmdni == false ) {
				rmdni = true;
				if ( campaign == "aweber" ) {
					field1 = surveysystem( ".aweber_authorizationcode" ).val();
				}
				var parentelement = surveysystem( this ).parent();
				surveysystem( this ).parent().html( '<img width="20" style="margin-left:50px;" src="' + sspa_params.plugin_url + '/templates/assets/img/preloader.gif">' );
				var data = {
					action: 'ajax_ms_campaigns',
					sspcmd: 'connect_campaign',
					field1: field1,
					campaign: campaign
				};
				surveysystem.post( sspa_params.admin_url, data, function( response ) {
				if ( response.indexOf( "success" ) >= 0 ) {
					var cred = response.split( ":ms-param:" );
					surveysystem( ".aweber_consumerkey" ).val( cred[ 0 ] );
					surveysystem( ".aweber_consumersecret" ).val( cred[ 1 ] );
					surveysystem( ".aweber_accesskey" ).val( cred[ 2 ] );
					surveysystem( ".aweber_accesssecret" ).val( cred[ 3 ] );					
					surveysystem( parentelement ).html( '<span class="connect_msg"><strong> SUCCESS </strong>- Save the Survey to apply the changes</span>' );
				}
				else {
						surveysystem( parentelement ).html( '<span class="connect_msg">' + response + '</strong></span>' );
				}
				rmdni = false;
				});
			
			};		
		}
	})
});
jQuery(document).ready( function () {
	jQuery( '.modal-survey-list-table-saved-surveys' ).DataTable({ 
					"dom": 'lfrtip', 
					"order": [[ 4, "desc" ]], 
					"aoColumnDefs": [
						{ 'bSortable': false, 'aTargets': [ 8 ] }
					] });
	jQuery( '.modal-survey-list-table-participants' ).DataTable({
					"dom": '<"ssfprotoolbar">T<"clear">lBfrtip', 
					"order": [[ 5, "desc" ]], 
					"buttons": [
					{
					extend: 'copyHtml5',
					exportOptions: {
						columns: [ 1, 2, 3, 4, 5]
					}},
					{
					extend: 'excelHtml5',
					exportOptions: {
						columns: [ 1, 2, 3, 4, 5]
					}},
					{
					extend: 'csvHtml5',
					exportOptions: {
						columns: [ 1, 2, 3, 4, 5]
					}},
					{
					extend: 'pdfHtml5',
					exportOptions: {
						columns: [ 1, 2, 3, 4, 5]
					}},
					{
					extend: 'print',
					exportOptions: {
						columns: [ 1, 2, 3, 4, 5]
					}},
					], 
					"aoColumnDefs": [
						{ 'bSortable': false, 'aTargets': [ 0 ] }
					] 
	});
  jQuery(function() {
    if ( jQuery( "#ms-participants-tabs" ).length > 0 ) {
		jQuery( "#ms-participants-tabs" ).tabs();
	}
  });
})
})( jQuery );