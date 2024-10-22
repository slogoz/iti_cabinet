<!-- templates/panel.php -->
<div class="panel <?php echo esc_attr($class); ?>"<?php echo $id_attr; ?>>
    <?php if (!empty($title)): ?>
        <div class="panel-heading">
            <h3 class="panel-title">
                <?php echo esc_html($title) . $after_title; ?>
            </h3>
        </div>
    <?php endif; ?>
    <div class="panel-body">
        <?php echo $content; ?>
    </div>
</div>