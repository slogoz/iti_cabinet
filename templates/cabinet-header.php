<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="iti-cabinet-header">
    <ul>
        <?php if (is_user_logged_in()) : ?>
            <li><a href="<?php echo site_url('/profile'); ?>">Профиль</a></li>
            <li><a href="<?php echo site_url('/profile-edit'); ?>">Редактировать профиль</a></li>
            <li><a href="<?php echo site_url('/orders'); ?>">История заказов</a></li>
            <li><a href="<?php echo wp_logout_url(site_url('/login')); ?>">Выйти</a></li>
        <?php else : ?>
            <li><a href="<?php echo iti_cabinet_login_url(); ?>">Войти</a></li>
        <?php endif; ?>
    </ul>
</div>
