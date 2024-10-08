<?php

function add_webhook_rewrite_rule()
{
    add_rewrite_rule('^webhook-listner/v1/event/?', 'index.php?webhook=event', 'top');
    add_rewrite_rule('^webhook-listner/v1/update-event/?', 'index.php?webhook=update-event', 'top');
    add_rewrite_rule('^webhook-listner/v1/delete-event/?', 'index.php?webhook=delete-event', 'top');
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
    if (isset($wp->query_vars['webhook'])) {
        switch ($wp->query_vars['webhook']) {
            case 'event':
                process_webhook_request('create');
                break;
            case 'update-event':
                process_webhook_request('update');
                break;
            case 'delete-event':
                process_webhook_request('delete');
                break;
        }
        exit;
    }
}

add_action('parse_request', 'handle_webhook_request');

function process_webhook_request($action)
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        status_header(405);
        echo 'Only POST requests are allowed';
        exit;
    }

    $data = json_decode(file_get_contents('php://input'), true);

    if ($action !== 'delete' && (!isset($data['details']['eventName'], $data['details']['startDate'], $data['details']['endDate'], $data['details']['timezone'], $data['details']['eventId']))) {
        status_header(400);
        echo 'Missing required fields';
        exit;
    }

    $event_id = sanitize_text_field($data['details']['eventId']);

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

    if ($action === 'delete') {
        if (!empty($existing_posts)) {
            $post_id = $existing_posts[0]->ID;
            wp_delete_post($post_id, true);
            status_header(200);
            echo 'Session deleted successfully';
        } else {
            status_header(404);
            echo 'Session not found';
        }
        exit;
    }

    $title = sanitize_text_field($data['details']['eventName']);
    $timezone = sanitize_text_field($data['details']['timezone']); // Sanitize timezone input

    try {
        $start_date = DateTime::createFromFormat('m/d/Y, g:i:s A', $data['details']['startDate'], new DateTimeZone('UTC'));
        $end_date = DateTime::createFromFormat('m/d/Y, g:i:s A', $data['details']['endDate'], new DateTimeZone('UTC'));

        if (!$start_date || !$end_date) {
            throw new Exception('Invalid date format');
        }

        $start_timestamp = $start_date->getTimestamp();
        $end_timestamp = $end_date->getTimestamp();
    } catch (Exception $e) {
        status_header(400);
        echo 'Invalid date or timezone';
        exit;
    }

    $thumbnail_url = esc_url_raw($data['details']['bannerUrl']);
    
    // Preserve HTML content by using wp_kses_post or a custom wp_kses call
    $description = wp_kses_post($data['details']['description']); // Allows safe HTML tags
    
    $short_description = wp_strip_all_tags($description);
    $short_description = substr($short_description, 0, 200) . "...";

    if ($action === 'update' && !empty($existing_posts)) {
        $post_id = $existing_posts[0]->ID;
        wp_update_post(array(
            'ID' => $post_id,
            'post_title' => $title
        ));
    } elseif ($action === 'create') {
        if (!empty($existing_posts)) {
            status_header(400);
            echo 'Event already exists';
            exit;
        }
        $post_id = wp_insert_post(array(
            'post_title' => $title,
            'post_type' => 'session',
            'post_status' => 'publish'
        ));
    } else {
        status_header(404);
        echo 'Session not found for updating';
        exit;
    }

    update_post_meta($post_id, '_session_start_date', $start_timestamp);
    update_post_meta($post_id, '_session_end_date', $end_timestamp);
    update_post_meta($post_id, '_session_thumbnail_url', $thumbnail_url);
    update_post_meta($post_id, '_session_event_id', $event_id);
    update_post_meta($post_id, '_session_description', $description); // Save HTML description
    update_post_meta($post_id, '_session_short_description', $short_description);
    update_post_meta($post_id, '_session_timezone', $timezone); // Save the timezone
    update_post_meta($post_id, '_session_payload', wp_json_encode($data)); // Save the JSON payload
	
	// Send POST request to external API
    $post_url = get_permalink($post_id);
    $payload = json_encode(array(
        'postId' => $event_id,
        'postUrl' => $post_url
    ));

    $response = wp_remote_post("https://api.introvoke.com/api/v3/events/{$event_id}/wpcms", array(
        'method'    => 'POST',
        'headers'   => array('Content-Type' => 'application/json'),
        'body'      => $payload,
        'timeout'   => 45
    ));

    if (is_wp_error($response)) {
        status_header(500);
        echo 'Error sending data to external API';
        exit;
    }

    status_header(200);
    echo 'Session processed successfully';
    exit;
}