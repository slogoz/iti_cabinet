<?php

namespace classes\iti;

class Block_Message extends Block
{
    protected $filter_defaults_args = 'message_defaults_args';

    protected function defaultArgs()
    {
        return [
            'class' => 'message-default',
            'template' => 'message.php',
        ];
    }
}