<?php


//add_filter('the_content', 'library_add_but_state');

function library_add_but_state($content)
{
    $content .= library_tag_but_state() . library_tag_but_state();

    return $content;
}

add_action('init', 'library_init_state');

function library_init_state()
{
    $state_arr = get_data_book_states();
}

function get_arr_book_states()
{
    return array(
        'none' => 'Нет статуса',
        'wish' => 'Хочу прочитать',
        'reading' => 'Читаю',
        'unread' => 'Не дочитал',
        'read' => 'Прочитал',
        'favorite' => 'Любимая',
        'unfinished' => 'Недописано',
        'black_list' => 'Чёрный список'
    );
}

function get_data_book_states($mod = 'no_none')
{
    $states = get_arr_book_states();

    if ($mod === 'no_none') {
        unset($states['none']);
    }

    return $states;
}

add_action('wp_footer', 'template_modal_state_style');
add_action('wp_footer', 'template_modal_state');
add_action('wp_footer', 'template_modal_state_script');

function template_modal_state()
{
    ob_start();
    ?>
    <div class="modal-state-container" style="display: none;">
        <div class="modal-state-overlay"></div>
        <div class="modal-state">
            <div class="modal-state__close"><?php echo iti_icon('cancel'); ?></div>
            <div class="modal-state__body">
                <div class="iti-state-list">
                    <?php foreach (get_data_book_states() as $name => $value) : ?>
                        <div class="iti-state-list__item" data-caption="<?php echo $value; ?>"
                             data-state="<?php echo $name; ?>">
                            <?php echo iti_icon('circle'); ?>
                            <?php echo $value; ?>
                            <?php echo iti_icon('cancel'); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="modal-state-loading" style="display: none;">
                <div class="modal-state-loading__overlay"></div>
                <span class="modal-state-loading__spinner"></span>
            </div>
        </div>
    </div>
    <?php
    echo ob_get_clean();
}

function template_modal_state_script()
{
    ob_start(); ?>
    <script>
        (function ($) {
            $(document).ready(function () {
                let $modalStateContainer = $('.modal-state-container');
                let $modalState = $('.modal-state');
                let $modalStateOverlay = $('.modal-state-overlay');
                let $modalStateButClose = $('.modal-state__close');
                let $modalStateItem = $('.iti-state-list__item');
                let $modalStateLoading = $('.modal-state-loading')
                let $butsAction;

                let id_post,
                    state,
                    caption;

                $modalStateButClose.on('click', modalStateClose);
                $modalStateOverlay.on('click', modalStateClose);
                $modalStateItem.on('click', modalStateSelect);

                $('.iti-but--library').on('click', function (e) {
                    id_post = $(this).attr('data-id');
                    let state = $(this).attr('data-state');
                    $butsAction = $('.iti-but--id-' + id_post);

                    modalStateShow(state);
                });

                function updateButtons(state, caption) {
                    $butsAction.text(caption);
                    $butsAction.attr('data-state', state);
                }

                function modalStateSelect(e) {
                    state = $(this).attr('data-state');
                    caption = $(this).attr('data-caption');

                    updateStateSelect(state);
                }

                function modalStateClose(e) {
                    $modalStateContainer.css('display', 'none');
                }

                function modalStateShow(state) {
                    showStateSelect(state);
                    $modalStateContainer.css('display', 'block');
                }

                function showStateSelect(state) {
                    let $itemState = $modalStateItem.filter('[data-state="' + state + '"]');
                    $modalStateItem.removeClass('active');
                    $itemState.addClass('active');
                }

                function updateStateSelect(_state) {

                    let $itemState = $modalStateItem.filter('[data-state="' + _state + '"]');
                    let hasActive = $itemState.hasClass('active');

                    if (hasActive) {
                        state = 'none';
                        caption = 'Добавить';
                    }

                    ajaxUpdateState();
                }

                function ajaxUpdateState() {

                    $modalStateLoading.show();

                    // Выполняем AJAX-запрос
                    $.ajax({
                        url: ajax_iti.ajax_url, // URL для отправки запроса (WordPress глобальная переменная)
                        type: 'POST', // Тип запроса
                        data: {
                            action: 'update_post_meta_status', // Название действия
                            nonce: ajax_iti.resend_nonce, // Передаем nonce
                            post_id: id_post, // ID поста
                            meta_key: 'state', // Название мета-поля
                            meta_value: state // Значение мета-поля
                        },
                        success: function (response) {
                            // console.log('Метаданные успешно сохранены:', response);

                            let $itemState = $modalStateItem.filter('[data-state="' + state + '"]');
                            let hasActive = $itemState.hasClass('active');

                            $modalStateItem.removeClass('active');

                            if (!hasActive) {
                                $itemState.addClass('active');
                            }

                            updateButtons(state, caption);
                        },
                        error: function (xhr, status, error) {
                            console.log('Произошла ошибка:', error);
                        },
                        complete: function (xhr, status) {
                            $modalStateLoading.hide();
                        }
                    });
                }
            });
        })(jQuery);
    </script>
    <?php
    echo ob_get_clean();
}

