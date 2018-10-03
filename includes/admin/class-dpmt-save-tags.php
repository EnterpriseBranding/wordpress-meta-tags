<?php


defined('ABSPATH') || die();


class DPMT_Save_Tags {
 

    // save meta tags of a particular wp item
    public static function save( $list_of_meta_tags, $data ){

        // get type so we'll know how to save things
        $possible_types = [ 'page', 'post', 'category', 'tag', 'author', 'woo-product', 'woo-category', 'woo-tag' ];

        if ( ! in_array( $data['dpmt_type'], $possible_types )){
            return;
        }

        $type = $data['dpmt_id'] == 'front' ? 'frontpage' : $data['dpmt_type'];


        // get list of all possible meta tags (plus custom) and walk them
        $list_of_meta_tags['Custom'] = [
            'fields' => [
                '0' => [
                    'variable' => 'dpmt_custom'
                ]
            ]
        ];


        foreach ( $list_of_meta_tags as $group => $item ){

            foreach ( $item['fields'] as $tag => $field ){
                
                $key = $field['variable'];


                // sanitize data
                if ( $key == 'dpmt_custom' ){

                    $allowed_html = array(
                        'meta' => array(
                            'name' => array(),
                            'property' => array(),
                            'http-equiv' => array(),
                            'content' => array()
                        )
                    );
                    
                    $value = wp_kses( $data[$field['variable']], $allowed_html );

                }else{

                    $value = sanitize_text_field( $data[$field['variable']] );

                }

                
                // switch statement to handle saving of $key / $value based on $type
                switch ($type) {
                    case 'category':
                    case 'tag':      
                    case 'woo-category':
                    case 'woo-tag': 
                        update_term_meta( $data['dpmt_id'], $key, $value );

                        break;


                    case 'author':
                        update_user_meta( $data['dpmt_id'], $key, $value );

                        break;


                    case 'frontpage':
                        update_option( 'dpmt_frontpage_'. $key, $value );

                        break;


                    default:
                        update_post_meta( $data['dpmt_id'], $key, $value );

                        break;
                }

            }

        }
        
    }



    // clear all meta tags in a group (bulk action) 
    public static function delete( $type, $meta_tag_group ){

        // get list of all possible meta tags in that group

        // get all items by type and delete meta tags


    }


    
    // set all meta tags to autopilot in a group (bulk action)
    public static function autopilot( $type, $meta_tag_group ){

        // get list of all possible meta tags in that group

        // get all items by type and set meta tags to "auto"


    }

}
