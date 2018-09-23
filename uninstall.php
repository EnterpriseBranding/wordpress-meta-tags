<?php

defined('WP_UNINSTALL_PLUGIN') || die();


// clear plugin data from post meta and options table
global $wpdb;
$table = $wpdb->prefix.'postmeta';

require_once dirname(__FILE__) . '/includes/dpmt-meta-tag-list.php';

if (!empty($dpmt_meta_tag_list) && is_array($dpmt_meta_tag_list)){

    foreach( $dpmt_meta_tag_list as $k => $v ){

        foreach( $v['fields'] as $field ){

            $wpdb->delete ($table, array('meta_key' => $field['variable']));

        }    

    }

}
