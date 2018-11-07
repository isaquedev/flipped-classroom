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
        $id = $request->query->get('id');
        return $c[$this->getModel()]->all(['id_questionnaire' => $id]);
    }

    public function create($c, $request) {
        $id = $request->query->get('id');
        $questions = $request->request->all();
        $retorno = [];
        foreach ($questions as $key => $question) {
            $question['id_questionnaire'] = $id;
            $retorno[$key] = $c[$this->getModel()]->create($question);
        }
        return $retorno;
    }

    public function update($c, $request) {
        $question = $request->request->all();
        $id = $request->query->get('id');
        $c[$this->getModel()]->delete(['id_questionnaire' => $id]);
        return $this->create($c, $request);
    }

    public function retorno($c, $request) {
        return $request->query->all();
    }

}