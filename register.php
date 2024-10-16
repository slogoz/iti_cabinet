<?php

add_action('init', 'iti_cabinet_handle_registration');

function iti_cabinet_handle_registration() {
    if (isset($_POST['iti_register_nonce']) && wp_verify_nonce($_POST['iti_register_nonce'], 'iti_register_action')) {
        // Проверяем все поля формы
        $username = sanitize_text_field($_POST['user_name']);
        $email = sanitize_email($_POST['user_email']);
        $password = sanitize_text_field($_POST['user_pass']);
        $password_confirm = sanitize_text_field($_POST['user_pass_confirm']);
        $gender = isset($_POST['user_gender']) ? sanitize_text_field($_POST['user_gender']) : '';

        // Проверка совпадения паролей
        if ($password !== $password_confirm) {
            wp_redirect(site_url('/register?error_reg=password_mismatch'));
            exit;
        }

        // Проверка, существует ли пользователь с таким email
        if (email_exists($email)) {
            wp_redirect(site_url('/register?error_reg=email_exists'));
            exit;
        }

        // Регистрация нового пользователя
        $user_id = wp_create_user($username, $password, $email);

        if (is_wp_error($user_id)) {
            // Обрабатываем ошибки регистрации
            wp_redirect(site_url('/register?error_reg=registration_failed'));
            exit;
        } else {
            // Успешная регистрация, авторизуем пользователя и перенаправляем на профиль
            wp_set_current_user($user_id);
            wp_set_auth_cookie($user_id);

            // Подтверждение регистрации по email
            $token = bin2hex(random_bytes(16)); // Генерация уникального токена
            update_user_meta($user_id, 'email_confirmation_token', $token); // Сохраняем токен в метаданных пользователя

            // Отправка письма с подтверждением
            $confirmation_link = site_url('/confirm-email?token=' . $token);
            $subject = 'Подтверждение регистрации';
            $message = 'Пожалуйста, подтвердите свою электронную почту, перейдя по следующей ссылке: ' . $confirmation_link;
            wp_mail($email, $subject, $message);

            error_log("|Generated link: " . $confirmation_link);

            wp_redirect(site_url('/profile'));
            exit;
        }
    }
}

add_action('init', 'handle_email_confirmation');

function handle_email_confirmation() {
    if (isset($_GET['token'])) {
        $token = sanitize_text_field($_GET['token']);

        // Поиск пользователя по токену
        $args = [
            'meta_query' => [
                [
                    'key' => 'email_confirmation_token',
                    'value' => $token,
                    'compare' => '='
                ]
            ]
        ];
        $user_query = new WP_User_Query($args);
        $users = $user_query->get_results();

        if (!empty($users)) {
            $user = $users[0];
            // Удаляем токен и активируем пользователя
            delete_user_meta($user->ID, 'email_confirmation_token');
            update_user_meta($user->ID, 'email_confirmed', true); // Можно добавить мета поле для проверки подтверждения
            wp_redirect(home_url('/profile?email_confirmed=true'));
            exit;
        } else {
            wp_redirect(home_url('/login?error_reg=invalid_token'));
            exit;
        }
    }
}

// Регистрируем обработчик AJAX для неавторизованных и авторизованных пользователей
add_action('wp_ajax_resend_email_confirmation', 'handle_ajax_resend_email_confirmation');
add_action('wp_ajax_nopriv_resend_email_confirmation', 'handle_ajax_resend_email_confirmation');

function handle_ajax_resend_email_confirmation() {
    // Проверка nonce
    check_ajax_referer('resend_email_confirmation_nonce', 'nonce'); // Если неудача, выполнение прерывается

    // Проверяем, что пользователь авторизован
    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;

        // Проверка, подтверждена ли почта
        if (!get_user_meta($user_id, 'email_confirmed', true)) {
            // Генерация нового токена
            $token = bin2hex(random_bytes(16));
            update_user_meta($user_id, 'email_confirmation_token', $token);

            // Отправка письма с подтверждением
            $email = $current_user->user_email;
            $confirmation_link = site_url('/confirm-email?token=' . $token);
            $subject = 'Подтверждение регистрации';
            $message = 'Пожалуйста, подтвердите свою электронную почту, перейдя по следующей ссылке: ' . $confirmation_link;
            wp_mail($email, $subject, $message);

            error_log("|Generated link: " . $confirmation_link);

            // Ответ AJAX с успехом
            wp_send_json_success('Email sent');
        } else {
            wp_send_json_error('Email already confirmed');
        }
    } else {
        wp_send_json_error('User not logged in');
    }
}

// Проверка на странице профиля
add_action('template_redirect', 'iti_check_email_confirmation');

function iti_check_email_confirmation() {
    if (is_user_logged_in()) {
        $user_id = get_current_user_id();
        $email_confirmed = get_user_meta($user_id, 'email_confirmed', true);

        // Проверяем, если пользователь на страницах кабинета
        if (!$email_confirmed && !isset($_GET['email_confirmation_needed']) && iti_is_cabinet_action()) {
            wp_redirect(home_url('/profile?email_confirmation_needed=true'));
            exit;
        }
    }
}

// Функция для проверки, является ли текущий запрос частью кабинета
function iti_is_cabinet_action() {
    // Проверяем, есть ли параметр iti_cabinet_action в URL
    return !empty(get_query_var('iti_cabinet_action'));
}
