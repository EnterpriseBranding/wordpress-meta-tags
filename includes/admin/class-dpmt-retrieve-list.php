<?php
/**
 * Loads all items of a WP object type, e.g. get all pages, posts, etc.
 * 
 * @since 2.0.0
 */


defined('ABSPATH') || die();


class DPMT_Retrieve_List{
    
    /**
     * Retrieves all items of a WP item type.
     *
     * @param string $wp_object_type WP item type, e.g.: page, post, etc.
     * @return array List of items.
     */
    public static function get_list( $wp_object_type ){
        
        $items_per_page = -1;

        switch ( $wp_object_type ){

            case 'page':
                $list = get_pages( array(
                    'post_type' => 'page',
                    'post_status' => 'publish', 
                    'posts_per_page' => $items_per_page
                ) );


                // if frontpage displays blog posts
                if ( get_option('page_on_front') == 0 ){
                        
                    $frontpage = (object) [
                        'ID' => 'front',
                        'post_title' => 'Frontpage'
                    ];
                    array_unshift($list, $frontpage);
                    
                }


                $type = 'page';
                $query_ID = 'ID';
                $query_title = 'post_title';

                break;


            case 'post':
                $list = get_posts( array(
                    'post_status' => 'publish',
                    'posts_per_page' => $items_per_page
                ) );

                $type = 'post';
                $query_ID = 'ID';
                $query_title = 'post_title';
                
                break;


            case 'category':
                $list = get_categories();

                $type = 'category';
                $query_ID = 'term_id';
                $query_title = 'name';

                break;


            case 'tag':
                $list = get_tags();

                $type = 'tag';
                $query_ID = 'term_id';
                $query_title = 'name';

                break;


            case 'author':
                $list = get_users( array(
                    'orderby' => 'display_name'
                ) );

                $type = 'author';
                $query_ID = 'ID';
                $query_title = 'display_name';

                break;


            case 'woo-product':
                $list = get_posts( array(
                    'post_type' => 'product', 
                    'posts_per_page' => $items_per_page,
                    'orderby' => 'name',
                    'order' => 'ASC'
                ) );

                $type = 'woo-product';
                $query_ID = 'ID';
                $query_title = 'post_title';

                break;


            case 'woo-category':
                $list = get_terms( array(
                    'taxonomy' => 'product_cat'
                ) );

                $type = 'woo-category';
                $query_ID = 'term_id';
                $query_title = 'name';

                break;


            case 'woo-tag':
                $list = get_terms( array(
                    'taxonomy' => 'product_tag'
                ) );

                $type = 'woo-tag';
                $query_ID = 'term_id';
                $query_title = 'name';

                break;


            default:

                break;

        }

        
        // return an array with the info
        $return_array = [
            'list' => $list,
            'type' => $type,
            'query_ID' => $query_ID,
            'query_title' => $query_title,
        ];

        return $return_array;

    }

}
