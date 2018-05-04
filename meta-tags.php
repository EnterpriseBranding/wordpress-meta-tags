<?php
/**
 * Plugin Name: Meta Tags
 * Plugin URI: https://wordpress.org/plugins/meta-tags/
 * Description: A super simple plugin to edit meta tags on all your posts and pages for SEO.
 * Author: DivPusher - WordPress Theme Club
 * Author URI: https://divpusher.com/
 * Version: 1.3.0
 * Text Domain: meta-tags
 * Tags: meta tags, seo, edit meta tags, search engine optimization, facebook open graph, twitter cards, schema.org
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0
 */



	//better safe than sorry
		if (!function_exists('add_action')){			
			exit('Hi there! I am just a plugin, not much I can do when called directly.');
		}



	//PHP 5.6.3 and WP 4.7 is required
		if(version_compare(PHP_VERSION, '5.6.3', '<') || version_compare(get_bloginfo('version'), '4.7', '<')){
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
			deactivate_plugins( __FILE__ );
			die('Meta Tags plugin requires PHP version 5.6.3 or greater and WordPress 4.7 or greater!');
		}


		
	//plugin main file path
		define( 'DP_META_TAGS_PLUGIN_FILE', __FILE__ );
		
		
		
	//add settings link to plugin page
		function dp_metatags_actions( $links, $file ) {
			if( $file == plugin_basename( DP_META_TAGS_PLUGIN_FILE ) && function_exists( 'admin_url' ) ) {
				$settings_link = '<a href="' . admin_url( 'options-general.php?page=meta-tags-options' ) . '">' . __('Set up tags','meta-tags') . '</a>';				
				array_unshift( $links, $settings_link );
			}
			return $links;
		}
		add_filter( 'plugin_action_links', 'dp_metatags_actions', 10, 2 );
	
	
	

	//admin notices
		require_once('inc/admin-notices.php');
	
		
	
	
	//add meta tags to settings menu in admin		
		require_once('inc/admin-index-settings.php');


			
	
	//add metabox in page/post/woo product editor
		function dp_metatags_metabox(){	
			if(function_exists('add_meta_box')){		
				add_meta_box( 'dp-metatags', esc_html__('Meta Tag Editor','meta-tags'), 'dp_metatags_editor', 'page', 'normal' );						
				add_meta_box( 'dp-metatags', esc_html__('Meta Tag Editor','meta-tags'), 'dp_metatags_editor', 'post', 'normal' );					
				if(class_exists('WooCommerce')){	
					add_meta_box( 'dp-metatags', esc_html__('Meta Tag Editor','meta-tags'), 'dp_metatags_editor', 'product', 'normal', 'low' );	
				}				
			}
		}		
		add_action('admin_menu', 'dp_metatags_metabox');
	
	


	//meta tag editor metabox
		require_once('inc/admin-meta-tags-editor.php');

	
	
	//frontend echo
		require_once('inc/frontend-output.php');