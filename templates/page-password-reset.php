<?php
/* Template for Password Reset Page */
if (!defined('ABSPATH')) {
    exit;
}
get_header();

// Проверка, если форма отправки email для восстановления пароля отправлена
if (isset($_POST['iti_reset_password'])) {
    $email = sanitize_email($_POST['email']);
    if (empty($email)) {
        echo '<p>Введите корректный email.</p>';
    } else {
        // Логика отправки email с токеном для сброса пароля
        iti_send_password_reset_email($email);
    }
} elseif (isset($_GET['key']) && isset($_GET['login'])) {
    // Если пользователь перешел по ссылке для сброса пароля с токеном
    $key = sanitize_text_field($_GET['key']);
    $login = sanitize_text_field($_GET['login']);

    // Проверка валидности токена сброса пароля
    $user = check_password_reset_key($key, $login);

    if (is_wp_error($user)) {
        echo '<p>Неверный или истекший ключ для сброса пароля.</p>';
    } else {
        // Если ключ валидный, показываем форму для ввода нового пароля
        if (isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
            $new_password = sanitize_text_field($_POST['new_password']);
            $confirm_password = sanitize_text_field($_POST['confirm_password']);

            // Проверка совпадения паролей
            if ($new_password !== $confirm_password) {
                echo '<p>Пароли не совпадают. Попробуйте снова.</p>';
            } elseif (empty($new_password) || strlen($new_password) < 6) {
                echo '<p>Пароль должен быть не менее 6 символов.</p>';
            } else {
                // Сброс пароля пользователя
                reset_password($user, $new_password);
                echo '<p>Ваш пароль был успешно сброшен. Вы можете войти с новым паролем.</p>';
            }
        } else {
            // Форма для ввода нового пароля
            ?>
            <h2>Введите новый пароль</h2>
            <form method="POST">
                <p>
                    <label for="new_password">Новый пароль:</label>
                    <input type="password" name="new_password" required />
                </p>
                <p>
                    <label for="confirm_password">Подтвердите новый пароль:</label>
                    <input type="password" name="confirm_password" required />
                </p>
                <p>
                    <input type="submit" value="Сбросить пароль" />
                </p>
            </form>
            <?php
        }
    }

    echo '<p><a href="' . site_url('/login') . '">Войти</a></p>';

} else {
    // Отображаем форму для отправки email, если не было передано ключа и логина
    ?>
    <h2>Восстановление пароля</h2>
    <form method="POST">
        <p>
            <label for="email">Введите ваш email для сброса пароля:</label>
            <input type="email" name="email" required value="test@test.com" />
        </p>
        <p>
            <input type="submit" name="iti_reset_password" value="Отправить письмо для сброса" />
        </p>
    </form>
    <?php
}

get_footer();

/**
 * Функция для отправки письма с токеном восстановления пароля.
 *
 * @param string $email Email адрес пользователя.
 */
function iti_send_password_reset_email($email) {
    if (!email_exists($email)) {
        echo '<p>Email не найден в системе.</p>';
        return;
    }

    // Получение информации о пользователе
    $user = get_user_by('email', $email);
    $reset_key = get_password_reset_key($user);

    if (is_wp_error($reset_key)) {
        echo '<p>Ошибка при создании ссылки для сброса.</p>';
        return;
    }

    // Генерация ссылки для сброса пароля
    $reset_url = site_url("/password-reset/?key=$reset_key&login=" . rawurlencode($user->user_login));

    // Отправка email пользователю
    $message = "Для сброса пароля перейдите по следующей ссылке: $reset_url";
    wp_mail($email, 'Восстановление пароля', $message);

    error_log($reset_url);

    echo '<p>Письмо для восстановления пароля отправлено на ваш email.</p>';
    echo '<p><a href="' . site_url('/login') . '">Войти</a></p>';

}
?>
