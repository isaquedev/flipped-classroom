<?php

namespace UNI\Framework\Schoolclasses\Models;

use UNI\Framework\Model;

class Lessons extends Model
{

    public function havePermission($conditions) {
        
        $users_turmas = parent::all($conditions, 'users_turmas');
        return sizeof($users_turmas);
    }
}