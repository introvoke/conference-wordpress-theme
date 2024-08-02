<?php get_header(); ?>

<?php
if (have_posts()) :
    while (have_posts()) : the_post();
        $start_date_timestamp = get_post_meta($post->ID, '_session_start_date', true);
        $end_date_timestamp = get_post_meta($post->ID, '_session_end_date', true);
        $timezone = get_post_meta($post->ID, '_session_timezone', true);
        $event_id = get_post_meta($post->ID, '_session_event_id', true);
        $description = get_post_meta($post->ID, '_session_description', true);
        $speaker_ids = get_post_meta($post->ID, '_session_speaker_ids', true);

        echo '<!-- Start Timestamp: ' . $start_date_timestamp . ' -->';
        echo '<!-- End Timestamp: ' . $end_date_timestamp . ' -->';

        $args = array(
            'post_type' => 'session',
            'posts_per_page' => 1,
            'meta_key' => '_session_start_date',
            'meta_value' => $start_date_timestamp,
            'meta_compare' => '>',
            'order' => 'ASC',
        );
        $next_session_query = new WP_Query($args);
        $next_session = null;
        if ($next_session_query->have_posts()) {
            $next_session_query->the_post();
            $next_session = $post;
            wp_reset_postdata();
        } else {
            echo '<!-- No next session found -->';
        }
        ?>
        <section class="session-title">
            <div class="container">
                <h1 class="title"><?php the_title(); ?></h1>
            </div>
        </section>
        <section class="session-embed">
            <div class="embed-container">
                <script type="text/javascript">
                    const e = "<?php echo $event_id; ?>", u = new URLSearchParams(window.location.search), i = document.createElement('iframe');
                    i.className = "sequel-iframe", i.title = "Sequel event", i.width = "100%", i.height = "90vh", i.src = "https://embed.sequel.io/event/" + e + (u.toString() ? '?' + u.toString() : ''), i.frameBorder = "0", i.allow = "camera *; microphone *; autoplay; display-capture *; picture-in-picture", i.allowFullscreen = !0, i.style.cssText = "height: 70vh; border-radius: 0; box-shadow: 3px 3px 10px 0 rgb(20 20 43 / 4%); width:100%;", document.currentScript.parentNode.insertBefore(i, document.currentScript);
                </script>
            </div>
        </section>
        <?php if ($next_session): ?>
            <section class="session-next">
                <div class="container">
                    <div class="next-wrapper">
                        <h2>Next session</h2>
                        <div class="item" x-data="dateHandler()" x-init="initialize()" x-start-date="<?php echo get_post_meta($next_session->ID, '_session_start_date', true); ?>"
                             x-end-date="<?php echo get_post_meta($next_session->ID, '_session_end_date', true); ?>">
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
                            <div class="content">
                                <a href="<?php echo get_permalink($next_session->ID); ?>" class="title">
                                    <h3><?php echo get_the_title($next_session->ID); ?></h3>
                                </a>
                                <div class="time">
                                    <div class="status" x-text="statusText">Live in:</div>
                                    <div class="timeleft" x-text="timeLeft"></div>
                                </div>
                                <div class="description">
                                    <?php echo get_post_meta($next_session->ID, '_session_short_description', true); ?>
                                </div>
                                <?php
                                if (!empty(get_post_meta($next_session->ID, '_session_speaker_ids', true)) && is_array(get_post_meta($next_session->ID, '_session_speaker_ids', true))) {
                                    ?>
                                    <div class="speakers">
                                        <?php
                                        foreach (get_post_meta($next_session->ID, '_session_speaker_ids', true) as $speaker_id) {
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
                                <a href="<?php echo get_permalink($next_session->ID); ?>" x-text="buttonText" class="cta">Learn more</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php endif; ?>
        <section class="session-info">
            <div class="container">
                <div class="info-wrapper">
                    <h2>About this session</h2>
                    <p><?php echo $description; ?></p>
                </div>
            </div>
        </section>
        <section class="session-speakers">
            <div class="container">
                <div class="speakers-wrapper">
                    <h2>Speakers</h2>
                    <div class="speakers">
                        <?php
                        if (is_array($speaker_ids)) {
                            foreach ($speaker_ids as $speaker_id) {
                                $speaker_post = get_post($speaker_id);
                                if ($speaker_post) {
                                    $speaker_title = get_post_meta($speaker_post->ID, '_speaker_title', true);
                                    $speaker_thumbnail_url = get_post_meta($speaker_post->ID, '_speaker_thumbnail_url', true);
                                    ?>
                                    <div class="speaker">
                                        <div class="photo">
                                            <img src="<?php echo $speaker_thumbnail_url ? esc_url($speaker_thumbnail_url) : esc_url(get_template_directory_uri() . '/assets/images/speaker.png'); ?>"
                                                 alt="<?php echo esc_attr($speaker_post->post_title); ?>">
                                        </div>
                                        <div class="info">
                                            <div class="name"><?php echo esc_html($speaker_post->post_title); ?></div>
                                            <div class="title"><?php echo esc_html($speaker_title); ?></div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </section>
    <?php
    endwhile;
    wp_reset_postdata();
else :
    ?>
    <section class="session-title">
        <h1 class="title">Sorry, no sessions found</h1>
    </section>
<?php
endif;
?>

<?php get_footer(); ?>