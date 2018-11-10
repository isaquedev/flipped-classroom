<?php

namespace UNI\Framework;

use Pimple\Container;
use UNI\Framework\QueryBuilder;

abstract class Model
{
    protected $db;
    protected $events;
    protected $queryBuilder;
    protected $table;

    public function __construct(Container $container)
    {
        $this->db = $container['db'];
        $this->events = $container['events'];
        $this->queryBuilder = new QueryBuilder;

        if(!$this->table) {
            $table = explode('\\', \get_called_class());
            $table = array_pop($table);
            $this->table = \strtolower($table);
        }
    }

    public function response($data) {
        return $data;
    }

    public function get(array $conditions, $table = null)
    {
        if($table == null){
            $table = $this->table;
        }

        $query = $this->queryBuilder->select($table)
            ->where($conditions)
            ->getData();

        $stmt = $this->db->prepare($query->sql);
        $stmt->execute($query->bind);

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function customQuery($query, $bind = []) {
        $stmt = $this->db->prepare($query);
        if (sizeof($bind) > 0) {
            foreach ($bind as $key => &$val) {
                $stmt->bindParam($key + 1, $val);
            }
        }
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function all(array $conditions = [], $table = null)
    {
        if ($table == null)
            $table = $this->table;
            
        $query = $this->queryBuilder->select($table)
            ->where($conditions)
            ->getData();

        $stmt = $this->db->prepare($query->sql);
        $stmt->execute($query->bind);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function create(array $data, $table = null, $haveDate = true)
    {        
        if ($table == null)
            $table = $this->table;

        $this->events->trigger('creating.' . $table, null, $data);

        if ($haveDate) {
            $data['created'] = date('Y-m-d G:i:s');
            $data['modified'] = date('Y-m-d G:i:s');
        }
        
        $data = $this->setData($data);

        $query = $this->queryBuilder->insert($table, $data)
            ->getData();

        $stmt = $this->db->prepare($query->sql);
        $stmt->execute($query->bind);

        if($haveDate){
            $data = $this->get(['id'=>$this->db->lastInsertId()]);
        }

        $this->events->trigger('created.' . $table, null, $data);

        return $data;
    }

    public function update(array $conditions, array $data)
    {
        $this->events->trigger('updating.' . $this->table, null, $data);
        $data['modified'] = date('Y-m-d G:i:s');
        $data = $this->setData($data);

        $query = $this->queryBuilder->update($this->table, $data)
            ->where($conditions)
            ->getData();

        $stmt = $this->db->prepare($query->sql);
        $stmt->execute(array_values($query->bind));

        $result = $this->get($conditions);

        $this->events->trigger('updated.' . $this->table, null, $result);

        return $result;
    }

    public function delete($conditions, $table = null)
    {
        if ($table == null)
            $table = $this->table;
        $result = $this->setData($conditions);
        
        $this->events->trigger('deleting.' . $table, null, $result);

        $query = $this->queryBuilder->delete($table)
            ->where($conditions)
            ->getData();

        $stmt = $this->db->prepare($query->sql);
        $stmt->execute($query->bind);

        $this->events->trigger('deleted.' . $table, null, $result);
        
        return $result;
    }

    protected function setData($data)
    {
        foreach ($data as $field => $value) {
            $method = str_replace('_', '', $field);
            $method = ucwords($method);
            $method = str_replace(' ', '', $method);
            $method = "set{$method}";
            if (method_exists($this, $method)) {
                $data[$field] = $this->$method($value);
            }        
        }

        return $data;
    }
}