<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?></title>
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?> x-data="{ registerModal: false }">

<nav class="navigation">
    <div class="container">
        <div class="navigation-wrapper">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="logo" rel="home">
                <?php if (get_theme_mod('logo')) : ?>
                    <img src="<?php echo esc_url(get_theme_mod('logo')); ?>" alt="<?php bloginfo('name'); ?>">
                <?php else : ?>
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/sequel.svg'); ?>" alt="<?php bloginfo('name'); ?>">
                <?php endif; ?>
            </a>
            <div class="menu" x-data="{ open: false }">
                <div class="register" @click="registerModal = true">
                    Register
                </div>
                <div class="toggle" @click="open = !open">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 12H21M3 6H21M3 18H15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="links" x-bind:class="{ 'drawer': open }">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_id' => 'primary-menu',
                        'menu_class' => 'primary-menu',
                        'walker' => new Conference_Walker_Nav_Menu(),
                    ));
                    ?>
                    <div class="close" @click="open = !open">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>