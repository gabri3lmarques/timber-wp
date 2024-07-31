<?php
/**
 * The Template for displaying all single posts
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

include 'includes/ContextHelper.php';

// creates a query for yellow products
$filtered_yellow_products = ContextHelper::filter_products_by_color('yellow', 2);

// creates a query for green products
$filtered_green_products = ContextHelper::filter_products_by_color('green', 2);

// creates a query for purple products
$filtered_purple_products = ContextHelper::filter_products_by_color('purple', 2);

$context = Timber::context();

//take the mais product of the page
$post = Timber::get_post();
$context['post'] = $post;
$color_name = ContextHelper::get_color_name($post);
$context['color_name'] = $color_name;

//put all the yellow products on a array
$yellow_products = Timber::get_posts($filtered_yellow_products);
$context['yellow_products'] = ContextHelper::get_color_names($yellow_products);

//put all the green products on a array
$green_products = Timber::get_posts($filtered_green_products);
$context['green_products'] = ContextHelper::get_color_names($green_products);

//put all the purple products on a array
$purple_products = Timber::get_posts($filtered_purple_products);
$context['purple_products'] = ContextHelper::get_color_names($purple_products);

// take the products with the same color of the mais product
$filtered_same_color = ContextHelper::filter_products_by_color($color_name, 4);
$same_color_products = Timber::get_posts($filtered_same_color);
$context['same_color_products'] = ContextHelper::get_color_names($same_color_products);

Timber::render('single-product.twig', $context);
 


