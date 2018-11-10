<?php

namespace App\Models;

use UNI\Framework\Model;

class Users extends Model
{
    public function setPassword($password)
    {
        return password_hash($password, \PASSWORD_DEFAULT);
    }

    public function getByLogin($login)
    {
        return parent::get(['login' => $login]);
    }

    public function allById($conditions) {
        $users = parent::all($conditions);

        foreach ($users as $key => $user) {
            $users[$key]['type'] = $user['type'] == 1 ? 'Professor' : 'Aluno';
        }

        return $users;
    }

    public function getUsersTurmas() {
        $query = "SELECT u.id AS student_id, u.name AS student_name, c.id AS schoolclass_id
                  FROM users u, user_schoolclass us, schoolclasses c
                  WHERE u.id = us.id_student AND us.id_schoolclass = c.id AND us.id_student <> c.id_teacher";

        $students = parent::customQuery($query);

        $turmas = parent::all([], 'schoolclasses');

        $result = [];
        foreach($turmas as $t_key => $turma) {
            $result[$t_key]['schoolclass_id'] = $turma['id'];
            $students_list = [];
            foreach ($students as $s_key => $student) {
                if($student['schoolclass_id'] == $turma['id']){
                    $students_list[sizeOf($students_list)] = [
                        'id' => $student['student_id'],
                        'name' => $student['student_name']
                    ];
                }
            }
            (array) $result[$t_key]['students_list'] = $students_list;
        }

        return $result;
    }

    public function createUsersTurmas($data) {
        $student =  parent::create($data, 'user_schoolclass', false);

        $query = "SELECT name FROM users WHERE id=?";
        $bind = [$student['id_student']];
        $request_student = parent::customQuery($query, $bind);
        
        return $request_student;
    }
}