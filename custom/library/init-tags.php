<?php

function library_tag_get_count_state($state)
{
    return '<span class="iti-badge">' . library_get_count_state($state) . '</span>';
}

function library_tag_but_state()
{
    $state = 'none';
    $caption = 'Добавить';

    $post_id = get_the_ID();

    if ($post_id) {
        $_state = get_post_meta($post_id, 'state', true);

        if ($_state) {
            $state = $_state;
            if ($state !== 'none') {
                $caption = get_data_book_states('all')[$state];
            }
        }
    }

    $class = ' class="iti-but iti-but_primary iti-but--library iti-but--id-' . get_the_ID() . '"';
    $data_id = ' data-id="' . get_the_ID() . '"';
    $data_state = ' data-state="' . $state . '"';
//    $caption .= ' ' . get_the_ID();

    $but = "<span{$class}{$data_id}{$data_state}>{$caption}</span>";

    return $but;
}
