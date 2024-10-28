<?php

add_filter('iti_cabinet_head_menu_links_array', 'library_head_menu_links_array');

function library_head_menu_links_array($links)
{
    $count_books = library_get_count_state();

    if ($count_books) {
        $links['library'] = array(
            'order' => 35,
            'url' => site_url('/library'),
            'name' => 'Моя библиотека ' . library_tag_get_count_state('all'),
            'state' => 'all'
        );
    }

    return $links;
}
