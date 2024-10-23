<?php

use classes\iti\Box;

if (!defined('ABSPATH')) {
    exit;
}

?>

<div class="iti-cabinet-content">

<?php

$actions = array(
    'profile',
    'profile_edit',
    'orders',
    'password_change'
);

//if (is_user_logged_in() && in_array(get_query_var('iti_cabinet_action'), $actions)) :
if (is_user_logged_in()) :

Box::getInstance()->register('cab-head', function () {

    $links = array(
            array(
                    'order' => 10,
                    'url' => site_url('/profile'),
                    'name' => 'Профиль'
            ),
            'profile-edit' => array(
                    'order' => 20,
                    'url' => site_url('/profile-edit'),
                    'name' => 'Редактировать профиль'
            ),
            'orders' => array(
                    'order' => 30,
                    'url' => site_url('/orders'),
                    'name' => 'История заказов'
            ),
            array(
                    'order' => 40,
                    'url' => wp_logout_url(site_url('/login')),
                    'name' => 'Выйти'
            ),
//            array(
//                    'url' => iti_cabinet_login_url(),
//                    'name' => 'Войти'
//            ),
    );

    $links = apply_filters('iti_cabinet_head_menu_links_array', $links);

    unset($links['orders']);
    unset($links['profile-edit']);

    usort($links, function($a, $b) {
        return $a['order'] <=> $b['order']; // Используем оператор spaceship для сравнения
    });

    $uri_current = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    $user_id = get_current_user_id();
    $user = get_iti_user($user_id);
    $background_image_url = "url('{$user->image_head}')";
?>
<div class="iti-nav">
    <ul>
        <?php foreach($links as $link ) :
            $uri = trim(parse_url($link['url'], PHP_URL_PATH), '/');
            $class_attr = '';
            if ($uri === $uri_current || str_starts_with($uri_current, $uri . '/')) {
                $class_attr = ' class="active"';
            }

            $name = $link['name'];

            if(isset($link['state'])) {
                $name .= library_tag_get_count_state($link['state']);
            }
        ?>
            <li<?php echo $class_attr; ?>><a data-path="<?php echo $uri_current; ?>" href="<?php echo $link['url']; ?>"><?php echo $name; ?></a></li>
        <?php endforeach; ?>
    </ul>
</div>
<div class="iti-user-view" style="background-image: <?php echo $background_image_url; ?>;">
    <div class="iti-user-view__pic">
        <img src="<?php echo $user->image; ?>" alt="" class="iti-user-view__img">
    </div>
    <div class="iti-user-view__info">
        <h1 class="iti-user-view__title"><?php echo $user->firstname; ?></h1>
        <div class="iti-user-view__text"><?php echo $user->status; ?></div>
        <div class="iti-user-view__text">пол: <?php echo $user->gender; ?></div>
        <div class="iti-user-view__text">регистрация: <?php echo $user->interval_info; ?></div>
    </div>
</div>
<?php

}, true);

add_action('cab-head', function () {

//    ob_start();
    Box::getInstance()->resolve('cab-head');

//    $head = ob_get_clean();

//    iti_bl_panel($head);

});

?>

<?php endif; ?>