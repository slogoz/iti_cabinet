<?php
/* Template for Login Page */
if (!defined('ABSPATH')) {
    exit;
}
get_header(); ?>

<?php include(plugin_dir_path(__FILE__) . 'cabinet-header.php'); ?>

<div class="iti-login-form">
    <h2>Вход в аккаунт</h2>
    <?php if (isset($_GET['login']) && $_GET['login'] == 'failed'): ?>
        <p style="color: red;">Ошибка: неверный логин или пароль.</p>
    <?php endif; ?>

    <form method="post" action="">
        <?php wp_nonce_field('iti_login_action', 'iti_login_nonce'); ?>
        <p>
            <label for="user_login">Email:</label>
            <input type="email" name="log" id="user_login" required>
        </p>
        <p>
            <label for="user_pass">Пароль:</label>
            <input type="password" name="pwd" id="user_pass" required>
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
