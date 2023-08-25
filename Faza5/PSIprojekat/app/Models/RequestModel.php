<?php

namespace App\Models;

use CodeIgniter\Model;


//Dusan Terzic 18/0664
//Model za tabelu Request 1.0
class RequestModel extends Model{
    protected $table = 'request';
    protected $primaryKey = 'IdReq';
    protected $returnType = 'object';
    
    protected $allowedFields = ['IdReq', 'Name',  'IdU', 'Approved', 'Description'];
    
    //dodaje novi request u bazu, vraca boolean
    public function addRequest($description, $IdU, $Name){
        return $this->insert(['Description'=>$description, 'IdU'=>$IdU, 'Name'=>$Name]);
    }
    
    //odobrava request i updatuje tabelu, vraca boolean
    public function approveRequest($IdReq){
        $obj = $this->find($IdReq);
        $data = [
            'IdU' => $obj->IdU,
            'Name' => $obj->Name,
            'Approved' =>1,
            'Description' =>$obj->Description
        ];
        return $this->update($IdReq, $data);
    }
    
    //odbija request i updatuje tabelu, vraca boolean
    public function denyRequest($IdReq){
           $obj = $this->find($IdReq);
        $data = [
            'IdU' => $obj->IdU,
            'Name' => $obj->Name,
            'Approved' =>0,
            'Description' =>$obj->Description 
        ];
        return $this->update($IdReq, $data);
    }
    
}
