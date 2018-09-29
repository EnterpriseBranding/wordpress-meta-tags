<?php


defined('ABSPATH') || die();


class DPMT_Frontend {


    // add actions and filters
    public function __construct(){

        add_action( 'init', array( $this, 'includes' ) );

    }



    // include all the classes and functions we need
    public function includes(){

        include_once dirname( __FILE__ ) . '/meta-tag-list.php';   

    }



    // output all filled meta tag
    public function print_meta_tags(){

        // check page type, use switch case

        // get the proper id

        // get meta tags from db using DPMT_Retrieve_Tags class

    }


}

return new DPMT_Frontend();