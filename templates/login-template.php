<?php
/* Template for Login Page */
if (!defined('ABSPATH')) {
    exit;
}

// Обработка формы при отправке
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Проверка nonce
    if (!isset($_POST['iti_login_nonce']) || !wp_verify_nonce($_POST['iti_login_nonce'], 'iti_login_action')) {
        echo '<p style="color: red;">Ошибка: недействительная форма.</p>';
    } else {
        // Получение данных из формы
        $email = sanitize_text_field($_POST['email']);
        $password = sanitize_text_field($_POST['pwd']);
        $remember = isset($_POST['rememberme']); // Проверка на "запомнить меня"

        // Пытаемся авторизовать пользователя
        $user = wp_authenticate($email, $password);

        if (is_wp_error($user)) {
            // Если ошибка, перенаправляем с параметром
            wp_redirect(add_query_arg('email', 'failed', $_SERVER['REQUEST_URI']));
            exit;
        } else {
            // После успешной авторизации
            wp_clear_auth_cookie(); // Очистка старых куки

            // Устанавливаем куки для авторизованного пользователя
            wp_set_current_user($user->ID); // Устанавливаем текущего пользователя
            wp_set_auth_cookie($user->ID, $remember); // Устанавливаем куки с учетом чекбокса "Запомнить меня"

            // Убедись, что роли установлены корректно
            do_action('wp_email', $user->user_email, $user);

            // Перенаправляем пользователя
            wp_redirect(home_url('/profile'));
            exit;
        }
    }
}

get_header();

include(plugin_dir_path(__FILE__) . 'cabinet-header.php');
?>

<div class="iti-login-form">
    <h2>Вход в аккаунт</h2>
    <?php if (isset($_GET['login']) && $_GET['login'] == 'failed'): ?>
        <p style="color: red;">Ошибка: неверный email или пароль.</p>
    <?php endif; ?>

    <form method="post" action="">
        <?php wp_nonce_field('iti_login_action', 'iti_login_nonce'); ?>
        <p>
            <label for="user_email">Email:</label>
            <input type="email" name="email" id="user_email" required value="test@test.com">
        </p>
        <p>
            <label for="user_pass">Пароль:</label>
            <input type="password" name="pwd" id="user_pass" required value="test">
        </p>
        <p>
            <label for="rememberme">
                <input name="rememberme" type="checkbox" id="rememberme" value="forever"> Запомнить меня
            </label>
        </p>
        <p>
            <button type="submit">Войти</button>
        </p>
    </form>

    <p>Нет аккаунта? <a href="<?php echo site_url('/register'); ?>">Зарегистрироваться</a></p>
</div>

<?php get_footer(); ?>
