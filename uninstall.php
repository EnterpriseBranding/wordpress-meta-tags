<?php

defined('WP_UNINSTALL_PLUGIN') || die();


// clear database
require_once dirname(__FILE__) . '/includes/dpmt-meta-tag-list.php';

// global $wpdb;
// $table = $wpdb->prefix.'postmeta';
// $wpdb->delete ($table, array('meta_key' => 'dpmt_'));