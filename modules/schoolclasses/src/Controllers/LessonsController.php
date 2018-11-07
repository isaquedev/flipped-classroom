<?php

namespace UNI\Framework\SchoolClasses\Controllers;

use UNI\Framework\CrudController;

class LessonsController extends CrudController
{
    protected function getModel(): string
    {
        return 'lessons_model';
    }

    public function listByTurma($c, $request)
    {        
        $id_schoolclasses = $request->query->get('id');
        return $c[$this->getModel()]->all(['id_schoolclasses' => $id_schoolclasses]);
    }

    public function havePermission($c, $request) {
        $id_schoolclasses = $request->query->get('id_schoolclasses');
        $model = $c[$this->getModel()];
        $user_id = $model->user_id;
        return $c[$this->getModel()]->havePermission(['id_student' => $user_id, 'id_schoolclasses' => $id_schoolclasses]);
    }
}