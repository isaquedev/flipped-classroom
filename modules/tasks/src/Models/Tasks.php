<?php

namespace UNI\Framework\Tasks\Models;

use UNI\Framework\Model;

class Tasks extends Model
{

    public function getByProjectId($id)
    {
        $sql = 'SELECT tasks.* FROM tasks
            LEFT JOIN sections ON tasks.section_id = sections.id
            WHERE sections.project_id=? and tasks.user_id=?';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id, $this->user_id]);
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    
}