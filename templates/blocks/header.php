<!-- templates/panel.php -->
<div class="<?php echo esc_attr($class); ?>">
    <?php
    if (!empty($tag)) {
        echo "<{$tag}>";
    }
    echo esc_html($title);
    if (!empty($tag)) {
        echo "</{$tag}>";
    } ?>
</div>