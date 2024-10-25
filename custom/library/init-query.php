<?php

function library_get_count_state($state = 'all')
{
    $library_book = get_library_books();

    if ($library_book) {
        if ($state === 'all') {
            $post_ids = array_keys($library_book);
        } else {
            $filtered_arr = array_filter($library_book, function ($book) use ($state) {
                return $book['state'] === $state;
            });

            $post_ids = array_keys($filtered_arr);
        }

        if (!empty($post_ids)) {
            $args = array(
                'post_type' => 'post',   // Тип постов
                'posts_per_page' => -1,  // Получаем все посты
                'post__in' => $post_ids, // Выбираем посты по их ID
                'orderby' => 'post__in', // Сортировка в порядке переданных ID
            );

            $query = new WP_Query($args);

            return $query->post_count;
        }
    }

    return 0;
}

function get_library_post_view($state, $no_filter = false)
{
    $library_book = get_library_books();
    $post_ids = array(-2);

    if ($library_book) {
        if ($state === 'library') {
            $post_ids = array_keys($library_book);
        } else {
            $filtered_arr = array_filter($library_book, function ($book) use ($state) {
                return $book['state'] === $state;
            });

            $post_ids = array_keys($filtered_arr);
        }

        if (empty($post_ids)) {
            $post_ids = array(-2);
        }
    }

    $post_ids = array_reverse($post_ids);

    $args = array(
        'post_type' => 'post',   // Тип постов
        'posts_per_page' => 20,  // Получаем все посты
        'post__in' => $post_ids, // Выбираем посты по их ID
        'orderby' => 'post__in', // Сортировка в порядке переданных ID
    );

    if (!$no_filter && !empty($_GET['genre'])) {
        $args['cat'] = $_GET['genre'];
    }

    if (isset($_GET['sort']) && $_GET['sort'] == 'updated') {
        $args['orderby'] = 'date';
        $args['order'] = 'DESC';
    }

    if (isset($_GET['sort']) && $_GET['sort'] == 'views') {
        $args['meta_key'] = 'views';
        $args['orderby'] = 'meta_value_num';
        $args['order'] = 'DESC';
    }

    $query = new WP_Query($args);

    return $query;
}

function get_library_user_post_state()
{
    $library_book = get_library_books();

    $args = array(
        'post_type' => 'post',    // Тип постов
        'posts_per_page' => 10,        // Получаем все посты
    );

    $query = new WP_Query($args);

    return $query->post_count;
}

function get_library_books()
{
    return get_user_meta(get_current_user_id(), 'library_book', true);
}

function set_library_books($meta_value)
{
    update_user_meta(get_current_user_id(), 'library_book', $meta_value);
}