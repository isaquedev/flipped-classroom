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
        if ($model->type != 0) {
            $conditions['id_teacher'] = $model->user_id;
        }
        return $model->all($conditions);
    }

    public function update($c, $request) {
        $id = $request->request->get('0');
        $data = $request->request->get('1');
        return $c[$this->getModel()]->update(['id' => $id], $data);
    }

    public function delete($c, $request)
    {
        //parent::deleteAnotherTable($c, ['id_schoolclasses' => $request->query->get('id')], 'users_schoolclasses');
        return parent::delete($c, $request);
    }

}