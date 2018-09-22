<?php


defined('ABSPATH') || die();


class DPMT_Admin {
    

    // add actions and filters
    public function __construct(){
        
        add_action( 'init', array( $this, 'includes' ));
        add_filter( 'plugin_action_links_' . DPMT_PLUGIN_FILE, array( $this, 'add_action_link') );
        add_action( 'admin_menu', array($this, 'add_editor_to_settings') );
        
        // add dismissable divpusher notice on theme page
        // add homepage meta tag editor
        // add meta tag editor metabox to page/post/woo editor
        
    }



    // include all the classes and functions we need
    public function includes(){
        
        // include_once dirname(__FILE__) . '/class-dpmt-notices.php';   
        include_once dirname(__FILE__) . '/../dpmt-meta-tag-list.php';   

    }


    
    // add action link below the plugin on plugins page
    public function add_action_link( $links ) {

        $new = '<a href="' . admin_url( 'options-general.php?page=dpmt-settings' ) . '">' . 
            esc_attr__('Set up tags', 'dp-meta-tags') . '</a>';               
        
        array_unshift( $links, $new );
        
        return $links;

    }



    // add homepage meta tag editor to settings menu
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



    }


}

return new DPMT_Admin();