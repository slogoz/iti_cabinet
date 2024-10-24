<?php
/* Template for Login Page */

use classes\iti\Box;

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
            wp_redirect(site_url('/profile'));
            exit;
        }
    }
}

get_header();

include(plugin_dir_path(__FILE__) . 'cabinet-header.php');

$message = false;

if (isset($_GET['email']) && $_GET['email'] == 'failed') {
    $message = iti_bl_message('Ошибка: неверный email или пароль.', 'error');
}

$html = 'Если у вас нет пользователя на сайте, то вам сначала надо <a href="' . site_url('/register') . '" class="iti-link">зарегистрироваться</a>.';
iti_bl_panel($html);

$title = 'Вход на сайт';
iti_bl_header($title);

ob_start();
if ($message) {
    echo $message;
}

?>
<form method="post" action="" class="iti-form">
    <?php wp_nonce_field('iti_login_action', 'iti_login_nonce'); ?>
    <div class="form-group">
        <label for="user_email" class="control-label">Email</label>
        <input type="email" name="email" id="user_email" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="user_pass" class="control-label">Пароль</label>
        <input type="password" name="pwd" id="user_pass" class="form-control" required>
    </div>
    <div class="form-group offset-top">
        <div class="control-label"></div>
        <label for="rememberme" class="control-field">
            <input name="rememberme" type="checkbox" id="rememberme" class="form-checkbox" value="forever"> запомнить
            меня
        </label>
    </div>
    <div class="form-group">
        <div class="control-label"></div>
        <div for="rememberme" class="control-field">
            <button type="submit" class="control-but control-but_primary">
                <?php iti_icon('sign-in'); ?>
                Войти
            </button>
            <a href="<?php echo site_url('/password-reset'); ?>" class="control-but control-but_link">Забыли пароль?</a>
        </div>
    </div>
</form>
<?php
$html = ob_get_clean();
iti_bl_panel($html);
?>

<?php include(plugin_dir_path(__FILE__) . 'cabinet-footer.php'); ?>

<?php get_footer(); ?>
