<?php

/**
 * Plugin Name: Meta Tags
 * Plugin URI: https://wordpress.org/plugins/meta-tags/
 * Description: A super simple plugin to edit meta tags in all your pages, posts, categories, tags and WooCommerce pages.
 * Author: DivPusher - WordPress Theme Club
 * Author URI: https://divpusher.com/
 * Version: 1.3.0
 * Text Domain: meta-tags
 * Tags: meta tags, edit meta tags, facebook open graph, twitter cards, seo
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0
 */



// direct calls are not allowed
defined('ABSPATH') || die();


// define plugin file
if( !defined('DPMT_PLUGIN_FILE') ){
    define( 'DPMT_PLUGIN_FILE', plugin_basename( __FILE__ ) );
}



// define full path to plugin file
if( !defined('DPMT_PLUGIN_FULL_PATH') ){
    define( 'DPMT_PLUGIN_FULL_PATH',  __FILE__ );
}



// include core class
if ( ! class_exists( 'DPMT_Meta_Tags' ) ){
	require_once dirname( __FILE__ ) . '/includes/class-dpmt-meta-tags.php';
}



// main instance of the plugin
return DPMT_Meta_Tags::get_instance();
