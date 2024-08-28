<?php

function create_pages()
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

    $networking_hub = get_page_by_path('networking');

    if (!$networking_hub) {

        $networking_hub_id = wp_insert_post(array(
            'post_title' => 'Networking Hub',
            'post_name' => 'networking',
            'post_content' => "You can update your Networking Hub settings by navigating to Appearance > Customize. If you prefer not to use the Networking Hub feature, you can hide the page in the Menus section instead.",
            'post_status' => 'publish',
            'post_type' => 'page',
        ));

        if ($networking_hub_id) {
            update_post_meta($networking_hub_id, '_wp_page_template', 'template-networking-hub.php');
        }
    }
}

add_action('after_switch_theme', 'create_pages');