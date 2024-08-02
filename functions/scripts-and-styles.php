<?php

function theme_styles(): void
{
    wp_enqueue_style('normalize', get_template_directory_uri() . '/assets/css/normalize.css');
    wp_enqueue_style('sequel', get_template_directory_uri() . '/assets/css/style.css');
}

add_action('wp_enqueue_scripts', 'theme_styles');

function theme_scripts(): void
{
    wp_enqueue_script('intersect', get_template_directory_uri() . '/assets/js/intersect.min.js', array(), '1.0.0', false);
    wp_enqueue_script('alpine', get_template_directory_uri() . '/assets/js/alpinejs.min.js', array(), '3.14.1', false);
    wp_enqueue_script('app', get_template_directory_uri() . '/assets/js/app.js', array(), '1.0.0', true);
}

function add_defer_attribute($tag, $handle)
{
    if ('alpine' === $handle) {
        return str_replace(' src', ' defer="defer" src', $tag);
    }
    return $tag;
}

add_action('wp_enqueue_scripts', 'theme_scripts');
add_filter('script_loader_tag', 'add_defer_attribute', 10, 2);