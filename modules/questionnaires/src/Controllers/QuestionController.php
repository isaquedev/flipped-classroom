<?php

namespace UNI\Framework\Questionnaires\Controllers;

use UNI\Framework\CrudController;

class QuestionController extends CrudController
{
    protected function getModel(): string
    {
        return 'questions_model';
    }

    public function index($c, $request) {
        $id = parent::getId($request, 'id_questionnaire');
        return $c[$this->getModel()]->all($id);
    }

    public function create($c, $request) {
        $id = $request->request->get('0');
        $questions = $request->request->get('1');
        $retorno = [];
        foreach ($questions as $key => $question) {
            $question['id_questionnaire'] = $id;
            $retorno[$key] = $c[$this->getModel()]->create($question);
        }
        return $retorno;
    }

    public function update($c, $request) {
        $id = $request->request->get('0');
        $questions = $request->request->get('1');
        $c[$this->getModel()]->delete(['id_questionnaire' => $id]);
        return $this->create($c, $request);
    }

    public function retorno($c, $request) {
        return $request->request->all();
    }

}