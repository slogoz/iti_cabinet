<?php
/* Template for Profile Page */
if (!defined('ABSPATH')) {
    exit;
}

get_header();

include(plugin_dir_path(__FILE__) . 'cabinet-header.php');

if (!get_user_meta(get_current_user_id(), 'email_confirmed', true)) :

    iti_bl_panel('Мы выслали вам на почту ссылку для активации аккаунта.', ['class' => 'email-status panel-success']);
    iti_bl_panel('Письмо может попасть в папку Спам.<br>
        Если у вас проблемы с активацией аккаунта, напишите письмо на [_iti_mail_contact]', ['class' => 'panel-info']);

    ?>

    <form id="resend-email-form" method="post" action="">
        <button type="submit" id="resend-email-btn" class="control-but control-but_primary">Отправить повторно</button>
    </form>

    <?php
    $args = array(
        'id' => '',
        'class' => 'email-secondary-status panel-hide panel-success'
    );
    iti_bl_panel('Ссылка для подтверждения была повторно отправлена на вашу почту.', $args);
    ?>

    <?php // Вставляем AJAX скрипт
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('#resend-email-form').on('submit', function (e) {
                $('#resend-email-btn').attr('disabled', true).text('Отправка...');
                e.preventDefault(); // Останавливаем стандартное поведение формы

                $.ajax({
                    url: ajax_iti.ajax_url, // Используем переданный URL
                    type: 'POST',
                    data: {
                        action: 'resend_email_confirmation',
                        nonce: ajax_iti.resend_nonce // Передаем nonce
                    },
                    success: function (response) {
                        if (response.success) {
                            let t = 300;

                            $('.email-status').slideUp(t, () => {
                                $('#resend-email-btn').slideUp(t, () => {
                                    $('.email-secondary-status').slideDown(t + 200);
                                });
                            })
                        }
                    },
                    error: function () {
                        alert('Произошла ошибка. Пожалуйста, попробуйте еще раз.');
                    }
                });
            });
        });
    </script>
<?php else :
    if (isset($_GET['email_confirmed']) && $_GET['email_confirmed'] == 'true') {
        iti_bl_panel('Ваш адрес электронной почты был успешно подтвержден!', ['class' => 'panel-success']);
    }

    do_action('cab-head');

    $edit_link = '<a href="' . site_url('/profile-edit') . '" class="control-but control-but--edit">' . iti_icon('pencil', [], true) . '</a>';

    echo '<div class="container-fluid container-no-padding">';
    echo '<div class="row">';
    echo '<div class="col-md-12 col-lg-9">';
    echo '<div class="row">';

    $user = get_iti_user(get_current_user_id());

    $data = get_iti_user_data(get_current_user_id());

    $tag_pattern = '<div class="iti-text"><span class="iti-text-bold">%1$s </span>%2$s</div>';

    $content = get_iti_list_info($data, $tag_pattern);

    echo '<div class="col-sm-12 col-md-6">';
    iti_bl_panel($content, array(
        'title' => 'Информация',
        'after_title' => $edit_link,
        'class' => 'panel-default'
    ));
    echo '</div>';

    $content = apply_filters('it_cabinet_user_about', 'Вы пока ничего не написали о себе.', $user);

    echo '<div class="col-sm-12 col-md-6">';
    iti_bl_panel($content, array(
        'title' => 'О себе',
        'after_title' => $edit_link,
        'class' => 'panel-default'
    ));
    echo '</div>';

    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';

endif; ?>

<?php include(plugin_dir_path(__FILE__) . 'cabinet-footer.php'); ?>

<?php get_footer(); ?>
