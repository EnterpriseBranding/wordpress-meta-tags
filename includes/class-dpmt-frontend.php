<?php
/**
 * Starts frontend processes.
 * 
 * @since 2.0.0
 */


defined('ABSPATH') || die();


class DPMT_Frontend {

    /**
     * List of all editable meta tags.
     * 
     * @var array
     */
    private $meta_tag_list;

    /**
     * All meta tags of current page.
     * 
     * @var array
     */
    private $tags;

    /**
     * Current page type.
     * 
     * @var string
     */
    private $page_type;

    /**
     * Current page id.
     * 
     * @var int
     */
    private $page_id;

    /**
     * Current page info for autopilot tags.
     * 
     * @var array
     */
    private $page_info;

    /**
     * Is open graph html attribute set?
     * 
     * @var bool
     */
    private $is_og_html_attr_set = false;

    /**
     * Meta tags to print in head tag.
     * 
     * @var string
     */
    private $output = '';



    /**
     * Adds actions and filters.
     */
    public function __construct(){

        add_action( 'init', array( $this, 'includes' ) );
        add_action( 'wp', array( $this, 'process_tags' ) );
        add_action( 'wp_head', array( $this, 'print_meta_tags' ), 0 );

    }



    /**
     * Includes all the classes, functions and variables we need.
     */
    public function includes(){

        include_once 'meta-tag-list.php';        
        include_once 'class-dpmt-retrieve-info.php';   
        include_once 'class-dpmt-retrieve-tags.php';   

        $this->meta_tag_list = $dpmt_meta_tag_list;

    }



    /**
     * Figures out the page type of current page.
     *
     * @return string Page type.
     */
    public function get_current_page_type(){

        // wp displays blog posts on front page
        if ( get_option('page_on_front') == 0 && is_front_page() ){
            return 'frontpage';
        }
        
        global $wp_query;

        // woocommerce
        if ( class_exists( 'WooCommerce' ) ) {
            if ( 
                is_cart() || is_checkout() || is_checkout_pay_page() || 
                is_edit_account_page() || is_lost_password_page() || is_order_received_page() ||
                is_shop() || is_view_order_page()
            ){
                return 'page';
            }

            if ( is_product() ){
                return 'woo-product';
            }

            if ( is_product_category() ){
                return 'woo-category';
            }

            if ( is_product_tag() ){
                return 'woo-tag';
            }
        }

        if ( $wp_query->is_page ){
            return 'page';
        }

        if ( $wp_query->is_single ){
            return 'post';
        }

        if ( $wp_query->is_category ){
            return 'category';
        }

        if ( $wp_query->is_tag ){
            return 'tag';
        }

        if ( $wp_query->is_author ) {
            return 'author';
        }

        if ( is_tax() ){
            return 'taxonomy';
        }

    }



     /**
     * Figures out the page ID of current page.
     *
     * @return int Page ID.
     */
    public function get_current_page_id(){

        global $wp_query;

        // woocommerce
        if ( class_exists( 'WooCommerce' ) ) {
            if ( is_shop() ){
                return get_option( 'woocommerce_shop_page_id' );
            }
        }

        return get_queried_object_id();

    }



    /**
     * If any open graph tag is set, it adds the required attribute to html tag.
     */
    public function set_html_prefix_attribute(){

        $this->is_og_html_attr_set = true;
        
        add_filter( 'language_attributes', 'dpmt_add_og_html_prefix' );

        function dpmt_add_og_html_prefix( $output ){
            return $output . ' prefix="og: http://ogp.me/ns#"';
        };

    }



