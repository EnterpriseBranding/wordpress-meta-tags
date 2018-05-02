<?php

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