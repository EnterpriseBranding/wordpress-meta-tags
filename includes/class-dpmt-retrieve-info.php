<?php
/**
 * Loads all information of a WordPress item from database.
 * 
 * @since 2.0.0
 */


defined('ABSPATH') || die();


class DPMT_Retrieve_Info {

    /**
     * WP item types which this plugin can handle.
     * 
     * @var array
     */
    private $allowed_types = [ 'page', 'post', 'category', 'tag', 'author', 'woo-product', 'woo-category', 'woo-tag' ];

    /**
     * WP item type.
     * 
     * @var string
     */
    private $type;

    /**
     * WP item ID.
     * 
     * @var string
     */
    private $id;

    /**
     * WP item title.
     * 
     * @var string
     */
    public $title;

    /**
     * WP item URL.
     * 
     * @var string
     */
    public $url;
    
    /**
     * WP item description.
     * 
     * @var string
     */
    public $description;

    /**
     * WP item image URL.
     * 
     * @var string
     */
    public $image;
    
    /**
     * WP item image alternate text.
     * 
     * @var string
     */
    public $image_alt;

    /**
     * WP item audio URL.
     * 
     * @var string
     */
    public $audio;

    /**
     * WP item video URL.
     * 
     * @var string
     */
    public $video;


    
    /**
     * Checks parameters and starts the loading process.
     * 
     * @param string $type WP item type, e.g.: page, post, etc.
     * @param string $id WP item ID or frontpage marker.
     */
    public function __construct( $type, $id ){
        
        $this->type = ($id == 'front' ? 'frontpage' : $type);
        $this->id = $id;

        $this->init();

    }



    /**
     * Loads all information it can find based on item content.
     */
    private function init(){

        switch ( $this->type ) {

            case 'category':
            case 'tag':      
            case 'woo-category':
            case 'woo-tag':
                $item = get_term( $this->id );


                // title
                $this->title = $item->name;


                // url
                $this->url = get_term_link( intval($this->id) );


                // description
                $this->description = $item->description;
                

                // woo categories can have an image
                if ( $this->type == 'woo-category' ){
                    $thumbnail_id = get_woocommerce_term_meta( intval($this->id), 'thumbnail_id', true );
                    $image = wp_get_attachment_url( $thumbnail_id );
                    $this->image = $image ? $image : null; 
                }


                break;


            case 'author':
                
                // title
                $this->title = get_the_author_meta( 'display_name', $this->id );


                // url
                $this->url = get_author_posts_url( $this->id );


                // description
                $this->description = get_the_author_meta( 'description', $this->id );


                break;


            case 'frontpage':

                // title
                $this->title = get_bloginfo( 'name' );


                // url
                $this->url = get_site_url();


                // description
                $this->description = get_bloginfo( 'description' );


                break;


            default:
                $item = get_post( $this->id );                


                // title
                $this->title = $item->post_title;


                // url
                $this->url = get_permalink( $this->id );


                // description: excerpt or the first 20 words from the content
                if( !empty($item->post_excerpt) ){                
                    $this->description = wp_strip_all_tags( $item->post_excerpt, true );
                }else{
                    $this->description = wp_strip_all_tags( wp_trim_words( $item->post_content, 20, '...' ), true );
                }


                // image: get featured image or the first image from the content
                if ( has_post_thumbnail( $this->id ) ){

                    $this->image = get_the_post_thumbnail_url($this->id, 'large');
                    $thumbnail_id = get_post_thumbnail_id( $this->id );
                    $this->image_alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);

                }


                if ( empty( $item->post_content ) ){
                    return;
                }


                // for fetching media elements
                $dom = new DOMDocument;
                libxml_use_internal_errors(true);
                $dom->loadHTML($item->post_content);
                libxml_clear_errors();
                
                                    
                if ( ! has_post_thumbnail( $this->id ) ){

                    $images = $dom->getElementsByTagName('img');                    
                    if( !empty($images) && is_array($images) ){
                        $this->image = $images[0]->getAttribute('src');
                        $this->image_alt = $images[0]->getAttribute('alt');    
                    }
                    
                }


                // video
                $videos = $dom->getElementsByTagName('video');                
                if( $videos->length != 0 ){

                    $source = $videos->item(0)->getElementsByTagName('source');

                    if ( $source->length != 0 ){

                        $this->video = $source->item(0)->getAttribute('src');   

                    }elseif( ! empty( $videos->item(0)->getAttribute('src') ) ){

                        $this->video = $videos->item(0)->getAttribute('src');

                    }                 

                }


                // audio
                $audio = $dom->getElementsByTagName('audio');              
                if( $audio->length != 0 ){
                    
                    $source = $audio->item(0)->getElementsByTagName('source');

                    if ( $source->length != 0 ){

                        $this->audio = $source->item(0)->getAttribute('src');                         

                    }elseif( ! empty( $audio->item(0)->getAttribute('src') ) ){

                        $this->audio = $audio->item(0)->getAttribute('src');

                    }

                }


                break;

        }

    }


}
