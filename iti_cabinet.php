<?php
/*
Plugin Name: ITI Cabinet
Plugin URI: https://vk.com/iti_group
Description: Плагин для создания личного кабинета.
Version: 1.0
Author: IT Inform
Author URI: https://vk.com/iti_group
*/

// Защита от прямого доступа
if (!defined('ABSPATH')) {
    exit;
}

include plugin_dir_path(__FILE__) . 'functions.php';
include plugin_dir_path(__FILE__) . 'assets.php';
include plugin_dir_path(__FILE__) . 'register.php';
include plugin_dir_path(__FILE__) . 'template-route.php';
include plugin_dir_path(__FILE__) . 'template-shortcodes.php';

register_activation_hook(__FILE__, 'iti_cabinet_flush_rewrite_rules');
add_action('init', 'iti_cabinet_flush_rewrite_rules');