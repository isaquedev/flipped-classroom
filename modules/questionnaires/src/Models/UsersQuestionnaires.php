<?php

namespace UNI\Framework\Questionnaires\Models;

use UNI\Framework\Model;

class UsersQuestionnaires extends Model
{
    public function done(array $data, $table = null, $haveDate = true) {
        return parent::create($data, 'users_questionnaires', $haveDate);
    }
}