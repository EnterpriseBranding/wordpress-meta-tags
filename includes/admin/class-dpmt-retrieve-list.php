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
    public static function get_list( $wp_object_type, $items_per_page = -1, $offset = 0 ){

        $list = array();

        switch ( $wp_object_type ){

            case 'page':

                $query = new WP_Query( array(
                    'post_type' => 'page',
                    'posts_per_page' => $items_per_page,
                    'offset' => $offset,
                    'orderby' => 'title',
                    'order' => 'ASC'
                ) );

                if ( $query->have_posts() ){
                    while ( $query->have_posts() ){
                        
                        $query->the_post();

                        $list[] = [
                            'id' => get_the_ID(),
                            'title' => get_the_title()                    
                        ];

                    }
                }

                wp_reset_postdata();

                // if frontpage displays blog posts
                if ( get_option('page_on_front') == 0 && $offset == 0 ){
                        
                    $frontpage = [
                        'id' => 'front',
                        'title' => 'Frontpage'
                    ];
                    array_unshift($list, $frontpage);

                }

                $items_found = $query->found_posts;

                break;



            case 'post':

                $query = new WP_Query( array(
                    'post_type' => 'post',
                    'posts_per_page' => $items_per_page,
                    'offset' => $offset
                ) );

                if ( $query->have_posts() ){
                    while ( $query->have_posts() ){
                        
                        $query->the_post();

                        $list[] = [
                            'id' => get_the_ID(),
                            'title' => get_the_title()                    
                        ];

                    }
                }

                wp_reset_postdata();

                $items_found = $query->found_posts;
                
                break;



            case 'category':

                $items = get_categories( array(
                    'number' => ( $items_per_page == -1 ? null : $items_per_page ), 
                    'offset' => $offset
                ) );
                
                foreach ($items as $item) {
                    $list[] = [
                        'id' => $item->term_id,
                        'title' => $item->name
                    ];
                }

                $items_found = count( get_categories() );


                break;



            case 'tag':

                $items = get_tags( array(
                    'number' => ( $items_per_page == -1 ? null : $items_per_page ), 
                    'offset' => $offset
                ) );

                foreach ($items as $item) {
                    $list[] = [
                        'id' => $item->term_id,
                        'title' => $item->name
                    ];
                }

                $items_found = count( get_tags() );


                break;



            case 'author':

                $items = get_users( array(
                    'number' => ( $items_per_page == -1 ? null : $items_per_page ), 
                    'offset' => $offset,
                    'orderby' => 'display_name'
                ) );

                foreach ($items as $item) {
                    $list[] = [
                        'id' => $item->ID,
                        'title' => $item->display_name
                    ];
                }

                $items_found = count( get_users() );


                break;



            case 'woo-product':

                $query = new WP_Query( array(
                    'post_type' => 'product',
                    'posts_per_page' => $items_per_page,
                    'offset' => $offset
                ) );

                if ( $query->have_posts() ){
                    while ( $query->have_posts() ){
                        
                        $query->the_post();

                        $list[] = [
                            'id' => get_the_ID(),
                            'title' => get_the_title()                    
                        ];

                    }
                }

                wp_reset_postdata();

                $items_found = $query->found_posts;
                

                break;



            case 'woo-category':

                $items = get_terms( array(
                    'taxonomy' => 'product_cat',
                    'number' => ( $items_per_page == -1 ? null : $items_per_page ), 
                    'offset' => $offset
                ) );

                foreach ($items as $item) {
                    $list[] = [
                        'id' => $item->term_id,
                        'title' => $item->name
                    ];
                }

                $items_found = count( get_terms( array( 'taxonomy' => 'product_cat' ) ) );


                break;



            case 'woo-tag':

                $items = get_terms( array(
                    'taxonomy' => 'product_tag',
                    'number' => ( $items_per_page == -1 ? null : $items_per_page ), 
                    'offset' => $offset
                ) );

                foreach ($items as $item) {
                    $list[] = [
                        'id' => $item->term_id,
                        'title' => $item->name
                    ];
                }

                $items_found = count( get_terms( array( 'taxonomy' => 'product_tag' ) ) );


                break;



            default:

                break;

        }

        
        // return an array with the info
        $return_array = [
            'list' => $list,
            'items_found' => $items_found
        ];

        return $return_array;

    }

}
