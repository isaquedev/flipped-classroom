<?php

namespace UNI\Framework\Tasks\Controllers;

use UNI\Framework\CrudController;

class TasksController extends CrudController
{
    protected function getModel(): string
    {
        return 'tasks_model';
    }

    public function listByProject($c, $request)
    {
        $id = $request->query->get('id');
        return $c['tasks_model']->getByProjectId($id);
    }
}