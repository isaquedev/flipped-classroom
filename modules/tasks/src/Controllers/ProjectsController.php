<?php

namespace UNI\Framework\Tasks\Controllers;

use UNI\Framework\CrudController;

class ProjectsController extends CrudController
{
    protected function getModel(): string
    {
        return 'projects_model';
    }

    public function create($c, $request)
    {
        return $c[$this->getModel()]->create($request->request->all());
    }

    public function index($c, $request) {
        $conditions = [];
        $model = $c[$this->getModel()];
        if ($model->user_type != 0) {
            $conditions['id_professor'] = $model->user_id;
        }
        return $model->all($conditions);
    }

    public function delete($c, $request)
    {          
        parent::deleteAnotherTable($c, ['id_turma' => $request->query->get('id')], 'users_turmas');
        return parent::delete($c, $request);
    }

}