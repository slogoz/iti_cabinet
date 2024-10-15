<?php
/* Template for Orders Page */
if (!defined('ABSPATH')) {
    exit;
}
get_header(); ?>

<div class="iti-orders">
    <h1>История заказов</h1>
    <?php echo do_shortcode('[iti_cabinet_orders]'); ?>
</div>

<?php get_footer(); ?>
