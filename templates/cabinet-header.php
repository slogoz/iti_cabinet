<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="iti-cabinet-header">
    <ul>
        <li><a href="<?php echo site_url('/profile'); ?>">Профиль</a></li>
        <li><a href="<?php echo site_url('/profile-edit'); ?>">Редактировать профиль</a></li>
        <li><a href="<?php echo site_url('/orders'); ?>">История заказов</a></li>
        <li><a href="<?php echo wp_logout_url(); ?>">Выйти</a></li>
    </ul>
</div>
