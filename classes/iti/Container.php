<?php

namespace classes\iti;

class Container
{
    private $services = [];
    private $instances = [];

    // Регистрация сервиса: либо класс, либо фабрика
    public function register($name, $service, $singleton = false)
    {
        $this->services[$name] = [
            'service' => $service,
            'singleton' => $singleton
        ];
    }

    // Разрешение зависимостей с поддержкой передачи дополнительных аргументов
    public function resolve($name, $arguments = [])
    {
        // Если сервис зарегистрирован как синглтон и уже создан, возвращаем его
        if (isset($this->instances[$name])) {
            return $this->instances[$name];
        }

        // Проверяем, зарегистрирован ли сервис
        if (!isset($this->services[$name])) {
            throw new \Exception("Сервис '{$name}' не найден.");
        }

        $serviceEntry = $this->services[$name];
        $service = $serviceEntry['service'];
        $singleton = $serviceEntry['singleton'];

        // Если это замыкание (фабрика), вызываем ее
        if (is_callable($service)) {
            $object = call_user_func_array($service, $arguments);
        } elseif (is_string($service) && class_exists($service)) {
            // Если это класс, создаем его с использованием ReflectionClass
            $object = $this->build($service, $arguments);
        } else {
            $object = $service; // В случае, если передано уже существующее значение
        }

        // Если сервис зарегистрирован как синглтон, сохраняем его
        if ($singleton) {
            $this->instances[$name] = $object;
        }

        return $object;
    }

    // Создание объекта с помощью Reflection и автоматическое разрешение зависимостей
    private function build($className, $arguments = [])
    {
        $reflection = new \ReflectionClass($className);

        // Если у класса нет конструктора, просто создаем новый экземпляр
        if (!$constructor = $reflection->getConstructor()) {
            return new $className();
        }

        // Получаем параметры конструктора
        $params = $constructor->getParameters();
        $dependencies = [];

        foreach ($params as $param) {
            $paramType = $param->getType(); // Используем getType() вместо getClass()

            if ($paramType && !$paramType->isBuiltin()) {
                // Разрешаем зависимость по типу класса (если это не встроенный тип)
                $dependencies[] = $this->resolve(strtolower($paramType->getName()));
            } elseif (isset($arguments[$param->getName()])) {
                // Если зависимость не класс, но передана в аргументах
                $dependencies[] = $arguments[$param->getName()];
            } elseif ($param->isDefaultValueAvailable()) {
                // Если есть значение по умолчанию
                $dependencies[] = $param->getDefaultValue();
            } else {
                throw new \Exception("Не удается разрешить зависимость для параметра {$param->getName()}.");
            }
        }

        // Возвращаем новый экземпляр класса с инъекцией зависимостей
        return $reflection->newInstanceArgs($dependencies);
    }
}