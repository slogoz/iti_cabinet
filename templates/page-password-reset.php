<?php

use classes\iti\Box;

/* Template for Password Reset Page */
if (!defined('ABSPATH')) {
    exit;
}
get_header();

include(plugin_dir_path(__FILE__) . 'cabinet-header.php');

$message = false;
$form_show = true;
$form_other = false;

// Проверка, если форма отправки email для восстановления пароля отправлена
if (isset($_POST['iti_reset_password'])) {
    $email = sanitize_email($_POST['email']);
    if (empty($email)) {
        $message = iti_bl_message('Введите корректный email.', 'error', [], true);

    } else {
        // Логика отправки email с токеном для сброса пароля
        $args = iti_send_password_reset_email($email);
        $message = iti_bl('message', $args, true);
    }
} elseif (isset($_GET['key']) && isset($_GET['login'])) {
    // Если пользователь перешел по ссылке для сброса пароля с токеном
    $key = sanitize_text_field($_GET['key']);
    $login = sanitize_text_field($_GET['login']);

    // Проверка валидности токена сброса пароля
    $user = check_password_reset_key($key, $login);

    if (is_wp_error($user)) {
        $message = iti_bl_message('Неверный или истекший ключ для сброса пароля.', 'error');

    } else {
        // Если ключ валидный, показываем форму для ввода нового пароля
        if (isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
            $new_password = sanitize_text_field($_POST['new_password']);
            $confirm_password = sanitize_text_field($_POST['confirm_password']);

            // Проверка совпадения паролей
            if ($new_password !== $confirm_password) {
                $message = iti_bl_message('Пароли не совпадают. Попробуйте снова.', 'error');

            } elseif (empty($new_password) || mb_strlen($new_password, 'UTF-8') < 6) {
                $message = iti_bl_message('Пароль должен быть не менее 6 символов.', 'error');

            } else {
                // Сброс пароля пользователя
                reset_password($user, $new_password);
                $msg_text = 'Ваш пароль был успешно сброшен. Вы можете <a href="' . site_url('/login') . '" class="iti-link">войти</a> с новым паролем.';
                $message = iti_bl_message($msg_text, 'success');
                $form_show = false;
            }
        }
        // Форма для ввода нового пароля

        if ($form_other === false) {
            ob_start();
            ?>
            <form method="POST" class="iti-form">
                <div class="form-group">
                    <label for="new_password" class="control-label">Новый пароль</label>
                    <input type="password" name="new_password" required class="form-control"/>
                </div>
                <div class="form-group">
                    <label for="confirm_password" class="control-label">Повтор пароля</label>
                    <input type="password" name="confirm_password" required class="form-control"/>
                </div>
                <div class="form-group">
                    <div class="control-label"></div>
                    <div class="control-field">
                        <button type="submit" class="control-but control-but_primary">
                            <?php iti_icon('refresh'); ?>
                            Поменять пароль
                        </button>
                    </div>
                </div>
            </form>
            <?php
            $form_other = ob_get_clean();
        }
    }

//    echo '<p><a href="' . site_url('/login') . '">Войти</a></p>';

}
// Отображаем форму для отправки email, если не было передано ключа и логина

if ($form_show) {
    iti_bl_header('Восстановление пароля');

    ob_start();

    if ($message) {
        echo $message;
    }

    if ($form_other !== false) {
        echo $form_other;
    } else {
        ?>
        <form method="POST" class="iti-form">
            <div class="form-group">
                <label for="email" class="control-label">Email</label>
                <input type="email" name="email" class="form-control" required value="test@test.com"/>
            </div>
            <div class="form-group">
                <div class="control-label"></div>
                <div class="control-field">
                    <input type="submit" name="iti_reset_password" class="control-but control-but_primary"
                           value="Восстановить пароль"/>
                </div>
            </div>
        </form>
        <?php
    }

    $content = ob_get_clean();
    iti_bl_panel($content);
}

include(plugin_dir_path(__FILE__) . 'cabinet-footer.php');

get_footer();

/**
 * Функция для отправки письма с токеном восстановления пароля.
 *
 * @param string $email Email адрес пользователя.
 */
function iti_send_password_reset_email($email)
{
    if (!email_exists($email)) {
        return array(
            'content' => 'Email не найден в системе.',
            'class' => 'message-error',
        );
    }

    // Получение информации о пользователе
    $user = get_user_by('email', $email);
    $reset_key = get_password_reset_key($user);

    if (is_wp_error($reset_key)) {
        return array(
            'content' => 'Ошибка при создании ссылки для сброса.',
            'class' => 'message-error',
        );
    }

    // Генерация ссылки для сброса пароля
    $reset_url = site_url("/password-reset/?key=$reset_key&login=" . rawurlencode($user->user_login));

    // Отправка email пользователю
    $message = "Для сброса пароля перейдите по следующей ссылке: $reset_url";
    wp_mail($email, 'Восстановление пароля', $message);

    error_log($reset_url);

    return array(
        'content' => 'Ссылка на сброс пароля была отправлена на ваш email.',
        'class' => 'message-success',
    );
}

?>
