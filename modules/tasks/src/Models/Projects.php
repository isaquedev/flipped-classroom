<?php

namespace UNI\Framework\Tasks\Models;

use UNI\Framework\Model;

class Projects extends Model
{

    /*
    public function create(array $data, $table = null, $dateTime = true) {

        $usersTurmas = [
            'id_aluno' => $data['id_professor'],
            'id_turma' => "",
        ];

        $data['created'] = date('Y-m-d G:i:s');
        $data['modified'] = date('Y-m-d G:i:s');
        $data = $this->setData($data);
        $query = $this->queryBuilder->insert($this->table, $data)
        ->getData();

        $stmt = $this->db->prepare($query->sql);
        $stmt->execute($query->bind);

        $result = $this->get(['id'=>$this->db->lastInsertId()]);

        $usersTurmas = $this->setData($usersTurmas);

        $usersTurmas['id_turma'] = $result['id'];

        $query = $this->queryBuilder->insert('users_turmas', $usersTurmas)
        ->getData();

        $stmt = $this->db->prepare($query->sql);
        $stmt->execute($query->bind);

        return $result;
    }*/

    public function create(array $data, $table = null) {
        return parent::create($data);
    }
}