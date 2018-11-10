<?php

namespace UNI\Framework\Schoolclasses\Models;

use UNI\Framework\Model;

class Lessons extends Model
{

    public function havePermission($conditions, $isTeacher) {
        if ($isTeacher){
            $isTeacherOfThisClass = parent::all($conditions, 'schoolclasses');
            return sizeof($isTeacherOfThisClass);
        } else {
            $user_schoolclass = parent::all($conditions, 'user_schoolclass');
            return sizeof($user_schoolclass);
        }
    }

    public function delete($conditions, $table = null) {
        $lesson = parent::get($conditions);
        $users_questionnaire_conditions = [
            'id_schoolclass' => $lesson['id_schoolclass'],
            'id_questionnaire' => $lesson['id_questionnaire'],
        ];
        $delQuest;
        if ($users_questionnaire_conditions['id_questionnaire'] != null) {
            $delQuest = parent::delete($users_questionnaire_conditions, 'users_questionnaires');
        }
        return parent::delete($conditions);
    }
}