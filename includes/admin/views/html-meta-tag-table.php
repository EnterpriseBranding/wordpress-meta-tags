<?php
/*
 * Displays the meta tags in a table.
 */

defined('ABSPATH') || die();

?>



<div class="wrap dpmt-table">
    <h1>Meta Tags</h1>

    <p>Click on an item to edit its meta tags. You can also set all of them to <b>autopilot</b> mode.
    <b>Autopilot</b> means that the plugin will retrieve the informations from the page itself.
    <a href="#" class="dpmt-toggle" data-toggle="1">Click here to learn how!</a></p>
    
    <div class="dpmt-hidden" data-toggle="1">
        <p><code>Posts:</code> title will be the post title, description will be the excerpt (if set) or the first few sentences, image will be the featured image or the first attached image</p>
        <p><code>Pages:</code> title will be the page title, description will be the first few sentences, image will be the featured image or the first attached image</p>
        <p><code>Categories, tags:</code> title will be the category/tag name, description will be the category/tag description</p>
        <p><code>Authors:</code> title will be the author name, description will be the biographical info</p>
        <p><code>Woo Product:</code> title will be the product name, description will be the short description, image will be the product image</p>
    </div>

    <div class="nav-tab-wrapper">
    <?php
        echo '
        <a href="options-general.php?page='. $_GET['page'] .'" 
            class="nav-tab'. (empty($_GET['tab']) ? ' nav-tab-active' : '') .'">Pages</a>
        
        <a href="options-general.php?page='. $_GET['page'] .'&tab=post" 
            class="nav-tab'. (!empty($_GET['tab']) && $_GET['tab'] == 'post' ? ' nav-tab-active' : '') .'">Posts</a>
        
        <a href="options-general.php?page='. $_GET['page'] .'&tab=category" 
            class="nav-tab'. (!empty($_GET['tab']) && $_GET['tab'] == 'category' ? ' nav-tab-active' : '') .'">Post Categories</a>
        
        <a href="options-general.php?page='. $_GET['page'] .'&tab=tag" 
            class="nav-tab'. (!empty($_GET['tab']) && $_GET['tab'] == 'tag' ? ' nav-tab-active' : '') .'">Post Tags</a>        
        
        <a href="options-general.php?page='. $_GET['page'] .'&tab=author" 
            class="nav-tab'. (!empty($_GET['tab']) && $_GET['tab'] == 'author' ? ' nav-tab-active' : '') .'">Authors</a>
        
        <a href="options-general.php?page='. $_GET['page'] .'&tab=woo-product" 
            class="nav-tab'. (!empty($_GET['tab']) && $_GET['tab'] == 'woo-product' ? ' nav-tab-active' : '') .'">Woo Products</a>
        
        <a href="options-general.php?page='. $_GET['page'] .'&tab=woo-category" 
            class="nav-tab'. (!empty($_GET['tab']) && $_GET['tab'] == 'woo-category' ? ' nav-tab-active' : '') .'">Woo Categories</a>
        
        <a href="options-general.php?page='. $_GET['page'] .'&tab=woo-tag" 
            class="nav-tab'. (!empty($_GET['tab']) && $_GET['tab'] == 'woo-tag' ? ' nav-tab-active' : '') .'">Woo Tags</a>
        ';
    ?>        
    </div>

    <form method="POST" action="<?php echo admin_url('admin-post.php'); ?>">
        <div class="table-holder">
        <table class="widefat striped">
            <thead>
                <tr>
                    <th>Title</th>
                    <?php
                    
                    foreach ($dpmt_meta_tag_list as $item => $details){
                        echo '<th>'. $item .' 
                        <span class="dashicons dashicons-editor-help" data-tip="'. esc_attr($details['info']) .'"></span></th>';
                    }

                    ?>                
                </tr>
            </thead>

            <tbody>
            <?php

                $taginfo = new DPMT_Retrieve_Tags( $dpmt_meta_tag_list );


                // list all items
                $items_per_page = -1;

                if ( isset($_GET['tab']) ){

                    switch ( $_GET['tab'] ){

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

                            $list = array();

                            break;

                    }

                }else{

                    $list = get_pages( array(
                        'post_type' => 'page',
                        'post_status' => 'publish', 
                        'posts_per_page' => $items_per_page
                    ) );


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

                }

                

                if ( ! empty($list) ){
                    foreach ( $list as $item ){

                        echo '                
                        <tr>
                            <td>';
                                if ($item->{$query_ID} == 'front'){
                                    echo '<i><b><a href="options-general.php?page='. $_GET['page'] .'&type='. $type .'&edit='. 
                                    $item->{$query_ID} .'">'. $item->{$query_title} .'</a></b></i>
                                    <span class="dashicons dashicons-editor-help" data-tip="'. 
                                    esc_attr('Your homepage displays the latest posts, you\'ll need meta tags there as well.')
                                    .'"></span>';
                                }else{
                                    echo '<a href="options-general.php?page='. $_GET['page'] .'&type='. $type .'&edit='. 
                                    $item->{$query_ID} .'">'. $item->{$query_title} .'</a>';
                                }
                            echo '
                            </td>';

                            $statuses = $taginfo->get_status( $type, $item->{$query_ID} );
                            foreach ($statuses as $group => $status){  
                                echo '<td>'. $status .'</td>';
                            }

                            echo '
                        </tr>
                        ';

                    }
                }

            ?>
            </tbody>    

            <tfoot>
                <tr>                    
                    <?php 

                    echo '
                    <th>
                        <input type="submit" id="doaction" class="button action" value="Apply Bulk Actions"  />
                        <input type="hidden" name="dpmt_type" value="';
                        if ( ! empty($_GET['tab']) ){
                            echo $_GET['tab'];
                        }else{
                            echo 'page';
                        }
                        echo '"  />
                        ';


                    // we need this line to fire our bulk action function after form submission
                    echo '<input name="action" type="hidden" value="dpmt_table_bulk_submit" />';


                    // nonces for security
                    wp_nonce_field( 'dpmt-bulk-actions' );

                    echo '</th>';


                    foreach ($dpmt_meta_tag_list as $group => $info){
                        echo '
                        <td>
                            <select name="bulk-'. esc_attr($info['var']) .'" id="bulk-action-selector-bottom">
                                <option value="-1">Bulk Actions</option>';

                        if ( $group != 'Custom' ){
                            echo '<option value="autopilot">Set all to autopilot</option>';
                        }
                        
                        echo '                                
                                <option value="delete">Delete all</option>
                            </select>
                        </td>
                        ';
                    }

                    ?>
                </tr>
            </tfoot>
        </table>
        </div>
    </form>

</div>