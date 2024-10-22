<?php
/* Template for Register Page */
if (!defined('ABSPATH')) {
    exit;
}
get_header();

include(plugin_dir_path(__FILE__) . 'cabinet-header.php');

$panel_message = false;

if (isset($_GET['error_reg'])): ?>
    <?php if ($_GET['error_reg'] == 'password_mismatch'):
        $panel_message = iti_bl_panel('Ошибка: пароли не совпадают.', ['class' => 'panel-error'], true);
    elseif ($_GET['error_reg'] == 'email_exists'):
        $panel_message = iti_bl_panel('Ошибка: этот email уже зарегистрирован.', ['class' => 'panel-error'], true);
    elseif ($_GET['error_reg'] == 'registration_failed'):
        $panel_message = iti_bl_panel('Ошибка: регистрация не удалась, попробуйте еще раз.', ['class' => 'panel-error'], true);
    endif; ?>
<?php endif;

if ($panel_message) {
    echo $panel_message;
}

iti_bl_header('Регистрация на сайте');

ob_start();

?>
<form method="post" action="" class="iti-form">
    <?php wp_nonce_field('iti_register_action', 'iti_register_nonce'); ?>
    <div class="form-group">
        <label for="user_name" class="control-label">Имя</label>
        <input type="text" name="user_name" id="user_name" class="form-control" required value="Test">
    </div>
    <div class="form-group">
        <label for="user_email" class="control-label">Email</label>
        <input type="email" name="user_email" id="user_email" class="form-control" required value="test@test.com">
    </div>
    <div class="form-group">
        <label for="user_pass" class="control-label">Пароль</label>
        <input type="password" name="user_pass" id="user_pass" class="form-control" required value="test">
    </div>
    <div class="form-group">
        <label for="user_pass_confirm" class="control-label">Повтор пароля</label>
        <input type="password" name="user_pass_confirm" id="user_pass_confirm" class="form-control" required value="test">
    </div>
    <div class="form-group">
        <label for="user_gender" class="control-label">Пол</label>
        <div class="switch-wrapper">
            <input type="checkbox" id="user_gender" name="user_gender" value="female">
        </div>
    </div>
    <div class="form-group">
        <div class="control-label"></div>
        <div class="control-field">
            <button type="submit" class="control-but control-but_primary">
                Зарегистрироваться
            </button>
        </div>
    </div>
</form>

<p>Уже зарегистрированы? <a href="<?php echo site_url('/login'); ?>" class="iti-link">Войти</a></p>
<?php
$html = ob_get_clean();
iti_bl_panel($html);
?>

<?php include(plugin_dir_path(__FILE__) . 'cabinet-footer.php'); ?>

<?php get_footer(); ?>
