<?php

namespace UNI\Framework\Questionnaires\Controllers;

use UNI\Framework\CrudController;

class UsersQuestionnairesController extends CrudController
{
    protected function getModel(): string
    {
        return 'users_questionnaires_model';
    }

    public function getByLesson($c, $request){
        $data = parent::getIds($request, ['id_lesson', 'id_schoolclass'], 2);
        $lesson_id = $data['id_lesson'];
        unset($data['id_lesson']);
        $lesson = $c[$this->getModel()]->get(['id' => $lesson_id], 'lessons');
        $data['id_questionnaire'] = $lesson['id_questionnaire'];
        
        $questionnairesResult = $c[$this->getModel()]->all($data, 'users_questionnaires');

        foreach ($questionnairesResult as $key => $quest) {
            $questionnairesResult[$key] = $this->unMaskUsersQuestionnaires($quest);
        }

        return $questionnairesResult;
    }

    public function get($c, $request){
        $data = parent::getId($request, 'id_schoolclass');
        $data['id_student'] = $c[$this->getModel()]->id;
        $questionnaires = $c[$this->getModel()]->all($data, 'users_questionnaires');
        foreach ($questionnaires as $key => $quest) {
            $questionnaires[$key] = $this->unMaskUsersQuestionnaires($quest);
        }
        return $questionnaires;
    }

    public function create($c, $request){
        $data = $this->maskUsersQuestionaires($c, $request);
        $users_questionnaires = $c[$this->getModel()]->done($data, null, false);
        return $this->unMaskUsersQuestionnaires($users_questionnaires);
    }

    public function maskUsersQuestionaires($c, $request){
        $answers_order =  $request->request->get(3);
        $answers_orderImploded = "";
        for ($i=0; $i < sizeOf($answers_order); $i++) { 
            $answers_orderImploded .= implode(",", $answers_order[$i]);
            if ($i < sizeOf($answers_order) - 1)
                $answers_orderImploded .= "-";
        }
        $data = [
            'id_student' => $c[$this->getModel()]->id,
            'id_questionnaire' => $request->request->get(0),
            'id_schoolclass' => $request->request->get(1),
            'questions_order' => implode(",", $request->request->get(2)),
            'answers_order' => $answers_orderImploded,
            'student_answers_order' => implode(",", $request->request->get(4)),
        ];
        return $data;
    }

    public function unMaskUsersQuestionnaires($data){
        $data['questions_order'] = explode(",", $data['questions_order']);
        
        $answers_order = explode("-", $data['answers_order']);
        $answers_orderExploded = [];
        
        for ($i=0; $i < sizeOf($answers_order); $i++) { 
            $answers_orderExploded[$i] = explode(",", $answers_order[$i]);
        }
        $data['answers_order'] = $answers_orderExploded;
        $data['student_answers_order'] = explode(",", $data['student_answers_order']);
        return $data;
    }
}