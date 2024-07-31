<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.2
 */

include 'includes/ContextHelper.php';

$context = Timber::context();
$posts = Timber::get_posts();
$context['posts'] = ContextHelper::get_color_names($posts);

Timber::render( 'archive.twig', $context );


