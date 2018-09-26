<?php
/*
 * Displays the meta tag editor table.
 */

defined('ABSPATH') || die();

?>



<div class="wrap dpmt-editor">
    <h1>Meta Tag Editor</h1>

    <p>Click on an item to edit its meta tags. You can also set all of them to <b>autopilot</b> mode.
    <b>Autopilot</b> means that the plugin will try to retrieve the information from the page itself.
    <a href="#" class="dpmt-toggle" data-toggle="1">Click here to read how!</a></p>
    
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
        
        <a href="options-general.php?page='. $_GET['page'] .'&tab=posts" 
            class="nav-tab'. (!empty($_GET['tab']) && $_GET['tab'] == 'posts' ? ' nav-tab-active' : '') .'">Posts</a>
        
        <a href="options-general.php?page='. $_GET['page'] .'&tab=categories" 
            class="nav-tab'. (!empty($_GET['tab']) && $_GET['tab'] == 'categories' ? ' nav-tab-active' : '') .'">Post Categories</a>
        
        <a href="options-general.php?page='. $_GET['page'] .'&tab=tags" 
            class="nav-tab'. (!empty($_GET['tab']) && $_GET['tab'] == 'tags' ? ' nav-tab-active' : '') .'">Post Tags</a>        
        
        <a href="options-general.php?page='. $_GET['page'] .'&tab=authors" 
            class="nav-tab'. (!empty($_GET['tab']) && $_GET['tab'] == 'authors' ? ' nav-tab-active' : '') .'">Authors</a>
        
        <a href="options-general.php?page='. $_GET['page'] .'&tab=woo-products" 
            class="nav-tab'. (!empty($_GET['tab']) && $_GET['tab'] == 'woo-products' ? ' nav-tab-active' : '') .'">Woo Products</a>
        
        <a href="options-general.php?page='. $_GET['page'] .'&tab=woo-categories" 
            class="nav-tab'. (!empty($_GET['tab']) && $_GET['tab'] == 'woo-categories' ? ' nav-tab-active' : '') .'">Woo Categories</a>
        
        <a href="options-general.php?page='. $_GET['page'] .'&tab=woo-tags" 
            class="nav-tab'. (!empty($_GET['tab']) && $_GET['tab'] == 'woo-tags' ? ' nav-tab-active' : '') .'">Woo Tags</a>
        ';
    ?>        
    </div>

    <table class="widefat striped">
        <thead>
            <tr>
                <th>Title</th>
                <th>General tags <span class="dashicons dashicons-editor-help" data-tip="Lorem ipsum dolor sit amet adepiscing elit."></span></th>
                <th>Open Graph <span class="dashicons dashicons-editor-help" data-tip="Dolor sit amet adepiscing elit."></span></th>
                <th>Twitter Cards <span class="dashicons dashicons-editor-help" data-tip="Dolor sit amet adepiscing elit."></span></th>
                <th>Custom tags <span class="dashicons dashicons-editor-help" data-tip="Dolor sit amet adepiscing elit."></span></th>
            </tr>
        </thead>

        <tbody>
        <?php
            
            if ( isset($_GET['tab']) ){

                switch ( $_GET['tab'] ){

                    case 'posts':
                        $list = get_posts( array(
                            'post_status' => 'publish',
                            'posts_per_page' => -1
                        ) );

                        $type = 'post';
                        $queryID = 'ID';
                        $queryTitle = 'post_title';
                        
                        break;


                    case 'categories':
                        $list = get_categories();

                        $type = 'category';
                        $queryID = 'ID';
                        $queryTitle = 'name';

                        break;


                    case 'tags':
                        $list = get_tags();

                        $type = 'tag';
                        $queryID = 'term_id';
                        $queryTitle = 'name';

                        break;


                    case 'authors':
                        $list = get_users( array(
                            'orderby' => 'display_name'
                        ) );

                        $type = 'author';
                        $queryID = 'ID';
                        $queryTitle = 'display_name';

                        break;
                        break;


                    case 'woo-products':
                        $list = get_posts( array(
                            'post_type' => 'product', 
                            'posts_per_page' => -1,
                            'orderby' => 'name',
                            'order' => 'ASC'
                        ) );

                        $type = 'woo-product';
                        $queryID = 'ID';
                        $queryTitle = 'post_title';

                        break;


                    case 'woo-categories':
                        $list = get_terms( array(
                            'taxonomy' => 'product_cat'
                        ) );

                        $type = 'woo-category';
                        $queryID = 'term_id';
                        $queryTitle = 'name';

                        break;


                    case 'woo-tags':
                        $list = get_terms( array(
                            'taxonomy' => 'product_tag'
                        ) );

                        $type = 'woo-tag';
                        $queryID = 'term_id';
                        $queryTitle = 'name';

                        break;


                    default:

                        $list = array();

                        break;

                }

            }else{

                $list = get_pages( array(
                    'post_type' => 'page',
                    'post_status' => 'publish', 
                    'posts_per_page' => -1
                ) );

                $type = 'page';
                $queryID = 'ID';
                $queryTitle = 'post_title';

            }

            

            if ( ! empty($list) ){
                foreach ( $list as $item ){

                    echo '                
                    <tr>
                        <td>
                            <a href="options-general.php?page='. $_GET['page'] .'&type='. $type .'&edit='. $item->{$queryID} .'">'. 
                            $item->{$queryTitle} .'</a>
                        </td>
                        <td>x</td>
                        <td>x</td>
                        <td>x</td>
                        <td>x</td>
                    </tr>
                    ';

                }
            }

        ?>
        </tbody>    
    </table>
</div>