<?php
/**
 * Starts backend processes.
 * 
 * @since 2.0.0
 */

defined('ABSPATH') || die();


class DPMT_Admin {    

    /**
     * Adds actions and filters.
     */
    public function __construct(){
        
        register_activation_hook( DPMT_PLUGIN_FILE, array( $this, 'on_activation' ) );
        add_action( 'upgrader_process_complete', array( $this, 'on_update' ) );
        add_action( 'init', array( $this, 'load_textdomain' ) );
        add_action( 'admin_init', array( $this, 'set_notices' ) );        
        add_action( 'admin_init', array( $this, 'check_version' ) );        
        add_filter( 'plugin_action_links_' . DPMT_PLUGIN_FILE, array( $this, 'add_action_link' ) );
        add_action( 'admin_menu', array( $this, 'add_admin_pages') );
        add_action( 'admin_enqueue_scripts', array( $this, 'add_css_js' ) );
        add_action( 'admin_footer_text', array( $this, 'change_footer_text' ) );
        add_action( 'admin_post_dpmt_editor_form_submit', array( $this, 'save_meta_tags' ) );
        add_action( 'admin_post_dpmt_table_bulk_submit', array( $this, 'table_bulk_actions' ) );
        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

    }



    /**
     * Things to do when plugin is activated.
     */
    public function on_activation(){

        set_transient( 'dmpt_activation_notice', 1 );

    }



    /**
     * Things to do when plugin is updated.
     */
    public function on_update(){

        set_transient( 'dmpt_update_notice', 1 );

    }



    /**
     * Loads translated text for the current language.
     */
    public function load_textdomain(){

        load_plugin_textdomain( 'dp-meta-tags', false, DPMT_PLUGIN_DIR .'/languages' );

    }



    /**
     * Checks plugin version to see if we have to run any migration from previous version.
     */
    public function check_version(){

        // run specific tasks
        if ( empty( get_option( 'dpmt_plugin_version' ) ) ){
                       
            include_once 'class-dpmt-migration.php';
            $migrate = new DPMT_Migration();

            // display notice if couldn't migrate data
            if ( ! $migrate ){
                set_transient( 'dmpt_migration_failed_notice', 1 );
            }

        }


        // update version number
        $plugin_info = get_plugin_data( DPMT_PLUGIN_FULL_PATH );
        if ( $plugin_info['Version'] != get_option( 'dpmt_plugin_version' )  ){
            
            update_option( 'dpmt_plugin_version', $plugin_info['Version'] );

        }

    }


    
    /**
     * Adds action link below the plugin on plugins page.
     *
     * @param array $links Required for the action.
     * @return array Plugin action link list.
     */
    public function add_action_link( $links ) {

        $new = '<a href="' . admin_url( 'options-general.php?page=dpmt-editor' ) . '">' . 
            esc_attr__('Set up tags', 'dp-meta-tags') . '</a>';               
        
        array_unshift( $links, $new );
        
        return $links;

    }



    /**
     * Adds plugin pages to the admin menu.
     */
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



    /**
     * Displays the meta tag table page.
     */
    public function meta_tag_pages(){
        
        include_once dirname( plugin_dir_path( __FILE__ ) ) . '/meta-tag-list.php';
        include_once dirname( plugin_dir_path( __FILE__ ) ) . '/class-dpmt-retrieve-tags.php';

        if ( ! empty($_GET['type']) && ! empty($_GET['edit']) ){
            
            include_once dirname( plugin_dir_path( __FILE__ ) ) . '/class-dpmt-retrieve-info.php';
            include_once 'views/html-meta-tag-editor.php';        

        }else{

            include_once 'class-dpmt-retrieve-list.php';
            include_once 'views/html-meta-tag-table.php';

        }

    }



    /**
     * Handles WP notices.
     */
    public function set_notices(){
        
        add_action( 'admin_notices', function() {
            
            // on plugin activation
            if( get_transient( 'dmpt_activation_notice' ) ){

                echo '<div class="notice notice-info is-dismissible"><p>';
                printf( 
                    __( 
                        'Thank you for using our plugin. Visit <a href="%s">Settings / Meta tags</a> to set up all the tags.', 
                        'dp-meta-tags'
                    ),
                    admin_url( 'options-general.php?page=dpmt-editor' ) 
                );
                echo '</p></div>';
                
                delete_transient( 'dmpt_activation_notice' );

            }            


            // on plugin update
            if( get_transient( 'dmpt_update_notice' ) ){

                echo '<div class="notice notice-info is-dismissible"><p>';
                printf( 
                    __( 
                        'New interface! Visit <a href="%s">Settings / Meta tags</a> to edit all of them in one table! Don\'t worry, your old settings won\'t be lost!', 
                        'dp-meta-tags'
                    ),
                    admin_url( 'options-general.php?page=dpmt-editor' ) 
                );
                echo '</p></div>';
                
                delete_transient( 'dmpt_update_notice' );

            }


            // on theme page
            $screen = get_current_screen()->parent_file;
            $user_id = get_current_user_id();
            if( $screen == 'themes.php' && ! get_user_meta( $user_id, 'dpmt_ad_dismissed' ) ){

                echo '<div class="notice notice-info"><p>';
                printf( 
                    __( 
                        'Need some nice, free or premium theme? <a href="%s" target="_blank">Have a look around here!</a>', 
                        'dp-meta-tags'
                    ),
                    esc_url( 'https://divpusher.com' ) 
                );
                
                echo '<span class="dpmt-dismiss-forever"><a href="?dpmt_ad_dismissed=1"><i class="dashicons dashicons-dismiss"></i> ';
                _e( 'Dismiss forever', 'dp-meta-tags' );
                echo '</span></a></p></div>';

            }


            // if migration failed
            if( get_transient( 'dmpt_migration_failed_notice' ) ){

                echo '<div class="notice notice-error is-dismissible"><p>';
                _e( 'For some reason we couldn\'t migrate all of your previous meta tag settings. Sorry!', 'dp-meta-tags' );
                echo '</p></div>';
                
                delete_transient( 'dmpt_migration_failed_notice' );

            }    
            

        });



        // dismiss notice
        if ( isset($_GET['dpmt_ad_dismissed']) ){
            
            $user_id = get_current_user_id();
            add_user_meta( $user_id, 'dpmt_ad_dismissed', 'true', true );

        }
        
    }



