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
        $id = parent::getId($request, 'id_schoolclasses');
        return $c[$this->getModel()]->all($id);
    }

    public function havePermission($c, $request) {
        $user_type  = $c[$this->getModel()]->type;
        return $user_type == 1 ? $this->teacherHavePermission($c, $request) : $this->studentHavePermission($c, $request);
    }

    public function teacherHavePermission($c, $request) {
        $id = parent::getId($request, 'id');
        $id['id_teacher'] = $c[$this->getModel()]->user_id;
        return $c[$this->getModel()]->havePermission($id, true);
    }
    
    public function studentHavePermission($c, $request) {
        $id = parent::getId($request, 'id_schoolclasses');
        $id['id_student'] = $c[$this->getModel()]->user_id;
        return $c[$this->getModel()]->havePermission($id, false);
    }
}