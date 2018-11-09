<?php

namespace UNI\Framework\Questionnaires\Models;

use UNI\Framework\Model;

class UsersQuestionnaires extends Model
{
    public function get($data){
        $query = "SELECT * FROM users_questionnaires WHERE id_schoolclass = ? AND id_student = ?";

        $bind = [];
        $bind[0] = $data['id_schoolclass'];
        $bind[1] = $data['id_student'];

        return parent::customQuery($query, $bind);
    }

    public function done(array $data, $table = null, $haveDate = true) {
        return parent::create($data, 'users_questionnaires', $haveDate);
    }
}