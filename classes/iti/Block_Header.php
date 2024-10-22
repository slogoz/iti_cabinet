<?php

namespace classes\iti;

class Block_Header extends Block
{
    protected $filter_defaults_args = 'header_defaults_args';

    protected function defaultArgs()
    {
        return [
            'title' => '#Заголовок',
            'tag' => 'h2',
            'class' => 'header',
            'template' => 'header.php',
        ];
    }
}