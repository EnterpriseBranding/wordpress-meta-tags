<?php


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
                $output .= '    <meta name="description" content="'.esc_attr($dp_metatags_general_description).'" />' . PHP_EOL;
            }
            
            if(!empty($dp_metatags_general_keywords)){
                $output .= '    <meta name="keywords" content="'.esc_attr($dp_metatags_general_keywords).'" />' . PHP_EOL;
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
                $output .= '    <meta property="og:title" content="'.esc_attr($dp_metatags_og_title).'" />' . PHP_EOL;
            }
            
            if(!empty($dp_metatags_og_type)){
                $output .= '    <meta property="og:type" content="'.esc_attr($dp_metatags_og_type).'" />' . PHP_EOL;
            }
            
            if(!empty($dp_metatags_og_audio)){
                $output .= '    <meta property="og:audio" content="'.esc_attr($dp_metatags_og_audio).'" />' . PHP_EOL;
            }
            
            if(!empty($dp_metatags_og_image)){
                $output .= '    <meta property="og:image" content="'.esc_attr($dp_metatags_og_image).'" />' . PHP_EOL;
            }
            
            if(!empty($dp_metatags_og_video)){
                $output .= '    <meta property="og:video" content="'.esc_attr($dp_metatags_og_video).'" />' . PHP_EOL;
            }
            
            if(!empty($dp_metatags_og_url)){
                $output .= '    <meta property="og:url" content="'.esc_attr($dp_metatags_og_url).'" />' . PHP_EOL;
            }
            
            if(!empty($dp_metatags_og_description)){
                $output .= '    <meta property="og:description" content="'.esc_attr($dp_metatags_og_description).'" />' . PHP_EOL;
            }
            
            if(!empty($dp_metatags_twitter_card)){
                $output .= '    <meta name="twitter:card" content="'.esc_attr($dp_metatags_twitter_card).'" />' . PHP_EOL;
            }
            
            if(!empty($dp_metatags_twitter_title)){
                $output .= '    <meta name="twitter:title" content="'.esc_attr($dp_metatags_twitter_title).'" />' . PHP_EOL;
            }
            
            if(!empty($dp_metatags_twitter_description)){
                $output .= '    <meta name="twitter:description" content="'.esc_attr($dp_metatags_twitter_description).'" />' . PHP_EOL;
            }
            
            if(!empty($dp_metatags_twitter_image)){
                $output .= '    <meta name="twitter:image" content="'.esc_attr($dp_metatags_twitter_image).'" />' . PHP_EOL;
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
                
                $output .= '    ' . wp_kses( $dp_metatags_custom, $allowed_html ) . PHP_EOL;                    
            }



            if(!empty($output)){

                echo PHP_EOL . '    <!-- META TAGS PLUGIN START -->' . PHP_EOL;
                echo $output;
                echo '  <!-- META TAGS PLUGIN END -->' . PHP_EOL;

            }           
                
        }
        add_action('wp_head', 'dp_metatags_echo', 0);