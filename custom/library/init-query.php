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
    }

    $query = new WP_Query($args);

    return $query->post_count;
}