<?php

function iti_cabinet_enqueue_styles()
{
    // Получаем значение параметра действия (iti_cabinet_action)
    $action = get_query_var('iti_cabinet_action');

    // Подключаем стили только на страницах кабинета
//    if (in_array($action, ['profile', 'profile_edit', 'orders', 'login', 'register', 'password_reset'])) {
    if ($action) {

        wp_enqueue_style(
            'iti-grid-style', // Уникальное имя стиля
            plugin_dir_url(__FILE__) . 'css/grid.css', // Путь к файлу стилей
            array(), // Зависимости, если есть
            '1.0', // Версия файла стилей
            'all' // Тип вывода
        );
        wp_enqueue_style(
            'iti-cabinet-style', // Уникальное имя стиля
            plugin_dir_url(__FILE__) . 'css/iti-cabinet.css', // Путь к файлу стилей
            array(), // Зависимости, если есть
            '1.0', // Версия файла стилей
            'all' // Тип вывода
        );
//        wp_enqueue_style(
//            'trix-style', // Уникальное имя стиля
//            plugin_dir_url(__FILE__) . 'css/trix.css', // Путь к файлу стилей
//            array(), // Зависимости, если есть
//            '1.0', // Версия файла стилей
//            'all' // Тип вывода
//        );
    }

    wp_enqueue_style('iti-common-style',plugin_dir_url(__FILE__) . 'css/iti-common.css');
}

add_action('wp_enqueue_scripts', 'iti_cabinet_enqueue_styles');

function iti_cabinet_enqueue_scripts()
{
    $action = get_query_var('iti_cabinet_action');

    wp_enqueue_script('jquery');

    if ($action) {
        // Подключаем ваши скрипты и стили
        wp_enqueue_script('jquery-switch-button', plugin_dir_url(__FILE__) . 'js/jquery.switchButton.js', array('jquery', 'jquery-ui-core'), null, true);
        wp_enqueue_script('iti-cabinet-script', plugin_dir_url(__FILE__) . 'js/iti-cabinet.js', array('jquery', 'jquery-ui-core'), null, true);

        do_action('iti_cabinet_enqueue_scripts');
    }

    // Генерация nonce и передача его в JavaScript
    wp_localize_script('jquery', 'ajax_iti', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'resend_nonce' => wp_create_nonce('resend_email_confirmation_nonce')
    ));
}

add_action('wp_enqueue_scripts', 'iti_cabinet_enqueue_scripts');

function enqueue_full_jquery_ui()
{
    // Подключаем jQuery UI целиком
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-accordion');
    wp_enqueue_script('jquery-ui-autocomplete');
    wp_enqueue_script('jquery-ui-button');
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_script('jquery-ui-dialog');
    wp_enqueue_script('jquery-ui-draggable');
    wp_enqueue_script('jquery-ui-droppable');
    wp_enqueue_script('jquery-ui-menu');
    wp_enqueue_script('jquery-ui-progressbar');
    wp_enqueue_script('jquery-ui-resizable');
    wp_enqueue_script('jquery-ui-selectable');
    wp_enqueue_script('jquery-ui-slider');
    wp_enqueue_script('jquery-ui-sortable');
    wp_enqueue_script('jquery-ui-spinner');
    wp_enqueue_script('jquery-ui-tabs');
    wp_enqueue_script('jquery-ui-tooltip');

    // Подключаем стили jQuery UI
    wp_enqueue_style('jquery-ui-css', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
}

//add_action('wp_enqueue_scripts', 'enqueue_full_jquery_ui');

function disable_jquery_migrate($scripts)
{
    if (!is_admin() && !empty($scripts->registered['jquery'])) {
        // Удаляем jQuery Migrate
        $jquery_dependencies = $scripts->registered['jquery']->deps;
        $scripts->registered['jquery']->deps = array_diff($jquery_dependencies, array('jquery-migrate'));
    }
}
//add_action( 'wp_default_scripts', 'disable_jquery_migrate' );
