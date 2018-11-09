<?php

namespace App\Controllers;

use UNI\Framework\Exceptions\HttpException;
use Firebase\JWT\JWT;
use UNI\Framework\CrudController;

class UsersController extends CrudController
{

    protected function getModel(): string
    {
        return 'users_model';
    }

    public function all($c) {
        return $this->removeNotNecessaryData($c[$this->getModel()]->allById("<> 0"));
    }

    public function create($c, $request)
    {        
        $user = parent::create($c, $request);        
        return $this->removeNotNecessaryData($user);
    }

    public function update($c, $request) {
        $user = parent::update($c, $request);
        return $this->removeNotNecessaryData($user);
    }

    //Remove as informações desnecessárias para as requisições envolvendo dados dos usuários
    public function removeNotNecessaryData($var, $minData = false) { 
        unset($var['password'], $var['created'], $var['modified']);
        if ($minData) {
            unset($var['login'], $var['type']);
        }
        return $var;
    }

    public function getToken($c, $request)
    {
        $login = $request->request->get('login');
        $password = $request->request->get('password');

        //Caso não haja usuários cria o administrador
        if(parent::index($c, $request) == []){
            $c[$this->getModel()]->create(['name' => 'Admin', 'login' => 'admin', 'password' => 'adminpass', 'type' => 0]);
        }

        $user = $c[$this->getModel()]->getByLogin($login);
        if (!$user){
            throw new HttpException("Forbidden", 401);
        }

        if (!password_verify($password, $user['password'])){
            throw new HttpException("Forbidden", 401);
        }
        
        unset($user['password']);
        $key = 'SECRET KEY';
        $data = [
            'iat' => time(),
            'ex' => time() + (60 * 60),
            'user' => $user
        ];

        $token = JWT::encode($data, $key);
        return ['token' => $token];
    }

    public function getCurrentUser($c)
    {
        $token = $this->getallheaders()['Authorization'] ?? null;

        if (!$token) {
            $token = filter_input(\INPUT_GET, 'token');
        }

        if (!$token) {            
            throw new HttpException("Forbidden", 401);
        }

        try {
            $key = 'SECRET KEY';
            $data = JWT::decode($token, $key, ['HS256']);
        } catch(\Exception $e) {            
            throw new HttpException("Forbidden", 401);
        }

        return (array)$data;
    }

    public function getallheaders() {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }

    public function getTeachers($c, $request)
    {
        $teachers = $c[$this->getModel()]->allById("= 1");

        for ($i=0; $i < sizeof($teachers); $i++) { 
            $teachers[$i] = $this->removeNotNecessaryData($teachers[$i], true);
        }
        return $teachers;
    }

    public function getUsersTurmas($c, $request) {
        return $c[$this->getModel()]->getUsersTurmas();
    }

    public function createUsersTurmas($c, $request) {
        $student =  $c[$this->getModel()]->create($request->request->all(), 'user_schoolclass', false);
        return $this->getStudent($c, $student['id_student'], $student['id_schoolclass']);
    }

    public function deleteUsersTurmas($c, $request) {
        $ids = parent::getIds($request, ['id_schoolclass', 'id_student'], 2);
        $student =  $c[$this->getModel()]->delete($ids, 'user_schoolclass');
        return $this->getStudent($c, $ids['id_student'], $ids['id_schoolclass']);
    }

    public function getStudent($c, $student_id, $class_id) {
        $query = "SELECT name FROM users WHERE id=?";
        $bind = [$student_id];
        $request_student = $c[$this->getModel()]->customQuery($query, $bind)[0];

        return $result = [
            'class_id' => $class_id,
            'student' => [
                'id'   => $student_id,
                'name' => $request_student['name'],
            ]
        ];
    } 

}