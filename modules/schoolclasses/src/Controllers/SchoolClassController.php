<?php

namespace UNI\Framework\SchoolClasses\Controllers;

use UNI\Framework\CrudController;

class SchoolClassController extends CrudController
{
    protected function getModel(): string
    {
        return 'schoolclasses_model';
    }

    public function index($c, $request) {
        $conditions = [];
        $model = $c[$this->getModel()];
        if ($model->type == 1) {
            $conditions['id_teacher'] = $model->id;
        } else if ($model->type == 2) {
            $classes = $model->all(['id_student' => $model->id], 'user_schoolclass');
            if (sizeof($classes) > 0){
                $query = "SELECT * FROM schoolclasses WHERE ";
                foreach ($classes as $key => $s_class) {
                    $query .= "id=" . $s_class['id_schoolclass'];
                    if ($key < sizeof($classes) - 1){
                        $query .= " OR ";
                    }
                }
                return $model->customQuery($query);
            }
            return [];
        }
        return $model->all($conditions);
    }

}