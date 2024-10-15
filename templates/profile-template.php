<?php
/* Template for Profile Page */
if (!defined('ABSPATH')) {
    exit;
}
get_header(); ?>

<div class="iti-profile">
    <h1>Профиль пользователя</h1>
    <?php echo do_shortcode('[iti_cabinet_profile]'); ?>
</div>

<?php get_footer(); ?>
