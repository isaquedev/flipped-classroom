<?php

namespace UNI\Framework\Questionnaires;

use UNI\Framework\Modules\Contract;

class QuestionnairesModule implements Contract
{
    public function getNamespaces() :array
    {
        return [
            'UNI\\Framework\\Questionnaires\\' => __DIR__ . '/src'
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