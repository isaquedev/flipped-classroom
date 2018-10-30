<?php

namespace UNI\Framework\Tasks\Models;

use UNI\Framework\Model;

class Sections extends Model
{

    public function havePermission($conditions) {
        
        $users_turmas = parent::all($conditions, 'users_turmas');
        return sizeof($users_turmas);
    }
}