<?php

function library_get_count_state($state = 'all')
{

    $meta_key = 'state';  // Мета-ключ для фильтрации
    $meta_value = $state;       // Значение мета-поля, которое мы ищем (например, видео)

    $args = array(
        'post_type' => 'post',    // Тип постов
        'posts_per_page' => -1,        // Получаем все посты
    );

    if ($state !== 'all') {
        $args['meta_query'] = array(
            array(
                'key' => $meta_key,    // Указываем мета-ключ
                'value' => $meta_value,  // Указываем значение мета-поля
                'compare' => '='           // Сравнение по равенству
            ),
        );
    } else {
        $states = get_arr_book_states();
        unset($states['none']);

        $args['meta_query'] = array(
            array(
                'key' => $meta_key,    // Указываем мета-ключ
                'value' => array_keys($states),  // Указываем массив значений
                'compare' => 'IN'          // Сравнение по вхождению
            ),
        );
    }

    $query = new WP_Query($args);

    return $query->post_count;
}

function get_library_post_view($state)
{

    $meta_key = 'state';  // Мета-ключ для фильтрации
    $meta_value = $state;       // Значение мета-поля, которое мы ищем (например, видео)

    $args = array(
        'post_type' => 'post',    // Тип постов
        'posts_per_page' => 10,        // Получаем все посты
    );

    if ($state !== 'all') {
        $args['meta_query'] = array(
            array(
                'key' => $meta_key,    // Указываем мета-ключ
                'value' => $meta_value,  // Указываем значение мета-поля
                'compare' => '='           // Сравнение по равенству
            ),
        );
    } else {
        $states = get_arr_book_states();
        unset($states['none']);

        $args['meta_query'] = array(
            array(
                'key' => $meta_key,    // Указываем мета-ключ
                'value' => array_keys($states),  // Указываем массив значений
                'compare' => 'IN'          // Сравнение по вхождению
            ),
        );
    }

    $query = new WP_Query($args);

    return $query;
}

function get_library_user_post_state()
{
    $meta_key = 'library_book';

    $library_book = get_user_meta(get_current_user_id(), $meta_key, true);

    $args = array(
        'post_type' => 'post',    // Тип постов
        'posts_per_page' => 10,        // Получаем все посты
    );

    $query = new WP_Query($args);

    return $query->post_count;
}