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
$posts = Timber::get_posts();


// Atribui o array de posts modificados ao contexto
$context['posts'] = ContextHelper::get_color_names($posts);

$templates        = array( 'index.twig' );
Timber::render( $templates, $context );
