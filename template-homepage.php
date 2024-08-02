<?php /*
Template Name: Homepage
*/ ?>

<?php get_header(); ?>

<section class="hero" id="top">
    <div class="container">
        <div class="hero-wrapper">
            <div class="title">
                <h1><?php echo esc_html(get_theme_mod('conference_name', '')); ?></h1>
                <?php if ($subtitle = get_theme_mod('conference_subtitle', '')): ?>
                    <h2><?php echo esc_html($subtitle); ?></h2>
                <?php endif; ?>
            </div>
            <div class="description">
                <?php if ($description = get_theme_mod('conference_description', '')): ?>
                    <p><?php echo wp_kses_post($description); ?></p>
                <?php endif; ?>
            </div>
            <div class="register" @click="registerModal = true">
                Register
            </div>
        </div>
    </div>
</section>

<section class="agenda" id="agenda">
    <div class="container">
        <div class="agenda-wrapper">
            <h2>Agenda</h2>
            <div class="items-wrapper">
                <?php
                $args = array(
                    'post_type' => 'session',
                    'posts_per_page' => -1,
                    'meta_key' => '_session_start_date',
                    'orderby' => 'meta_value',
                    'order' => 'ASC',
                );

                $query = new WP_Query($args);
                $posts_with_timestamps = array();

                if ($query->have_posts()) {
                    while ($query->have_posts()) {
                        $query->the_post();
                        $start_date_timestamp = get_post_meta(get_the_ID(), '_session_start_date', true);
                        $end_date_timestamp = get_post_meta(get_the_ID(), '_session_end_date', true);
                        $posts_with_timestamps[] = array(
                            'post' => $post,
                            'start_timestamp' => $start_date_timestamp,
                            'end_timestamp' => $end_date_timestamp,
                        );
                    }

                    usort($posts_with_timestamps, function ($a, $b) {
                        return $a['start_timestamp'] - $b['start_timestamp'];
                    });

                    foreach ($posts_with_timestamps as $item) {
                        $post = $item['post'];
                        setup_postdata($post);

                        $start_date_timestamp = $item['start_timestamp'];
                        $end_date_timestamp = $item['end_timestamp'];
                        $timezone = get_post_meta($post->ID, '_session_timezone', true);
                        $event_id = get_post_meta($post->ID, '_session_event_id', true);
                        $thumbnail_url = get_post_meta($post->ID, '_session_thumbnail_url', true);
                        $description = get_post_meta($post->ID, '_session_short_description', true);
                        $speaker_ids = get_post_meta($post->ID, '_session_speaker_ids', true);

                        // Decode the speaker IDs if they are stored in JSON format
                        if (!is_array($speaker_ids)) {
                            $speaker_ids = json_decode($speaker_ids, true);
                        }
                        ?>
                        <div class="item" x-data="dateHandler()" x-init="initialize()" x-start-date="<?php echo $start_date_timestamp; ?>" x-end-date="<?php echo $end_date_timestamp; ?>">
                            <div class="datetime">
                                <div class="section">
                                    <div class="main" x-text="formattedDate"></div>
                                    <div class="small" x-text="dayOfWeek"></div>
                                </div>
                                <div class="section">
                                    <div class="main" x-text="formattedTime"></div>
                                    <div class="small" x-text="userTimezone"></div>
                                </div>
                            </div>
                            <div class="line">
                                <div class="dot"></div>
                            </div>
                            <div class="content hidden" x-data="{ visible: false }" x-intersect:enter="visible = true" x-intersect:leave="visible = false" :class="{ 'visible': visible }" x-transition>
                                <a href="<?php echo get_permalink(); ?>" class="thumbnail">
                                    <img src="<?php echo $thumbnail_url ? $thumbnail_url : get_template_directory_uri() . '/assets/images/thumbnail.png'; ?>">
                                    <div class="time">
                                        <div class="status" x-text="statusText">Live in:</div>
                                        <div class="timeleft" x-text="timeLeft"></div>
                                    </div>
                                </a>
                                <a href="<?php echo get_permalink(); ?>" class="title">
                                    <h3><?php echo $post->post_title; ?></h3>
                                </a>
                                <div class="description">
                                    <?php echo $description; ?>
                                </div>
                                <?php
                                if (!empty($speaker_ids) && is_array($speaker_ids)) {
                                    ?>
                                    <div class="speakers">
                                        <?php
                                        foreach ($speaker_ids as $speaker_id) {
                                            $speaker_post = get_post($speaker_id);
                                            if ($speaker_post) {
                                                $speaker_title = get_post_meta($speaker_post->ID, '_speaker_title', true);
                                                $speaker_thumbnail_url = get_post_meta($speaker_post->ID, '_speaker_thumbnail_url', true);
                                                ?>
                                                <div class="speaker">
                                                    <div class="photo">
                                                        <img src="<?php echo $speaker_thumbnail_url ? $speaker_thumbnail_url : get_template_directory_uri() . '/assets/images/speaker.png'; ?>">
                                                    </div>
                                                    <div class="info">
                                                        <div class="name"><?php echo $speaker_post->post_title; ?></div>
                                                        <div class="title"><?php echo $speaker_title; ?></div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                    <?php
                                }
                                ?>
                                <a href="<?php echo get_permalink(); ?>" x-text="buttonText" class="cta">Learn more</a>
                            </div>
                        </div>
                        <?php
                    }
                    wp_reset_postdata();
                } else {
                    echo '<p>No sessions found.</p>';
                }
                ?>
            </div>
        </div>
    </div>
</section>

<section class="speakers" id="speakers">
    <div class="container">
        <div class="speakers-wrapper">
            <h2>Speakers</h2>
            <div class="items-wrapper">
                <?php
                $args = array(
                    'post_type' => 'speaker',
                    'posts_per_page' => -1,
                    'orderby' => 'title',
                    'order' => 'ASC',
                );

                $query = new WP_Query($args);

                if ($query->have_posts()) {
                    while ($query->have_posts()) {
                        $query->the_post();
                        $speaker_title = get_post_meta(get_the_ID(), '_speaker_title', true);
                        $speaker_thumbnail_url = get_post_meta(get_the_ID(), '_speaker_thumbnail_url', true);
                        $speaker_linkedin = get_post_meta(get_the_ID(), '_speaker_linkedin_url', true);
                        ?>
                        <div class="speaker">
                            <div class="photo">
                                <img src="<?php echo $speaker_thumbnail_url ? $speaker_thumbnail_url : get_template_directory_uri() . '/assets/images/speaker.png'; ?>">
                            </div>
                            <div class="info">
                                <div class="name"><?php the_title(); ?></div>
                                <div class="title"><?php echo $speaker_title; ?></div>
                                <?php if (!empty($speaker_linkedin)) { ?>
                                    <a href="<?php echo $speaker_linkedin; ?>" target="_blank" class="linkedin">LinkedIn</a>
                                <?php } ?>
                            </div>
                        </div>
                        <?php
                    }
                    wp_reset_postdata();
                } else {
                    echo '<p>No speakers found.</p>';
                }
                ?>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>


