<?php


defined('ABSPATH') || die();


final class DP_Meta_Tags {

    
    const DPMT_REQUIRED_PHP = '5.6.3';

    const DPMT_REQUIRED_WP = '4.7';
    

    /*
     * @var The one and only instance of the plugin (singleton)
     */
    private static $instance;



    public static function get_instance(){
    
        if ( is_null( self::$instance ) ){
            self::$instance = new self();
        }

        return self::$instance;

    }



    private function __construct(){

        if ( ! $this->check_system() ){
            return;
        }
            

        $this->run();        

    }



    // prevent instance from being cloned, serialized and unserialized (which would create a second instance of it)
    private function __clone(){}

    private function __sleep(){}

    private function __wakeup(){}



    /**
     * Stop and deactivate plugin if current environment is not ideal.
     * This check should always run first, because what if the owner changes 
     * PHP version in CPanel and a visitor loads the site first?
     */ 
    private function check_system(){

        if (
            version_compare(PHP_VERSION, self::DPMT_REQUIRED_PHP, '<') || 
            version_compare(get_bloginfo('version'), self::DPMT_REQUIRED_WP, '<')
        ){

            if ( is_admin() ){          

                require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                deactivate_plugins( DPMT_PLUGIN_FILE );  
                        
                wp_die(
                    sprintf(
                        esc_attr__(
                            'Meta Tags plugin requires at least PHP version %1$s or greater and WordPress %2$s or greater!', 
                            'dp-meta-tags'
                        ),
                        self::DPMT_REQUIRED_PHP, 
                        self::DPMT_REQUIRED_WP
                    ) . 
                    '<br /><br /><a href="plugins.php" class="button">'. esc_attr__('Click here to go back', 'dp-meta-tags') . '</a>'
                );

            }

            return false;

        }


        return true;

    }



    private function run(){

        if ( is_admin() ){
            include_once dirname( __FILE__ ) . '/admin/class-dpmt-admin.php';
        }else{
            include_once dirname( __FILE__ ) . '/class-dpmt-front.php';
        }

    }

}