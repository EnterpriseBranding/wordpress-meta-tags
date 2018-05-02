<?php
/**
 * Plugin Name: Meta Tags
 * Plugin URI: https://wordpress.org/plugins/meta-tags/
 * Description: A super simple plugin to edit meta tags on all your posts and pages for SEO. Facebook's OpenGraph and Twitter Cards are included.
 * Author: DivPusher - WordPress Theme Club
 * Author URI: https://divpusher.com/
 * Version: 1.2.6
 * Text Domain: meta-tags
 * Tags: meta tags, seo, edit meta tags, search engine optimization, facebook open graph, twitter cards, schema.org
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0
 */



	//better safe than sorry
		if (!function_exists('add_action')){			
			exit('Hi there! I\'m just a plugin, not much I can do when called directly.');
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
	
	
	
	//add notice to theme page
		function dp_metatags_notice() {				
			global $pagenow;			
			
			if($pagenow == 'theme-install.php' || $pagenow == 'themes.php'){
				echo '<div class="notice notice-success is-dismissible">
					<p>'.__('Need some nice, free or premium theme? <a href="https://divpusher.com" target="_blank">
						Have a look around here!','meta-tags').'</a></p>			
				</div>';			
			}
		}
		add_action( 'admin_notices', 'dp_metatags_notice' );
		
		
		
	//add notice on plugin activation				
		register_activation_hook( __FILE__, 'dp_metatags_notice_activation_hook' );		 
		function dp_metatags_notice_activation_hook() {
			set_transient( 'dp-metatags-activation-notice', true, 5 );
		}
		 
		 
		add_action( 'admin_notices', 'dp_metatags_activation_notice' );		 
		function dp_metatags_activation_notice(){			
			if( get_transient( 'dp-metatags-activation-notice' ) ){
				
				echo '<div class="updated notice is-dismissible">
					<p>'.__('Thank you for using our plugin. In case you need some nice, free or premium theme, 
						have a <a href="https://divpusher.com" target="_blank">look around here!','meta-tags').'</a></p>			
				</div>';
				
				delete_transient( 'dp-metatags-activation-notice' );
			}
		}	
		
		
		
	
	
	//add meta tags to settings menu in admin		
		function dp_metatags_admin_option(){		
				
			 add_submenu_page(
				'options-general.php',
				esc_html__( 'Meta tags', 'meta-tags' ),
				esc_html__( 'Meta tags', 'meta-tags' ),
				'manage_options',
				'meta-tags-options',
				'dp_metatags_settings_page'
			);
			
		}
		add_action( 'admin_menu', 'dp_metatags_admin_option' );
		
		
		function dp_metatags_settings_page(){			
		
			//check user permission
				if(!current_user_can('administrator')){				
					return;
				}


			//save changes
				if(!empty($_POST['submit']) && check_admin_referer('dp_metatags_save_settings', 'dp-metatags-nonce')){
					
					
					$textFieldsToSave = array(
						'dp-metatags-general-description', 'dp-metatags-general-keywords', 'dp-metatags-general-title', 
						'dp-metatags-og-title', 'dp-metatags-og-type', 'dp-metatags-og-description', 'dp-metatags-twitter-card',
						'dp-metatags-twitter-title', 'dp-metatags-twitter-description'
					);
					$urlFieldsToSave = array(
						'dp-metatags-og-audio', 'dp-metatags-og-image', 'dp-metatags-og-video', 'dp-metatags-og-url', 
						'dp-metatags-twitter-image'
					);

					foreach($textFieldsToSave as $field){

						if(!empty($_POST[$field])){
							update_option($field, sanitize_text_field($_POST[$field]));
						}

					}

					foreach($urlFieldsToSave as $field){

						if(!empty($_POST[$field])){
							update_option($field, esc_url($_POST[$field]));
						}

					}



					$allowed_html = array(
						'meta' => array(
							'name' => array(),
							'property' => array(),
							'content' => array(),						
							'http-equiv' => array()
						)
					);

					if(!empty($_POST['dp-metatags-custom'])){ 
						update_option('dp-metatags-custom',wp_kses( $_POST['dp-metatags-custom'], $allowed_html )); 
					}
					
				}

		

			$dp_metatags_general_description = get_option('dp-metatags-general-description');
			$dp_metatags_general_keywords = get_option('dp-metatags-general-keywords');
			$dp_metatags_general_title = get_option('dp-metatags-general-title');
			
			$dp_metatags_og_title = get_option('dp-metatags-og-title');
			$dp_metatags_og_type = get_option('dp-metatags-og-type');
			$dp_metatags_og_audio = get_option('dp-metatags-og-audio');
			$dp_metatags_og_image = get_option('dp-metatags-og-image');
			$dp_metatags_og_video = get_option('dp-metatags-og-video');
			$dp_metatags_og_url = get_option('dp-metatags-og-url');
			$dp_metatags_og_description = get_option('dp-metatags-og-description');
			
			$dp_metatags_twitter_card = get_option('dp-metatags-twitter-card');			
			$dp_metatags_twitter_title = get_option('dp-metatags-twitter-title');
			$dp_metatags_twitter_description = get_option('dp-metatags-twitter-description');
			$dp_metatags_twitter_image = get_option('dp-metatags-twitter-image');
			
			$dp_metatags_custom = get_option('dp-metatags-custom');
			
			$page_on_front = get_option('page_on_front');
		

		
			echo '<h1>'.esc_html__('Meta Tags','meta-tags').'</h1>';
			

			if($page_on_front == '0'){
				//if frontpage shows latest posts		

					echo '<p>'.__('It seems the frontpage shows your latest posts (based on <b>Settings - Reading</b>). 
						Here you can set up meta tags for the frontpage.').'<br />'.
					__('For the rest please visit the page/post editor where you can add specific meta tags for each of them in the 
						<b>Meta Tag Editor</b> box.','meta-tags').'</p>
					
					<p>&nbsp;</p>
					
					<form method="post" action="options-general.php?page=meta-tags-options" novalidate="novalidate">';
				
				
				//add nonce
					wp_nonce_field( 'dp_metatags_save_settings', 'dp-metatags-nonce' );
				
				
				//general meta tags
					echo'
					<h2 class="title">'.esc_html__('General meta tags','meta-tags').'</h2>
					
					<div style="margin-left: 20px;">';

					$fieldsToEcho = array(
						array(
							'id' 			=> 'dp-metatags-general-description',
							'var' 			=> 'dp_metatags_general_description',
							'title'			=> __('Description','meta-tags'),
							'description'	=> __('This text will appear below your title in Google search results. Describe this page/post in 155 maximum characters. Note: Google will not consider this in its search ranking algorithm.','meta-tags')
						), array(
							'id' 			=> 'dp-metatags-general-keywords',
							'var' 			=> 'dp_metatags_general_keywords',
							'title'			=> __('Keywords','meta-tags'),
							'description'	=> __('Improper or spammy use most likely will hurt you with some search engines. Google will not consider this in its search ranking algorithm, so it\'s not really recommended.','meta-tags')
						), array(
							'id' 			=> 'dp-metatags-general-title',
							'var' 			=> 'dp_metatags_general_title',
							'title'			=> __('Page title','meta-tags'),
							'description'	=> __('Make page titles as keyword-relevant as possible and up to 70 characters. Longer titles are oftentimes chopped down or rewritten algorithmically.','meta-tags')
						)
					);

					foreach($fieldsToEcho as $field){
						$fieldValue = ${$field['var']};
						echo '
						<p><label for="'.$field['id'].'"><b>'.$field['title'].'</b></label><br /><span class="description">'.
						$field['description'].'</span></p>
						<p><input type="text" id="'.$field['id'].'" name="'.$field['id'].'" class="regular-text" value="'.
						(!empty($fieldValue) ? esc_attr($fieldValue) : '').'" /></p>'; 
					}
						
						
					echo '
					</div>
					
					<p>&nbsp;</p>
					<hr />
					';
			
			
				//Facebook's OpenGraph meta tags
					echo '
					<h2 class="title">'.esc_html__('Facebook\'s OpenGraph meta tags','meta-tags').'</h2>
					<p>'.esc_html__('Open Graph has become very popular, so most social networks default to Open Graph if no other meta tags are present.','meta-tags').'</p>
					
					<div style="margin-left: 20px;">';

					$fieldsToEcho = array(
						array(
							'id' 			=> 'dp-metatags-og-title',
							'var' 			=> 'dp_metatags_og_title',
							'title'			=> __('Title','meta-tags'),
							'description'	=> __('The headline.','meta-tags')
						), array(
							'id' 			=> 'dp-metatags-og-type',
							'var' 			=> 'dp_metatags_og_type',
							'title'			=> __('Type','meta-tags'),
							'description'	=> __('Article, website or other. Here is a list of all available types: <a href="http://ogp.me/#types" target="_blank">http://ogp.me/#types</a>','meta-tags')
						), array(
							'id' 			=> 'dp-metatags-og-audio',
							'var' 			=> 'dp_metatags_og_audio',
							'title'			=> __('Audio','meta-tags'),
							'description'	=> __('URL to your content\'s audio.','meta-tags')
						), array(
							'id' 			=> 'dp-metatags-og-image',
							'var' 			=> 'dp_metatags_og_image',
							'title'			=> __('Image','meta-tags'),
							'description'	=> __('URL to your content\'s image. It should be at least 600x315 pixels, but 1200x630 or larger is preferred (up to 5MB). Stay close to a 1.91:1 aspect ratio to avoid cropping.','meta-tags')
						), array(
							'id' 			=> 'dp-metatags-og-video',
							'var' 			=> 'dp_metatags_og_video',
							'title'			=> __('Video','meta-tags'),
							'description'	=> __('URL to your content\'s video. Videos need an og:image tag to be displayed in News Feed.','meta-tags')
						), array(
							'id' 			=> 'dp-metatags-og-url',
							'var' 			=> 'dp_metatags_og_url',
							'title'			=> __('URL','meta-tags'),
							'description'	=> __('The URL of your page. Use the canonical URL for this tag (the search engine friendly URL that you want the search engines to treat as authoritative).','meta-tags')
						), array(
							'id' 			=> 'dp-metatags-og-description',
							'var' 			=> 'dp_metatags_og_description',
							'title'			=> __('Description','meta-tags'),
							'description'	=> __('A short summary about the content.','meta-tags')
						)
					);

					foreach($fieldsToEcho as $field){
						$fieldValue = ${$field['var']};
						echo '
						<p><label for="'.$field['id'].'"><b>'.$field['title'].'</b></label><br /><span class="description">'.
						$field['description'].'</span></p>
						<p><input type="text" id="'.$field['id'].'" name="'.$field['id'].'" class="regular-text" value="'.
						(!empty($fieldValue) ? esc_attr($fieldValue) : '').'" /></p>'; 
					}
						

					echo '
					</div>
					
					<p>&nbsp;</p>
					<hr />
					';
				
				
				//Twitter meta tags
					echo '
					<h2 class="title">'.esc_html__('Twitter cards','meta-tags').'</h2>
					
					<div style="margin-left: 20px;">';

					$fieldsToEcho = array(
						array(
							'id' 			=> 'dp-metatags-twitter-card',
							'var' 			=> 'dp_metatags_twitter_card',
							'title'			=> __('Card','meta-tags'),
							'description'	=> __('This is the card type. Your options are summary, photo or player. Twitter will default to "summary" if it is not specified.','meta-tags')
						), array(
							'id' 			=> 'dp-metatags-twitter-title',
							'var' 			=> 'dp_metatags_twitter_title',
							'title'			=> __('Title','meta-tags'),
							'description'	=> __('A concise title for the related content.','meta-tags')
						), array(
							'id' 			=> 'dp-metatags-twitter-description',
							'var' 			=> 'dp_metatags_twitter_description',
							'title'			=> __('Description','meta-tags'),
							'description'	=> __('Summary of content.','meta-tags')
						), array(
							'id' 			=> 'dp-metatags-twitter-image',
							'var' 			=> 'dp_metatags_twitter_image',
							'title'			=> __('Image','meta-tags'),
							'description'	=> __('Image representing the content. Use aspect ratio of 1:1 with minimum dimensions of 144x144 or maximum of 4096x4096 pixels. Images must be less than 5MB in size.','meta-tags')
						)
					);

					foreach($fieldsToEcho as $field){
						$fieldValue = ${$field['var']};
						echo '
						<p><label for="'.$field['id'].'"><b>'.$field['title'].'</b></label><br /><span class="description">'.
						$field['description'].'</span></p>
						<p><input type="text" id="'.$field['id'].'" name="'.$field['id'].'" class="regular-text" value="'.
						(!empty($fieldValue) ? esc_attr($fieldValue) : '').'" /></p>'; 
					}


					echo '	
					</div>
					
					<p>&nbsp;</p>
					<hr />';
							
				
				//custom meta tags 			
					echo '
					<h2 class="title">'.esc_html__('Custom meta tags','meta-tags').'</h2>			
					
					<div style="margin-left: 20px;">					
						<textarea id="dp-metatags-custom" name="dp-metatags-custom" class="regular-text code">'.(!empty($dp_metatags_custom) ? esc_textarea($dp_metatags_custom) : '').'</textarea>
					</div>';
					


				//save changes
					echo '
					<p class="submit"><input name="submit" id="submit" class="button button-primary" value="'.__('Save Changes','meta-tags').'" type="submit"></p>
					</form>
					';
				
			}else{
				//frontpage shows a specific page				
				echo '<p>'.__('Go to your page/post editor and you will find a new <b>Meta Tag Editor</b> box where you can add specific meta tags for each of them.','meta-tags').'</p>';
			}
			
			
		}
	
		
	
	
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
	
	


	//meta tag editor 
		function dp_metatags_editor(){			
			global $post;
		
			//load saved values
			$dp_metatags_general_description = get_post_meta($post->ID, 'dp-metatags-general-description', true);
			$dp_metatags_general_keywords = get_post_meta($post->ID, 'dp-metatags-general-keywords', true);
			$dp_metatags_general_title = get_post_meta($post->ID, 'dp-metatags-general-title', true);
			$dp_metatags_og_title = get_post_meta($post->ID, 'dp-metatags-og-title', true);
			$dp_metatags_og_type = get_post_meta($post->ID, 'dp-metatags-og-type', true);
			$dp_metatags_og_audio = get_post_meta($post->ID, 'dp-metatags-og-audio', true);
			$dp_metatags_og_image = get_post_meta($post->ID, 'dp-metatags-og-image', true);
			$dp_metatags_og_video = get_post_meta($post->ID, 'dp-metatags-og-video', true);
			$dp_metatags_og_url = get_post_meta($post->ID, 'dp-metatags-og-url', true);
			$dp_metatags_og_description = get_post_meta($post->ID, 'dp-metatags-og-description', true);
			$dp_metatags_twitter_card = get_post_meta($post->ID, 'dp-metatags-twitter-card', true);			
			$dp_metatags_twitter_title = get_post_meta($post->ID, 'dp-metatags-twitter-title', true);
			$dp_metatags_twitter_description = get_post_meta($post->ID, 'dp-metatags-twitter-description', true);
			$dp_metatags_twitter_image = get_post_meta($post->ID, 'dp-metatags-twitter-image', true);
			$dp_metatags_custom = get_post_meta($post->ID, 'dp-metatags-custom', true);
		
		
			//general meta tags
				echo'
				<p><b>'.esc_html__('General meta tags','meta-tags').'</b></p>
				
				<div style="margin-left: 20px;">';

				$fieldsToEcho = array(
						array(
							'id' 			=> 'dp-metatags-general-description',
							'var' 			=> 'dp_metatags_general_description',
							'title'			=> __('Description','meta-tags'),
							'description'	=> __('This text will appear below your title in Google search results. Describe this page/post in 155 maximum characters. Note: Google will not consider this in its search ranking algorithm.','meta-tags')
						), array(
							'id' 			=> 'dp-metatags-general-keywords',
							'var' 			=> 'dp_metatags_general_keywords',
							'title'			=> __('Keywords','meta-tags'),
							'description'	=> __('Improper or spammy use most likely will hurt you with some search engines. Google will not consider this in its search ranking algorithm, so it\'s not really recommended.','meta-tags')
						), array(
							'id' 			=> 'dp-metatags-general-title',
							'var' 			=> 'dp_metatags_general_title',
							'title'			=> __('Page title','meta-tags'),
							'description'	=> __('Make page titles as keyword-relevant as possible and up to 70 characters. Longer titles are oftentimes chopped down or rewritten algorithmically.','meta-tags')
						)
					);

					foreach($fieldsToEcho as $field){
						$fieldValue = ${$field['var']};
						echo '
						<p><label for="'.$field['id'].'"><b>'.$field['title'].'</b></label><br /><span class="description">'.
						$field['description'].'</span></p>
						<p><input type="text" id="'.$field['id'].'" name="'.$field['id'].'" class="regular-text" value="'.
						(!empty($fieldValue) ? esc_attr($fieldValue) : '').'" /></p>'; 
					}

				echo '
				</div>
				
				<p>&nbsp;</p>
				<hr />
				';
			
			
			//Facebook's OpenGraph meta tags
				echo '
				<p><b>'.esc_html__('Facebook\'s OpenGraph meta tags','meta-tags').'</b></p>
				<p>'.esc_html__('Open Graph has become very popular, so most social networks default to Open Graph if no other meta tags are present.','meta-tags').'</p>
				
				<div style="margin-left: 20px;">';


				$fieldsToEcho = array(
						array(
							'id' 			=> 'dp-metatags-og-title',
							'var' 			=> 'dp_metatags_og_title',
							'title'			=> __('Title','meta-tags'),
							'description'	=> __('The headline.','meta-tags')
						), array(
							'id' 			=> 'dp-metatags-og-type',
							'var' 			=> 'dp_metatags_og_type',
							'title'			=> __('Type','meta-tags'),
							'description'	=> __('Article, website or other. Here is a list of all available types: <a href="http://ogp.me/#types" target="_blank">http://ogp.me/#types</a>','meta-tags')
						), array(
							'id' 			=> 'dp-metatags-og-audio',
							'var' 			=> 'dp_metatags_og_audio',
							'title'			=> __('Audio','meta-tags'),
							'description'	=> __('URL to your content\'s audio.','meta-tags')
						), array(
							'id' 			=> 'dp-metatags-og-image',
							'var' 			=> 'dp_metatags_og_image',
							'title'			=> __('Image','meta-tags'),
							'description'	=> __('URL to your content\'s image. It should be at least 600x315 pixels, but 1200x630 or larger is preferred (up to 5MB). Stay close to a 1.91:1 aspect ratio to avoid cropping.','meta-tags')
						), array(
							'id' 			=> 'dp-metatags-og-video',
							'var' 			=> 'dp_metatags_og_video',
							'title'			=> __('Video','meta-tags'),
							'description'	=> __('URL to your content\'s video. Videos need an og:image tag to be displayed in News Feed.','meta-tags')
						), array(
							'id' 			=> 'dp-metatags-og-url',
							'var' 			=> 'dp_metatags_og_url',
							'title'			=> __('URL','meta-tags'),
							'description'	=> __('The URL of your page. Use the canonical URL for this tag (the search engine friendly URL that you want the search engines to treat as authoritative).','meta-tags')
						), array(
							'id' 			=> 'dp-metatags-og-description',
							'var' 			=> 'dp_metatags_og_description',
							'title'			=> __('Description','meta-tags'),
							'description'	=> __('A short summary about the content.','meta-tags')
						)
					);

					foreach($fieldsToEcho as $field){
						$fieldValue = ${$field['var']};
						echo '
						<p><label for="'.$field['id'].'"><b>'.$field['title'].'</b></label><br /><span class="description">'.
						$field['description'].'</span></p>
						<p><input type="text" id="'.$field['id'].'" name="'.$field['id'].'" class="regular-text" value="'.
						(!empty($fieldValue) ? esc_attr($fieldValue) : '').'" /></p>'; 
					}


				echo '	
				</div>
				
				<p>&nbsp;</p>
				<hr />
				';
			
			
			//Twitter meta tags
				echo '
				<p><b>'.esc_html__('Twitter cards','meta-tags').'</b></p>
				
				<div style="margin-left: 20px;">';					

					$fieldsToEcho = array(
						array(
							'id' 			=> 'dp-metatags-twitter-card',
							'var' 			=> 'dp_metatags_twitter_card',
							'title'			=> __('Card','meta-tags'),
							'description'	=> __('This is the card type. Your options are summary, photo or player. Twitter will default to "summary" if it is not specified.','meta-tags')
						), array(
							'id' 			=> 'dp-metatags-twitter-title',
							'var' 			=> 'dp_metatags_twitter_title',
							'title'			=> __('Title','meta-tags'),
							'description'	=> __('A concise title for the related content.','meta-tags')
						), array(
							'id' 			=> 'dp-metatags-twitter-description',
							'var' 			=> 'dp_metatags_twitter_description',
							'title'			=> __('Description','meta-tags'),
							'description'	=> __('Summary of content.','meta-tags')
						), array(
							'id' 			=> 'dp-metatags-twitter-image',
							'var' 			=> 'dp_metatags_twitter_image',
							'title'			=> __('Image','meta-tags'),
							'description'	=> __('Image representing the content. Use aspect ratio of 1:1 with minimum dimensions of 144x144 or maximum of 4096x4096 pixels. Images must be less than 5MB in size.','meta-tags')
						)
					);

					foreach($fieldsToEcho as $field){
						$fieldValue = ${$field['var']};
						echo '
						<p><label for="'.$field['id'].'"><b>'.$field['title'].'</b></label><br /><span class="description">'.
						$field['description'].'</span></p>
						<p><input type="text" id="'.$field['id'].'" name="'.$field['id'].'" class="regular-text" value="'.
						(!empty($fieldValue) ? esc_attr($fieldValue) : '').'" /></p>'; 
					}

				echo '	
				</div>
				
				<p>&nbsp;</p>
				<hr />
				';
						
			
			//Custom meta tags 			
				echo '
				<p><b>'.esc_html__('Custom meta tags','meta-tags').'</b></p>			
				
				<div style="margin-left: 20px;">					
					<textarea id="dp-metatags-custom" name="dp-metatags-custom" class="regular-text code">'.(!empty($dp_metatags_custom) ? esc_textarea($dp_metatags_custom) : '').'</textarea>
				</div>
				';
				
			
			echo '<p>&nbsp;</p>';
		}
		
		

		
	//save tags
		function dp_metatags_save($post_id){						
			
			if(empty($post_id)){
				return;
			}


			//check post type
			$post_type = get_post_type($post_id);
			if('page' != $post_type && 'post' != $post_type && 'product' != $post_type){
				return;
			}


			$textFieldsToSave = array(
				'dp-metatags-general-description', 'dp-metatags-general-keywords', 'dp-metatags-general-title', 
				'dp-metatags-og-title', 'dp-metatags-og-type', 'dp-metatags-og-description', 'dp-metatags-twitter-card',
				'dp-metatags-twitter-title', 'dp-metatags-twitter-description'
			);
			$urlFieldsToSave = array(
				'dp-metatags-og-audio', 'dp-metatags-og-image', 'dp-metatags-og-video', 'dp-metatags-og-url', 
				'dp-metatags-twitter-image'
			);

			foreach($textFieldsToSave as $field){

				if(!empty($_POST[$field])){
					update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
				}elseif(!empty($post_id)){
					delete_post_meta($post_id, $field); 
				}

			}

			foreach($urlFieldsToSave as $field){

				if(!empty($_POST[$field])){
					update_post_meta($post_id, $field, esc_url($_POST[$field]));
				}elseif(!empty($post_id)){
					delete_post_meta($post_id, $field); 
				}

			}
			
			
			
			if(!empty($_POST['dp-metatags-custom'])){	
				$allowed_html = array(
					'meta' => array(
						'name' => array(),
						'property' => array(),
						'content' => array(),						
						'http-equiv' => array()
					)
				);
				
				update_post_meta($post_id, 'dp-metatags-custom', wp_kses( $_POST['dp-metatags-custom'], $allowed_html ) );

			}elseif(!empty($post_id)){								
				delete_post_meta($post_id,'dp-metatags-custom'); 
			}
						
			
		}
		add_action('save_post', 'dp_metatags_save');
	


	
	
	//frontend echo
		function dp_metatags_echo(){
			global $post;
			
			$page_on_front = get_option('page_on_front');
		
		
			if($page_on_front == 0 && is_front_page()){
			//latest posts on front page

				$dp_metatags_general_description = get_option('dp-metatags-general-description');
				$dp_metatags_general_keywords = get_option('dp-metatags-general-keywords');
				$dp_metatags_general_title = get_option('dp-metatags-general-title');
				
				$dp_metatags_og_title = get_option('dp-metatags-og-title');
				$dp_metatags_og_type = get_option('dp-metatags-og-type');
				$dp_metatags_og_audio = get_option('dp-metatags-og-audio');
				$dp_metatags_og_image = get_option('dp-metatags-og-image');
				$dp_metatags_og_video = get_option('dp-metatags-og-video');
				$dp_metatags_og_url = get_option('dp-metatags-og-url');
				$dp_metatags_og_description = get_option('dp-metatags-og-description');
				
				$dp_metatags_twitter_card = get_option('dp-metatags-twitter-card');			
				$dp_metatags_twitter_title = get_option('dp-metatags-twitter-title');
				$dp_metatags_twitter_description = get_option('dp-metatags-twitter-description');
				$dp_metatags_twitter_image = get_option('dp-metatags-twitter-image');
				
				$dp_metatags_custom = get_option('dp-metatags-custom');

			}else{
			//load actual page settings
				
				//woocommerce hack to show proper ID
					if(class_exists('WooCommerce')){
						if(is_shop()){
							$post->ID = get_option('woocommerce_shop_page_id');
							
						}elseif(is_cart()){
							$post->ID = get_option('woocommerce_cart_page_id');
							
						}elseif(is_checkout()){
							$post->ID = get_option('woocommerce_checkout_page_id');
							
						}elseif(is_account_page()){
							$post->ID = get_option('woocommerce_myaccount_page_id');
						}
						
					}			


				//check if current page is set as Posts page in Settings / Reading				
					if(is_home()){
						$post->ID = get_option('page_for_posts');
					} 

			
				if(!empty($post->ID)){
					$dp_metatags_general_description = get_post_meta($post->ID, 'dp-metatags-general-description', true);
					$dp_metatags_general_keywords = get_post_meta($post->ID, 'dp-metatags-general-keywords', true);
					$dp_metatags_general_title = get_post_meta($post->ID, 'dp-metatags-general-title', true);
					
					$dp_metatags_og_title = get_post_meta($post->ID, 'dp-metatags-og-title', true);
					$dp_metatags_og_type = get_post_meta($post->ID, 'dp-metatags-og-type', true);
					$dp_metatags_og_audio = get_post_meta($post->ID, 'dp-metatags-og-audio', true);
					$dp_metatags_og_image = get_post_meta($post->ID, 'dp-metatags-og-image', true);
					$dp_metatags_og_video = get_post_meta($post->ID, 'dp-metatags-og-video', true);
					$dp_metatags_og_url = get_post_meta($post->ID, 'dp-metatags-og-url', true);
					$dp_metatags_og_description = get_post_meta($post->ID, 'dp-metatags-og-description', true);
					
					$dp_metatags_twitter_card = get_post_meta($post->ID, 'dp-metatags-twitter-card', true);			
					$dp_metatags_twitter_title = get_post_meta($post->ID, 'dp-metatags-twitter-title', true);
					$dp_metatags_twitter_description = get_post_meta($post->ID, 'dp-metatags-twitter-description', true);
					$dp_metatags_twitter_image = get_post_meta($post->ID, 'dp-metatags-twitter-image', true);
					
					$dp_metatags_custom = get_post_meta($post->ID, 'dp-metatags-custom', true);
				}
			}


			$output = '';

			if(!empty($dp_metatags_general_description)){
				$output .= '	<meta name="description" content="'.esc_attr($dp_metatags_general_description).'" />' . PHP_EOL;
			}
			
			if(!empty($dp_metatags_general_keywords)){
				$output .= '	<meta name="keywords" content="'.esc_attr($dp_metatags_general_keywords).'" />' . PHP_EOL;
			}

			if(!empty($dp_metatags_general_title)){ 					
				add_filter('pre_get_document_title', 'dp_metatags_title');
				function dp_metatags_title($title) {			
					global $post;
					$dp_metatags_general_title = get_post_meta($post->ID, 'dp-metatags-general-title', true);
					return esc_html($dp_metatags_general_title);						
				}				
			}
			
			if(!empty($dp_metatags_og_title)){
				$output .= '	<meta property="og:title" content="'.esc_attr($dp_metatags_og_title).'" />' . PHP_EOL;
			}
			
			if(!empty($dp_metatags_og_type)){
				$output .= '	<meta property="og:type" content="'.esc_attr($dp_metatags_og_type).'" />' . PHP_EOL;
			}
			
			if(!empty($dp_metatags_og_audio)){
				$output .= '	<meta property="og:audio" content="'.esc_attr($dp_metatags_og_audio).'" />' . PHP_EOL;
			}
			
			if(!empty($dp_metatags_og_image)){
				$output .= '	<meta property="og:image" content="'.esc_attr($dp_metatags_og_image).'" />' . PHP_EOL;
			}
			
			if(!empty($dp_metatags_og_video)){
				$output .= '	<meta property="og:video" content="'.esc_attr($dp_metatags_og_video).'" />' . PHP_EOL;
			}
			
			if(!empty($dp_metatags_og_url)){
				$output .= '	<meta property="og:url" content="'.esc_attr($dp_metatags_og_url).'" />' . PHP_EOL;
			}
			
			if(!empty($dp_metatags_og_description)){
				$output .= '	<meta property="og:description" content="'.esc_attr($dp_metatags_og_description).'" />' . PHP_EOL;
			}
			
			if(!empty($dp_metatags_twitter_card)){
				$output .= '	<meta name="twitter:card" content="'.esc_attr($dp_metatags_twitter_card).'" />' . PHP_EOL;
			}
			
			if(!empty($dp_metatags_twitter_title)){
				$output .= '	<meta name="twitter:title" content="'.esc_attr($dp_metatags_twitter_title).'" />' . PHP_EOL;
			}
			
			if(!empty($dp_metatags_twitter_description)){
				$output .= '	<meta name="twitter:description" content="'.esc_attr($dp_metatags_twitter_description).'" />' . PHP_EOL;
			}
			
			if(!empty($dp_metatags_twitter_image)){
				$output .= '	<meta name="twitter:image" content="'.esc_attr($dp_metatags_twitter_image).'" />' . PHP_EOL;
			}				

			if(!empty($dp_metatags_custom)){ 
				$allowed_html = array(
					'meta' => array(
						'name' => array(),
						'property' => array(),
						'content' => array(),						
						'http-equiv' => array()
					)
				);
				
				$output .= '	' . wp_kses( $dp_metatags_custom, $allowed_html ) . PHP_EOL;					
			}



			if(!empty($output)){

				echo PHP_EOL . '	<!-- META TAGS PLUGIN START -->' . PHP_EOL;
				echo $output;
				echo '	<!-- META TAGS PLUGIN END -->' . PHP_EOL;

			}			
				
		}
		add_action('wp_head', 'dp_metatags_echo', 0);
			
?>