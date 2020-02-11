;(function ( $, window, document, undefined ) {

	"use strict";

		var pluginName = "wpmediauploader",
				defaults = {
				preview		 	: '.preview-upload',
				target    		: '.uploaded_image',
				title	  		: 'Upload an image',
				container		: "<div class=\"image_container\"><img src=\"[content]\"><input type=\"hidden\" class=\"upl_image upl-photo\" name=\"image\" value=\"[objImageUrl]\"><div><input class=\"remove_customimage_button button remove-button\" type=\"button\" value=\"Remove\" /></div></div>",
				indexcontainer	: '.slide_container',
				mode 			: 'insert',
				ajax			: true,
				type			: 'single',
				callback		: function() {}
		};


		var appender = '', appended_images = 0, selection = '', objImage = {}, opts = {}, resp = {};

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
				opts = this.settings;
					//console.log(opts);
					/** Registering the function when the Uploader Button has clicked... **/
						opts.openNewImageDialog = function(title, onInsert, isMultiple, opts) {
						if( isMultiple == undefined ) {
							isMultiple = false;
						}
						/** Initialize the Media Library with parameters **/
						opts.frame = wp.media({
								title : title,
								multiple : true,
								library : { type : 'image'},
								button : { text : 'Insert' },
								opts: opts
							});

						/** Select images in Media Uploader Window **/
						opts.frame.on( 'select', function() {
							selection = opts.frame.state().get( 'selection' );
							appended_images = $( opts.indexcontainer ).length;

							/** Clear the variable **/
							appender = '';
							resp.datas = $( opts.selector ).data();
								/** Return image object in multi mode **/
								selection.map( function( attachment ) {
									objImage = attachment.toJSON();
									appended_images++;

									/** Adding multiple images or insert single image **/
									if ( opts.type == 'multi' ) {
										appender += opts.container;
									}
									else {
										appender = opts.container;
									}

									/** replacing smart tags with values by regex **/
									appender = appender.replace( /\[content]/g, objImage.url).replace( /\[objImageUrl]/g, objImage.url ).replace( /\[index]/g, appended_images );
								});
								$.each( resp.datas, function( key, value ) {
									appender = appender.replace( new RegExp("\\[data-" + key + "\\]", "g"), value );
								})
								/** Append or insert the image(s) to the target container **/
								if ( $( opts.selector ).is( "input[type=text]" ) ) {
									$( opts.selector ).val( objImage.url );
								}
								else {
									if ( opts.mode == 'append' ) {
										$( opts.target ).append( appender );
									}
									else {
										$( opts.target ).html( appender );
									}
								}
								if ( objImage != undefined ) {
									resp.objImage = objImage;
								}
														
								delete resp.datas.plugin_wpmediauploader;
								var data = {
									'action': 'wpmediauploader',
									'wpmediauploader': JSON.stringify( resp )
								};
								if ( resp.datas.ajax != false && opts.ajax != false ) {
									$.post( ajaxurl, data, function( response ) {
										resp.response = response;
										/** Call the defined callback function if exists **/
										if ( typeof opts.callback == 'function' ) {
											opts.callback.call( resp );
										}
									});
								}
								else {
									/** Call the defined callback function if exists **/
									if ( typeof opts.callback == 'function' ) {
										opts.callback.call( resp );
									}									
								}						
								$( opts.selector ).wpmediauploader( 'destroy' );
						});

						opts.frame.on( 'close', function() {
							$( opts.selector ).wpmediauploader( 'destroy' );
						})
						/** Open Media Uploader Window **/
						opts.frame.open();
					}
						
						/** Trigger the uploader button **/
						opts.openNewImageDialog( opts.title, '', true, opts );
						return false;
		}
		});

		$.fn[ pluginName ] = function ( options ) {
		var args = arguments;
			if ( typeof options == undefined ) {
				var options = {};
			}
			if ( options === undefined || typeof options === 'object' ) {
				return this.each( function () {
					if ( ! $.data( this, 'plugin_' + pluginName ) ) {
						options.selector = this;					
						$.data( this, 'plugin_' + pluginName, new Plugin( this, options ) );
					}
				});
			} else if ( typeof options === 'string' && options[ 0 ] !== '_' && options !== 'init' ) {
				var returns;
				this.each( function () {
					var instance = $.data( this, 'plugin_' + pluginName );
					if ( instance instanceof Plugin && typeof instance[ options ] === 'function' ) {
						options.selector = this;					
						returns = instance[ options ].apply( instance, Array.prototype.slice.call( args, 1 ) );
					}
					if ( options === 'destroy' ) {
					  $.data( this, 'plugin_' + pluginName, null );
					}
				});
				return returns !== undefined ? returns : this;
			}		
		};
})( jQuery, window, document );

jQuery( document ).ready( function( $ ) {
	$( document ).on( "click", ".wpmediauploader", function() {
		$( this ).wpmediauploader({
			callback: function() {
				if ( $.isFunction( window.wpmediauploader_callback ) ) {
					window.wpmediauploader_callback( this );
				}
			}
		});
	});
});