<?php
/* Template for Profile Edit Page */
if (!defined('ABSPATH')) {
    exit;
}
if (isset($_POST['user_id'])) {
    update_user_profile($_POST['user_id']);
}

get_header();

include(plugin_dir_path(__FILE__) . 'cabinet-header.php');

do_action('cab-head');

iti_bl_header('Данные пользователя');

$user = get_iti_user(get_current_user_id());

$gender_checked = $user->gender === 'мужской' ? '' : ' checked';

echo '<form method="post" action="" enctype="multipart/form-data">';

echo '<div class="container-fluid container-no-padding">';
echo '<div class="row">';
echo '<div class="col-md-12 col-lg-9">';

ob_start();
?>
    <div class="iti-form">
        <?php wp_nonce_field('iti_login_action', 'iti_login_nonce'); ?>
        <input type="hidden" name="user_id" value="<?php echo $user->id; ?>">
        <div class="form-group">
            <label for="user_email" class="control-label">Email</label>
            <input type="email" name="email" id="user_email" class="form-control"
                   value="<?php echo $user->email; ?>" disabled>
        </div>
        <div class="form-group">
            <label for="user_firstname" class="control-label">Имя</label>
            <input type="text" name="user_firstname" id="user_firstname" class="form-control" required
                   value="<?php echo $user->firstname; ?>">
        </div>
        <div class="form-group">
            <label for="user_gender" class="control-label">Пол</label>
            <div class="control-editor">
                <div class="switch-wrapper">
                    <input type="checkbox" id="user_gender" name="user_gender"
                           value="female" <?php echo $gender_checked; ?>>
                </div>
            </div>
        </div>
        <?php do_action('it_cabinet_fields_profile_edit', $user); ?>
    </div>
<?php
$html = ob_get_clean();
iti_bl_panel($html, array('class' => 'panel-unobserved'));

echo '</div>';
echo '</div>';
echo '</div>';

iti_bl_header('Изображения профиля');

echo '<div class="container-fluid container-no-padding">';
echo '<div class="row">';
echo '<div class="col-md-12 col-lg-9">';

ob_start();
?>
    <div class="iti-form">
        <?php do_action('it_cabinet_fields_profile_edit_bottom', $user); ?>
        <div class="form-group">
            <div class="control-label"></div>
            <div class="control-field">
                <button type="submit" class="control-but control-but_primary">
                    Сохранить изменения
                </button>
            </div>
        </div>
    </div>
<?php
$html = ob_get_clean();
iti_bl_panel($html, array('class' => 'panel-unobserved'));

echo '</div>';
echo '</div>';
echo '</div>';

echo '</form>';

include(plugin_dir_path(__FILE__) . 'cabinet-footer.php');

get_footer();
