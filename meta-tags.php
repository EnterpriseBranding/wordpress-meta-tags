<?php

/**
 * Plugin Name: Meta Tags
 * Plugin URI: https://wordpress.org/plugins/meta-tags/
 * Description: A super simple plugin to edit meta tags in all your pages, posts and WooCommerce product pages.
 * Author: DivPusher - WordPress Theme Club
 * Author URI: https://divpusher.com/
 * Version: 1.3.0
 * Text Domain: meta-tags
 * Tags: meta tags, edit meta tags, facebook open graph, twitter cards, schema.org
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0
 */



// direct calls are not allowed
defined('ABSPATH') || die();



// define plugin file
if( !defined('DPMT_PLUGIN_FILE') ){
    define( 'DPMT_PLUGIN_FILE', plugin_basename( __FILE__ ) );
}



// include core class
if ( ! class_exists( 'DP_Meta_Tags' ) ){
	require_once dirname(__FILE__) . '/includes/class-dp-meta-tags.php';
}



// main instance of the plugin
function DPMT(){
	return DP_Meta_Tags::get_instance();
}



// start the plugin
DPMT();

