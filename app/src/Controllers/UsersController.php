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

    public function getUsers($c) {
        return $this->removeNotNecessaryData($c[$this->getModel()]->getUsers());
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

    //Método criado para remover informações desnecessárias para as requisições
    //aumentando a segurança dos dados dos usuários
    //minData = true    -> remove login,    password, user_type,    created e modified
    //minData = false   -> remove           password,               created e modified 
    public function removeNotNecessaryData($var, $minData = false) { 
        unset($var['password'], $var['created'], $var['modified']);
        if ($minData) {
            unset($var['login'], $var['user_type']);
        }
        return $var;
    }

    public function getToken($c, $request)
    {
        $login = $request->request->get('login');
        $password = $request->request->get('password');

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
        $token = getallheaders()['Authorization'] ?? null;

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

    public function getTeachers($c, $request)
    {
        $teachers = $c[$this->getModel()]->all(['user_type' => 1]);
        for ($i=0; $i < sizeof($teachers); $i++) { 
            $teachers[$i] = $this->removeNotNecessaryData($teachers[$i], true);
        }        
        return $teachers;
    }

    public function getUsersTurmas($c, $request) {
        return $c[$this->getModel()]->getUsersTurmas();
    }

    public function createUsersTurmas($c, $request) {
        //return $c[$this->getModel()]->create($request->request->all());
        return $request->request->all();
    }

}