<?php

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('skanbook-library', plugins_url('css/skanbook.css', __FILE__));
});