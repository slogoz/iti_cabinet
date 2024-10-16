<?php
if (!defined('ABSPATH')) {
    exit;
}

if (isset($_POST['iti_change_password_nonce']) && wp_verify_nonce($_POST['iti_change_password_nonce'], 'iti_change_password_action')) {
    $current_password = sanitize_text_field($_POST['current_password']);
    $new_password = sanitize_text_field($_POST['new_password']);
    $confirm_password = sanitize_text_field($_POST['confirm_password']);
    $user_id = get_current_user_id();
    $user = get_user_by('ID', $user_id);

    if ($user) {
        if (!wp_check_password($current_password, $user->user_pass, $user_id)) {
            $error_message = 'Текущий пароль неверен.';
        } elseif ($new_password !== $confirm_password) {
            $error_message = 'Пароли не совпадают.';
        } elseif (empty($new_password) || strlen($new_password) < 6) {
            $error_message = 'Пароль должен быть не менее 6 символов.';
        } else {
            // Смена пароля и обновление куки
            wp_set_password($new_password, $user_id);
            wp_set_auth_cookie($user_id, true);

            // Перенаправление с параметром успеха
            wp_redirect(add_query_arg('password_changed', 'true', site_url('/password-change/')));
            exit;
        }
    }
}

get_header();

// Проверка, был ли успешно изменен пароль
if (isset($_GET['password_changed']) && $_GET['password_changed'] === 'true') : ?>
    <p>Пароль успешно изменен!</p>
    <p><a href="<?php echo site_url('/profile'); ?>">Перейти в профиль</a></p>
<?php else :
    // Если ошибка, выводим ее
    if (isset($error_message)) {
        echo '<p>' . esc_html($error_message) . '</p>';
    }
    ?>
    <h2>Изменить пароль</h2>
    <form method="POST">
        <?php wp_nonce_field('iti_change_password_action', 'iti_change_password_nonce'); ?>
        <p>
            <label for="current_password">Текущий пароль:</label>
            <input type="password" name="current_password" required/>
        </p>
        <p>
            <label for="new_password">Новый пароль:</label>
            <input type="password" name="new_password" required/>
        </p>
        <p>
            <label for="confirm_password">Подтвердите новый пароль:</label>
            <input type="password" name="confirm_password" required/>
        </p>
        <p>
            <input type="submit" value="Изменить пароль"/>
        </p>
    </form>
<?php
endif;

get_footer();
?>
