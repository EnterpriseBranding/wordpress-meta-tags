<?php
/**
 * Displays all meta tags in a table.
 * 
 * @since 2.0.0
 */

defined('ABSPATH') || die();

?>

<div class="wrap dpmt-table">
    <?php
    
    echo '
    <h1>'. __( 'Meta Tags', 'dp-meta-tags' ) . '</h1>

    <p>' . __( 'Click on an item to edit its meta tags. You can also set all of them to <b>autopilot</b> mode. <b>Autopilot</b> means that the plugin will retrieve the informations from the page itself.', 'dp-meta-tags' ) .    
    ' <a href="#" class="dpmt-toggle" data-toggle="1">' . __( 'Click here to learn how!', 'dp-meta-tags' ) . '</a></p>

    <div class="dpmt-hidden" data-toggle="1">

        <p>' . __( '<code>Posts:</code> title will be the post title, description will be the excerpt (if set) or the first few sentences, image will be the featured image or the first attached image, video and audio is the same', 'dp-meta-tags' ) . 
        '</p>

        <p>' . __( '<code>Pages:</code> title will be the page title, description will be the first few sentences, image will be the featured image or the first attached image, video and audio is the same', 'dp-meta-tags' ) . 
        '</p>

        <p>' . __( '<code>Categories, tags:</code> title will be the category/tag name, description will be the category/tag description', 'dp-meta-tags' ) . 
        '</p>

        <p>' . __( '<code>Authors:</code> title will be the author name, description will be the biographical info', 'dp-meta-tags' ) . 
        '</p>

        <p>' . __( '<code>Woo Product:</code> title will be the product name, description will be the short description, image will be the product image', 'dp-meta-tags' ) . 
        '</p>

        <p>' . __( '<b>Please note:</b> some meta tags cannot be filled automatically, e.g.: Twitter username', 'dp-meta-tags' ) . 
        '</p>

    </div>

    <div class="nav-tab-wrapper">';

        // display tabbed navigation    
        $possible_types = [
            'page' => esc_html__( 'Pages', 'dp-meta-tags' ),
            'post' => esc_html__( 'Posts', 'dp-meta-tags' ),
            'category' => esc_html__( 'Post Categories', 'dp-meta-tags' ),
            'tag' => esc_html__( 'Post Tags', 'dp-meta-tags' ),
            'author' => esc_html__( 'Authors', 'dp-meta-tags' ),
            'woo-product' => esc_html__( 'Woo Products', 'dp-meta-tags' ),
            'woo-category' => esc_html__( 'Woo Categories', 'dp-meta-tags' ),
            'woo-tag' => esc_html__( 'Woo Tags', 'dp-meta-tags' ),
        ];

        foreach ( $possible_types as $key => $value ) {

            $key = ($key == 'page' ? '' : $key);

            echo '<a href="options-general.php?page='. $_GET['page'];

            if ( !empty($key) ) {
                echo '&tab='. $key;
            }

            echo '" class="nav-tab';

            if ( !empty($_GET['tab']) && $_GET['tab'] == $key || empty($_GET['tab']) && $key == '' ){
                echo  ' nav-tab-active';
            }  

            echo '">' . $value . '</a>';

        }
        
    ?>        
    </div>

    <form method="POST" action="<?php echo admin_url('admin-post.php'); ?>">
        <div class="table-holder">
        <table class="widefat striped">
            <thead>
                <tr>
                    <th>Title</th>
                    <?php
                    
                    // meta tag groups
                    foreach ($dpmt_meta_tag_list as $item => $details){
                        echo '<th>'. $item .' 
                        <span class="dashicons dashicons-editor-help" data-tip="'. esc_attr( wp_strip_all_tags( $details['info'] ) ) .'"></span></th>';
                    }

                    ?>                
                </tr>
            </thead>

            <tbody>
            <?php

                // list all items of the wp object type
                $type = ( !empty($_GET['tab']) ? $_GET['tab'] : 'page' );

                $paged = ( !empty($_GET['paged']) ? intval( abs( $_GET['paged'] ) ) : 1 );
                
                $items_per_page = 25;

                $offset = ($paged * $items_per_page) - $items_per_page;

                $items = DPMT_Retrieve_List::get_list( $type, $items_per_page, $offset );
                
                $taginfo = new DPMT_Retrieve_Tags( $dpmt_meta_tag_list );

                if ( ! empty($items['list']) ){
                    foreach ( $items['list'] as $item ){

                        echo '                
                        <tr>
                            <td>';
                                if ($item['id'] == 'front'){
                                    echo '<i><b><a href="options-general.php?page='. $_GET['page'] .'&type='. $type .'&edit='. 
                                    $item['id'] .'">'. esc_html__( 'Frontpage', 'dp-meta-tags' ) .'</a></b></i>
                                    <span class="dashicons dashicons-editor-help" data-tip="'. 
                                    esc_attr__('Your homepage displays the latest posts, you\'ll need meta tags there as well.')
                                    .'"></span>';
                                }else{
                                    echo '<a href="options-general.php?page='. $_GET['page'] .'&type='. $type .'&edit='. 
                                    $item['id'] .'">'. $item['title'] .'</a>';
                                }
                            echo '
                            </td>';

                            $statuses = $taginfo->get_status( $type, $item['id'] );
                            foreach ($statuses as $group => $status){  
                                echo '<td>'. $status .'</td>';
                            }

                            echo '
                        </tr>
                        ';

                    }
                }else{
                    echo '<tr><td colspan="6">&nbsp;</td></tr>';
                }

            ?>
            </tbody>    

            <tfoot>
                <tr>                    
                <?php 
                
                    // bulk actions
                    echo '
                    <th>
                        <input type="submit" id="doaction" class="button action" value="' . __( 'Apply Bulk Actions', 'dp-meta-tags' ). '"  />

                        <span class="dashicons dashicons-warning" data-tip="'. 
                        esc_attr__( 'Actions will be applied to all items in this section!' )
                        .'"></span>

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


                    // bulk actions
                    foreach ($dpmt_meta_tag_list as $group => $info){

                        echo '
                        <td>
                            <select name="bulk-'. esc_attr($info['var']) .'" id="bulk-action-selector-bottom">
                                <option value="-1">' . __( 'Bulk Actions', 'dp-meta-tags' ) . '</option>';

                        if ( $group != 'Custom' ){
                            echo '<option value="autopilot">' . __( 'Set all to autopilot', 'dp-meta-tags' ) . '</option>';
                        }
                        
                        echo '                                
                                <option value="delete">' . __( 'Delete all', 'dp-meta-tags' ) . '</option>
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

    <?php

        // pagination        

        $total_pages = ceil( $items['items_found'] / $items_per_page );
        $prev_page = $paged - 1;
        $next_page = $paged + 1;
        $tab = ( !empty($_GET['tab']) ? $_GET['tab'] : '' );

        echo '
        <form method="GET">
        <div class="tablenav bottom">
            <div class="tablenav-pages">

                <span class="displaying-num">' . sprintf(
                    __( '%d items', 'dp-meta-tags' ),
                    $items['items_found']
                ) . '</span>';


            if ( $total_pages > 1 ){

                echo '
                <span class="pagination-links">';


                // prev page links
                if ( !empty($paged) && $paged > 1 ){

                    echo '
                    <a class="first-page" href="' . 
                    admin_url( 'options-general.php?page=dpmt-editor&tab=' . $tab ) . '">
                        <span class="screen-reader-text">'. esc_html__('First page', 'dp-meta-tags' ) .'</span>
                        <span aria-hidden="true">&laquo;</span>
                    </a>

                    <a class="prev-page" href="' . 
                    admin_url( 'options-general.php?page=dpmt-editor&tab=' . $tab ) . '&amp;paged='. $prev_page .'">
                        <span class="screen-reader-text">'. esc_html__('Previous page', 'dp-meta-tags' ) .'</span>
                        <span aria-hidden="true">&lsaquo;</span>
                    </a>';
                
                }else{

                    echo '
                    <span class="tablenav-pages-navspan" aria-hidden="true">&laquo;</span>
                    <span class="tablenav-pages-navspan" aria-hidden="true">&lsaquo;</span>';

                }
                    

                // page info
                echo '
                <span class="paging-input">
                    <label for="current-page-selector" class="screen-reader-text">'. 
                        esc_html__('Current page', 'dp-meta-tags' ) 
                    .'</label>
                    <input type="hidden" name="page" value="dpmt-editor" />
                    <input type="hidden" name="tab" value="'. $tab .'" />
                    <input class="current-page" id="current-page-selector" type="text" name="paged" value="'. $paged .'" size="1" aria-describedby="table-paging" />
                    <span class="tablenav-paging-text"> / <span class="total-pages">'. $total_pages .'</span>
                    </span>
                </span>';


                // next page links
                if ( $paged != $total_pages ){

                    echo '
                    <a class="next-page" href="' . 
                    admin_url( 'options-general.php?page=dpmt-editor&tab=' . $tab ) . '&amp;paged='. $next_page .'">
                        <span class="screen-reader-text">'. esc_html__('Next page', 'dp-meta-tags' ) .'</span>
                        <span aria-hidden="true">&rsaquo;</span>
                    </a>
                    
                    <a class="last-page" href="' . 
                    admin_url( 'options-general.php?page=dpmt-editor&tab=' . $tab ) . '&amp;paged=' . $total_pages .'">
                        <span class="screen-reader-text">'. esc_html__('Last page', 'dp-meta-tags' ) .'</span>
                        <span aria-hidden="true">&raquo;</span>
                    </a>';

                }else{

                    echo '
                    <span class="tablenav-pages-navspan" aria-hidden="true">&rsaquo;</span>
                    <span class="tablenav-pages-navspan" aria-hidden="true">&raquo;</span>';

                }


                echo '
                </span>';

            }


            echo ' 
            </div>
        </div>
        </form>
        ';

    ?>  

</div>