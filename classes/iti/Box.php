<?php

namespace classes\iti;

class Box
{
    private static $instance;
    private $container;

    private function __construct()
    {
        $this->container = new Container();
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Box();
        }
        return self::$instance->container;
    }
}
