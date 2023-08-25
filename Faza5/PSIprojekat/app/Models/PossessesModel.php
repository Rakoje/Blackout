<?php

namespace App\Models;
use CodeIgniter\Model;

//Andrija Rakojevic 18/0718
//Model za tabelu Possesses 1.0
class PossessesModel extends Model{
    protected $table = 'possesses';
    protected $primaryKey = 'IdU';
    protected $returnType = 'object';
    
    protected $allowedFields = ['IdI', 'Price', 'IdU'];
    
    //dohvata sastojke koje posjeduje jedan user, vraca niz
    public function getIngredientsFromUser($user){
        return $this->where('IdU', $user->IdU)->findAll();
    }
    
    //dohvata broj sastojaka datog usera, vraca int
    public function getNumOfIngredients($user){
        return $this->where('IdU', $user->IdU)->countAllResults();
    }
    
    //brise iz tabele possession sa datim podacima, vraca boolean
    public function removePossession($user, $id){
        return $this->where('IdU', $user->IdU)->where('IdI', $id)->delete();
    }
    
    //dodaje u tabelu possession u skladu sa proslijedjenim podacima, vraca 1 ili null
    public function addPossession($idu, $idi, $price = null){
        $data = ['IdU' => $idu,
                 'IdI' => $idi,
                 'Price' => $price];
        $result = $this->where('IdU', $idu)->where('IdI', $idi)->findAll();
        if(empty($result)) {
           $this->insert($data);
            return 1;
        }
        else
            return null;
    }
}

