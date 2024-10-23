<?php

function library_tag_get_count_state($state)
{
    return '<span class="iti-badge">' . library_get_count_state($state) . '</span>';
}