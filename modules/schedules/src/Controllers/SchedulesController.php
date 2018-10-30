<?php

namespace UNI\Framework\Schedules\Controllers;

use UNI\Framework\CrudController;

class SchedulesController extends CrudController
{
    protected function getModel(): string
    {
        return 'schedules_model';
    }
}