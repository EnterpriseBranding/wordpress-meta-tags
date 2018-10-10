<?php
/**
 * Displays all meta tags in a table.
 */

defined('ABSPATH') || die();

?>

<div class="wrap dpmt-table">
    <?php
    
    echo '<h1>'. __( 'Meta Tags', 'dp-meta-tags' ) . '</h1>';

    echo '<p>' . __( 'Click on an item to edit its meta tags. You can also set all of them to <b>autopilot</b> mode. <b>Autopilot</b> means that the plugin will retrieve the informations from the page itself.', 'dp-meta-tags' ) .    
    '<a href="#" class="dpmt-toggle" data-toggle="1">' . __( 'Click here to learn how!', 'dp-meta-tags' ) . '</a></p>';
    
    echo '<div class="dpmt-hidden" data-toggle="1">

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
                    
                    foreach ($dpmt_meta_tag_list as $item => $details){
                        echo '<th>'. $item .' 
                        <span class="dashicons dashicons-editor-help" data-tip="'. esc_attr( wp_strip_all_tags( $details['info'] ) ) .'"></span></th>';
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
                                    $item->{$items['query_ID']} .'">'. esc_html__( 'Frontpage', 'dp-meta-tags' ) .'</a></b></i>
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
                        <input type="submit" id="doaction" class="button action" value="' . __( 'Apply Bulk Actions', 'dp-meta-tags' ). '"  />
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

</div>