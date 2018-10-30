<?php

namespace UNI\Framework\Tasks\Controllers;

use UNI\Framework\CrudController;

class SectionsController extends CrudController
{
    protected function getModel(): string
    {
        return 'sections_model';
    }

    public function listByTurma($c, $request)
    {        
        $turma_id = $request->query->get('id');
        return $c['sections_model']->all(['turma_id' => $turma_id]);
    }

    public function havePermission($c, $request) {
        $turma_id = $request->query->get('turma_id');
        $model = $c['sections_model'];
        $user_id = $model->user_id;
        return $c['sections_model']->havePermission(['id_aluno' => $user_id, 'id_turma' => $turma_id]);
    }
}