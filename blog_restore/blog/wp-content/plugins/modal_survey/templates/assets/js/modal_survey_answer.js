;(function ( $, window, document, undefined ) {

	"use strict";

		/** Create the defaults once **/
		var pluginName = "pmsresults",
				defaults = {
				style		 	: {},
				datas    		: []
		};
/** The actual plugin constructor **/
function Plugin ( element, options ) {
		this.element = element;
		this.settings = $.extend( {}, defaults, options );
		this._defaults = defaults;
		this._name = pluginName;
		this.init();
}

/** Avoid Plugin.prototype conflicts **/
$.extend( Plugin.prototype, {
		init: function () {
		var msChartSData = [], labs = [], dset = [], dset2 = [], msChartData = {}, msChartOptions = {}, bgcolors, advcolors = [], mschartelem;
		var fillColor = '', fillColor2 = '', strokeColor = '', strokeColor2 = '', highlightFill = '', highlightFill2 = '', highlightStroke = '', highlightStroke2 = '', element = '', style = '', lng = {}, elementstyle = this.settings.style.style, counter, msChartType, afillColor = [], astrokeColor = [], ahighlightFill = [], ahighlightStroke = [], thismax = 0, arfillColor = [], arstrokeColor = [], arhighlightFill = [], arstrokeColor2 = [];	
		if ( this.settings.style.bgcolor == undefined ) {
			this.settings.style.bgcolor = "";
		}
		this.settings.style.aftertag = "";
		if ( this.settings.style.percentage != undefined ) {
			if ( this.settings.style.percentage == "true" ) {
				this.settings.style.aftertag = "%";
			}
		}
		if ( this.settings.style.after != undefined ) {
			if ( this.settings.style.after != "" ) {
				this.settings.style.aftertag = this.settings.style.after;
			}
		}
		if ( this.settings.style.legend != undefined ) {
			if ( this.settings.style.legend != "" ) {
				this.settings.style.legend = this.settings.style.legend;
			}
		}
		if ( ( bgcolors == "" || bgcolors == undefined ) && this.settings.style.bgcolor != "" ) {
			bgcolors = this.settings.style.bgcolor.split( "," );
		}
		if ( this.settings.style.style == "progressbar" || this.settings.style.style == "linebar" ) {
			jQuery( '#' + jQuery( this.element ).attr( 'id' ) + " .survey_global_percent" ).each( function( index ) {
				element = jQuery(this);
				if ( elementstyle == "linebar" ) {
					element.css( "width", parseInt( element.closest( ".lineprocess" ).find( ".hiddenperc" ).val() ) + "%" );
					incNumbers( 0, element.closest( ".lineprocess" ).find( ".hiddenperc" ).val(), element.parent().children( ".perc" ), 16 );
				}
				else {
					element.css( "width", parseInt( element.closest( ".process" ).find( ".hiddenperc" ).val() ) + "%" );					
				}
			})
		}
		function incNumbers( start, end, jqstr, speed ) {
			var clr = null;
			var ele = jQuery( jqstr );
			var rand = start;
			var res = "";
			if ( arguments.length < 3 ) {
				throw "missing required parameters";
			}
			function loop() {
				clearTimeout( clr );
				function inloop() {
					res = rand += 1;
					ele.html( res + "%" );
					if ( !( rand < end ) ) {
						ele.html( end + "%" );
						return;
					}
					clr = setTimeout( inloop, ( speed || 32 ) );
				};
				inloop();
				//  setTimeout(loop, 2500); //Increment Loop TIme
			};
			loop();
		}
		Object.size = function( obj ) {
			var size = 0, key;
			for ( key in obj ) {
				if ( obj.hasOwnProperty( key ) ) {
					size++;
				}
			}
			return size;
		};
		
		element = this.element;
		style = this.settings.style;
		if ( this.settings.style.lng != undefined ) {
			lng = this.settings.style.lng;
		}
		else {
			lng.label1 = "";
			lng.label2 = "";		
		}
		if ( style.style == "barchart" || style.style == "linechart" || style.style == "radarchart" || style.style == "piechart" || style.style == "doughnutchart" || style.style == "polarchart" || style.style == "bubblechart" ) {
			var thisdatas = this.settings.datas[ 0 ];
			var thisdatassize = 0;
			if ( thisdatas != undefined ) {
				if ( thisdatas.length == undefined ) {
					thisdatassize = Object.size( thisdatas );
				}
				else {
					thisdatassize = thisdatas.length;
				}
			}
			if ( bgcolors == undefined && thisdatas != undefined ) {
				for (var i = 0; i < thisdatassize; i++ ) {
					advcolors.push( [ getFromHex( get_random_color(), 0.7 ), getFromHex( get_random_color(), 0.7 ), getFromHex( get_random_color(), 0.7 ), getFromHex( get_random_color(), 0.7 ) ] );
				}
			}
			else {
				jQuery.each( bgcolors, function ( a, bs ) {
					if ( a == undefined ) {
						a = 0;
					}
					advcolors[ a ] = bs.split( ":" );
				})
				for (var c = 0; c < thisdatassize; c++ ) {
					if ( advcolors[ c ] == undefined ) {
						if ( advcolors[ c - 1 ] != undefined ) {
							advcolors[ c ] = advcolors[ c - 1 ];
						}
						else {
							advcolors[ c ] = [ getFromHex( get_random_color(), 0.7 ), getFromHex( get_random_color(), 0.7 ), getFromHex( get_random_color(), 0.7 ), getFromHex( get_random_color(), 0.7 ) ];
						}
					}
				}
			}
			for ( var s = 0; s < thisdatassize; s++ ) {
				for (var c = 0; c < 4; c++ ) {
					if ( advcolors[ s ] == undefined ) {
						advcolors[ s ] = new Array();
					}
					if ( advcolors[ s ][ c ] == undefined ) {
						advcolors[ s ][ c ] = getFromHex( get_random_color(), 0.7 );
					}
				}				
			}
			jQuery.each( this.settings.datas, function ( i, elem ) {
			dset = [];dset2 = [];
			fillColor = ( advcolors[ 1 ] != undefined && advcolors[ 1 ] != '' ) ? getFromHex( advcolors[ 1 ], 0.5 ) : getFromHex( get_random_color(), 0.7 ), 
			strokeColor = ( advcolors[ 2 ] != undefined && advcolors[ 2 ] != '' ) ? getFromHex( advcolors[ 2 ], 0.5 ) : getFromHex( get_random_color(), 0.7 ), 
			highlightFill = ( advcolors[ 3 ] != undefined && advcolors[ 3 ] != '' ) ? getFromHex( advcolors[ 3 ], 0.5 ) : getFromHex( get_random_color(), 0.7 ), 
			highlightStroke = ( advcolors[ 4 ] != undefined && advcolors[ 4 ] != '' ) ? getFromHex( advcolors[ 4 ], 0.5 ) : getFromHex( get_random_color(), 0.7 );
			labs = [];
			thismax = 0;
				jQuery.each(elem, function (e, el) {
					labs.push( el.answer );
					dset.push( el.count );
					if ( el.gcount != undefined ) {
						dset2.push( el.gcount );
					}
					if ( bgcolors == "" ) {
						afillColor.push( getFromHex( get_random_color(), 0.7 ) );
						//astrokeColor.push( get_random_color() );
						ahighlightFill.push( getFromHex( get_random_color(), 0.7 ) );
						//ahighlightStroke.push( get_random_color() );
					}
				});
				thismax = Math.max.apply(Math,dset);
				if ( bgcolors == "" ) {
					fillColor = afillColor;
					strokeColor = "rgba(0, 0, 0, 0.1)";
					highlightFill = ahighlightFill;
					highlightStroke = "rgba(0, 0, 0, 0.2)";
				}
				if ( ! jQuery.isEmptyObject( dset2 ) ) {
					if ( fillColor[ 1 ] != undefined ) {
						if ( fillColor[ 1 ].substring( 0, 3 ) == 'rgb' ) {
						   highlightFill = getFromRGB( fillColor[ 1 ], 0.6 );
						   fillColor = getFromRGB( fillColor[ 1 ], 0.8 );
						} else {
						   highlightFill = getFromHex( fillColor[ 1 ], 0.6 );
						   fillColor = getFromHex( fillColor[ 1 ], 0.8 );
						}
					}
					else {
						fillColor = afillColor;
						highlightFill = ahighlightFill;
					}
					if ( fillColor[ 1 ] != undefined ) {
						if ( strokeColor[ 1 ].substring( 0, 3 ) == 'rgb' ) {
						   strokeColor = getFromRGB( strokeColor[ 1 ], 0.8 );
						} else {
						   strokeColor = getFromHex( strokeColor[ 1 ], 0.8 );
						}
					}
					else {
						strokeColor = "rgba(0, 0, 0, 0.1)";
						highlightStroke = "rgba(0, 0, 0, 0.2)";
					}
				}
				jQuery.each( advcolors, function ( i, ar ) {
					jQuery.each( advcolors[ i ], function (e, el) {
						advcolors[ i ][ e ] = getFromHex( el, 0.8 );
					} )
						arfillColor.push( advcolors[ i ][ 0 ] );
						arstrokeColor.push( advcolors[ i ][ 1 ] );
						arhighlightFill.push( advcolors[ i ][ 2 ] );
						arstrokeColor2.push( advcolors[ i ][ 3 ] );
				} )
				msChartData = {
						labels : labs,
						datasets : [
							{
								label: lng.label1,
								backgroundColor : ( arfillColor != undefined ) ? arfillColor : fillColor,
								borderColor : ( arstrokeColor != undefined ) ? arstrokeColor : strokeColor,
								hoverBackgroundColor: ( arhighlightFill != undefined ) ? arhighlightFill : highlightFill,
								hoverBorderColor: ( arstrokeColor2 != undefined ) ? arstrokeColor2 : strokeColor,
								pointBackgroundColor: "rgba(0, 0, 0, 0.2)",
								pointBorderColor: "rgba(0, 0, 0, 0.2)",
								pointHoverBackgroundColor: "rgba(0, 0, 0, 0.2)",
								pointHoverBorderColor: "rgba(0, 0, 0, 0.2)",
								data : dset
							}
						]

					}
					if ( ! jQuery.isEmptyObject( dset2 ) ) {
						fillColor2 = getFromHex( get_random_color(), 0.7 ), 
						strokeColor2 = getFromHex( get_random_color(), 0.7 ), 
						highlightFill2 = getFromHex( get_random_color(), 0.7 ), 
						highlightStroke2 = getFromHex( get_random_color(), 0.7 );
						msChartData.datasets.push({
								label: lng.label2,
								backgroundColor : fillColor2,
								borderColor : strokeColor2,
								hoverBackgroundColor: highlightFill2,
								hoverBorderColor: strokeColor2,
								pointBackgroundColor: fillColor2,
								pointBorderColor: strokeColor2,
								pointHoverBackgroundColor: highlightFill2,
								pointHoverBorderColor: strokeColor2,
								data : dset2				
						})
					}
				msChartOptions.tooltipTemplate = "<%if (label){%><%=label%>: <%}%><%= value %>" + style.aftertag;
				msChartOptions.multiTooltipTemplate = "<%= datasetLabel %><%= value %>" + style.aftertag;
				msChartOptions.barStrokeWidth = 1;
				if ( style.legend != "true" && Chart.defaults != undefined ) {
					Chart.defaults.global.legend = {
								display: false
							}
				}
				if ( elementstyle == "barchart" ) {
					msChartType = 'bar';
					if ( style.max > 0 ) {
						msChartOptions.scales = {
							yAxes: [{
								ticks: {
									max: parseInt( style.max ),
									stepSize: Math.ceil( style.max / 10 ),
									beginAtZero: true
								}
							}]
						}
					}
					else {
						msChartOptions.scales = {
							yAxes: [{
								ticks: {
									stepSize: Math.ceil( thismax / 10 ),
									beginAtZero: true
								}
							}]
						}						
					}
				}
				if ( elementstyle == "linechart" ) {
					msChartType = 'line';
					if ( style.max > 0 ) {
						msChartOptions.scales = {
							yAxes: [{
								ticks: {
									max: parseInt( style.max ),
									min: 0,
									stepSize: Math.ceil( style.max / 10 ),
									beginAtZero: true
								}
							}]
						}
					}
					else {
						msChartOptions.scales = {
							yAxes: [{
								ticks: {
									min: 0,
									stepSize: Math.ceil( thismax / 10 ),
									beginAtZero: true
								}
							}]
						}						
					}
				}
				if ( elementstyle == "radarchart" ) {
					msChartType = 'radar';
					if ( style.max > 0 ) {
						msChartOptions.scale = {
							ticks: {
								max: parseInt( style.max ),
								beginAtZero: true
							}
						}
					}
					else {
						msChartOptions.scale = {
							ticks: {
								beginAtZero: true
							}
						}						
					}
				}
				if ( elementstyle == "piechart" ) {
					msChartType = 'pie';
				}
				if ( elementstyle == "doughnutchart" ) {
					msChartType = 'doughnut';
				}
				if ( elementstyle == "polarchart" ) {
					msChartType = 'polarArea';
					if ( style.max > 0 ) {
						msChartOptions.scale = {
							ticks: {
								max: parseInt( style.max ),
								stepSize: Math.ceil( style.max / 10 ),
								beginAtZero: true
							}
						}
					}
					else {
						msChartOptions.scale = {
							ticks: {
								stepSize: Math.ceil( thismax / 10 ),
								beginAtZero: true
							}
						}						
					}
				}
				if ( elementstyle != "" ) {
					mschartelem = jQuery( "#" + jQuery( element ).attr( 'id' ) + " .modal-survey-chart" + i + " canvas")[ 0 ].getContext( "2d" );
					window.modalSurveyChart = new Chart(
						mschartelem,
						{
							type: msChartType,
							data: msChartData,
							options: msChartOptions
						}
					);
				}
			});
		}
		
		function colourNameToHex( colour ) {
			var colours = {"aliceblue":"#f0f8ff","antiquewhite":"#faebd7","aqua":"#00ffff","aquamarine":"#7fffd4","azure":"#f0ffff",
			"beige":"#f5f5dc","bisque":"#ffe4c4","black":"#000000","blanchedalmond":"#ffebcd","blue":"#0000ff","blueviolet":"#8a2be2","brown":"#a52a2a","burlywood":"#deb887",
			"cadetblue":"#5f9ea0","chartreuse":"#7fff00","chocolate":"#d2691e","coral":"#ff7f50","cornflowerblue":"#6495ed","cornsilk":"#fff8dc","crimson":"#dc143c","cyan":"#00ffff",
			"darkblue":"#00008b","darkcyan":"#008b8b","darkgoldenrod":"#b8860b","darkgray":"#a9a9a9","darkgreen":"#006400","darkkhaki":"#bdb76b","darkmagenta":"#8b008b","darkolivegreen":"#556b2f",
			"darkorange":"#ff8c00","darkorchid":"#9932cc","darkred":"#8b0000","darksalmon":"#e9967a","darkseagreen":"#8fbc8f","darkslateblue":"#483d8b","darkslategray":"#2f4f4f","darkturquoise":"#00ced1",
			"darkviolet":"#9400d3","deeppink":"#ff1493","deepskyblue":"#00bfff","dimgray":"#696969","dodgerblue":"#1e90ff",
			"firebrick":"#b22222","floralwhite":"#fffaf0","forestgreen":"#228b22","fuchsia":"#ff00ff",
			"gainsboro":"#dcdcdc","ghostwhite":"#f8f8ff","gold":"#ffd700","goldenrod":"#daa520","gray":"#808080","grey":"#808080","green":"#008000","greenyellow":"#adff2f",
			"honeydew":"#f0fff0","hotpink":"#ff69b4",
			"indianred ":"#cd5c5c","indigo":"#4b0082","ivory":"#fffff0","khaki":"#f0e68c",
			"lavender":"#e6e6fa","lavenderblush":"#fff0f5","lawngreen":"#7cfc00","lemonchiffon":"#fffacd","lightblue":"#add8e6","lightcoral":"#f08080","lightcyan":"#e0ffff","lightgoldenrodyellow":"#fafad2",
			"lightgrey":"#d3d3d3","lightgreen":"#90ee90","lightpink":"#ffb6c1","lightsalmon":"#ffa07a","lightseagreen":"#20b2aa","lightskyblue":"#87cefa","lightslategray":"#778899","lightsteelblue":"#b0c4de",
			"lightyellow":"#ffffe0","lime":"#00ff00","limegreen":"#32cd32","linen":"#faf0e6",
			"magenta":"#ff00ff","maroon":"#800000","mediumaquamarine":"#66cdaa","mediumblue":"#0000cd","mediumorchid":"#ba55d3","mediumpurple":"#9370d8","mediumseagreen":"#3cb371","mediumslateblue":"#7b68ee",
			"mediumspringgreen":"#00fa9a","mediumturquoise":"#48d1cc","mediumvioletred":"#c71585","midnightblue":"#191970","mintcream":"#f5fffa","mistyrose":"#ffe4e1","moccasin":"#ffe4b5",
			"navajowhite":"#ffdead","navy":"#000080",
			"oldlace":"#fdf5e6","olive":"#808000","olivedrab":"#6b8e23","orange":"#ffa500","orangered":"#ff4500","orchid":"#da70d6",
			"palegoldenrod":"#eee8aa","palegreen":"#98fb98","paleturquoise":"#afeeee","palevioletred":"#d87093","papayawhip":"#ffefd5","peachpuff":"#ffdab9","peru":"#cd853f","pink":"#ffc0cb","plum":"#dda0dd","powderblue":"#b0e0e6","purple":"#800080",
			"red":"#ff0000","rosybrown":"#bc8f8f","royalblue":"#4169e1",
			"saddlebrown":"#8b4513","salmon":"#fa8072","sandybrown":"#f4a460","seagreen":"#2e8b57","seashell":"#fff5ee","sienna":"#a0522d","silver":"#c0c0c0","skyblue":"#87ceeb","slateblue":"#6a5acd","slategray":"#708090","snow":"#fffafa","springgreen":"#00ff7f","steelblue":"#4682b4",
			"tan":"#d2b48c","teal":"#008080","thistle":"#d8bfd8","tomato":"#ff6347","turquoise":"#40e0d0",
			"violet":"#ee82ee",
			"wheat":"#f5deb3","white":"#ffffff","whitesmoke":"#f5f5f5",
			"yellow":"#ffff00","yellowgreen":"#9acd32"};

			if (typeof colours[colour.toLowerCase()] != 'undefined')
				return colours[colour.trim().toLowerCase()];

			return "#808080";
		}		
		function getFromRGB( color, transparency ) {
			var result = "";
			if ( color.indexOf( 'a' ) == -1 ){
				result = color.replace( ')', ', ' + transparency + ' )').replace( 'rgb', 'rgba' );
			}
			return result;
		}

		function getFromHex( color, transparency ) {
			var searchrgba = new RegExp( "rgba" );;
			if ( searchrgba.test( color ) == false ) {
				if ( typeof color === 'string' ) {
					var patt = /^#([\da-fA-F]{2})([\da-fA-F]{2})([\da-fA-F]{2})$/;
					var matches = patt.exec( color.trim() );
					if ( matches == null && searchrgba.test( color ) == false ) {
						var matches = patt.exec( colourNameToHex( color.trim() ) );
					}
					var rgba = "rgba(" + parseInt( matches[ 1 ], 16 ) + "," + parseInt( matches[ 2 ], 16 ) + "," + parseInt( matches[ 3 ], 16 ) + ", " + transparency + ")";
					return rgba;
				}
				else {
					var colorcodes = [];
					jQuery.each( color, function ( i, elem ) {
						var patt = /^#([\da-fA-F]{2})([\da-fA-F]{2})([\da-fA-F]{2})$/;
						var matches = patt.exec( elem.trim() );
						if ( matches == null && searchrgba.test( color ) == false ) {
							var matches = patt.exec( colourNameToHex( elem.trim() ) );
						}
						colorcodes[ i ] = "rgba(" + parseInt( matches[ 1 ], 16 ) + "," + parseInt( matches[ 2 ], 16 ) + "," + parseInt( matches[ 3 ], 16 ) + ", " + transparency + ")";
					})
					return colorcodes;
				}
			}
			else {
				return color;
			}
		}			
		function get_random_color() {
			var letters = '0123456789ABCDEF'.split('');
			var color = '#';
			for (var i = 0; i < 6; i++ ) {
				color += letters[Math.round(Math.random() * 15)];
			}
			return color;
		}
	}
});
		$.fn[ pluginName ] = function ( options ) {
				return this.each(function() {
						if ( !$.data( this, "plugin_" + pluginName ) ) {
								$.data( this, "plugin_" + pluginName, new Plugin( this, options ) );
						}
				});
		};
})( jQuery, window, document );