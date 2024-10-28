<?php

function library_tag_get_count_state($state)
{
    return '<span class="iti-badge">' . library_get_count_state($state) . '</span>';
}

function library_tag_but_state($caption = 'Добавить')
{
    $state = 'none';

    $post_id = get_the_ID();

    if ($post_id) {
        $library_book = get_library_books();

        if ($library_book && isset($library_book[$post_id])) {
            $_state = $library_book[$post_id]['state'];

            if ($_state) {
                $state = $_state;
                if ($state !== 'none') {
                    $caption = get_data_book_states('all')[$state];
                }
            }
        }
    }

    $class = ' class="iti-but iti-but_primary iti-but--library iti-but--id-' . get_the_ID() . '"';
    $data_id = ' data-id="' . get_the_ID() . '"';
    $data_state = ' data-state="' . $state . '"';

    $but = "<span{$class}{$data_id}{$data_state}>{$caption}</span>";

    return $but;
}
