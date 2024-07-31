<?php 

class ContextHelper {

    //this method returns a array of posts object with the 'color_name' property 
    public static function get_color_names($posts) {
        $modified_posts = [];
        foreach ($posts as $post) {
            $terms = get_the_terms($post->ID, 'color');
            if (!is_wp_error($terms) && !empty($terms)) {
                $post->color_name = $terms[0]->name;
            } else {
                $post->color_name = null;
            }
            $modified_posts[] = $post;
        }
        return $modified_posts;
    }

    //this method returns a color_name property of a single post
    public static function get_color_name($post) {
        $color_terms = $post->terms('color');
        $color_name = !empty($color_terms) ? $color_terms[0]->name : '';
        return $color_name;
    }

    //this method create a query filtering products by color and amount
    public static function filter_products_by_color($color, $posts_per_page) {
        return array(
            'post_type'      => 'product',
            'posts_per_page' => $posts_per_page,
            'order'          => 'DESC',
            'orderby'        => 'date',
            'tax_query'      => array(
                array(
                    'taxonomy' => 'color',
                    'field'    => 'slug',
                    'terms'    => $color,
                ),
            ),
        );
    }    
}