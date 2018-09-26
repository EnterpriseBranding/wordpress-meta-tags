<?php


defined('ABSPATH') || die();


class DPMT_Admin {
    

    // add actions and filters
    public function __construct(){
        
        register_activation_hook( DPMT_PLUGIN_FILE, array( $this, 'on_activation' ) );
        add_action( 'upgrader_process_complete', array( $this, 'on_update' ) );
        add_action( 'admin_init', array( $this, 'includes' ) );
        add_action( 'admin_init', array( $this, 'set_notices' ) );        
        add_filter( 'plugin_action_links_' . DPMT_PLUGIN_FILE, array( $this, 'add_action_link' ) );
        add_action( 'admin_menu', array( $this, 'add_editor_to_settings') );
        add_action( 'admin_enqueue_scripts', array( $this, 'add_css_js' ) );
                    
    }



    // include all the classes and functions we need
    public function includes(){
        
        include_once dirname( plugin_dir_path( __FILE__ ) ) . '/dpmt-meta-tag-list.php';  

    }



    // things to do when plugin is activated
    public function on_activation(){

        set_transient( 'dmpt_activation_notice', 1 );

    }



    // things to do when plugin is update
    public function on_update(){

        set_transient( 'dmpt_update_notice', 1 );

    }


    
    // add action link below the plugin on plugins page
    public function add_action_link( $links ) {

        $new = '<a href="' . admin_url( 'options-general.php?page=dpmt-settings' ) . '">' . 
            esc_attr__('Set up tags', 'dp-meta-tags') . '</a>';               
        
        array_unshift( $links, $new );
        
        return $links;

    }



    // add  meta tag editor link settings menu
    public function add_editor_to_settings(){

        add_submenu_page(
            'options-general.php',
            esc_html__( 'Meta tags', 'dp-meta-tags' ),
            esc_html__( 'Meta tags', 'dp-meta-tags' ),
            'manage_options',
            'dpmt-settings',
            array( $this, 'meta_tag_editor_page' )
        );

    }



    // display meta tag editor page
    public function meta_tag_editor_page(){

        include_once 'views/html-tag-editor.php';

    }



    // add notices
    public function set_notices(){
        
        add_action( 'admin_notices', function() {
            
            // on plugin activation
            if( get_transient( 'dmpt_activation_notice' ) ){

                echo '<div class="notice notice-info is-dismissible"><p>
                Thank you for using our plugin. Visit <b>Settings / Meta tags</b> to set up all the tags.
                </p></div>';
                
                delete_transient( 'dmpt_activation_notice' );

            }            


            // on plugin update
            if( get_transient( 'dmpt_update_notice' ) ){

                echo '<div class="notice notice-info is-dismissible"><p>
                New interface! Visit <b>Settings / Meta tags</b> to edit all of them in one table!
                </p></div>';
                
                delete_transient( 'dmpt_update_notice' );

            }


            // on theme page
            $screen = get_current_screen()->parent_file;
            $user_id = get_current_user_id();
            if( $screen == 'themes.php' && ! get_user_meta( $user_id, 'dpmt_ad_dismissed' ) ){

                echo '<div class="notice notice-info"><p>
                Need some nice, free or premium theme? <a href="https://divpusher.com" target="_blank">Have a look around here!</a>
                <span class="dpmt-dismiss-forever"><a href="?dpmt_ad_dismissed=1"><i class="dashicons dashicons-dismiss"></i> Dismiss forever</span></a>
                </p></div>';

            }

        });



        // dismiss notice
        if ( isset($_GET['dpmt_ad_dismissed']) ){
            
            $user_id = get_current_user_id();
            add_user_meta( $user_id, 'dpmt_ad_dismissed', 'true', true );

        }
        
    }



    // enqueue css and js files for admin
    public function add_css_js($hook){

        wp_enqueue_style( 'dpmt_admin_css', plugins_url('assets/css/admin.css', DPMT_PLUGIN_FILE) );
        wp_enqueue_script( 'dpmt_admin_js', plugins_url('assets/js/admin.js', DPMT_PLUGIN_FILE), array('jquery') );

    }



}

return new DPMT_Admin();