<?php
function customizer_register($wp_customize)
{

    $wp_customize->add_setting('logo');
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'logo_control', array(
        'label' => __('Logo', 'sequel'),
        'section' => 'title_tagline',
        'settings' => 'logo',
    )));

    $wp_customize->add_setting('logo_height', array(
        'default' => 32,
        'sanitize_callback' => 'absint',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('logo_height_control', array(
        'label' => __('Logo Height', 'sequel'),
        'section' => 'title_tagline',
        'settings' => 'logo_height',
        'type' => 'range',
        'input_attrs' => array(
            'min' => 20,
            'max' => 36,
            'step' => 2,
        ),
    ));

    $wp_customize->add_section('fonts_section', array(
        'title' => __('Fonts', 'sequel'),
        'description' => __('Customize the theme’s body text and heading fonts. All are provided by <a href="https://fonts.google.com" target="_blank">Google Fonts</a>.', 'sequel'),
        'priority' => 30,
    ));

    $fonts_file = get_template_directory() . '/assets/json/google-fonts.json';
    $fonts = array();

    if (file_exists($fonts_file)) {
        $fonts = json_decode(file_get_contents($fonts_file), true);
    }

    $wp_customize->add_setting('global_font', array(
        'default' => 'Inter',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('global_font_control', array(
        'label' => __('Body', 'sequel'),
        'section' => 'fonts_section',
        'settings' => 'global_font',
        'type' => 'select',
        'choices' => $fonts,
    ));

    $wp_customize->add_setting('global_headings_font', array(
        'default' => 'Space Grotesk',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('global_headings_font_control', array(
        'label' => __('Headings', 'sequel'),
        'section' => 'fonts_section',
        'settings' => 'global_headings_font',
        'type' => 'select',
        'choices' => $fonts,
    ));

    $wp_customize->add_panel('colors_panel', array(
        'title' => __('Colors & Backgrounds', 'sequel'),
        'description' => __('Customize the theme’s global colors and backgrounds, along with specific options for different sections.', 'sequel'),
        'priority' => 31,
    ));

    $wp_customize->add_section('global_colors_section', array(
        'title' => __('Global', 'sequel'),
        'panel' => 'colors_panel',
        'priority' => 1,
    ));

    $wp_customize->add_setting('global_brand', array(
        'default' => '#000000',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'global_brand_control', array(
        'label' => __('Brand Color', 'sequel'),
        'section' => 'global_colors_section',
        'settings' => 'global_brand',
    )));

    $wp_customize->add_setting('global_background', array(
        'default' => '#ffffff',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'global_background_control', array(
        'label' => __('Background Color', 'sequel'),
        'section' => 'global_colors_section',
        'settings' => 'global_background',
    )));

    $wp_customize->add_setting('global_text', array(
        'default' => '#000000',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'global_text_control', array(
        'label' => __('Text Color', 'sequel'),
        'section' => 'global_colors_section',
        'settings' => 'global_text',
    )));

    $wp_customize->add_section('navigation_section', array(
        'title' => __('Navigation', 'sequel'),
        'panel' => 'colors_panel',
        'priority' => 2,
    ));

    $wp_customize->add_setting('navigation_background', array(
        'default' => '#ffffff',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'navigation_background_control', array(
        'label' => __('Background Color', 'sequel'),
        'section' => 'navigation_section',
        'settings' => 'navigation_background',
    )));

    $wp_customize->add_setting('navigation_links', array(
        'default' => '#000000',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'navigation_links_control', array(
        'label' => __('Text Color', 'sequel'),
        'section' => 'navigation_section',
        'settings' => 'navigation_links',
    )));

    $wp_customize->add_section('hero_section', array(
        'title' => __('Hero', 'sequel'),
        'panel' => 'colors_panel',
        'priority' => 3,
    ));

    $wp_customize->add_setting('hero_background', array(
        'default' => '#ffffff',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'hero_background_control', array(
        'label' => __('Background Color', 'sequel'),
        'section' => 'hero_section',
        'settings' => 'hero_background',
    )));

    $wp_customize->add_setting('hero_text', array(
        'default' => '#000000',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'hero_text_control', array(
        'label' => __('Text Color', 'sequel'),
        'section' => 'hero_section',
        'settings' => 'hero_text',
    )));

    $wp_customize->add_setting('hero_background_image');
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'hero_background_image_control', array(
        'label' => __('Background Image', 'sequel'),
        'section' => 'hero_section',
        'settings' => 'hero_background_image',
    )));

    $wp_customize->add_setting('hero_background_repeat', array(
        'default' => 'rounded',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('hero_background_repeat_control', array(
        'label' => __('Background Image Repeat', 'sequel'),
        'section' => 'hero_section',
        'settings' => 'hero_background_repeat',
        'type' => 'radio',
        'choices' => array(
            'no-repeat' => __('No repeat', 'sequel'),
            'repeat-x' => __('Repeat horizontally', 'sequel'),
            'repeat-y' => __('Repeat vertically', 'sequel'),
            'repeat' => __('Repeat horizontally & vertically', 'sequel'),
        ),
    ));

    $wp_customize->add_section('styling_section', array(
        'title' => __('Styling', 'sequel'),
        'priority' => 32,
    ));

    $wp_customize->add_setting('button_style', array(
        'default' => 'rounded',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('button_style_control', array(
        'label' => __('Corners Style', 'sequel'),
        'section' => 'styling_section',
        'settings' => 'button_style',
        'type' => 'radio',
        'choices' => array(
            'sharp' => __('Sharp', 'sequel'),
            'rounded' => __('Rounded', 'sequel'),
            'circle' => __('Circle', 'sequel'),
        ),
    ));

    $wp_customize->add_setting('navigation_shadow', array(
        'default' => false,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('navigation_shadow_control', array(
        'label' => __('Navigation Shadow', 'sequel'),
        'section' => 'styling_section',
        'settings' => 'navigation_shadow',
        'type' => 'checkbox',
    ));

    $wp_customize->add_section('conference_details_section', array(
        'title' => __('Conference Details', 'sequel'),
        'priority' => 33,
    ));

    $wp_customize->add_setting('conference_name', array(
        'default' => 'Innovate & Elevate',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('conference_name_control', array(
        'label' => __('Name', 'sequel'),
        'section' => 'conference_details_section',
        'settings' => 'conference_name',
        'type' => 'text',
    ));

    $wp_customize->add_setting('conference_subtitle', array(
        'default' => 'Strategies, Trends, and Technologies Shaping Tomorrow’s Marketplace',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('conference_subtitle_control', array(
        'label' => __('Subtitle', 'sequel'),
        'section' => 'conference_details_section',
        'settings' => 'conference_subtitle',
        'type' => 'text',
    ));
    $wp_customize->add_setting('conference_description', array(
        'default' => 'Join us at Innovate & Elevate: The Future of Marketing, a premier conference dedicated to exploring the cutting-edge strategies, emerging trends, and transformative technologies that are reshaping the marketing landscape. This event is designed for marketing professionals, industry leaders, and innovators who are eager to stay ahead in an ever-evolving marketplace.',
        'sanitize_callback' => 'wp_kses_post',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('conference_description_control', array(
        'label' => __('Description', 'sequel'),
        'section' => 'conference_details_section',
        'settings' => 'conference_description',
        'type' => 'textarea',
    ));

    $wp_customize->add_section('registration_form_section', array(
        'title' => __('Registration Form', 'sequel'),
        'priority' => 34,
    ));

	$wp_customize->add_setting('registration_form_code', array(
		'default' => '',
		'sanitize_callback' => null, // Disable sanitization
	));

	$wp_customize->add_control('registration_form_code_control', array(
		'label' => __('Registration Form Code', 'sequel'),
		'section' => 'registration_form_section',
		'settings' => 'registration_form_code',
		'type' => 'textarea',
	));
	$wp_customize->add_section('networking_hub_section', array(
		'title' => __('Networking Hub', 'sequel'),
		'priority' => 35,
	));
	$wp_customize->add_setting('networking_hub_id', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_text_field', 
	));
	$wp_customize->add_control('networking_hub_id_control', array(
		'label' => __('Networking Hub ID', 'sequel'),
		'section' => 'networking_hub_section',
		'settings' => 'networking_hub_id',
		'type' => 'text', 
	));
    $wp_customize->add_section('tracking_codes_section', array(
        'title' => __('Tracking Codes', 'sequel'),
        'priority' => 36,
    ));
    $wp_customize->add_setting('tracking_codes', array(
        'default' => '',
		'sanitize_callback' => null, // Disable sanitization
    ));
    $wp_customize->add_control('tracking_codes_control', array(
        'label' => __('Tracking Codes', 'sequel'),
        'section' => 'tracking_codes_section',
        'settings' => 'tracking_codes',
        'type' => 'textarea',
    ));
}

function customizer_preview_js()
{
    wp_enqueue_script('sequel-customizer', get_template_directory_uri() . 'assets/js/customizer.js', array('customize-preview'), '1.0.0', true);
}

function hexToRgba($hex, $alpha)
{
    $hex = str_replace('#', '', $hex);
    if (strlen($hex) == 6) {
        list($r, $g, $b) = array(
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2))
        );
    } elseif (strlen($hex) == 3) {
        list($r, $g, $b) = array(
            hexdec(str_repeat(substr($hex, 0, 1), 2)),
            hexdec(str_repeat(substr($hex, 1, 1), 2)),
            hexdec(str_repeat(substr($hex, 2, 1), 2))
        );
    } else {
        return false;
    }
    return "rgba($r, $g, $b, $alpha)";
}

function getLuminance($r, $g, $b)
{
    return 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;
}

function getContrastColor($hex)
{
    $hex = str_replace('#', '', $hex);
    if (strlen($hex) == 6) {
        list($r, $g, $b) = array(
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2))
        );
    } elseif (strlen($hex) == 3) {
        list($r, $g, $b) = array(
            hexdec(str_repeat(substr($hex, 0, 1), 2)),
            hexdec(str_repeat(substr($hex, 1, 1), 2)),
            hexdec(str_repeat(substr($hex, 2, 1), 2))
        );
    } else {
        return '#000000';
    }
    $luminance = getLuminance($r, $g, $b);
    return ($luminance > 128) ? '#000000' : '#FFFFFF';
}

function enqueue_google_fonts()
{
    $body_font = get_theme_mod('global_font', 'Inter');
    $headings_font = get_theme_mod('global_headings_font', 'Space Grotesk');
    wp_enqueue_style('google-fonts-body', 'https://fonts.googleapis.com/css2?family=' . str_replace(' ', '+', $body_font) . '&display=swap', false);
    if ($headings_font != $body_font) {
        wp_enqueue_style('google-fonts-headings', 'https://fonts.googleapis.com/css2?family=' . str_replace(' ', '+', $headings_font) . '&display=swap', false);
    }
}

function customizer_css()
{
    ?>
    <style>
        :root {
            --global-brand-color: <?php echo esc_attr(get_theme_mod('global_brand', '#000000')); ?>;
            --global-background-color: <?php echo esc_attr(get_theme_mod('global_background', '#FFFFFF')); ?>;
            --global-text-color: <?php echo esc_attr(get_theme_mod('global_text', '#000000')); ?>;
            --global-headings-font-family: <?php echo esc_attr(get_theme_mod('global_headings_font', 'Space Grotesk')); ?>;
            --global-font-family: <?php echo esc_attr(get_theme_mod('global_font', 'Inter')); ?>;
            --global-button-radius: <?php echo esc_attr(get_theme_mod('button_style', 'sharp') == 'rounded') ? '8px' : ((get_theme_mod('button_style', 'sharp') == 'circle') ? '1000px' : '0px'); ?>;
            --global-box-radius: <?php echo esc_attr(get_theme_mod('button_style', 'sharp') == 'rounded') ? '8px' : ((get_theme_mod('button_style', 'sharp') == 'circle') ? '16px' : '0px'); ?>;
            --navigation-logo-height: <?php echo esc_attr(get_theme_mod('logo_height', '32'))."px"; ?>;
            --navigation-background-color: <?php echo esc_attr(get_theme_mod('navigation_background', '#FFFFFF')); ?>;
            --navigation-links-color: <?php echo esc_attr(get_theme_mod('navigation_links', '#000000')); ?>;
            --navigation-button-text-color: <?php echo esc_attr(getContrastColor(get_theme_mod('navigation_links', '#000000'))); ?>;
            --navigation-drawer-background: <?php echo esc_attr(hexToRgba(get_theme_mod('navigation_background', '#FFFFFF'),0.85)); ?>;
            --navigation-shadow: <?php if (get_theme_mod('navigation_shadow', false)): ?> 0px 0.4px 5.3px rgba(0, 0, 0, 0.024), 0px 1.3px 17.9px rgba(0, 0, 0, 0.036), 0px 6px 80px rgba(0, 0, 0, 0.06) <?php else: ?> none<?php endif; ?>;
            --hero-text-color: <?php echo esc_attr(get_theme_mod('hero_text', '#000000')); ?>;
            --hero-background-color: <?php echo esc_attr(get_theme_mod('hero_background', '#FFFFFF')); ?>;
            --hero-background-image: url("<?php echo esc_attr(get_theme_mod('hero_background_image', 'none')); ?>");
            --hero-background-image-repeat: <?php echo esc_attr(get_theme_mod('hero_background_repeat', 'no-repeat')); ?>;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
    <?php
}

add_action('customize_register', 'customizer_register');
add_action('customize_preview_init', 'customizer_preview_js');
add_action('wp_enqueue_scripts', 'enqueue_google_fonts');
add_action('wp_head', 'customizer_css');