<?php

add_filter('show_admin_bar', function($show) {
    // Проверяем, если пользователь вошёл в систему и находитесь не в админке
    if (!is_admin() && is_user_logged_in()) {
        $user = wp_get_current_user();
        // Указываем массив ролей, для которых панель нужно скрыть
        $roles_to_hide = ['subscriber', 'contributor'];

        // Если роль пользователя соответствует одной из указанных
        if (array_intersect($roles_to_hide, $user->roles)) {
            return false; // Отключаем admin bar на фронте
        }
    }
    return $show; // Включаем admin bar для остальных
});
