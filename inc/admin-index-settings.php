<?php


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
                    <h2 class="title">'.esc_html__('Facebook\'s OpenGraph meta tags','meta-tags').'</h2>
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
                    <h2 class="title">'.esc_html__('Twitter cards','meta-tags').'</h2>
                    
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