<?php

function iti_cabinet_custom_template($template)
{
    // Проверка для страницы профиля
    if (is_page('profile') && !locate_template('page-profile.php')) {
        return plugin_dir_path(__FILE__) . 'templates/profile-template.php';
    }

    // Проверка для страницы редактирования профиля
    if (is_page('profile-edit') && !locate_template('page-profile-edit.php')) {
        return plugin_dir_path(__FILE__) . 'templates/profile-edit-template.php';
    }

    // Проверка для страницы истории заказов
    if (is_page('orders') && !locate_template('page-orders.php')) {
        return plugin_dir_path(__FILE__) . 'templates/orders-template.php';
    }

    return $template;
}

//add_filter('template_include', 'iti_cabinet_custom_template');

function iti_cabinet_template_redirect()
{
    global $wp_query;

    // Проверка и подмена шаблонов на основе query var
    if (get_query_var('iti_cabinet_action') == 'profile') {
        iti_cabinet_load_template('profile-template.php');
        exit;
    }

    if (get_query_var('iti_cabinet_action') == 'profile_edit') {
        iti_cabinet_load_template('profile-edit-template.php');
        exit;
    }

    if (get_query_var('iti_cabinet_action') == 'orders') {
        iti_cabinet_load_template('orders-template.php');
        exit;
    }
}

add_action('template_redirect', 'iti_cabinet_template_redirect');

// Функция для загрузки шаблонов плагина
function iti_cabinet_load_template($template_name)
{
    $template_path = plugin_dir_path(__FILE__) . 'templates/' . $template_name;
    if (file_exists($template_path)) {
        include($template_path);
    } else {
        wp_die('Шаблон не найден');
    }
}

function iti_cabinet_rewrite_rules()
{
    add_rewrite_rule('^profile/?$', 'index.php?iti_cabinet_action=profile', 'top');
    add_rewrite_rule('^profile-edit/?$', 'index.php?iti_cabinet_action=profile_edit', 'top');
    add_rewrite_rule('^orders/?$', 'index.php?iti_cabinet_action=orders', 'top');
}

add_action('init', 'iti_cabinet_rewrite_rules');

// Регистрация query vars для перехвата кастомных параметров
function iti_cabinet_query_vars($vars)
{
    $vars[] = 'iti_cabinet_action';
    return $vars;
}

add_filter('query_vars', 'iti_cabinet_query_vars');

// Функция для регистрации правил и перестроения правил маршрутизации при активации плагина
function iti_cabinet_flush_rewrite_rules() {
    iti_cabinet_rewrite_rules(); // Зарегистрировать новые правила
    flush_rewrite_rules();       // Перестроить правила маршрутизации
}
