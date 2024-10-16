<?php

function iti_cabinet_enqueue_styles() {
    // Получаем значение параметра действия (iti_cabinet_action)
    $current_action = get_query_var('iti_cabinet_action');

    // Подключаем стили только на страницах кабинета
    if (in_array($current_action, ['profile', 'profile_edit', 'orders', 'login', 'register'])) {
        wp_enqueue_style(
            'iti-cabinet-style', // Уникальное имя стиля
            plugin_dir_url(__FILE__) . 'css/iti-cabinet.css', // Путь к файлу стилей
            array(), // Зависимости, если есть
            '1.0', // Версия файла стилей
            'all' // Тип вывода
        );
    }
}
add_action('wp_enqueue_scripts', 'iti_cabinet_enqueue_styles');

function iti_cabinet_enqueue_scripts() {
    // Подключаем jQuery
    // wp_enqueue_script('jquery');

    // Подключаем ваши скрипты и стили
    wp_enqueue_script('iti-cabinet-script', plugin_dir_url(__FILE__) . 'js/iti-cabinet.js', array('jquery'), null, true);

    // Генерация nonce и передача его в JavaScript
    wp_localize_script('iti-cabinet-script', 'ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'resend_nonce' => wp_create_nonce('resend_email_confirmation_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'iti_cabinet_enqueue_scripts');