    /**
     * Enqueues CSS and JS files for admin.
     */
    public function add_css_js(){

        wp_enqueue_style( 'dpmt_admin_css', plugins_url('assets/css/admin.css', DPMT_PLUGIN_FILE) );
        wp_enqueue_script( 'dpmt_admin_js', plugins_url('assets/js/admin.js', DPMT_PLUGIN_FILE), array('jquery') );

    }



    /**
     * Changes footer text on plugin pages.
     *
     * @param string $footer_text
     * @return string The new footer text.
     */
    public function change_footer_text( $footer_text ){

        if( !empty($_GET['page']) && $_GET['page'] == 'dpmt-editor' ){

            $footer_text = sprintf(
                __( 'Found a bug? Please <a href="%s" target="_blank">report it here</a> and we will fix that as soon as we can!', 'dp-meta-tags' ),
                esc_url( 'https://divpusher.com/contact' )
            ) . '<br />';
            
            $footer_text .= sprintf(
                __( 'If you like our <strong>%s</strong> please leave us a %s rating. Thank you in advance!', 'dp-meta-tags' ),
                esc_html__( 'Meta Tags plugin', 'dp-meta-tags' ),
                '<a href="https://wordpress.org/support/plugin/meta-tags/reviews?rate=5#new-post" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a>'
            );

        }
        
        return $footer_text;

    }



    /**
     * Handles meta tag saving after form submission.
     */
    public function save_meta_tags(){

        // check nonce
        check_admin_referer( 'dpmt-save-changes' );

        
        // check user capabilities
        if ( ! current_user_can('edit_others_pages') ){
            wp_die( esc_html__( 'You don\'t have permission to edit meta tags!', 'dp-meta-tags' ) );
        }


        // process and save tags
        include_once dirname( plugin_dir_path( __FILE__ ) ) . '/meta-tag-list.php';
        include_once 'class-dpmt-save-tags.php';       

        DPMT_Save_Tags::save( $dpmt_meta_tag_list, $_POST );


        // redirect to previous page
        wp_redirect( admin_url( 'options-general.php?page=dpmt-editor&type='. $_POST['dpmt_type'] .'&edit='. $_POST['dpmt_id'] ) );

    }



    /**
     * Handles meta tag table bulk actions.
     */
    public function table_bulk_actions(){

        // check nonce
        check_admin_referer( 'dpmt-bulk-actions' );

        
        // check user capabilities
        if ( ! current_user_can('edit_others_pages') ){
            wp_die( esc_html__( 'You don\'t have permission to edit meta tags!', 'dp-meta-tags' ) );
        }


        // process and update tags
        include_once dirname( plugin_dir_path( __FILE__ ) ) . '/meta-tag-list.php';
        include_once 'class-dpmt-retrieve-list.php';    
        include_once 'class-dpmt-save-tags.php';    

        $type = $_POST['dpmt_type'];
        

        // figure out which meta tag group needs update
        $groups = [ 'general', 'og', 'twitter', 'custom' ];


        foreach( $groups as $group ){

            if ( $_POST['bulk-'. $group] != -1 ){

                DPMT_Save_Tags::bulk( $_POST['bulk-'. $group], $dpmt_meta_tag_list, $type, $group );
                
            }

        }


        // redirect to previous page
        wp_redirect( admin_url( 'options-general.php?page=dpmt-editor&tab=' . $type ) );

    }



    /**
     * Adds a direct link to the meta tag editor in each page/post/product editor page.
     * @since 2.0.2
     */
    public function add_meta_boxes(){

        $screens = ['post', 'page', 'product'];
        foreach ($screens as $screen) {
            add_meta_box(
                'dpmt_meta_tag_editor_link',
                esc_html__('Edit Meta Tags', 'dp-meta-tags'),
                array( $this, 'meta_box_html' ),
                $screen,
                'side'
            );
        }

    }


    public function meta_box_html(){

        if (!empty($_GET['post'])){

            $currentScreen = get_current_screen();
            $postType = ($currentScreen->post_type == 'product' ? 'woo-product' : $currentScreen->post_type);

            echo '<a href="' . 
                admin_url('options-general.php?page=dpmt-editor&type='. $postType .'&edit='. intval($_GET['post'])) .'">' . 
                esc_html__('Click here to edit meta tags of this item.', 'dp-meta-tags') . 
                '</a>';    

        }else{

            echo '<i>'. esc_html__('You need to save this item first to edit its meta tags.', 'dp-meta-tags') .'</i>';

        }

    }




}

return new DPMT_Admin();