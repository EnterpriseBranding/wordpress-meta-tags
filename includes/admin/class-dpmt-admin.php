<?php


defined('ABSPATH') || die();


class DPMT_Admin {
    

    // add actions and filters
    public function __construct(){
        
        register_activation_hook( DPMT_PLUGIN_FILE, array( $this, 'on_activation' ) );
        add_action( 'upgrader_process_complete', array( $this, 'on_update' ) );
        add_action( 'admin_init', array( $this, 'set_notices' ) );        
        add_filter( 'plugin_action_links_' . DPMT_PLUGIN_FILE, array( $this, 'add_action_link' ) );
        add_action( 'admin_menu', array( $this, 'add_admin_pages') );
        add_action( 'admin_enqueue_scripts', array( $this, 'add_css_js' ) );
        add_action( 'admin_footer_text', array( $this, 'change_footer_text' ) );
        add_action( 'admin_post_dpmt_editor_form_submit', array( $this, 'save_meta_tags' ) );

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

        $new = '<a href="' . admin_url( 'options-general.php?page=dpmt-editor' ) . '">' . 
            esc_attr__('Set up tags', 'dp-meta-tags') . '</a>';               
        
        array_unshift( $links, $new );
        
        return $links;

    }



    // add plugin pages to the admin menu
    public function add_admin_pages(){

        add_submenu_page(
            'options-general.php',
            esc_html__( 'Meta tags', 'dp-meta-tags' ),
            esc_html__( 'Meta tags', 'dp-meta-tags' ),
            'manage_options',
            'dpmt-editor',
            array( $this, 'meta_tag_pages' )
        );

    }



    // display meta tag table page
    public function meta_tag_pages(){
        
        include_once dirname( plugin_dir_path( __FILE__ ) ) . '/meta-tag-list.php';
        include_once dirname( plugin_dir_path( __FILE__ ) ) . '/class-dpmt-retrieve-tags.php';

        if ( ! empty($_GET['type']) && ! empty($_GET['edit']) ){
            
            include_once dirname( plugin_dir_path( __FILE__ ) ) . '/class-dpmt-retrieve-info.php';
            include_once 'views/html-meta-tag-editor.php';        

        }else{

            include_once 'views/html-meta-tag-table.php';

        }

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
                New interface! Visit <b>Settings / Meta tags</b> to edit all of them in one table! Don\'t worry, 
                your old settings won\'t be lost!
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



    // change footer text
    public function change_footer_text($footer_text){

        if( !empty($_GET['page']) && $_GET['page'] == 'dpmt-editor' ){
            $footer_text = sprintf(
                __( 'Found a bug? Please <a href="https://divpusher.com/contact" target="_blank">report it here</a> and we will fix that as soon as we can!<br />
                    If you like our %1$s please leave us a %2$s rating. Thank you in advance!', 'dp-meta-tags' ), 
                sprintf( '<strong>%s</strong>', esc_html__( 'Meta Tags plugin', 'dp-meta-tags' ) ), 
                '<a href="https://wordpress.org/support/plugin/meta-tags/reviews?rate=5#new-post" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a>'
            );
        }
        
        return $footer_text;

    }



    // save meta tags
    public function save_meta_tags(){

        // check nonce
        check_admin_referer( 'dpmt-save-changes' );

        
        // check user capabilities
        if ( ! current_user_can('edit_others_pages') ){
            wp_die( 'You don\'t have permission to edit meta tags!' );
        }


        // process and save tags
        include_once dirname( plugin_dir_path( __FILE__ ) ) . '/meta-tag-list.php';
        include_once 'class-dpmt-save-tags.php';       

        DPMT_Save_Tags::save( $dpmt_meta_tag_list, $_POST );


        // redirect to previous page
        wp_redirect( admin_url( 'options-general.php?page=dpmt-editor&type='. $_POST['dpmt_type'] .'&edit='. $_POST['dpmt_id'] ) );

    }



}

return new DPMT_Admin();