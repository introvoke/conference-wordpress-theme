<?php

function add_webhook_rewrite_rule()
{
    add_rewrite_rule('^webhook/session/?', 'index.php?webhook=session', 'top');
}

add_action('init', 'add_webhook_rewrite_rule');

function add_webhook_query_vars($query_vars)
{
    $query_vars[] = 'webhook';
    return $query_vars;
}

add_filter('query_vars', 'add_webhook_query_vars');

function handle_webhook_request($wp)
{
    if (isset($wp->query_vars['webhook']) && $wp->query_vars['webhook'] == 'session') {
        process_webhook_request();
        exit;
    }
}

add_action('parse_request', 'handle_webhook_request');

function process_webhook_request()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        status_header(405);
        echo 'Only POST requests are allowed';
        exit;
    }

    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['title'], $data['start_date'], $data['end_date'], $data['event_id'])) {
        status_header(400);
        echo 'Missing required fields';
        exit;
    }

    if (!is_numeric($data['start_date']) || !is_numeric($data['end_date']) ||
        (int)$data['start_date'] < 0 || (int)$data['end_date'] < 0 ||
        (int)$data['start_date'] > 2147483647 || (int)$data['end_date'] > 2147483647) {
        status_header(400);
        echo 'Invalid start_date or end_date';
        exit;
    }

    // Sanitize data
    $title = sanitize_text_field($data['title']);
    $start_date = (int)$data['start_date'];
    $end_date = (int)$data['end_date'];
    $thumbnail_url = esc_url_raw($data['thumbnail_url']);
    $event_id = sanitize_text_field($data['event_id']);

    $existing_posts = get_posts(array(
        'post_type' => 'session',
        'meta_query' => array(
            array(
                'key' => '_session_event_id',
                'value' => $event_id,
                'compare' => '='
            )
        )
    ));

    if (!empty($existing_posts)) {
        $post_id = $existing_posts[0]->ID;
        wp_update_post(array(
            'ID' => $post_id,
            'post_title' => $title
        ));
    } else {
        $post_id = wp_insert_post(array(
            'post_title' => $title,
            'post_type' => 'session',
            'post_status' => 'publish'
        ));
    }

    update_post_meta($post_id, '_session_start_date', $start_date);
    update_post_meta($post_id, '_session_end_date', $end_date);
    update_post_meta($post_id, '_session_thumbnail_url', $thumbnail_url);
    update_post_meta($post_id, '_session_event_id', $event_id);

    // Return a success response
    status_header(200);
    echo 'Session processed successfully';
    exit;
}