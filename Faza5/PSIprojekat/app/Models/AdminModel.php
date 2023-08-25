<?php
namespace App\Models;
use CodeIgniter\Model;


//Dusan Terzic 0664/18, Andrija Rakojevic 0718/18
//Model za tabelu admin verzija 1.0
class AdminModel extends Model{
    protected $table = 'administrator';
    protected $primaryKey = 'IdU'; //int
    protected $returnType = 'object';
    
    protected $allowedFields = ['IdU', 'description'];
    //Vraca sve admine(niz)
    function getAllAdmins(){
        return $this->get();
    }

}

