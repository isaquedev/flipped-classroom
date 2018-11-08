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
            $conditions['id_teacher'] = $model->user_id;
        } else if ($model->type == 2) {
            $classes = $model->all(['id_student' => $model->user_id], 'users_schoolclasses');
            if (sizeof($classes) > 0){
                $query = "SELECT * FROM schoolclasses WHERE ";
                foreach ($classes as $key => $s_class) {
                    $query .= "id=" . $s_class['id_schoolclasses'];
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

    public function delete($c, $request)
    {
        //parent::deleteAnotherTable($c, ['id_schoolclasses' => $request->query->get('id')], 'users_schoolclasses');
        return parent::delete($c, $request);
    }

}