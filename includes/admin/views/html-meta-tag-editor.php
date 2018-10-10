<?php
/*
 * Displays the meta tag editor form.
 */

defined('ABSPATH') || die();


// validation
$types = [ 'page', 'post', 'category', 'tag', 'author', 'woo-product', 'woo-category', 'woo-tag' ];
if ( !in_array($_GET['type'], $types) ){
    return;
}


if ( !is_numeric($_GET['edit']) && $_GET['edit'] != 'front' ){
    return;
}



// get meta tags
$taginfo = new DPMT_Retrieve_Tags( $dpmt_meta_tag_list );
$meta_tags = $taginfo->get_tags($_GET['type'], $_GET['edit']);


// get object title
$iteminfo = new DPMT_Retrieve_Info($_GET['type'], $_GET['edit']);


?>
<div class="wrap dpmt-editor">
    
    <?php
    echo '<h1 class="wp-heading-inline">' . __( 'Meta Tag Editor', 'dp-meta-tags' ) . ' / ';
    
        if ( $_GET['edit'] == 'front' ){
            echo $iteminfo->title . ' (' . __( 'frontpage', 'dp-meta-tags' ) . ')';     
        }else{
            echo $iteminfo->title . ' (' . str_replace('-', ' ', $_GET['type']) . ')';     
        }

    echo '</h1>
    <a href="#" class="page-title-action dpmt-set-all-auto">' . __( 'Set All to Autopilot', 'dp-meta-tags' ) . '</a>
    <a href="#" class="page-title-action dpmt-clear-all">' . __( 'Clear All', 'dp-meta-tags' ) . '</a>';

        

    echo '<form method="POST" action="' . admin_url('admin-post.php') . '">';


    // we need this line to fire our form processor function after submission
    echo '<input name="action" type="hidden" value="dpmt_editor_form_submit" />';


    // submit item type and id via post method as well
    echo '
    <input name="dpmt_type" type="hidden" value="'. $_GET['type'] .'" />
    <input name="dpmt_id" type="hidden" value="'. $_GET['edit'] .'" />
    ';


    // nonces for security
    wp_nonce_field( 'dpmt-save-changes' );


    // list all tags
    foreach ( $dpmt_meta_tag_list as $group => $items ){

        echo '<h2 class="title">'. $group .'</h2>
        <p>'. $items['info'] .'</p>
        <table class="form-table">';

        foreach ( $items['fields'] as $field => $tag ){
            echo '
            <tr>
                <th scope="row"><label for="'. $tag['variable'] .'">'. $field .'</label></th>
                <td>';
                    
                    if ( !empty($tag['values']) ){

                        echo '
                        <select name="'. $tag['variable'] .'" id="'. $tag['variable'] .'">
                            <option value="">-</option>';

                        foreach ( $tag['values'] as $option ){

                            echo '<option value="'. $option .'"';
                            if ( $meta_tags[$group][$tag['variable']] == $option ){
                                echo ' selected="selected"';
                            }
                            echo '>'. $option .'</option>';

                        }

                        echo '
                            <option value="auto"'. 
                            ($meta_tags[$group][$tag['variable']] == 'auto' ? ' selected="selected"' : '') 
                            .'>auto</option>
                        </select>';

                    }else{

                        if( $tag['variable'] == 'dpmt_custom' ){
                            
                            $allowed_html = array(
                                'meta' => array(
                                    'name' => array(),
                                    'property' => array(),
                                    'http-equiv' => array(),
                                    'content' => array()
                                )
                            );

                            echo '
                            <textarea name="'. esc_attr($tag['variable']) .'" id="'. esc_attr($tag['variable']) .'" class="regular-text code" rows="3" placeholder="'. 
                            htmlentities('<meta name="" content="" />') .'">'. 
                            wp_kses($meta_tags[$group][$tag['variable']], $allowed_html) .
                            '</textarea>';

                        }else{

                            echo '
                            <input name="'. esc_attr($tag['variable']) .'" type="text" id="'. esc_attr($tag['variable']) .'" 
                            value="'. esc_attr($meta_tags[$group][$tag['variable']]) .'" class="regular-text" />';

                        }

                    }

                echo '
                    <p class="description">'. $tag['info'] .'</p>
                </td>
            </tr>';
        }

        echo '</table>';

    }


    echo '
    <p class="submit">
        <input type="submit" name="submit" id="submit" class="button button-primary" value="' . __( 'Save Changes', 'dp-meta-tags' ) . '"  />
    </p>';

    ?>

    </form>

</div>
