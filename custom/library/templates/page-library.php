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
    'wish' => array(
        'order' => 20,
        'url' => site_url('/library/wish'),
        'name' => 'Хочу прочитать',
        'state' => 'wish'
    ),
    'reading' => array(
        'order' => 30,
        'url' => site_url('/library/reading'),
        'name' => 'Читаю',
        'state' => 'reading'
    ),
    'unread' => array(
        'order' => 40,
        'url' => site_url('/library/unread'),
        'name' => 'Не дочитал',
        'state' => 'unread'
    ),
    'read' => array(
        'order' => 50,
        'url' => site_url('/library/read'),
        'name' => 'Прочитал',
        'state' => 'read'
    ),
    'favorite' => array(
        'order' => 60,
        'url' => site_url('/library/favorite'),
        'name' => 'Любимые',
        'state' => 'favorite'
    ),
    'unfinished' => array(
        'order' => 60,
        'url' => site_url('/library/unfinished'),
        'name' => 'Недописано',
        'state' => 'unfinished'
    ),
    'black_list' => array(
        'order' => 60,
        'url' => site_url('/library/black_list'),
        'name' => 'Чёрный список',
        'state' => 'black_list'
    ),
);

$links = apply_filters('iti_cabinet_library_menu_links_array', $links);

usort($links, function ($a, $b) {
    return $a['order'] <=> $b['order'];
});

$uri_current = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

?>
<div class="iti-nav">
    <div  class="iti-nav__list">
        <?php foreach ($links as $link) :
            $uri = trim(parse_url($link['url'], PHP_URL_PATH), '/');

            $class_attr = ' class="iti-nav__list-item';
            if ($uri === $uri_current) {
                $class_attr .= ' active"';
            }
            $class_attr .= '"';

            $name = $link['name'] . library_tag_get_count_state($link['state']);

            ?>
            <div<?php echo $class_attr; ?>><a data-path="<?php echo $uri_current; ?>"
                                             href="<?php echo $link['url']; ?>"><?php echo $name; ?></a></div>
        <?php endforeach; ?>
    </div>
</div>
<?php

echo '</div>';

include plugin_dir_path(__FILE__) . 'block-lib-nav-sorting.php';
include plugin_dir_path(__FILE__) . 'block-lib-result-view.php';

echo '</div>'; // .row
echo '</div>'; // .container
?>

<?php include(WP_ITI_CABINET_DIR . 'templates/' . 'cabinet-footer.php'); ?>

<?php get_footer(); ?>
