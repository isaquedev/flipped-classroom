<?php

namespace UNI\Framework;

use App\Models\Users;

abstract class CrudController
{

    abstract protected function getModel(): string;

    public function index($c, $request)
    {        
        return $c[$this->getModel()]->all();
    }

    public function show($c, $request)
    {
        $id = $request->attributes->get(1);
        return $c[$this->getModel()]->get(['id' => $id]);
    }

    public function create($c, $request)
    {        
        return $c[$this->getModel()]->create($request->request->all());
    }
    
    public function update($c, $request)
    {        
        $id = $request->request->get('0');
        $data = $request->request->get('1');
        return $c[$this->getModel()]->update(['id' => $id], $data);
    }
    
    public function delete($c, $request)
    {    
        $id = $this->getId($request, 'id');
        return $c[$this->getModel()]->delete($id);
    }

    public function deleteAnotherTable($c, $conditions, $table) {        
        return $c[$this->getModel()]->delete($conditions, $table);
    }

    public function getId($request, $key){
        $url = explode("/", $request->attributes->all()[0]);
        $id = $url[sizeOf($url) - 1];
        if ($id != explode("_", $this->getModel())[0]){
            return [$key => $id];
        } else {
            return [];
        }
    }

    public function getIds($request, $key, $qtes){
        $url = explode("/", $request->attributes->all()[0]);
        $response = [];
        for ($i=0; $i < $qtes; $i++) { 
            $response[$key[$i]] = $url[sizeOf($url) - 1 - $i];
        }
        return $response;
    }

}