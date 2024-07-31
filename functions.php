<?php
/**
 * Timber starter-theme
 * https://github.com/timber/starter-theme
 */

// Load Composer dependencies.
require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/src/StarterSite.php';

Timber\Timber::init();

// Sets the directories (inside your theme) to find .twig files.
Timber::$dirname = [ 'templates', 'views' ];

new StarterSite();


function theme_enqueue_assets() {
    // Enqueue Styles
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css', array(), '5.3.0');
    wp_enqueue_style('main-style', get_template_directory_uri() . '/dist/css/main.min.css');

    //scripts
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', array(), '5.3.0', true);
    wp_enqueue_script('main-js', get_template_directory_uri() . '/dist/js/main.min.js', array(), '1.0', true);
}

add_action('wp_enqueue_scripts', 'theme_enqueue_assets');

//remove taxonomias
function disable_categories_and_tags() {
    unregister_taxonomy_for_object_type('category', 'post');
    unregister_taxonomy_for_object_type('post_tag', 'post');
}
add_action('init', 'disable_categories_and_tags');

//remove tas e taxonomias do painel admin
function remove_categories_tags_admin_menu() {
    remove_menu_page('edit-tags.php?taxonomy=category');
    remove_menu_page('edit-tags.php?taxonomy=post_tag'); 
}
add_action('admin_menu', 'remove_categories_tags_admin_menu');

//remove tags a categorias de metaboxes
function remove_categories_tags_metaboxes() {
    remove_meta_box('categorydiv', 'post', 'side'); 
    remove_meta_box('tagsdiv-post_tag', 'post', 'side');
}
add_action('admin_menu', 'remove_categories_tags_metaboxes');

// Registra o custom post type 'product'
function create_product_post_type() {
    $labels = array(
        'name'               => _x('Products', 'post type general name', 'textdomain'),
        'singular_name'      => _x('Product', 'post type singular name', 'textdomain'),
        'menu_name'          => _x('Products', 'admin menu', 'textdomain'),
        'name_admin_bar'     => _x('Product', 'add new on admin bar', 'textdomain'),
        'add_new'            => _x('Add New', 'product', 'textdomain'),
        'add_new_item'       => __('Add New Product', 'textdomain'),
        'new_item'           => __('New Product', 'textdomain'),
        'edit_item'          => __('Edit Product', 'textdomain'),
        'view_item'          => __('View Product', 'textdomain'),
        'all_items'          => __('All Products', 'textdomain'),
        'search_items'       => __('Search Products', 'textdomain'),
        'parent_item_colon'  => __('Parent Products:', 'textdomain'),
        'not_found'          => __('No products found.', 'textdomain'),
        'not_found_in_trash' => __('No products found in Trash.', 'textdomain')
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'products'), // Define a slug como 'products'
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'menu_icon'          => 'dashicons-cart', // Define o ícone do menu
        'supports'           => array('title', 'editor', 'excerpt', 'thumbnail', 'comments', 'revisions'),
        'show_in_rest'       => true, 
    );

    register_post_type('product', $args);
}
add_action('init', 'create_product_post_type');

// Registra a taxonomia 'color'
function create_color_taxonomy() {
    $labels = array(
        'name'              => _x('Colors', 'taxonomy general name', 'textdomain'),
        'singular_name'     => _x('Color', 'taxonomy singular name', 'textdomain'),
        'search_items'      => __('Search Colors', 'textdomain'),
        'all_items'         => __('All Colors', 'textdomain'),
        'parent_item'       => __('Parent Color', 'textdomain'),
        'parent_item_colon' => __('Parent Color:', 'textdomain'),
        'edit_item'         => __('Edit Color', 'textdomain'),
        'update_item'       => __('Update Color', 'textdomain'),
        'add_new_item'      => __('Add New Color', 'textdomain'),
        'new_item_name'     => __('New Color Name', 'textdomain'),
        'menu_name'         => __('Color', 'textdomain'),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'color', 'with_front' => false),
        'show_in_rest'       => true, // Enable Gutenberg editor
    );

    register_taxonomy('color', array('post', 'product'), $args);
}
add_action('init', 'create_color_taxonomy');

// Adiciona regras de reescrita personalizadas
function add_custom_rewrite_rules() {

    // /color/<color>
    add_rewrite_rule(
        '^color/([^/]+)/?$',
        'index.php?color=$matches[1]',
        'top'
    );


    // /color/<color>/<post>
    add_rewrite_rule(
        '^color/([^/]+)/([^/]+)/?$',
        'index.php?color=$matches[1]&name=$matches[2]',
        'top'
    );


    // /products
    add_rewrite_rule(
        '^products/?$',
        'index.php?post_type=product',
        'top'
    );


    // /products/color/<color>
    add_rewrite_rule(
        '^products/color/([^/]+)/?$',
        'index.php?post_type=product&color=$matches[1]',
        'top'
    );

    // /products/color/<color>/<product>
    add_rewrite_rule(
        '^products/color/([^/]+)/([^/]+)/?$',
        'index.php?post_type=product&color=$matches[1]&name=$matches[2]',
        'top'
    );

    // /product/<product> whit no color
    add_rewrite_rule(
        '^products/([^/]+)/?$',
        'index.php?post_type=product&name=$matches[1]',
        'top'
    );

    // Adiciona a regra genérica para posts, mas verifica se não é uma página
    add_rewrite_rule(
        '^([^/]+)/?$',
        'index.php?pagename=$matches[1]',
        'top'
    );
}
add_action('init', 'add_custom_rewrite_rules');

function filter_post_type_link($post_link, $post) {
    if ($post->post_type == 'post' || $post->post_type == 'product') {
        $terms = wp_get_object_terms($post->ID, 'color');
        if ($terms && !is_wp_error($terms)) {
            $term_slug = $terms[0]->slug;
            if ($post->post_type == 'product') {
                return home_url('/products/color/' . $term_slug . '/' . $post->post_name . '/');
            } else {
                return home_url('/color/' . $term_slug . '/' . $post->post_name . '/');
            }
        } else {
            if ($post->post_type == 'product') {
                return home_url('/products/' . $post->post_name . '/');
            } else {
                return home_url('/' . $post->post_name . '/');
            }
        }
    }
    return $post_link;
}

add_filter('post_type_link', 'filter_post_type_link', 10, 2);
add_filter('post_link', 'filter_post_type_link', 10, 2);

// Flush reescreve as regras ao ativar o plugin/tema
function custom_flush_rewrite_rules() {
    create_product_post_type();
    create_color_taxonomy();
    add_custom_rewrite_rules();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'custom_flush_rewrite_rules');






