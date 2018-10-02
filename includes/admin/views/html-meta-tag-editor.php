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
    
    <h1 class="wp-heading-inline">Meta Tag Editor / <?php 
    
        if ( $_GET['edit'] == 'front' ){
            echo $iteminfo->title . ' (frontpage)';     
        }else{
            echo $iteminfo->title . ' (' . str_replace('-', ' ', $_GET['type']) . ')';     
        }

    ?></h1>
    <a href="#" class="page-title-action dpmt-set-all-auto">Set All to Autopilot</a>
    <a href="#" class="page-title-action dpmt-clear-all">Clear All</a>

    <form method="POST">
        
    <?php 

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

                        echo '
                        <input name="'. $tag['variable'] .'" type="text" id="'. $tag['variable'] .'" 
                        value="'. esc_attr($meta_tags[$group][$tag['variable']]) .'" class="regular-text" />';

                    }

                echo '
                    <p class="description">'. $tag['info'] .'</p>
                </td>
            </tr>';
        }

        echo '</table>';

    }

    ?>

        <table class="form-table">
            <tr>
                <th scope="row"><label for="custom">Custom meta tags</label></th>
                <td>
                    <?php 

                    $allowed_html = array(
                        'meta' => array(
                            'name' => array(),
                            'property' => array(),
                            'http-equiv' => array(),
                            'content' => array()
                        )
                    );

                    echo '
                    <textarea name="custom" id="custom" class="regular-text code" rows="3" placeholder="'. htmlentities('<meta name="" content="" />') .'">'. wp_kses($meta_tags['Custom'], $allowed_html) .'</textarea>
                    ';

                    ?>
                    
                </td>
            </tr>
        </table>

        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"  /></p>

    </form>

</div>
