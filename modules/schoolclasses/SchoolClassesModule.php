<?php

namespace UNI\Framework\SchoolClasses;

use UNI\Framework\Modules\Contract;

class SchoolClassesModule implements Contract
{
    public function getNamespaces() :array
    {
        return [
            'UNI\\Framework\\SchoolClasses\\' => __DIR__ . '/src'
        ];
    }
    public function getContainerConfig() :string
    {
        return __DIR__ . '/config/containers.php';
    }
    public function getEventConfig() :string
    {
        return __DIR__ . '/config/events.php';
    }
    public function getMiddlewareConfig() :string
    {
        return __DIR__ . '/config/middlewares.php';
    }
    public function getRouteConfig() :string
    {
        return __DIR__ . '/config/routes.php';
    }
}