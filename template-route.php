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
    error_log('GET parameters in custom route: ' . print_r($_GET, true));
    global $wp_query;

    $actions = array(
        'profile',
        'profile_edit',
        'orders'
    );

    if (!is_user_logged_in() && in_array(get_query_var('iti_cabinet_action'), $actions)) {
        wp_redirect(site_url('/login'));
        exit;
    }

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

    $actions = array(
        'login',
        'register'
    );

    if (is_user_logged_in() && in_array(get_query_var('iti_cabinet_action'), $actions)) {
        wp_redirect(site_url('/profile'));
        exit;
    }

    // Страница входа
    if (get_query_var('iti_cabinet_action') == 'login') {
        iti_cabinet_load_template('login-template.php');
        exit;
    }

    // Страница регистрации
    if (get_query_var('iti_cabinet_action') == 'register') {
        iti_cabinet_load_template('register-template.php');
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
    // Страницы профиля
    add_rewrite_rule('^profile/?$', 'index.php?iti_cabinet_action=profile', 'top');
    add_rewrite_rule('^profile-edit/?$', 'index.php?iti_cabinet_action=profile_edit', 'top');
    add_rewrite_rule('^orders/?$', 'index.php?iti_cabinet_action=orders', 'top');

    // Страницы логина и регистрации
    add_rewrite_rule('^login/?$', 'index.php?iti_cabinet_action=login', 'top');
    add_rewrite_rule('^register/?$', 'index.php?iti_cabinet_action=register', 'top');

}

add_action('init', 'iti_cabinet_rewrite_rules');

// Регистрация query vars для перехвата кастомных параметров
function iti_cabinet_query_vars($vars)
{
    $vars[] = 'iti_cabinet_action';
    $vars[] = 'error';
    return $vars;
}

add_filter('query_vars', 'iti_cabinet_query_vars');

// Функция для регистрации правил и перестроения правил маршрутизации при активации плагина
function iti_cabinet_flush_rewrite_rules()
{
    iti_cabinet_rewrite_rules(); // Зарегистрировать новые правила
    flush_rewrite_rules();       // Перестроить правила маршрутизации
}

function iti_cabinet_handle_login()
{
    if (isset($_POST['iti_login_nonce']) && wp_verify_nonce($_POST['iti_login_nonce'], 'iti_login_action')) {
        $creds = array(
            'user_login' => $_POST['log'],
            'user_password' => $_POST['pwd'],
            'remember' => isset($_POST['rememberme']) ? true : false,
        );

        $user = wp_signon($creds, false);

        if (is_wp_error($user)) {
            // Если произошла ошибка авторизации
            wp_redirect(site_url('/login?login=failed'));
            exit;
        } else {
            // Если логин успешен
            wp_redirect(site_url('/profile'));
            exit;
        }
    }
}

add_action('init', 'iti_cabinet_handle_login');

function iti_cabinet_login_url($redirect = '') {
    // Возвращаем URL страницы логина с опциональным редиректом после входа
    $login_url = site_url('/login');
    if (!empty($redirect)) {
        $login_url = add_query_arg('redirect_to', urlencode($redirect), $login_url);
    }
    return $login_url;
}
