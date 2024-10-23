<?php

add_filter('iti_cabinet_head_menu_links_array', 'library_head_menu_links_array');

function library_head_menu_links_array($links)
{
    $links['library'] = array(
        'order' => 35,
        'url' => site_url('/library'),
        'name' => 'Моя библиотека',
        'state' => 'all'
    );

    return $links;
}
