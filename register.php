<?php

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
            wp_redirect(site_url('/profile'));
            exit;
        }
    }
}
add_action('init', 'iti_cabinet_handle_registration');
