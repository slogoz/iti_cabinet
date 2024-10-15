<?php

// Функция для добавления страницы профиля
function iti_cabinet_profile_page() {
    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();
        ob_start(); ?>

        <h2>Добро пожаловать, <?php echo esc_html($current_user->display_name); ?>!</h2>
        <ul>
            <li><a href="<?php echo esc_url(home_url('/profile-edit')); ?>">Редактировать профиль</a></li>
            <li><a href="<?php echo esc_url(home_url('/orders')); ?>">История заказов</a></li>
            <li><a href="<?php echo wp_logout_url(); ?>">Выйти</a></li>
        </ul>

        <?php
        return ob_get_clean();
    } else {
        return '<p>Пожалуйста, <a href="' . wp_login_url() . '">войдите</a>, чтобы получить доступ к вашему профилю.</p>';
    }
}

// Шорткод для страницы профиля
add_shortcode('iti_cabinet_profile', 'iti_cabinet_profile_page');

// Страница редактирования профиля
function iti_cabinet_profile_edit_page() {
    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();
        ob_start(); ?>

        <form method="post">
            <label for="first_name">Имя:</label>
            <input type="text" id="first_name" name="first_name" value="<?php echo esc_attr($current_user->first_name); ?>">

            <label for="last_name">Фамилия:</label>
            <input type="text" id="last_name" name="last_name" value="<?php echo esc_attr($current_user->last_name); ?>">

            <input type="submit" name="update_profile" value="Обновить">
        </form>

        <?php
        if (isset($_POST['update_profile'])) {
            $user_id = $current_user->ID;
            update_user_meta($user_id, 'first_name', sanitize_text_field($_POST['first_name']));
            update_user_meta($user_id, 'last_name', sanitize_text_field($_POST['last_name']));
            echo '<p>Профиль обновлен!</p>';
        }

        return ob_get_clean();
    } else {
        return 'Пожалуйста, войдите в систему.';
    }
}
add_shortcode('iti_cabinet_profile_edit', 'iti_cabinet_profile_edit_page');

// Создание страницы "История заказов" (заглушка)
function iti_cabinet_orders_page() {
    if (is_user_logged_in()) {
        ob_start(); ?>
        <h2>История заказов</h2>
        <p>Здесь будет список заказов пользователя.</p>
        <?php
        return ob_get_clean();
    } else {
        return '<p>Пожалуйста, войдите в систему.</p>';
    }
}
add_shortcode('iti_cabinet_orders', 'iti_cabinet_orders_page');
