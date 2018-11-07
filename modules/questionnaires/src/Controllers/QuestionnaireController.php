<?php

namespace UNI\Framework\Questionnaires\Controllers;

use UNI\Framework\CrudController;

class QuestionnaireController extends CrudController
{
    protected function getModel(): string
    {
        return 'questionnaires_model';
    }

    public function create($c, $request) {
        $questionnaire =  $request->request->all();
        foreach ($questionnaire as $key => $quest) {
            if ($quest == 'true') {
                $questionnaire[$key] = 1;
            }
            else if ($quest == 'false') {
                $questionnaire[$key] = 0;
            }
        }

        return $c[$this->getModel()]->create($questionnaire);
    }

    public function index($c, $request){
        $url = explode("/", $request->attributes->all()[0]);
        $id = $url[sizeOf($url) - 1];
        if ($id != explode("_", $this->getModel()[0])){
            return $c[$this->getModel()]->all([$id]);
        } else {
            return $c[$this->getModel()]->all();
        }
    }

}