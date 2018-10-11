<?php
/**
 * Checks environment and starts the plugin.
 * 
 * @since 2.0.0
 */


defined('ABSPATH') || die();


final class DPMT_Meta_Tags {

    /**
     * Minimum PHP version.
     *
     * @var float
     */
    const DPMT_REQUIRED_PHP = '5.6.3';

    /**
     * Minimum WordPress version.
     *
     * @var float
     */
    const DPMT_REQUIRED_WP = '4.7';
    
    /**
     * The one and only instance of the plugin (singleton).
     *
     * @var DPMT_Meta_Tags
     */
    private static $instance;



    /**
     * Returns the instance of the plugin.
     *
     * @return DPMT_Meta_Tags
     */
    public static function get_instance(){
    
        if ( is_null( self::$instance ) ){
            self::$instance = new self();
        }

        return self::$instance;

    }



    /**
     * Checks the system and starts the plugin.
     */
    private function __construct(){

        if ( ! $this->check_system() ){
            return;
        }
            

        $this->run();        

    }



    /**
     * Prevent instance from being cloned, serialized and unserialized (which would create a second instance of it).
     */
    private function __clone(){}

    private function __sleep(){}

    private function __wakeup(){}



    /**
     * Stops and deactivates plugin if current environment is not ideal.
     * This check should always run first, because, for example; what if the owner changes 
     * PHP version in CPanel and a visitor loads the site first?
     *
     * @return bool
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
                        esc_html__(
                            'Meta Tags plugin requires at least PHP version %1$s or greater and WordPress %2$s or greater!', 
                            'dp-meta-tags'
                        ),
                        self::DPMT_REQUIRED_PHP, 
                        self::DPMT_REQUIRED_WP
                    ) . 
                    '<br /><br /><a href="plugins.php" class="button">'. esc_html__('Click here to go back', 'dp-meta-tags') . '</a>'
                );

            }

            return false;

        }


        return true;

    }



    /**
     * Starts the plugin's backend or frontend section.
     */
    private function run(){

        if ( is_admin() ){
            include_once dirname(__FILE__) . '/admin/class-dpmt-admin.php';
        }else{
            include_once dirname(__FILE__) . '/class-dpmt-frontend.php';
        }

    }

}