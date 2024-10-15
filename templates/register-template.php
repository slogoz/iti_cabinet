<?php
/* Template for Register Page */
if (!defined('ABSPATH')) {
    exit;
}
get_header(); ?>

<?php include(plugin_dir_path(__FILE__) . 'cabinet-header.php'); ?>

<div class="iti-register-form">
    <h2>Регистрация</h2>

    <?php if (isset($_GET['error_reg'])): ?>
        <?php if ($_GET['error_reg'] == 'password_mismatch'): ?>
            <p style="color: red;">Ошибка: пароли не совпадают.</p>
        <?php elseif ($_GET['error_reg'] == 'email_exists'): ?>
            <p style="color: red;">Ошибка: этот email уже зарегистрирован.</p>
        <?php elseif ($_GET['error_reg'] == 'registration_failed'): ?>
            <p style="color: red;">Ошибка: регистрация не удалась, попробуйте еще раз.</p>
        <?php endif; ?>
    <?php endif; ?>

    <form method="post" action="">
        <?php wp_nonce_field('iti_register_action', 'iti_register_nonce'); ?>
        <p>
            <label for="user_name">Имя:</label>
            <input type="text" name="user_name" id="user_name" required>
        </p>
        <p>
            <label for="user_email">Email:</label>
            <input type="email" name="user_email" id="user_email" required>
        </p>
        <p>
            <label for="user_pass">Пароль:</label>
            <input type="password" name="user_pass" id="user_pass" required>
        </p>
        <p>
            <label for="user_pass_confirm">Повторите пароль:</label>
            <input type="password" name="user_pass_confirm" id="user_pass_confirm" required>
        </p>
        <p>
            <label>Пол:</label>
            <label><input type="radio" name="user_gender" value="Мужской" required> Мужской</label>
            <label><input type="radio" name="user_gender" value="Женский" required> Женский</label>
        </p>
        <p>
            <button type="submit">Зарегистрироваться</button>
        </p>
    </form>

    <p>Уже зарегистрированы? <a href="<?php echo site_url('/login'); ?>">Войти</a></p>
</div>

<?php get_footer(); ?>
