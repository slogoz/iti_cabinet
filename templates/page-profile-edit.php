<?php
/* Template for Profile Edit Page */
if (!defined('ABSPATH')) {
    exit;
}
get_header(); ?>

<?php include(plugin_dir_path(__FILE__) . 'cabinet-header.php'); ?>

<div class="iti-profile-edit">
    <h1>Редактирование профиля</h1>
    <?php echo do_shortcode('[iti_cabinet_profile_edit]'); ?>
    <p><a href="<?php echo site_url('/password-change'); ?>">Сменить пароль</a></p>
</div>

<?php get_footer(); ?>
