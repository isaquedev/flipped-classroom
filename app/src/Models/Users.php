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

    public function getUsers() {
        $query = "SELECT * FROM users WHERE user_type <> 0";

        $users = parent::customQuery($query);

        foreach ($users as $key => $user) {
            $users[$key]['user_type'] = $user['user_type'] == 1 ? 'Professor' : 'Aluno';
        }

        return $users;
    }

    public function getUsersTurmas() {        
        $query = "SELECT u.id AS student_id, u.name AS student_name, t.id AS turma_id
                  FROM users u, users_turmas ut, projects t
                  WHERE u.id = ut.id_aluno AND ut.id_turma = t.id AND ut.id_aluno <> t.id_professor";

        $students = parent::customQuery($query);

        $turmas = parent::all([], 'projects');

        $result = [];

        foreach($turmas as $t_key => $turma) {
            $result[$t_key]['turma_id'] = $turma['id'];
            $result[$t_key]['turma_name'] = $turma['title'];
            $students_list = [];
            foreach ($students as $s_key => $student) {
                if($student['turma_id'] == $turma['id']){
                    $students_list[$s_key]['student_id'] = $student['student_id'];
                    $students_list[$s_key]['student_name'] = $student['student_name'];
                }
            }
            $result[$t_key]['stundents_list'] = $students_list;
        }

        return $result;
    }

    /* 
    SELECT
    u.id AS student_id, u.name AS student_name, t.id AS turma_id
FROM
    users u, users_turmas ut, projects t
WHERE
    u.id = ut.id_aluno AND ut.id_turma = t.id AND ut.id_aluno <> t.id_professor;
    */
}