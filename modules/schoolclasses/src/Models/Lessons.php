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
}