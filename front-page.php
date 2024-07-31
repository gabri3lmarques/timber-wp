<?php
/**
 * The main template file
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

include 'includes/ContextHelper.php';

$context = Timber::context();

// Argumentos para obter produtos
$product_args = array(
    'post_type'      => 'product',
    'posts_per_page' => 9,
    'order'          => 'DESC',
    'orderby'        => 'date',
);

$products = Timber::get_posts($product_args);

$context['products'] = ContextHelper::get_color_names($products);

// Argumentos para obter posts
$post_args = array(
    'post_type'      => 'post',
    'posts_per_page' => 9,
    'order'          => 'DESC',
    'orderby'        => 'date'
);

$context['posts'] = Timber::get_posts($post_args);

// Renderiza o template
Timber::render('front-page.twig', $context);
 
