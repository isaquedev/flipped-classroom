<?php

namespace UNI\Framework\Questionnaires\Models;

use UNI\Framework\Model;

class Questionnaires extends Model
{
    public function all(array $conditions = [], $table = null) {
        $query = "SELECT * FROM questionnaires WHERE id_teacher=? OR is_public=1";
        $questionnaires = parent::customQuery($query, $conditions);
        
        $teachers = $this->getTeachers($conditions);

        //Mask questionnaires to send masked to front
        foreach ($questionnaires as $key => $questionnaire) {
            $questionnaires[$key] = $this->maskQuestionnaire($questionnaire, $teachers);
        }

        return $questionnaires;
    }

    public function create(array $data, $table = null, $haveDate = true) {
        $data['id_teacher'] = $this->id;
        $questionnaire = parent::create($data, $table, $haveDate);
        $teachers = $this->getTeachers([$questionnaire['id_teacher']]);
        return $this->maskQuestionnaire($questionnaire, $teachers);
    }

    public function update(array $conditions, array $data){
        $teachers = $this->getTeachers([$data['id_teacher']]);
        $oldData = $data;
        $data = $this->maskQuestionnaire($data, $teachers, false);
        $questionnaire = parent::update($conditions, $data);
        return $this->maskQuestionnaire($questionnaire, $teachers);
    }

    public function getTeachers($id = []) {
        $query = "SELECT id_teacher FROM questionnaires WHERE id_teacher=? OR is_public=1";
        $teachers_id = parent::customQuery($query, $id);

        //Pega os nomes dos professores donos dos questionários
        if (sizeof($teachers_id) > 0){
            $query = "SELECT id, name FROM users WHERE ";
            foreach ($teachers_id as $key => $teacher) {
                $query .= "id=" . $teacher['id_teacher'];
                if ($key < sizeof($teachers_id ) - 1){
                    $query .= " OR ";
                }
            }
        }
        return sizeof($teachers_id) > 0 ? parent::customQuery($query) : [];
    }

    public function maskQuestionnaire($questionnaire, $teachers, $toMask = true){
        $questionnaire['is_public']        = $this->maskBoolean($questionnaire['is_public'], $toMask);
        $questionnaire['random_questions']   = $this->maskBoolean($questionnaire['random_questions'], $toMask);
        foreach ($teachers as $teacher) {
            if ($questionnaire['id_teacher'] == $teacher['id']){
                if ($toMask){
                    $questionnaire['id_teacher'] = $teacher['id'] . " - " . $teacher['name'];
                }
            }
        }
        if(!$toMask){
            $questionnaire['id_teacher'] = explode(" ", $questionnaire['id_teacher'])[0];
        }
        return $questionnaire;
    }

    public function maskBoolean($value, $toMask) {
        if ($toMask){
            if($value == 0) {
                return "não";
            }
            return "sim";
        } else {
            if($value == "false") {
                return 0;
            }
            return 1;
        }
    }

}