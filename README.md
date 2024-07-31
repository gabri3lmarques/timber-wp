# ContextHelper Class

## Description

The `ContextHelper` class provides utility methods to manipulate and filter posts based on the custom 'color' taxonomy in WordPress.

## Methods

### `get_color_names($posts)`

Adds the `color_name` property to the provided post objects with the name of the 'color' taxonomy term.

**Parameters:**

- `$posts` (array): An array of post objects.

**Returns:**

- (array): An array of post objects with the additional `color_name` property.

**Usage Example:**

```php
$context = Timber::context();
$posts = Timber::get_posts();
//returns all the posts with a 'color_name' property
//this property comes from the taxonomy 'color' of the post
//this property can be called directly inside a .twig file
$context['posts'] = ContextHelper::get_color_names($posts);
```

### `get_color_name($post)`

Returns the name of the first 'color' taxonomy term associated with the provided post.

**Parameters:**

- `$post` (WP_Post): The post object.

**Returns:**

- (string): The name of the 'color' taxonomy term, or an empty string if there are no terms.

**Usage Example:**

```php
//single product
$post = Timber::get_post();
$context['post'] = $post;
//gets the color of the 'color' taxonomy
$color_name = ContextHelper::get_color_name($post);
//provides a 'color_name' property to $context
$context['color_name'] = $color_name;
```

### `filter_products_by_color($color, $posts_per_page)`

Creates an array of WP_Query arguments to filter products by the 'color' taxonomy.

**Parameters:**

- `$color` (string): The slug of the 'color' taxonomy term.
- `$posts_per_page` (int): The number of posts to be returned per page.

**Returns:**

- (array): An array of WP_Query arguments.

**Usage Example:**

```php
// creates a query for 'purple' color posts
$filtered_purple_products = ContextHelper::filter_products_by_color('purple', 2);
//returns the posts from the query 
$purple_products = Timber::get_posts($filtered_purple_products);
//give all posts a 'color_name' property with the filtered color
$context['purple_products'] = ContextHelper::get_color_names($purple_products);
```