    /**
     * Handles autopilot tags.
     *
     * @param string $tag_to_process Meta tag variable name to process.
     * @return string Meta tag value.
     */
    public function process_auto_tags( $tag_to_process ){

        if ( empty($this->page_info) ){
            $this->page_info = new DPMT_Retrieve_Info( $this->page_type, $this->page_id );
        }



        // general tags - we skip keywords because it's not recommended
        if( $tag_to_process == 'dpmt_general_description' ){
            return $this->page_info->description;
        }



        // open graph
        if( $tag_to_process == 'dpmt_og_title' ){
            return $this->page_info->title;
        }
        
        if( $tag_to_process == 'dpmt_og_description' ){
            return $this->page_info->description;
        }

        if( $tag_to_process == 'dpmt_og_type' ){

            // possible types: website, article or other (we will handle those later maybe)
            if ( $this->page_type == 'post' || $this->page_type == 'author' ){

                return 'article';

            }elseif ( $this->page_type == 'woo-product' ){                

                return 'product';

            }elseif ( $this->page_type == 'woo-category' || $this->page_type == 'woo-tag' ){                

                return 'product.group';

            }else{

                return 'website';

            }
            
        }
        
        if( $tag_to_process == 'dpmt_og_audio' ){
            return $this->page_info->audio;
        }
        
        if( $tag_to_process == 'dpmt_og_image' ){
            return $this->page_info->image;
        }
        
        if( $tag_to_process == 'dpmt_og_image_alt' ){
            return $this->page_info->image_alt;
        }
        
        if( $tag_to_process == 'dpmt_og_video' ){
            return $this->page_info->video;
        }
        
        if( $tag_to_process == 'dpmt_og_url' ){
            return $this->page_info->url;
        }



        // twitter cards
        if( $tag_to_process == 'dpmt_twitter_card' ){

            if ( 
                ! empty( $this->page_info->video ) && 
                ( ! empty( $this->tags['dpmt_twitter_player'] ) || ! empty( $this->tags['dpmt_twitter_player_stream'] ) )
            ){
                return 'player';
            }

            if ( 
                ! empty( $this->page_info->audio ) && 
                ( ! empty( $this->tags['dpmt_twitter_player'] ) || ! empty( $this->tags['dpmt_twitter_player_stream'] ) )
            ){
                return 'player';
            }

            if ( ! empty( $this->page_info->image ) ){
                return 'summary_large_image';
            }

            return 'summary';            

        }
        
        if( $tag_to_process == 'dpmt_twitter_title' ){
            return $this->page_info->title;
        }

        if( $tag_to_process == 'dpmt_twitter_description' ){
            return $this->page_info->description;
        }
        
        if( $tag_to_process == 'dpmt_twitter_image' ){
            return $this->page_info->image;
        }

        if( $tag_to_process == 'dpmt_twitter_image_alt' ){
            return $this->page_info->image_alt;
        }

        if( $tag_to_process == 'dpmt_twitter_player_stream' ){
            
            if ( ! empty( $this->page_info->video ) ){
                return $this->page_info->video;
            }
            
            if ( ! empty( $this->page_info->audio ) ){
                return $this->page_info->audio;
            }

        }

        if( $tag_to_process == 'dpmt_twitter_player_stream_content_type' ){
            
            if ( ! empty( $this->page_info->video ) ){

                $mime = wp_check_filetype( $this->page_info->video );
                return $mime['type'];

            }
            
            if ( ! empty( $this->page_info->audio ) ){

                $mime = wp_check_filetype( $this->page_info->audio );
                return $mime['type'];

            }

        }
        


    }



    /**
     * Generates the meta tag output.
     */
    public function process_tags(){

        $this->page_type = $this->get_current_page_type(); 
        $this->page_id = ( $this->page_type == 'frontpage' ? 'front' : $this->get_current_page_id() );

        if ( ! $this->page_type || ! $this->page_id ){
            return;
        }

        $taginfo = new DPMT_Retrieve_Tags( $this->meta_tag_list );
        $tags = $taginfo->get_tags( $this->page_type, $this->page_id );        
        $this->tags = call_user_func_array('array_merge', $tags);

        $allowed_html = array(
            'meta' => array(
                'name' => array(),
                'property' => array(),
                'http-equiv' => array(),
                'content' => array()
            )
        );


        foreach ( $this->meta_tag_list as $group => $values ){

            foreach ($values['fields'] as $field => $info) {
                
                if ( !empty( $this->tags[$info['variable']] ) ){
                    
                    if ( $info['variable'] == 'dpmt_custom' ){

                        $this->output .= wp_kses( $this->tags[$info['variable']], $allowed_html ) . PHP_EOL;

                    }else{

                        if ( ! $this->is_og_html_attr_set && substr($info['variable'], 0, 7) == 'dpmt_og' ){
                            
                            $this->set_html_prefix_attribute();

                        }


                        if ( $this->tags[$info['variable']] != 'auto' ){

                            $this->output .= '<meta '. $values['attr'] .'="'. 
                                esc_attr( $field ) . '" content="' . 
                                esc_attr( stripslashes( $this->tags[$info['variable']] ) ) . '" />' .
                                PHP_EOL;

                        }else{

                            if ( $content = $this->process_auto_tags( $info['variable'] ) ){
                                $this->output .= '<meta '. $values['attr'] .'="'.
                                    esc_attr( $field ) . '" content="' .
                                    esc_attr( stripslashes( $content ) ) . '" />' .
                                    PHP_EOL;    
                            }                            

                        }

                    }
                }

            }      

        }

    }



    /**
     * Prints all filled meta tags.
     */
    public function print_meta_tags(){

        echo $this->output;

    }


}

return new DPMT_Frontend();
