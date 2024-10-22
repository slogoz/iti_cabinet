<?php

namespace classes\iti;

class Block
{
    private $args = [];
    protected $filter_defaults_args = 'block_defaults_args';

// Конструктор принимает массив аргументов и подменяет их значениями по умолчанию
    public function __construct(array $args = [])
    {
// Аргументы по умолчанию
        $defaultArgs = array_merge(self::defaultArgs(), $this->defaultArgs());
        $defaultArgs = apply_filters($this->filter_defaults_args, $defaultArgs, $args);

// Подменяем недостающие значения значениями по умолчанию
        $this->args = array_merge($defaultArgs, $args);
    }

    protected function defaultArgs()
    {
        return [
            'title' => 'Заголовок по умолчанию',
            'after_title' => '',
            'content' => 'Контент по умолчанию',
            'class' => 'panel-default',
            'template' => 'default_template.php',
            'template_path' => WP_ITI_CABINET_DIR . "/templates/blocks/",
            'id_attr' => ' id="%1$s"',
            'id' => ''
        ];
    }

// Метод для рендера блока
    public function render($return = false)
    {
        ob_start();

        $args = $this->render_id($this->args);

// Извлекаем аргументы для удобства
        extract($args);

// Подключаем шаблон, который может использовать переменные $title, $content, $class
        include "{$template_path}{$template}";

        $html = apply_filters( 'block_render_html', ob_get_clean());

        if($return) {
            return $html;
        }

        echo $html;
    }

    protected function render_id($args)
    {
        if(!empty($args['id'])) {
            $args['id_attr'] = sprintf( $args['id_attr'], $args['id']);
        } else {
            $args['id_attr'] = '';
        }

        return $args;
    }
}
