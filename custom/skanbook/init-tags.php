<?php

function skanbook_tag_but_state()
{
    echo library_tag_but_state();
}

function get_library_author_urls()
{
    $tags = get_the_tags();

    $out = '';

    if ($tags) {
        foreach ($tags as $tag) {
            $out .= '<a href="' . get_tag_link($tag->term_id) . '">' . $tag->name . '</a> ';
        }
    } else {
       $out = '-';
    }

    return $out;
}