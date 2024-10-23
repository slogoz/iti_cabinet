<?php
/* Template for Profile Page */
if (!defined('ABSPATH')) {
    exit;
}

get_header();

include(WP_ITI_CABINET_DIR . 'templates/' . 'cabinet-header.php');

do_action('cab-head');

echo '<div class="container-fluid container-no-padding">';
echo '<div class="row">';
echo '<div class="col-md-12 col-lg-12">';

$links = array(
    array(
        'order' => 10,
        'url' => site_url('/library'),
        'name' => 'Все',
        'state' => 'all'
    ),
    'profile-edit' => array(
        'order' => 20,
        'url' => site_url('/library/wish'),
        'name' => 'Хочу прочитать',
        'state' => 'wish'
    ),
    'orders' => array(
        'order' => 30,
        'url' => site_url('/library/reading'),
        'name' => 'Читаю',
        'state' => 'reading'
    ),
);

$links = apply_filters('iti_cabinet_library_menu_links_array', $links);

usort($links, function($a, $b) {
    return $a['order'] <=> $b['order'];
});

$uri_current = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

?>
<div class="iti-nav">
    <ul>
        <?php foreach($links as $link ) :
            $uri = trim(parse_url($link['url'], PHP_URL_PATH), '/');
            $class_attr = '';
            if ($uri === $uri_current) {
                $class_attr = ' class="active"';
            }

            $name = $link['name'] . library_tag_get_count_state($link['state']);

            ?>
            <li<?php echo $class_attr; ?>><a data-path="<?php echo $uri_current; ?>" href="<?php echo $link['url']; ?>"><?php echo $name; ?></a></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php

echo '</div>';
echo '<div class="col-md-12 col-lg-12">';
echo '<div class="row">';

$content = 'Вы пока ничего не написали здесь.';

echo '<div class="col-sm-12 col-md-6">';
iti_bl_panel($content, array(
    'title' => 'Блок 1',
    'class' => 'panel-default'
));
echo '</div>';

$content = 'Вы пока ничего не написали здесь.';

echo '<div class="col-sm-12 col-md-6">';
iti_bl_panel($content, array(
    'title' => 'Блок 2',
    'class' => 'panel-default'
));
echo '</div>';

echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';
?>

<?php include(WP_ITI_CABINET_DIR . 'templates/' . 'cabinet-footer.php'); ?>

<?php get_footer(); ?>
