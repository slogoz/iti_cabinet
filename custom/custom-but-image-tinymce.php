<?php

add_filter('mce_buttons', function ($buttons) {
    array_push($buttons, 'iti_but_image_tinymce');
    return $buttons;
});


add_filter('mce_external_plugins', function ($plugin_array)
{
    $plugin_array['iti_but_image_tinymce'] = WP_ITI_CABINET_URL . 'js/iti-but-image-tinymce.js';
    return $plugin_array;
});

add_action('wp_enqueue_scripts', function ()
{
    wp_enqueue_script('iti-but-image-tinymce', WP_ITI_CABINET_URL . 'js/iti-but-image-tinymce.js', array('jquery', 'editor', 'wp-tinymce'), null, true);
});


add_action('it_cabinet_fields_profile_edit', function ($user) {
    ?>
    <div class="form-group">
        <label for="user_about" class="control-label">О себе</label>
        <div class="control-editor">
            <?php
            wp_editor($user->about, 'iti_editor_id', array(
                'textarea_name' => 'user_about',
                'editor_height' => 150,
                'teeny' => true, // Упрощённый режим
                'quicktags' => false, // Отключаем вкладку "Текст" (HTML)
                'media_buttons' => false, // Убираем кнопки медиа
                'tinymce' => array(
                    'toolbar1' => 'bold italic underline blockquote strikethrough bullist numlist alignleft aligncenter alignright undo redo link fullscreen iti_but_image_tinymce',
                    'plugins' => 'iti_but_image_tinymce link fullscreen lists', // Здесь указываем ваш плагин
                ),
            ));
            ?>
        </div>
    </div>
    <?php
});
