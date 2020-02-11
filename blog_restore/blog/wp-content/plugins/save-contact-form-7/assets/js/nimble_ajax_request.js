/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

jQuery(document).ready(function () {

    jQuery("#nimble_cf7_names").on('change', function () {
        form_id = jQuery(this).find("option:selected").val();
        jQuery.ajax({
            "type": "POST",
            "url": ajax_object.ajaxurl,
            "data": {action: 'nimble_ajax_data', id: form_id},
            "dataType": "json",
            "beforeSend":function(){
                sessionStorage.setItem('nimble_form_id',form_id);
            },
            "success": function (data) {
                jQuery("#nimble_table_wrapper").html('');
                jQuery("#nimble_table_wrapper").html(data.dt_header);
                jQuery("#nimble_table_data").dataTable({
                    'processing': true,
                    'serverSide': true,
                    'responsive': true,
                    lengthMenu: [[10, 25, 100, -1], [10, 25, 100, "All"]],
                    //oSelectorOpts:{filter:'applied',order:'current',pages:'all'},
                    "ajax": {
                        'type': 'POST',
                        "url": ajax_object.ajaxurl,
                        "dataType": "json",
                        "data": {action: 'nimble_ajax_datatable', id: form_id},
                    },
                    "bAutoWidth": true,
                    dom: 'Bflrtip',
                    buttons: [
                        {
                            extend: 'csvHtml5',
                            exportOptions: {
                                columns: [data.dt_columnslist]
                            }

                        },
                        {
                            extend: 'pdfHtml5',
                            exportOptions: {
                                columns: [data.dt_columnslist]
                            },
                            orientation: 'landscape',
                            PdfSize: "A3"

                        },
                        {
                            extend: 'print',
                            exportOptions: {
                                "columns": [data.dt_columnslist]
                            }
                        }],
                    "columnDefs": [{
                            "targets": [data.dt_column_target],
                            "visible": false,
                            "searchable": false
                        }],
                   
                    "oLanguage": {
                        "sProcessing": "<div></div><div></div><div></div><div></div><div></div>"
                    },
                    "fnPreDrawCallback": function (oSettings) {
                        jQuery('#nimble_table_data').css('opacity', '0.2');
                    },
                    "fnDrawCallback": function () {
                        jQuery('#nimble_table_data').css('opacity', '1');
                    }
                });


            }
        })
    });
    
    var form_id = sessionStorage.getItem('nimble_form_id');
    if (form_id > 0) {
        jQuery('#nimble_cf7_names option[value="'+form_id+'"]').attr('selected', 'selected');
        jQuery.ajax({
            "type": "POST",
            "url": ajax_object.ajaxurl,
            "data": {action: 'nimble_ajax_data', id: form_id},
            "dataType": "json",
            "success": function (data) {
                jQuery("#nimble_table_wrapper").html('');
                jQuery("#nimble_table_wrapper").html(data.dt_header);
                jQuery("#nimble_table_data").dataTable({
                    'processing': true,
                    'serverSide': true,
                    'responsive': true,
                    'bSearchable': true,
                    lengthMenu: [[10, 25, 100, -1], [10, 25, 100, "All"]],
                    "ajax": {
                        'type': 'POST',
                        "url": ajax_object.ajaxurl,
                        "dataType": "json",
                        "data": {action: 'nimble_ajax_datatable', id: form_id}
                    },
                    "dom": 'Bflrtip',
                    buttons: [
                        {
                            extend: 'csvHtml5',
                            exportOptions: {
                                columns: [data.dt_columnslist]
                            }

                        },
                        {
                            extend: 'pdfHtml5',
                            exportOptions: {
                                columns: [data.dt_columnslist]
                            },
                            orientation: 'landscape',
                            PdfSize: "A3"

                        },
                        {
                            extend: 'print',
                            exportOptions: {
                                "columns": [data.dt_columnslist]
                            }
                            //autoPrint: false
                        }],
                    
                    "columnDefs": [{
                            "targets": [data.dt_column_target],
                            "visible": false,
                            "searchable": false
                        }],
                    "oLanguage": {
                        "sProcessing": "<div></div><div></div><div></div><div></div><div></div>"
                    },
                    "fnPreDrawCallback": function (oSettings) {
                        jQuery('#nimble_table_data').css('opacity', '0.2');
                    },
                    "fnDrawCallback": function () {
                        jQuery('#nimble_table_data').css('opacity', '1');
                    }
                });
            }
        });
    }
//formname dropdown for settings page
});
function getid()
{
    var id = document.getElementById("nimble_cf7_names").value;
    return id;
}


