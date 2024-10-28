<?php

add_action('init', 'init_library');

function init_library()
{
    if (is_user_logged_in()) {
        add_action('wp_head', 'add_page_button_user_menu', 100);
        add_action('wp_footer', 'library_template_button_user_menu', 1);
    } else {
        add_action('wp_footer', 'library_template_button_user_login', 1);
    }
}
