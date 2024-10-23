<?php
/*
function iti_cabinet_custom_template($template)
{
    // Проверка для страницы профиля
    if (is_page('profile') && !locate_template('page-profile.php')) {
        return plugin_dir_path(__FILE__) . 'templates/page-profile.php';
    }

    // Проверка для страницы редактирования профиля
    if (is_page('profile-edit') && !locate_template('page-profile-edit.php')) {
        return plugin_dir_path(__FILE__) . 'templates/page-profile-edit.php';
    }

    // Проверка для страницы истории заказов
    if (is_page('orders') && !locate_template('page-orders.php')) {
        return plugin_dir_path(__FILE__) . 'templates/page-orders.php';
    }

    return $template;
}
*/
//add_filter('template_include', 'iti_cabinet_custom_template');

function iti_cabinet_template_redirect()
{
    global $wp_query;

    $action = get_query_var('iti_cabinet_action');

    $actions = array(
        'profile',
        'profile_edit',
        'orders',
        'password_change'
    );

    if (!is_user_logged_in() && in_array(get_query_var('iti_cabinet_action'), $actions)) {
        wp_redirect(site_url('/login'));
        exit;
    }
    if ($action == 'password_reset') {
        iti_cabinet_load_template('page-password-reset.php');
        exit;
    }

    // Проверка и подмена шаблонов на основе query var
    if ($action == 'profile') {
        iti_cabinet_load_template('page-profile.php');
        exit;
    }

    if ($action == 'profile_edit') {
        iti_cabinet_load_template('page-profile-edit.php');
        exit;
    }

//    if ($action == 'orders') {
//        iti_cabinet_load_template('page-orders.php');
//        exit;
//    }

    $actions = array(
        'login',
        'register'
    );

    if (is_user_logged_in() && in_array($action, $actions)) {
        wp_redirect(site_url('/profile'));
        exit;
    }

    // Страница входа
    if ($action == 'login') {
        iti_cabinet_load_template('page-login.php');
        exit;
    }

    // Страница регистрации
    if ($action == 'register') {
        iti_cabinet_load_template('page-register.php');
        exit;
    }

    // Страница смены пароля
    if ($action == 'password_change') {
        iti_cabinet_load_template('page-password-change.php');
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
//    add_rewrite_rule('^orders/?$', 'index.php?iti_cabinet_action=orders', 'top');

    // Страницы логина и регистрации
    add_rewrite_rule('^login/?$', 'index.php?iti_cabinet_action=login', 'top');
    add_rewrite_rule('^register/?$', 'index.php?iti_cabinet_action=register', 'top');

    // Страница сброса пароля
    add_rewrite_rule('^password-reset/?$', 'index.php?iti_cabinet_action=password_reset', 'top');

    // Страница смены пароля
    add_rewrite_rule('^password-change/?$', 'index.php?iti_cabinet_action=password_change', 'top');
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
function iti_cabinet_flush_rewrite_rules()
{
    iti_cabinet_rewrite_rules(); // Зарегистрировать новые правила
    flush_rewrite_rules();       // Перестроить правила маршрутизации
}

function iti_cabinet_handle_email()
{
    if (isset($_POST['iti_email_nonce']) && wp_verify_nonce($_POST['iti_email_nonce'], 'iti_email_action')) {
        $creds = array(
            'user_email' => $_POST['email'],
            'user_password' => $_POST['pwd'],
            'remember' => isset($_POST['rememberme']) ? true : false,
        );

        $user = wp_signon($creds, false);

        if (is_wp_error($user)) {
            // Если произошла ошибка авторизации
            wp_redirect(site_url('/login?email=failed'));
            exit;
        } else {
            // Если логин успешен
            wp_redirect(site_url('/profile'));
            exit;
        }
    }
}

add_action('init', 'iti_cabinet_handle_email');

function iti_cabinet_login_url($redirect = '') {
    // Возвращаем URL страницы логина с опциональным редиректом после входа
    $login_url = site_url('/login');
    if (!empty($redirect)) {
        $login_url = add_query_arg('redirect_to', urlencode($redirect), $login_url);
    }
    return $login_url;
}

add_action('admin_init', 'restrict_admin_access');

function restrict_admin_access() {
    // Получаем текущего пользователя
    $current_user = wp_get_current_user();

    // Проверяем, есть ли у пользователя права на доступ к админке
    if (in_array('subscriber', (array) $current_user->roles)) {
        // Если это не AJAX-запрос и не происходит выход из системы
        if (!defined('DOING_AJAX') || !DOING_AJAX) {
            // Перенаправляем на главную страницу профиля в кабинет
            wp_redirect(site_url('/profile'));
            exit;
        }
    }
}

add_action('init', 'handle_password_reset_request_new_page');

function handle_password_reset_request_new_page() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_email'])) {
        $email = sanitize_email($_POST['user_email']);
        $user = get_user_by('email', $email);

        if ($user) {
            // Генерация токена
            $token = bin2hex(random_bytes(16));
            update_user_meta($user->ID, 'password_reset_token', $token);

            // Формирование ссылки для сброса пароля
            $reset_link = add_query_arg(['token' => $token], site_url('/password-reset'));

            // Отправка email
            $subject = 'Сброс пароля';
            $message = 'Перейдите по следующей ссылке, чтобы сбросить пароль: ' . $reset_link;
            wp_mail($email, $subject, $message);

            error_log('|reset password link: ' . $reset_link);

            // Сообщение об успешной отправке
            wp_redirect(site_url('/password-reset?reset_email_sent=true'));
            exit;
        } else {
            // Обработка случая, если email не найден
            wp_redirect(site_url('/password-reset?reset_email_sent=false'));
            exit;
        }
    }
}
