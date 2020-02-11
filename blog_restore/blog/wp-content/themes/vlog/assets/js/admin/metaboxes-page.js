(function($) {
    $(document).ready(function (){
            
    		/* Image opts selection */
            $('body').on('click', 'img.vlog-img-select', function(e){
                e.preventDefault();
                
                //alert('click');

                if ( !$(this).parent().hasClass('vlog-disabled')){
                    $(this).closest('ul').find('img.vlog-img-select').removeClass('selected');
                    $(this).addClass('selected');
                    $(this).closest('ul').find('input').removeAttr('checked');
                    $(this).closest('li').find('input').attr('checked','checked');
                }

                if( $(this).closest('ul').hasClass('vlog-col-dep-control') ){
                   
                    var $wrap = $(this).closest('.vlog-opt').parent();
                    var col_dep = $(this).closest('li').find('input').val();
                    
                    $wrap.find('.vlog-col-dep').each( function() {
                        var reset_layout = false;
                        $( this ).find('img.vlog-img-select').each( function() {
                            var col = $( this ).attr('data-col');
                            $( this ).parent().removeClass('vlog-disabled');
                            if( col && col_dep % col){
                                $( this ).parent().addClass('vlog-disabled');
                                if( $(this ).hasClass('selected')){
                                    $(this ).removeClass('selected');
                                    reset_layout = true;
                                }
                            }
                        });

                        if(reset_layout){
                             $( this ).find('img.vlog-img-select').each( function() {
                                var col = $( this ).attr('data-col');
                                    if( col_dep % col == false ){
                                        //alert($( this ).html());
                                        $( this ).click();
                                        return false;
                                    }
                            });
                        }
                    });
                }

            });

            /* Hack to dynamicaly apply select value */
            $('body').on('change', '.vlog-opt-select', function(e){
                //e.preventDefault();
                var sel = $(this).val();
                $(this).find('option').removeAttr('selected');
                $(this).find('option[value='+sel+']').attr('selected','selected');
            });

            /* Module form tabs */
            $('body').on('click', '.vlog-opt-tabs a', function(e){
                e.preventDefault();
                $(this).parent().find('a').removeClass('active');
                $(this).addClass('active');
                $(this).closest('.vlog-module-form').find('.vlog-tab').hide();
                $(this).closest('.vlog-module-form').find('.vlog-tab').eq($(this).index()).show();
                
            });

            /* Show/hide */
            $('body').on('click', '.vlog-next-hide', function(e){
                
                if($(this).is(':checked')){
                    $(this).closest('.vlog-opt').next().fadeIn(400);
                } else {
                    $(this).closest('.vlog-opt').next().fadeOut(400);
                }
            });
           
           
            /* Make sections sortable */
            $( "#vlog-sections" ).sortable({
              revert: false,
              cursor: "move",
              placeholder: "vlog-section-drop"
            });

             /* Make modules sortable */
            $( ".vlog-modules" ).sortable({
              revert: false,
              cursor: "move",
              placeholder: "vlog-module-drop"
            });


             var vlog_current_section;
             var vlog_current_module;
             var vlog_module_type;


            /* Add new section */
            $('body').on('click', '.vlog-add-section', function(e){
                e.preventDefault(); 
                var $modal = $($.parseHTML('<div class="vlog-section-form">' + $("#vlog-section-clone .vlog-section-form").html() + '</div>'));               
                vlog_dialog( $modal, 'Add New Section', 'vlog-save-section' );

            });
            
            /* Edit section */
            $('body').on('click', '.vlog-edit-section', function(e){
                e.preventDefault();
                vlog_current_section = parseInt($(this).closest('.vlog-section').attr('data-section'));
                var $modal = $(this).closest('.vlog-section').find('.vlog-section-form').clone();
                vlog_dialog( $modal, 'Edit Section', 'vlog-save-section' );

               

            });

             /* Remove section */
            $('body').on('click', '.vlog-remove-section', function(e){
                e.preventDefault();
                remove = vlog_confirm();
                if(remove){
                    $(this).closest('.vlog-section').fadeOut(300, function(){
                        $(this).remove();
                    });
                }
            });


            /* Save section */
            
            $('body').on('click', 'button.vlog-save-section', function(e){
                
                e.preventDefault();
                
                var $vlog_form = $(this).closest('.wp-dialog').find('.vlog-section-form').clone();

                if($vlog_form.hasClass('edit')){
                    $vlog_form = vlog_fill_form_fields( $vlog_form );    
                    var $section = $('#vlog-sections .vlog-section-'+vlog_current_section);
                    $section.find('.vlog-section-form').html($vlog_form.html());
                    $section.find('.vlog-sidebar').text( $vlog_form.find('.sec-sidebar:checked').val());
                
                } else {
                    var count = $('#vlog-sections-count').attr('data-count');
                    $vlog_form = vlog_fill_form_fields( $vlog_form, 'vlog[sections]['+ count +']');
                    $('#vlog-sections').append($('#vlog-section-clone').html());
                    var $new_section = $('#vlog-sections .vlog-section').last();
                    $new_section.addClass('vlog-section-' + parseInt(count)).attr('data-section', parseInt(count) ).find('.vlog-section-form').addClass('edit').html($vlog_form.html());
                    $new_section.find('.vlog-sidebar').text( $vlog_form.find('.sec-sidebar:checked').val());
                    $('#vlog-sections-count').attr('data-count', (parseInt(count)+1));

                    $( "#vlog-sections .vlog-section-" + count + " .vlog-modules" ).sortable({
                      revert: false,
                      cursor: "move",
                      placeholder: "vlog-module-drop"
                    });
                }

            });

            
            /* Add new module */
            $('body').on('click', '.vlog-add-module', function(e){
                e.preventDefault();
                vlog_module_type = $(this).attr('data-type');
                vlog_current_section = parseInt($(this).closest('.vlog-section').attr('data-section'));
                var $modal = $($.parseHTML('<div class="vlog-module-form">' + $('#vlog-module-clone .' + vlog_module_type +' .vlog-module-form').html() + '</div>'));           
                vlog_dialog( $modal, 'Add New Module', 'vlog-save-module' );

                 /* Make some options sortable */
                $( ".vlog-opt-content.sortable" ).sortable({
                  revert: false,
                  cursor: "move"
                });
            });

            /* Edit module */
            $('body').on('click', '.vlog-edit-module', function(e){
                e.preventDefault();
                vlog_current_section = parseInt($(this).closest('.vlog-section').attr('data-section'));
                vlog_current_module = parseInt($(this).closest('.vlog-module').attr('data-module'));
                var $modal = $(this).closest('.vlog-module').find('.vlog-module-form').clone();
                vlog_dialog( $modal, 'Edit Module', 'vlog-save-module' );

                 /* Make some options sortable */
                $( ".vlog-opt-content.sortable" ).sortable({
                          revert: false,
                          cursor: "move"
                });
            });

            /* Remove module */
            $('body').on('click', '.vlog-remove-module', function(e){
                e.preventDefault();
                remove = vlog_confirm();
                if(remove){
                    $(this).closest('.vlog-module').fadeOut(300, function(){
                        $(this).remove();
                    });
                }
            });

             /* Save module */
            
            $('body').on('click', 'button.vlog-save-module', function(e){
                
                e.preventDefault();
                
                var $vlog_form = $(this).closest('.wp-dialog').find('.vlog-module-form').clone();
                
                /* Nah, jQuery clone bug, clone text area manually */
                var txt_content = $(this).closest('.wp-dialog').find('.vlog-module-form').find("textarea").first().val();
                if(txt_content !== undefined){
                    $vlog_form.find("textarea").first().val(txt_content);
                }

                if($vlog_form.hasClass('edit')){
                    $vlog_form = vlog_fill_form_fields( $vlog_form );    
                    var $module = $('.vlog-section-'+vlog_current_section+' .vlog-module-'+vlog_current_module); 
                    $module.find('.vlog-module-form').html($vlog_form.html());
                    $module.find('.vlog-module-title').text($vlog_form.find('.mod-title').val());
                    $module.find('.vlog-module-columns').text($vlog_form.find('.mod-columns:checked').closest('li').find('span').text());
                } else {
                    var $section = $('.vlog-section-'+vlog_current_section);
                    var count = $section.find('.vlog-modules-count').attr('data-count');
                    $vlog_form = vlog_fill_form_fields( $vlog_form, 'vlog[sections]['+vlog_current_section+'][modules]['+ count +']');
                    $section.find('.vlog-modules').append($('#vlog-module-clone .'+vlog_module_type).html());
                    var $new_module = $section.find('.vlog-modules .vlog-module').last();
                    $new_module.addClass('vlog-module-' + parseInt(count)).attr('data-module', parseInt(count) ).find('.vlog-module-form').addClass('edit').html($vlog_form.html());
                    $new_module.find('.vlog-module-title').text($vlog_form.find('.mod-title').val());
                    $new_module.find('.vlog-module-columns').text($vlog_form.find('.mod-columns:checked').closest('li').find('span').text());
                    $section.find('.vlog-modules-count').attr('data-count', parseInt(count)+1);
                }

            });

            /* Open our dialog modal */
             function vlog_dialog( obj, title, action ){

                obj.dialog({
                    'dialogClass'   : 'wp-dialog',
                    'appendTo': false,         
                    'modal'         : true,
                    'autoOpen'      : false, 
                    'closeOnEscape' : true,
                    'draggable'     : false,
                    'resizable': false,
                    'width': 800,
                    'height': $(window).height() - 60,
                    'title': title,
                    'close': function(event, ui) { $('body').removeClass('modal-open'); },
                    'buttons': [ { 'text': "Save", 'class': 'button-primary '+ action , 'click': function() { $(this).dialog('close'); } } ] 
                });

                obj.dialog('open');

                $('body').addClass('modal-open');
            }
            
            
            /* Fill form fields dynamically */
            function vlog_fill_form_fields( $obj, name){
                
                $obj.find('.vlog-count-me').each( function( index ) {
                        
                        if( name !== undefined && !$(this).is('option')){
                            $(this).attr('name', name + $(this).attr('name'));
                        }

                        if($(this).is('textarea')){
                            $(this).html($(this).val());
                        }

                        
                        if(!$(this).is('select')){
                            $(this).attr('value',$(this).val());
                        }
                        
                        
                        
                        if($(this).is(":checked")){
                                $(this).attr('checked','checked');
                        } else {
                             $(this).removeAttr('checked');
                        }
   
                });

                return $obj;
            }

            function vlog_confirm(){
               var ret_val = confirm("Are you sure?");
               return  ret_val;
            }

            /* Metabox switch - do not show every metabox for every template */
            
            vlog_template_metaboxes(false);

            $('#page_template').change(function(e){
                    vlog_template_metaboxes( true );
            });
            
            function vlog_template_metaboxes(scroll){


                var template = $('select#page_template').val();
                
                if( template == 'template-modules.php' ){
                    $('#vlog_sidebar').fadeOut(300);
                    $('#vlog_modules').fadeIn(300);
                    $('#vlog_pagination').fadeIn(300);
                    $('#vlog_fa').fadeIn(300); 
                    if(scroll){
                        var target = $('#vlog_modules').attr('id');
                            $('html, body').stop().animate({
                                'scrollTop': $('#'+target).offset().top
                            }, 900, 'swing', function () {
                                window.location.hash = target;
                        });
                    }
                } else if ( template == 'template-full-width.php') {
                    $('#vlog_sidebar').fadeOut(300);
                    $('#vlog_modules').fadeOut(300);
                    $('#vlog_pagination').fadeOut(300);
                    $('#vlog_fa').fadeOut(300); 
                } else{
                    $('#vlog_sidebar').fadeIn(300);
                    $('#vlog_modules').fadeOut(300);
                    $('#vlog_pagination').fadeOut(300); 
                    $('#vlog_fa').fadeOut(300);                  
                }
            
            }

          
   
    });


    
})(jQuery);