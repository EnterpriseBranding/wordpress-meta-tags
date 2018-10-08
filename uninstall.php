<?php

defined('WP_UNINSTALL_PLUGIN') || die();


// clear plugin data from post meta and options table
global $wpdb;
$tables_to_clean = [
    $wpdb->prefix.'postmeta',
    $wpdb->prefix.'termmeta',
    $wpdb->prefix.'usermeta',
    $wpdb->prefix.'options'
];

require_once dirname(__FILE__) . '/includes/meta-tag-list.php';

if (!empty($dpmt_meta_tag_list) && is_array($dpmt_meta_tag_list)){

    foreach( $dpmt_meta_tag_list as $k => $v ){

        foreach( $v['fields'] as $field ){

            foreach ( $tables_to_clean as $table ){

                if ( $table == $wpdb->prefix.'options' ){

                    $wpdb->delete( $table, array('option_name' => 'dpmt_frontpage_' . $field['variable']) );

                }else{

                    $wpdb->delete( $table, array('meta_key' => $field['variable']) );

                }

            }

        }    

    }

}
