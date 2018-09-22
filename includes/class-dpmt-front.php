<?php


defined('ABSPATH') || die();


class DPMT_Front {


    // add actions and filters
    public function __construct(){

        add_action( 'init', array( $this, 'includes' ));

    }



    // include all the classes and functions we need
    public function includes(){

        include_once dirname( __FILE__ ) . '/dpmt-meta-tag-list.php';   

    }



    // output all filled meta tag
    public function print_meta_tags(){



    }


}

return new DPMT_Front();