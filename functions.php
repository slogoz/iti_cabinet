<?php

use classes\iti\Box;

function iti_bl($name, $args = array(), $return = false)
{
    return Box::getInstance()->resolve($name, array('args' => $args))->render($return);
}

function iti_bl_header($title, $args = array(), $return = false)
{
    $args['title'] = $title;

    return iti_bl('header', $args, $return);
}

function iti_bl_panel($content, $args = array(), $return = false)
{
    $args['content'] = $content;

    return iti_bl('panel', $args, $return);
}

function iti_bl_message($message, $type = 'default', $args = array(), $return = true)
{
    $args['content'] = $message;

    if (empty($args['class'])) {
        $args['class'] = 'message-' . $type;
    } else {
        $args['class'] .= ' message-' . $type;
    }

    return iti_bl('message', $args, $return);
}

function iti_icon($name, $args = array(), $return = false)
{
    $html = <<<HTML
        <svg class="icon-{$name}">
            <use xlink:href="#icon-{$name}"></use>
        </svg>
        HTML;

    if ($return) {
        return $html;
    }

    echo $html;
}

function create_image($name = 'S', $args = array())
{
    $defaults = array(
        'width' => 150,
        'height' => 150,
        'background' => [61, 140, 163],
        'color' => [255, 255, 255],
        'letter' => mb_substr($name, 0, 1, 'UTF-8'),
        'font_size' => 50,
        'font_file' => WP_ITI_CABINET_DIR . '/fonts/arial.ttf' // Путь к TTF файлу шрифта
    );

    $args = array_merge($defaults, $args);

// Создание изображения
    $image = imagecreatetruecolor($args['width'], $args['height']);

// Установка цветов
    $bgColor = imagecolorallocate($image, $args['background'][0], $args['background'][1], $args['background'][2]);
    $textColor = imagecolorallocate($image, $args['color'][0], $args['color'][1], $args['color'][2]);

// Заливка фона
    imagefill($image, 0, 0, $bgColor);

// Получение границ текста для центрирования
    $textBox = imagettfbbox($args['font_size'], 0, $args['font_file'], $args['letter']);
    $textWidth = $textBox[4] - $textBox[0];
    $textHeight = $textBox[5] - $textBox[1];

    $textX = ($args['width'] - $textWidth) / 2;
    $textY = ($args['height'] - $textHeight) / 2;

// Добавление текста с использованием TTF шрифта
    imagettftext($image, $args['font_size'], 0, (int)$textX, (int)$textY, $textColor, $args['font_file'], $args['letter']);

// Захват изображения в буфер
    ob_start();
    imagepng($image);
    $imageData = ob_get_contents();
    ob_end_clean();

// Освобождение памяти
    imagedestroy($image);

// Закодировать изображение в base64
    $base64Image = base64_encode($imageData);

// Вывод изображения в формате data URL
    return 'data:image/png;base64,' . $base64Image;
}

function get_interval_days($date)
{
    $current_date = new DateTime();

    $interval = $date->diff($current_date);

    return $interval->days;
}

function plural_form($number, $one = 'день', $few = 'дня', $many = 'дней')
{
    $number = abs($number) % 100;
    $n1 = $number % 10;

    if ($number > 10 && $number < 20) {
        return $many;
    }
    if ($n1 > 1 && $n1 < 5) {
        return $few;
    }
    if ($n1 == 1) {
        return $one;
    }

    return $many;
}

function update_user_activity()
{
    $user_id = get_current_user_id();

    if ($user_id) {
        update_user_meta($user_id, 'last_activity', current_time('timestamp'));
    }
}

function get_user_status($user_id)
{
    $last_activity = get_user_meta($user_id, 'last_activity', true);
    $current_time = current_time('timestamp');

    // Определяем, что пользователь онлайн, если последний активный запрос был в течение последних 5 минут (300 секунд)
    if ($last_activity && ($current_time - $last_activity) < 300) {
        return 'онлайн';
    } else {
        return 'офлайн';
    }
}

function save_gender_meta($user_id)
{
    if (isset($_POST['user_gender'])) {
        update_user_meta($user_id, 'gender', 'female');
    } else {
        update_user_meta($user_id, 'gender', 'male');
    }
}

function get_user_gender($user_id)
{
    $gender = get_user_meta($user_id, 'gender', true);

    if ($gender === 'male') {
        return 'мужской';
    } elseif ($gender === 'female') {
        return 'женский';
    } else {
        return 'не указан';
    }
}

function get_iti_user($user_id)
{
    $user = new stdClass();
    $user->id = $user_id;

    $userdata = get_userdata($user_id);
    $user->userdata = $userdata;

    $user->email = $userdata->user_email;
    $user->login = $userdata->user_login;
    $user->interval_days = get_interval_days(new DateTime($userdata->user_registered));
    $user->interval_info = 'сегодня';
    if ($user->interval_days) {
        $user->interval_info = $user->interval_days . ' ' . plural_form($user->interval_days) . ' назад';
    }
    $user->gender = get_user_gender($user_id);
    $user->status = get_user_status($user_id);

    do_action_ref_array('it_cabinet_get_user', [&$user]);

    return $user;
}

function get_iti_user_data($user_id)
{
    $user = get_iti_user($user_id);

    $data = array(
        'firstname' => array(
            'prop_name' => 'Имя:',
            'prop_value' => $user->firstname,
        ),
        'gender' => array(
            'prop_name' => 'Пол:',
            'prop_value' => $user->gender,
        ),
        'interval_info' => array(
            'prop_name' => 'Регистрация:',
            'prop_value' => $user->interval_info,
        ),
    );

    return apply_filters('it_cabinet_user_data', $data, $user);
}

function get_iti_list_info($data, $tag_pattern, $empty = false)
{
    $html = '';

    foreach ($data as $item_name => $item_value) {
        $no_has_empty_values = empty(array_filter($item_value, function ($value) {
            return empty($value);
        }));

        if ($no_has_empty_values) {
            $html .= vsprintf($tag_pattern, $item_value);
        }
    }

    return $html;
}

function update_user_profile($user_id)
{
    if (isset($_POST['user_gender'])) {
        update_user_meta($user_id, 'gender', 'female');
    } else {
        update_user_meta($user_id, 'gender', 'male');
    }

    do_action('iti_cabinet_update_user_profile', $user_id);
}

function iti_transliterate($text) {
    // Массив сопоставления кириллицы и латиницы
    $transliterationTable = [
        'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo',
        'Ж' => 'Zh', 'З' => 'Z', 'И' => 'I', 'Й' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M',
        'Н' => 'N', 'О' => 'O', 'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U',
        'Ф' => 'F', 'Х' => 'Kh', 'Ц' => 'Ts', 'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Shch', 'Ъ' => '',
        'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya',
        'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo',
        'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm',
        'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u',
        'ф' => 'f', 'х' => 'kh', 'ц' => 'ts', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'shch', 'ъ' => '',
        'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya'
    ];

    // Заменяем кириллические буквы на латиницу
    $text = strtr($text, $transliterationTable);

    // Заменяем все недопустимые символы на подчеркивание
    $text = preg_replace('/[^\w]+/', '_', $text);

    // Удаляем начальные и конечные подчеркивания
    $text = trim($text, '_');

    return $text;
}
