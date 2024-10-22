<?php


function autoload_files_from_directory($directory, $pattern = '*.php')
{
    // Проверка, что это директория
    if (!is_dir($directory)) {
        return false;
    }

    // Получаем список файлов в директории
    $files = scandir($directory);

    // Перебираем все файлы и папки
    foreach ($files as $file) {
        // Пропускаем текущую и родительскую директории
        if ($file === '.' || $file === '..') {
            continue;
        }

        $fullPath = $directory . DIRECTORY_SEPARATOR . $file;

        // Если это папка, то рекурсивно заходим в нее
        if (is_dir($fullPath)) {
            autoload_files_from_directory($fullPath, $pattern);
        }

        // Проверяем, подходит ли файл под шаблон
        if (fnmatch($pattern, $file)) {
            // Подключаем файл
            require_once $fullPath;
        }
    }
}

// Пример использования:
$auto_directory = __DIR__; // Замените на свою директорию
error_log('connected:');
autoload_files_from_directory($auto_directory, 'init*.php');
