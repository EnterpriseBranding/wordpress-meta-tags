<?php

/**
 * Get all meta tags of an item from database
 */

defined('ABSPATH') || die();


class DPMT_Retrieve_Tags {


    private $list_of_meta_tags;

    private $retrieved_tag_list;

    private $status;



    // get the list of all meta tags
    public function __construct( $list_of_meta_tags ){
        
        $this->list_of_meta_tags = $list_of_meta_tags;

    }


    
    public function get_tags( $type, $id ){

        $type = ($id == 'front' ? 'frontpage' : $type);


        foreach ( $this->list_of_meta_tags as $group => $item ){

            foreach ( $item['fields'] as $tag => $field ){
                switch ($type) {

                    case 'category':
                    case 'tag':      
                    case 'woo-category':
                    case 'woo-tag':              
                        $retrieved_tag_list[$group][$field['variable']] = get_term_meta($id, $field['variable'], true);

                        break;


                    case 'author':
                        $retrieved_tag_list[$group][$field['variable']] = get_user_meta($id, $field['variable'], true);

                        break;


                    case 'frontpage':                        
                        $retrieved_tag_list[$group][$field['variable']] = get_option( 'dpmt_frontpage_' . $field['variable']);

                        break;
                    

                    default:                        
                        $retrieved_tag_list[$group][$field['variable']] = get_post_meta($id, $field['variable'], true);

                        break;

                }

            }

        }



        // set status
        $this->retrieved_tag_list = $retrieved_tag_list;
        $this->set_status();



        return $this->retrieved_tag_list;

    }



    // check which tags are filled and return a summary about them
    public function set_status(){

        foreach ( $this->retrieved_tag_list as $group => $tags ){
            
            $found_auto = 0;
            $found_custom = 0;

            if ( $group == 'Custom' && ! empty($tags['dpmt_custom']) ){

                $found_custom = 1;

            }elseif ( ! empty($tags) && is_array($tags) ){
             
                foreach ( $tags as $tag => $value ){

                    if ( ! empty( $value ) ){
                        if ( $value == 'auto' ){
                            $found_auto = 1;
                        }else{
                            $found_custom = 1;
                        }

                    }

                }

            }


            if ( $found_auto == 0 && $found_custom == 0 ){

                $statuses[$group] = '—';    

            }elseif( $found_auto == 1 && $found_custom == 0 ){

                $statuses[$group] = __( 'autopilot', 'dp-meta-tags' );

            }elseif( $found_auto == 0 && $found_custom == 1 ){

                $statuses[$group] = __( 'custom', 'dp-meta-tags' );

            }elseif( $found_auto == 1 && $found_custom == 1 ){

                $statuses[$group] = __( 'mixed', 'dp-meta-tags' );

            }
            
        }   

        $this->status = $statuses;

    }



    public function get_status( $type = null, $id = null ){

        if ($type && $id){            
            $this->get_tags( $type, $id );
        }

        return $this->status;

    }
    
}
