<?php

function create_and_set_homepage()
{
    $homepage = get_page_by_title('Homepage');

    if (!$homepage) {
        $homepage_id = wp_insert_post(array(
            'post_title' => 'Homepage',
            'post_content' => 'This page content is automatically generated.',
            'post_status' => 'publish',
            'post_type' => 'page',
        ));
        update_post_meta($homepage_id, '_wp_page_template', 'template-homepage.php');
        update_option('show_on_front', 'page');
        update_option('page_on_front', $homepage_id);
    } else {
        update_option('show_on_front', 'page');
        update_option('page_on_front', $homepage->ID);

        $template = get_post_meta($homepage->ID, '_wp_page_template', true);
        if ($template != 'template-homepage.php') {
            update_post_meta($homepage->ID, '_wp_page_template', 'template-homepage.php');
        }
    }
}

add_action('after_switch_theme', 'create_and_set_homepage');