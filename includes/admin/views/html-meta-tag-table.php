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

        $possible_types = [
            'page' => 'Pages',
            'post' => 'Posts',
            'category' => 'Post Categories',
            'tag' => 'Post Tags',
            'author' => 'Authors',
            'woo-product' => 'Woo Products',
            'woo-category' => 'Woo Categories',
            'woo-tag' => 'Woo Tags',
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

                // get all items of the wp object type
                $type = ( !empty($_GET['tab']) ? $_GET['tab'] : 'page' );
                $items = DPMT_Retrieve_List::get_list( $type );

                if ( ! empty($items['list']) ){
                    foreach ( $items['list'] as $item ){

                        echo '                
                        <tr>
                            <td>';
                                if ($item->{$items['query_ID']} == 'front'){
                                    echo '<i><b><a href="options-general.php?page='. $_GET['page'] .'&type='. $type .'&edit='. 
                                    $item->{$items['query_ID']} .'">'. $item->{$items['query_title']} .'</a></b></i>
                                    <span class="dashicons dashicons-editor-help" data-tip="'. 
                                    esc_attr('Your homepage displays the latest posts, you\'ll need meta tags there as well.')
                                    .'"></span>';
                                }else{
                                    echo '<a href="options-general.php?page='. $_GET['page'] .'&type='. $type .'&edit='. 
                                    $item->{$items['query_ID']} .'">'. $item->{$items['query_title']} .'</a>';
                                }
                            echo '
                            </td>';

                            $statuses = $taginfo->get_status( $type, $item->{$items['query_ID']} );
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


                    // bulk actions
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