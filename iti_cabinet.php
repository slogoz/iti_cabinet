<?php
/*
Plugin Name: ITI Cabinet
Plugin URI:
Description: Плагин для создания личного кабинета.
Version: 1.0
Author:
Author URI:
*/

// Защита от прямого доступа
if (!defined('ABSPATH')) {
    exit;
}

if ( true ) {

    if (!defined('WP_ITI_CABINET_DIR')) {
        define('WP_ITI_CABINET_DIR', plugin_dir_path(__FILE__));
    }

    if (!defined('WP_ITI_CABINET_URL')) {
        define('WP_ITI_CABINET_URL', plugin_dir_url(__FILE__));
    }

    include plugin_dir_path(__FILE__) . 'functions.php';
    include plugin_dir_path(__FILE__) . 'assets.php';
    include plugin_dir_path(__FILE__) . 'register.php';
    include plugin_dir_path(__FILE__) . 'route.php';
    include plugin_dir_path(__FILE__) . 'template-shortcodes.php';
    include plugin_dir_path(__FILE__) . 'inc/users-custom-columns.php';
    include plugin_dir_path(__FILE__) . 'inc/user_fields.php';
    include plugin_dir_path(__FILE__) . 'custom/connected.php';
    include plugin_dir_path(__FILE__) . 'custom/custom.php';
    include plugin_dir_path(__FILE__) . 'custom/custom-but-image-tinymce.php';
    include plugin_dir_path(__FILE__) . 'custom/custom-image-upload.php';

    register_activation_hook(__FILE__, 'iti_cabinet_flush_rewrite_rules');
    add_action('init', 'iti_cabinet_flush_rewrite_rules');


    /*
     * Регистрация объектов
     */

    include plugin_dir_path(__FILE__) . 'classes/iti/Container.php';
    include plugin_dir_path(__FILE__) . 'classes/iti/Box.php';
    include plugin_dir_path(__FILE__) . 'classes/iti/Block.php';
    include plugin_dir_path(__FILE__) . 'classes/iti/Block_Header.php';
    include plugin_dir_path(__FILE__) . 'classes/iti/Block_Message.php';

    add_filter('block_defaults_args', function ($def_args) {
        $def_args['title'] = '';
        $def_args['template'] = 'panel.php';
        return $def_args;
    });

    add_filter('block_render_html', function ($html) {

        $tags = array(
            '[_iti_mail_contact]' => 'contact@mail.__'
        );

        $result = str_replace(array_keys($tags), array_values($tags), $html);

        echo $result;
    });

// Регистрация сервиса
    classes\iti\Box::getInstance()->register('panel', classes\iti\Block::class);
    classes\iti\Box::getInstance()->register('header', classes\iti\Block_Header::class);
    classes\iti\Box::getInstance()->register('message', classes\iti\Block_Message::class);

    add_action('wp', 'update_user_activity');
    add_action('user_register', 'save_gender_meta');

    add_action('wp_head', 'iti_library_template_head');

    function iti_library_template_head()
    {
        include(WP_ITI_CABINET_DIR . '/templates/cabinet-svg.php');
    }
}