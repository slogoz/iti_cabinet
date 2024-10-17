<?php

// Добавляем колонку в список пользователей
add_filter('manage_users_columns', 'add_email_confirmed_column');

function add_email_confirmed_column($columns) {
    $columns['email_confirmed'] = 'Активация';
    return $columns;
}

// Отображаем значение в новой колонке
add_action('manage_users_custom_column', 'show_email_confirmed_status', 10, 3);

function show_email_confirmed_status($value, $column_name, $user_id) {
    if ('email_confirmed' === $column_name) {
        // Получаем мета-данные email_confirmed
        $email_confirmed = get_user_meta($user_id, 'email_confirmed', true);
        if ($email_confirmed) {
            return '<span style="color:green;">Активирован</span>';
        } else {
            return '<span style="color:red;">Не активирован</span>';
        }
    }
    return $value;
}

// Делаем колонку сортируемой
add_filter('manage_users_sortable_columns', 'make_email_confirmed_sortable');

function make_email_confirmed_sortable($sortable_columns) {
    $sortable_columns['email_confirmed'] = 'email_confirmed';
    return $sortable_columns;
}

// Обрабатываем сортировку по нашей колонке
add_action('pre_user_query', 'custom_sort_by_email_confirmed');
function custom_sort_by_email_confirmed($user_query) {
    global $wpdb;

    // Проверяем, что запрос идет из админки и указан порядок сортировки по email_confirmed
    if (is_admin() && isset($_GET['orderby']) && $_GET['orderby'] == 'email_confirmed') {
        // Получаем основной SQL-запрос
        $user_query->query_from .= " LEFT JOIN {$wpdb->usermeta} AS email_confirmed_meta ON ({$wpdb->users}.ID = email_confirmed_meta.user_id AND email_confirmed_meta.meta_key = 'email_confirmed')";

        // Изменяем запрос для сортировки
        if (isset($_GET['order']) && $_GET['order'] == 'asc') {
            $user_query->query_orderby = " ORDER BY email_confirmed_meta.meta_value+0 ASC, {$wpdb->users}.user_login ASC";
        } else {
            $user_query->query_orderby = " ORDER BY email_confirmed_meta.meta_value+0 DESC, {$wpdb->users}.user_login ASC";
        }
    }
}
