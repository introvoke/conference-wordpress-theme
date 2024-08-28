<?php

function create_session_post_type()
{
    $labels = array(
        'name' => _x('Sessions', 'post type general name', 'sequel'),
        'singular_name' => _x('Session', 'post type singular name', 'sequel'),
        'menu_name' => _x('Sessions', 'admin menu', 'sequel'),
        'name_admin_bar' => _x('Session', 'add new on admin bar', 'sequel'),
        'add_new' => _x('Add New', 'session', 'sequel'),
        'add_new_item' => __('Add New Session', 'sequel'),
        'new_item' => __('New Session', 'sequel'),
        'edit_item' => __('Edit Session', 'sequel'),
        'view_item' => __('View Session', 'sequel'),
        'all_items' => __('All Sessions', 'sequel'),
        'search_items' => __('Search Sessions', 'sequel'),
        'parent_item_colon' => __('Parent Sessions:', 'sequel'),
        'not_found' => __('No sessions found.', 'sequel'),
        'not_found_in_trash' => __('No sessions found in Trash.', 'sequel')
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => false,
        'query_var' => true,
        'rewrite' => array('slug' => 'sessions'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title'),
        'show_in_rest' => true,
    );

    register_post_type('session', $args);
}

function add_session_meta_boxes()
{
    add_meta_box(
        'session_meta_box',
        'Session Details',
        'render_session_meta_box',
        'session',
        'normal',
        'default'
    );
}

function render_session_meta_box($post)
{

    $start_date = get_post_meta($post->ID, '_session_start_date', true);
    $end_date = get_post_meta($post->ID, '_session_end_date', true);
    $event_id = get_post_meta($post->ID, '_session_event_id', true);
    $thumbnail_url = get_post_meta($post->ID, '_session_thumbnail_url', true);
    $short_description = get_post_meta($post->ID, '_session_short_description', true);
    $description = get_post_meta($post->ID, '_session_description', true);
    $speaker_ids = get_post_meta($post->ID, '_session_speaker_ids', true);
    $timezone = get_post_meta($post->ID, '_session_timezone', true);
    $payload = get_post_meta($post->ID, '_session_payload', true);

    wp_nonce_field('session_nonce_action', 'session_nonce');

    $speakers = get_posts(array(
        'post_type' => 'speaker',
        'posts_per_page' => -1
    ));

    ?>
    <style>
        .session-meta-box input[type="text"],
        .session-meta-box input[type="datetime-local"],
        .session-meta-box textarea,
        .session-meta-box select {
            width: 100%;
            box-sizing: border-box;
        }

        .session-meta-box hr {
            width: 100%;
            border: none;
            margin: 24px 0px;
            height: 1px;
            background: #EFEFEF;
        }

        .session-meta-box .warning-box {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 8px;
            border-radius: 5px;
            margin: 20px 0;
            font-size: 14px;
        }
    </style>
    <div class="session-meta-box">
        <p>
            <label for="session_short_description">Short description:</label><br/>
            <textarea id="session_short_description" name="session_short_description" rows="4"><?php echo esc_textarea($short_description); ?></textarea>
            <em>This description will be shown on the front page.</em>
        </p>
        <p>
            <label for="session_description">Description:</label><br/>
            <?php
            wp_editor(
                $description,
                'session_description',
                array(
                    'textarea_name' => 'session_description',
                    'media_buttons' => true,
                    'textarea_rows' => 10,
                    'teeny' => false,
                    'quicktags' => true,
                )
            );
            ?>
            <em>This description will be shown on the session page.</em>
        </p>
        <p>
            <label for="session_speaker_ids">Speakers:</label><br/>
            <select id="session_speaker_ids" name="session_speaker_ids[]" multiple>
                <?php foreach ($speakers as $speaker) : ?>
                    <option value="<?php echo esc_attr($speaker->ID); ?>" <?php echo in_array($speaker->ID, (array)$speaker_ids) ? 'selected' : ''; ?>>
                        <?php echo esc_html($speaker->post_title); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <em>Hold down the Ctrl (Windows) or Cmd (Mac) key to select multiple speakers.</em>
        </p>
        <hr/>
        <div class="warning-box">
            This data automatically syncs with the event details from Sequel.io.
        </div>

        <p>
            <label for="session_timezone"><strong>Timezone:</strong></label><br/>
            <span><?php echo esc_attr($timezone); ?></span>
        </p>

        <p>
            <label for="session_start_date"><strong>Start Date:</strong></label><br/>
            <input type="hidden" id="session_start_date" name="session_start_date" value="<?php echo esc_attr($start_date); ?>"/>
            <span>
				<?php
                if (is_numeric($start_date)) {
                    $timestamp = $start_date;
                } else {
                    $timestamp = strtotime($start_date);
                }

                if ($timestamp !== false) {
                    $timezone = get_post_meta($post->ID, '_session_timezone', true);
                    $timezone = $timezone ? $timezone : 'UTC'; // Default to UTC if no timezone is set

                    try {
                        $datetime = new DateTime();
                        $datetime->setTimestamp($timestamp);
                        $datetime->setTimezone(new DateTimeZone($timezone));

                        echo $datetime->format('l, F d Y, h:i A');
                        echo " (".$timezone.")";
                    } catch (Exception $e) {
                        echo 'Invalid timezone';
                    }
                } else {
                    echo 'Warning: No start date';
                }
                ?>
			</span>
        </p>

        <p>
            <label for="session_start_date"><strong>Start Date:</strong></label><br/>
            <input type="hidden" id="session_end_date" name="session_end_date" value="<?php echo esc_attr($end_date); ?>"/>
            <span>
				<?php
                if (is_numeric($end_date)) {
                    $timestamp = $end_date;
                } else {
                    $timestamp = strtotime($end_date);
                }

                if ($timestamp !== false) {
                    $timezone = get_post_meta($post->ID, '_session_timezone', true);
                    $timezone = $timezone ? $timezone : 'UTC'; // Default to UTC if no timezone is set

                    try {
                        $datetime = new DateTime();
                        $datetime->setTimestamp($timestamp);
                        $datetime->setTimezone(new DateTimeZone($timezone));

                        echo $datetime->format('l, F d Y, h:i A');
                        echo " (".$timezone.")";
                    } catch (Exception $e) {
                        echo 'Invalid timezone';
                    }
                } else {
                    echo 'Warning: No start date';
                }
                ?>
			</span>
        </p>

        <p>
            <label for="session_thumbnail_url"><strong>Thumbnail URL:</strong></label><br/>
            <input type="hidden" id="session_thumbnail_url" name="session_thumbnail_url" value="<?php echo esc_attr($thumbnail_url); ?>"/>
            <span><?php echo esc_attr($thumbnail_url); ?></span>
        </p>
        <p>
            <label for="session_event_id"><strong>Event ID:</strong></label><br/>
            <input type="hidden" id="session_event_id" name="session_event_id" value="<?php echo esc_attr($event_id); ?>"/>
            <span><?php echo esc_attr($event_id); ?></span>
        </p>
        <p>
            <label for="session_payload"><strong>Payload:</strong></label><br/>
            <textarea id="session_payload" name="session_payload" rows="15" style="width: 100%;"><?php echo esc_textarea($payload); ?></textarea>
        </p>
    </div>
    <?php
}

function save_session_meta($post_id)
{
    if (!isset($_POST['session_nonce'])) {
        return $post_id;
    }
    $nonce = $_POST['session_nonce'];
    if (!wp_verify_nonce($nonce, 'session_nonce_action')) {
        return $post_id;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }
    if (isset($_POST['session_start_date'])) {
        update_post_meta($post_id, '_session_start_date', sanitize_text_field($_POST['session_start_date']));
    }
    if (isset($_POST['session_end_date'])) {
        update_post_meta($post_id, '_session_end_date', sanitize_text_field($_POST['session_end_date']));
    }
    if (isset($_POST['session_thumbnail_url'])) {
        update_post_meta($post_id, '_session_thumbnail_url', sanitize_text_field($_POST['session_thumbnail_url']));
    }
    if (isset($_POST['session_event_id'])) {
        update_post_meta($post_id, '_session_event_id', sanitize_text_field($_POST['session_event_id']));
    }
    if (isset($_POST['session_description'])) {
        update_post_meta($post_id, '_session_description', wp_kses_post($_POST['session_description']));
    }
    if (isset($_POST['session_short_description'])) {
        update_post_meta($post_id, '_session_short_description', sanitize_textarea_field($_POST['session_short_description']));
    }
    if (isset($_POST['session_speaker_ids'])) {
        $speaker_ids = array_map('sanitize_text_field', $_POST['session_speaker_ids']);
        update_post_meta($post_id, '_session_speaker_ids', $speaker_ids);
    }
    if (isset($_POST['session_timezone'])) {
        update_post_meta($post_id, '_session_timezone', sanitize_text_field($_POST['session_timezone']));
    }
}

function set_session_columns($columns)
{
    $columns = array(
        'cb' => '<input type="checkbox" />',
        'title' => 'Title',
        'start_date' => 'Start Date and Time',
        'thumbnail' => 'Thumbnail',
        'speakers' => 'Speakers'
    );
    return $columns;
}

function custom_session_column($column, $post_id)
{
    switch ($column) {
        case 'start_date':
            $start_date = get_post_meta($post_id, '_session_start_date', true);
            $timezone = get_post_meta($post_id, '_session_timezone', true);

            if (!empty($start_date)) {
                if (is_numeric($start_date)) {
                    $timestamp = $start_date;
                } else {
                    $timestamp = strtotime($start_date);
                }

                if ($timestamp !== false) {
                    // Use the saved timezone, or default to UTC
                    $timezone = $timezone ? $timezone : 'UTC';

                    try {
                        // Create DateTime object with the saved timezone
                        $datetime = new DateTime();
                        $datetime->setTimestamp($timestamp);
                        $datetime->setTimezone(new DateTimeZone($timezone));

                        // Display the date, time, and timezone
                        echo $datetime->format('l, F d Y, h:i A') . ' (' . esc_html($timezone) . ')';
                    } catch (Exception $e) {
                        echo 'Invalid timezone';
                    }
                } else {
                    echo 'Invalid start date';
                }
            } else {
                echo 'No start date available';
            }
            break;
        case 'thumbnail':
            $thumbnail = get_post_meta($post_id, '_session_thumbnail_url', true);
            echo "<img src='" . esc_html($thumbnail) . "' style='width: 100%; max-width: 300px; height: auto; border-radius: 4px;' />";
            break;
        case 'speakers':
            $speaker_ids = get_post_meta($post_id, '_session_speaker_ids', true);
            if (!empty($speaker_ids)) {
                $speakers = get_posts(array(
                    'post_type' => 'speaker',
                    'post__in' => $speaker_ids,
                    'posts_per_page' => -1
                ));
                $speaker_names = array();
                foreach ($speakers as $speaker) {
                    $speaker_names[] = $speaker->post_title;
                }
                echo esc_html(implode(', ', $speaker_names));
            } else {
                echo 'No speakers assigned';
            }
            break;
    }
}

function create_speaker_post_type()
{
    $labels = array(
        'name' => _x('Speakers', 'post type general name', 'sequel'),
        'singular_name' => _x('Speaker', 'post type singular name', 'sequel'),
        'menu_name' => _x('Speakers', 'admin menu', 'sequel'),
        'name_admin_bar' => _x('Speaker', 'add new on admin bar', 'sequel'),
        'add_new' => _x('Add New', 'speaker', 'sequel'),
        'add_new_item' => __('Add New Speaker', 'sequel'),
        'new_item' => __('New Speaker', 'sequel'),
        'edit_item' => __('Edit Speaker', 'sequel'),
        'view_item' => __('View Speaker', 'sequel'),
        'all_items' => __('All Speakers', 'sequel'),
        'search_items' => __('Search Speakers', 'sequel'),
        'parent_item_colon' => __('Parent Speaker:', 'sequel'),
        'not_found' => __('No speakers found.', 'sequel'),
        'not_found_in_trash' => __('No speakers found in Trash.', 'sequel')
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => false,
        'query_var' => true,
        'rewrite' => array('slug' => 'speakers'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title'),
        'show_in_rest' => true,
    );

    register_post_type('speaker', $args);
}

function add_speaker_meta_boxes()
{
    add_meta_box(
        'speaker_meta_box',
        'Speaker Details',
        'render_speaker_meta_box',
        'speaker',
        'normal',
        'default'
    );
}

function render_speaker_meta_box($post)
{

    $speaker_title = get_post_meta($post->ID, '_speaker_title', true);
    $linkedin_url = get_post_meta($post->ID, '_speaker_linkedin_url', true);
    $thumbnail_url = get_post_meta($post->ID, '_speaker_thumbnail_url', true);
    $speaker_email = get_post_meta($post->ID, '_speaker_email', true);

    wp_nonce_field('session_nonce_action', 'session_nonce');
    ?>
    <style>
        .speaker-meta-box input[type="text"],
        .speaker-meta-box input[type="email"],
        .speaker-meta-box select {
            width: 100%;
            box-sizing: border-box;
        }
    </style>
    <div class="speaker-meta-box">
        <p>
            <label for="speaker_title">Title:</label><br/>
            <input type="text" id="speaker_title" name="speaker_title" value="<?php echo esc_attr($speaker_title); ?>"/>
        </p>
        <p>
            <label for="speaker_linkedin_url">LinkedIn URL:</label><br/>
            <input type="text" id="speaker_linkedin_url" name="speaker_linkedin_url" value="<?php echo esc_attr($linkedin_url); ?>"/>
        </p>
        <p>
            <label for="speaker_thumbnail_url">Thumbnail:</label><br/>
            <input type="hidden" id="speaker_thumbnail_url" name="speaker_thumbnail_url" value="<?php echo esc_attr($thumbnail_url); ?>"/>
            <img id="speaker_thumbnail_preview" src="<?php echo esc_attr($thumbnail_url); ?>" style="max-width: 100%; height: auto;"/>
            <button type="button" id="upload_image_button" class="button">Upload Image</button>
        </p>
        <p>
            <label for="speaker_email">Email:</label><br/>
            <input type="email" id="speaker_email" name="speaker_email" value="<?php echo esc_attr($speaker_email); ?>"/>
        </p>
    </div>
    <?php
}


function save_speaker_meta($post_id)
{
    if (!isset($_POST['session_nonce'])) {
        return $post_id;
    }
    $nonce = $_POST['session_nonce'];
    if (!wp_verify_nonce($nonce, 'session_nonce_action')) {
        return $post_id;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }
    if (isset($_POST['speaker_title'])) {
        update_post_meta($post_id, '_speaker_title', sanitize_text_field($_POST['speaker_title']));
    }
    if (isset($_POST['speaker_linkedin_url'])) {
        update_post_meta($post_id, '_speaker_linkedin_url', sanitize_text_field($_POST['speaker_linkedin_url']));
    }
    if (isset($_POST['speaker_thumbnail_url'])) {
        update_post_meta($post_id, '_speaker_thumbnail_url', sanitize_text_field($_POST['speaker_thumbnail_url']));
    }
    if (isset($_POST['speaker_email'])) {
        update_post_meta($post_id, '_speaker_email', sanitize_text_field($_POST['speaker_email']));
    }
}


function set_speaker_columns($columns)
{
    $columns = array(
        'cb' => '<input type="checkbox" />',
        'title' => 'Name',
        'position' => 'Title',
        'thumbnail' => 'Thumbnail'
    );
    return $columns;
}

function custom_speaker_column($column, $post_id)
{
    switch ($column) {
        case 'position':
            $title = get_post_meta($post_id, '_speaker_title', true);
            echo esc_html($title);
            break;
        case 'thumbnail':
            $thumbnail = get_post_meta($post_id, '_speaker_thumbnail_url', true);
            if (!empty($thumbnail)) {
                echo "<img src='" . esc_html($thumbnail) . "' style='width: 64px; height: 64px; border-radius: 100%; object-fit: cover;' />";
            }
            break;
    }
}

function create_conference_menu()
{
    // Create top-level menu
    add_menu_page(
        __('Sequel.io', 'sequel'),
        __('Sequel.io', 'sequel'),
        'manage_options',
        'conference_menu',
        '',
        'dashicons-video-alt2',
        3
    );

    // Add Sessions as sub-menu item
    add_submenu_page(
        'conference_menu',
        __('Sessions', 'sequel'),
        __('Sessions', 'sequel'),
        'manage_options',
        'edit.php?post_type=session'
    );

    // Add Speakers as sub-menu item
    add_submenu_page(
        'conference_menu',
        __('Speakers', 'sequel'),
        __('Speakers', 'sequel'),
        'manage_options',
        'edit.php?post_type=speaker'
    );
}

function remove_conference_submenu()
{
    remove_submenu_page('conference_menu', 'conference_menu');
}

function enqueue_media_uploader()
{
    wp_enqueue_media();
    wp_enqueue_script('custom-media-uploader', get_template_directory_uri() . '/assets/js/media-uploader.js', array('jquery'), null, false);
}

add_action('init', 'create_session_post_type');
add_action('add_meta_boxes', 'add_session_meta_boxes');
add_action('save_post', 'save_session_meta');
add_filter('manage_edit-session_columns', 'set_session_columns');
add_action('manage_session_posts_custom_column', 'custom_session_column', 10, 2);

add_action('init', 'create_speaker_post_type');
add_action('add_meta_boxes', 'add_speaker_meta_boxes');
add_action('save_post', 'save_speaker_meta');
add_filter('manage_edit-speaker_columns', 'set_speaker_columns');
add_action('manage_speaker_posts_custom_column', 'custom_speaker_column', 10, 2);

add_action('admin_menu', 'create_conference_menu');
add_action('admin_menu', 'remove_conference_submenu', 999);
add_action('admin_enqueue_scripts', 'enqueue_media_uploader');