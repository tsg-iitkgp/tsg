<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

function cf7data_delete_plugin() {
       
    global $wpdb;
    $nimble_dir_pah = wp_upload_dir();
    $result = $wpdb->get_results("select  CFDBA_tbl_name from SaveContactForm7_lookup");
        
	foreach ($result as $value) 
	{
	    $wpdb->query( "DROP TABLE IF EXISTS " . $value->CFDBA_tbl_name  );
	}
	$wpdb->query( "DROP TABLE IF EXISTS SaveContactForm7_lookup " );
         
         foreach (glob($nimble_dir_pah['basedir']."/nimble_uploads/*") as $subfolder)
         {
             foreach (glob($subfolder."/*")as $file_to_delete)
             {
                 unlink($file_to_delete);
             }
             rmdir($subfolder);
         }
         rmdir($nimble_dir_pah['basedir']."/nimble_uploads");
}

cf7data_delete_plugin(); 

?>