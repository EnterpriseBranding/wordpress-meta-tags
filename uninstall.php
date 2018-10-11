<?php
/**
 * Removes all plugin data from postmeta and options tables on uninstall.
 * 
 * @since 2.0.0
 */ 


defined('WP_UNINSTALL_PLUGIN') || die();


global $wpdb;

$tables_to_clean = [
    $wpdb->prefix.'postmeta' => 'meta_key',
    $wpdb->prefix.'termmeta' => 'meta_key',
    $wpdb->prefix.'usermeta' => 'meta_key',
    $wpdb->prefix.'options' => 'option_name'
];

require_once dirname(__FILE__) . '/includes/meta-tag-list.php';

if (!empty($dpmt_meta_tag_list) && is_array($dpmt_meta_tag_list)){

    foreach( $dpmt_meta_tag_list as $k => $v ){

        foreach( $v['fields'] as $field ){

            foreach ( $tables_to_clean as $table => $key ){

                $wpdb->delete( $table, array( $key => $field['variable'] ) );
                $wpdb->delete( $table, array( $key => 'dpmt_frontpage_' . $field['variable'] ) );

            }

        }    

    }

}

delete_option( 'dpmt_plugin_version' );
