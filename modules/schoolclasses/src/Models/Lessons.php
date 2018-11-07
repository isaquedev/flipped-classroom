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
            $users_schoolclasses = parent::all($conditions, 'users_schoolclasses');
            return sizeof($users_schoolclasses);
        }
    }
}