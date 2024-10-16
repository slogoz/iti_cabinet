<?php
/* Template for Profile Page */
if (!defined('ABSPATH')) {
    exit;
}
get_header();

if (!get_user_meta(get_current_user_id(), 'email_confirmed', true)) : ?>
    <p style="color: red;">Пожалуйста, подтвердите свою электронную почту.</p>

    <?php // Кнопка для повторной отправки подтверждения ?>
    <form id="resend-email-form" method="post" action="">
        <button type="submit" id="resend-email-btn">Отправить повторное подтверждение</button>
    </form>
    <div id="email-status" style="display:none; color: green;">Ссылка для подтверждения была отправлена на вашу почту.
    </div>

    <?php // Вставляем AJAX скрипт ?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('#resend-email-form').on('submit', function (e) {
                $('#resend-email-btn').attr('disabled', true).text('Отправка...');
                e.preventDefault(); // Останавливаем стандартное поведение формы

                $.ajax({
                    url: ajax_object.ajax_url, // Используем переданный URL
                    type: 'POST',
                    data: {
                        action: 'resend_email_confirmation',
                        nonce: ajax_object.resend_nonce // Передаем nonce
                    },
                    success: function (response) {
                        if (response.success) {
                            $('#resend-email-btn').hide(); // Скрываем кнопку
                            $('#email-status').show();     // Показываем сообщение об успешной отправке
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
    if (isset($_GET['email_confirmed']) && $_GET['email_confirmed'] == 'true') : ?>
        <p style="color: green;">Ваш адрес электронной почты был успешно подтвержден!</p>
    <?php endif; ?>

    <?php include(plugin_dir_path(__FILE__) . 'cabinet-header.php'); ?>

    <div class="iti-profile">
        <h1>Профиль пользователя</h1>
        <?php echo do_shortcode('[iti_cabinet_profile]'); ?>
    </div>

<?php endif; ?>

<?php get_footer(); ?>