function template_modal_state_style()
{
    ob_start(); ?>
    <style>

        .modal-state-container {
            width: 100%;
            height: 100%;
            left: 0;
            top: 0;
            position: fixed;
            z-index: 2005;
        }

        .modal-state-overlay {
            background: #000;
            position: absolute;
            opacity: .5;
            width: 100%;
            height: 100%;
            left: 0;
            top: 0;
        }

        .modal-state {
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, calc(-50% - 60px));
            z-index: 255;
            background: #fff;
            border: 1px solid #a0a0a0;
            -moz-box-shadow: 0 0 10px #a0a0a0;
            -webkit-box-shadow: 0 0 10px #a0a0a0;
            width: 100%;
            max-width: 310px;
        }

        .modal-state__close {
            height: 20px;
            width: 20px;
            position: absolute;
            top: 3px;
            z-index: 99;
            overflow: hidden;
            right: 2px;
            /*text-indent: -999em;*/
            cursor: pointer;
            opacity: .2;
        }

        .modal-state__close:hover {
            opacity: .5;
        }

        .modal-state__close .icon-cancel {
            position: relative;
            fill: #D0021B;
        }

        .modal-state__body {
            padding: 20px;
        }

        .modal-state .icon-circle {
            fill: #39424c;
        }

        .iti-state-list {

        }

        .iti-state-list__item {
            cursor: pointer;
            display: block;
            /*font-size: 15px;*/
            color: #39424c;
            line-height: 28px;
            padding: 0 10px;
        }

        .iti-state-list__item .icon-circle {
            position: relative;
            margin: 0 10px 0 0;
            top: 3px;
        }

        .iti-state-list__item .icon-cancel {
            position: relative;
            /*margin: 0 10px 0 0;*/
            top: 7px;
            float: right;
            opacity: 0;
        }

        .iti-state-list__item:hover {
            background: #eee;
            border-radius: 15px;
        }

        .iti-state-list__item.active .icon-circle {
            fill: #ffffff;
        }

        .iti-state-list__item.active .icon-cancel {
            opacity: .5;
        }

        .iti-state-list__item.active:hover .icon-cancel {
            opacity: 1;
        }

        .iti-state-list__item.active {
            color: #fff;
            background-color: #3fa969;
            border-radius: 15px;
            position: relative;
        }

        .modal-state-loading {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
        }

        .modal-state-loading__overlay {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            background: #fff;
            opacity: .5;
        }

        .modal-state-loading__spinner {
            position: absolute;
            display: block;
            background: url(<?php echo WP_ITI_CABINET_URL ?>/images/spinner.gif) no-repeat;
            background-color: transparent;
            width: 31px;
            height: 32px;
            left: 50%;
            top: 50%;
            margin-left: -15px;
            margin-top: -15px;
        }
    </style>
    <?php
    echo ob_get_clean();
}

add_action('wp_ajax_update_post_meta_status', 'update_post_meta_status');
add_action('wp_ajax_nopriv_update_post_meta_status', 'update_post_meta_status'); // Для неавторизованных пользователей

function update_post_meta_status()
{
    // Проверяем, передан ли post_id и данные мета-поля
    if (isset($_POST['post_id']) && isset($_POST['meta_key']) && isset($_POST['meta_value'])) {
        $post_id = intval($_POST['post_id']);
        $meta_key = sanitize_text_field($_POST['meta_key']);
        $meta_value = sanitize_text_field($_POST['meta_value']);

        // Сохраняем метаданные для поста
        update_post_meta($post_id, $meta_key, $meta_value);

        // Возвращаем успешный ответ
        wp_send_json_success('Метаданные обновлены.');
    } else {
        wp_send_json_error('Не переданы данные.');
    }

    wp_die(); // Завершаем выполнение скрипта
}