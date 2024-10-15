<?php
/* Template for Profile Edit Page */
if (!defined('ABSPATH')) {
    exit;
}
get_header(); ?>

<div class="iti-profile-edit">
    <h1>Редактирование профиля</h1>
    <?php echo do_shortcode('[iti_cabinet_profile_edit]'); ?>
</div>

<?php get_footer(); ?>
