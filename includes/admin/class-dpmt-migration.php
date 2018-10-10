<?php

/**
 * Move all filled meta tag values from older plugin.
 */

defined('ABSPATH') || die();


class DPMT_Migration{
    

    private $old_and_new_keys = [
        'dp-metatags-general-description' => 'dpmt_general_description', 
        'dp-metatags-general-keywords' => 'dpmt_general_keywords',
        'dp-metatags-og-title' => 'dpmt_og_title',
        'dp-metatags-og-description' => 'dpmt_og_description',
        'dp-metatags-og-type' => 'dpmt_og_type',
        'dp-metatags-og-audio' => 'dpmt_og_audio',
        'dp-metatags-og-image' => 'dpmt_og_image',
        'dp-metatags-og-video' => 'dpmt_og_video',
        'dp-metatags-og-url' => 'dpmt_og_url',
        'dp-metatags-twitter-card' => 'dpmt_twitter_card',
        'dp-metatags-twitter-title' => 'dpmt_twitter_title',
        'dp-metatags-twitter-description' => 'dpmt_twitter_description',
        'dp-metatags-twitter-image' => 'dpmt_twitter_image',
        'dp-metatags-custom' => 'dpmt_custom'
    ];

    private $tables_to_update = [
        'postmeta' => 'meta_key',
        'options' => 'option_name'
    ];



    public function __construct(){
        $this->run();
    }



    public function run(){

        global $wpdb;

        foreach ( $this->tables_to_update as $table => $key ){

            // remove all title tag settings because we don't handle them anymore
            $wpdb->delete( $wpdb->prefix . $table, array( $key => 'dp-metatags-general-title' ) );

            // rename old keys to new one
            $result = true;
            $key_prefix = ( $table == 'options' ? 'dpmt_frontpage_' : '' );
            foreach ($this->old_and_new_keys as $old => $new){

                if ( ! $wpdb->update( $wpdb->prefix . $table, array( $key => $key_prefix . $new ), array( $key => $old ) ) ){
                    $result = false;
                }

            }

        }


        return $result;

    }

}