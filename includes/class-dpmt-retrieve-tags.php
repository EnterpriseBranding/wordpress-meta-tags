<?php
/**
 * Loads all meta tags of a WordPress item from database.
 * 
 * @since 2.0.0
 */


defined('ABSPATH') || die();


class DPMT_Retrieve_Tags {

    /**
     * List of all editable meta tags.
     *
     * @var array
     */
    private $list_of_meta_tags;

    /**
     * All meta tags of the requested WP item.
     *
     * @var array
     */
    private $retrieved_tag_list;

    /**
     * Meta tag fulfillment statuses by groups.
     *
     * @var array
     */
    private $status;



    /**
     * Loads the list of all editable meta tags.
     *
     * @param array $list_of_meta_tags Editable meta tag list.
     */
    public function __construct( $list_of_meta_tags ){
        
        $this->list_of_meta_tags = $list_of_meta_tags;

    }


    
    /**
     * Retrieves meta tags from the appropriate table based on WP item $type.
     *
     * @param string $type WP item type, e.g.: page, post, etc.
     * @param string $id WP item ID or frontpage marker.
     * @return array List of meta tags.
     */
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



    /**
     * Checks meta tag fulfillments in each group and returns a one word summary about them.
     *
     * @return array Statuses.
     */
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



    /**
     * Retrieves meta tag fulfillment statuses.
     *
     * @return array Statuses.
     */
    public function get_status( $type = null, $id = null ){

        if ($type && $id){            
            $this->get_tags( $type, $id );
        }

        return $this->status;

    }
    
}
