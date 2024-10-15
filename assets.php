<?php

function iti_cabinet_enqueue_styles() {
    // Получаем значение параметра действия (iti_cabinet_action)
    $current_action = get_query_var('iti_cabinet_action');

    // Подключаем стили только на страницах кабинета
    if (in_array($current_action, ['profile', 'profile_edit', 'orders'])) {
        wp_enqueue_style(
            'iti-cabinet-style', // Уникальное имя стиля
            plugin_dir_url(__FILE__) . 'css/iti-cabinet-style.css', // Путь к файлу стилей
            array(), // Зависимости, если есть
            '1.0', // Версия файла стилей
            'all' // Тип вывода
        );
    }
}
add_action('wp_enqueue_scripts', 'iti_cabinet_enqueue_styles');
