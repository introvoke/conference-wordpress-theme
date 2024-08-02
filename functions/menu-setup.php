<?php

function menu_setup()
{
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'sequel'),
    ));
}

function create_default_menu()
{
    $menu_name = 'Primary Menu';
    $menu_exists = wp_get_nav_menu_object($menu_name);

    if (!$menu_exists) {
        $menu_id = wp_create_nav_menu($menu_name);

        if (!has_nav_menu('primary')) {
            $locations = get_theme_mod('nav_menu_locations');
            $locations['primary'] = $menu_id;
            set_theme_mod('nav_menu_locations', $locations);
        }

        wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' => __('Overview', 'sequel'),
            'menu-item-url' => '#top',
            'menu-item-status' => 'publish'
        ));

        wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' => __('Agenda', 'sequel'),
            'menu-item-url' => '#agenda',
            'menu-item-status' => 'publish'
        ));

        wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' => __('Speakers', 'sequel'),
            'menu-item-url' => '#speakers',
            'menu-item-status' => 'publish'
        ));
    }
}

class Conference_Walker_Nav_Menu extends Walker_Nav_Menu
{
    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
    {
        $classes_to_remove = array(
            'menu-item-type-custom',
            'menu-item-object-custom',
            'current_page_item',
            'menu-item-home',
        );
        $item->classes = array_diff($item->classes, $classes_to_remove);

        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        $classes = empty($item->classes) ? array() : (array)$item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';

        $output .= $indent . '<li' . $id . $class_names . '>';

        $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
        $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
        $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';
        $attributes .= ' @click="open = false"';

        $item_output = $args->before;
        $item_output .= '<a' . $attributes . '>';
        $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

    function end_el(&$output, $item, $depth = 0, $args = array())
    {
        $output .= "</li>\n";
    }

    function start_lvl(&$output, $depth = 0, $args = array())
    {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul>\n";
    }

    function end_lvl(&$output, $depth = 0, $args = array())
    {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }
}

function remove_nav_menu_container($args = '')
{
    $args['container'] = false;
    return $args;
}

add_action('after_setup_theme', 'menu_setup');
add_action('after_setup_theme', 'create_default_menu');
add_filter('wp_nav_menu_args', 'remove_nav_menu_container');
