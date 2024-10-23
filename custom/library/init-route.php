<?php

// Подключение шаблона библиотеки
add_action('template_redirect', 'library_templates');

function library_templates()
{

    $action = get_query_var('iti_cabinet_action');
    $uri = 'library';
    $template_name = 'page-library.php';

    if($action == $uri) {
        if (is_user_logged_in()) {

            $template_path = plugin_dir_path(__FILE__) . 'templates/' . $template_name;
            if (file_exists($template_path)) {
                include($template_path);
            } else {
                wp_die('Шаблон не найден');
            }
            exit;
        } else {
            wp_redirect(site_url('/login'));
            exit;
        }
    }
}

function library_rewrite_rules()
{
    // Страницы библиотеки
    add_rewrite_rule('^library/([^/]*)/?$', 'index.php?iti_cabinet_action=library&category=$matches[1]', 'top');
    add_rewrite_rule('^library/?$', 'index.php?iti_cabinet_action=library', 'top');
}

add_action('init', 'library_rewrite_rules');
