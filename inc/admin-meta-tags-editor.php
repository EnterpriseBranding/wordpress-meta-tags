<?php


    //meta tag editor metabox
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
                            'id'            => 'dp-metatags-general-description',
                            'var'           => 'dp_metatags_general_description',
                            'title'         => __('Description','meta-tags'),
                            'description'   => __('This text will appear below your title in Google search results. Describe this page/post in 155 maximum characters. Note: Google will not consider this in its search ranking algorithm.','meta-tags')
                        ), array(
                            'id'            => 'dp-metatags-general-keywords',
                            'var'           => 'dp_metatags_general_keywords',
                            'title'         => __('Keywords','meta-tags'),
                            'description'   => __('Improper or spammy use most likely will hurt you with some search engines. Google will not consider this in its search ranking algorithm, so it\'s not really recommended.','meta-tags')
                        ), array(
                            'id'            => 'dp-metatags-general-title',
                            'var'           => 'dp_metatags_general_title',
                            'title'         => __('Page title','meta-tags'),
                            'description'   => __('Make page titles as keyword-relevant as possible and up to 70 characters. Longer titles are oftentimes chopped down or rewritten algorithmically.','meta-tags')
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
                            'id'            => 'dp-metatags-og-title',
                            'var'           => 'dp_metatags_og_title',
                            'title'         => __('Title','meta-tags'),
                            'description'   => __('The headline.','meta-tags')
                        ), array(
                            'id'            => 'dp-metatags-og-type',
                            'var'           => 'dp_metatags_og_type',
                            'title'         => __('Type','meta-tags'),
                            'description'   => __('Article, website or other. Here is a list of all available types: <a href="http://ogp.me/#types" target="_blank">http://ogp.me/#types</a>','meta-tags')
                        ), array(
                            'id'            => 'dp-metatags-og-audio',
                            'var'           => 'dp_metatags_og_audio',
                            'title'         => __('Audio','meta-tags'),
                            'description'   => __('URL to your content\'s audio.','meta-tags')
                        ), array(
                            'id'            => 'dp-metatags-og-image',
                            'var'           => 'dp_metatags_og_image',
                            'title'         => __('Image','meta-tags'),
                            'description'   => __('URL to your content\'s image. It should be at least 600x315 pixels, but 1200x630 or larger is preferred (up to 5MB). Stay close to a 1.91:1 aspect ratio to avoid cropping.','meta-tags')
                        ), array(
                            'id'            => 'dp-metatags-og-video',
                            'var'           => 'dp_metatags_og_video',
                            'title'         => __('Video','meta-tags'),
                            'description'   => __('URL to your content\'s video. Videos need an og:image tag to be displayed in News Feed.','meta-tags')
                        ), array(
                            'id'            => 'dp-metatags-og-url',
                            'var'           => 'dp_metatags_og_url',
                            'title'         => __('URL','meta-tags'),
                            'description'   => __('The URL of your page. Use the canonical URL for this tag (the search engine friendly URL that you want the search engines to treat as authoritative).','meta-tags')
                        ), array(
                            'id'            => 'dp-metatags-og-description',
                            'var'           => 'dp_metatags_og_description',
                            'title'         => __('Description','meta-tags'),
                            'description'   => __('A short summary about the content.','meta-tags')
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
                            'id'            => 'dp-metatags-twitter-card',
                            'var'           => 'dp_metatags_twitter_card',
                            'title'         => __('Card','meta-tags'),
                            'description'   => __('This is the card type. Your options are summary, photo or player. Twitter will default to "summary" if it is not specified.','meta-tags')
                        ), array(
                            'id'            => 'dp-metatags-twitter-title',
                            'var'           => 'dp_metatags_twitter_title',
                            'title'         => __('Title','meta-tags'),
                            'description'   => __('A concise title for the related content.','meta-tags')
                        ), array(
                            'id'            => 'dp-metatags-twitter-description',
                            'var'           => 'dp_metatags_twitter_description',
                            'title'         => __('Description','meta-tags'),
                            'description'   => __('Summary of content.','meta-tags')
                        ), array(
                            'id'            => 'dp-metatags-twitter-image',
                            'var'           => 'dp_metatags_twitter_image',
                            'title'         => __('Image','meta-tags'),
                            'description'   => __('Image representing the content. Use aspect ratio of 1:1 with minimum dimensions of 144x144 or maximum of 4096x4096 pixels. Images must be less than 5MB in size.','meta-tags')
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