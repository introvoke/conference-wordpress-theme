<?php

include('functions/scripts-and-styles.php');
include('functions/theme-customizer.php');
include('functions/custom-post-types.php');
include('functions/add-pages.php');
include('functions/menu-setup.php');
include('functions/webhook.php');

// Tweaks

add_filter('show_admin_bar', '__return_false');