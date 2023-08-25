<?php
namespace App\Models;
use CodeIgniter\Model;


//Andrija Rakojevic 0718/2018 
//Model za tabelu Recipe 1.0
class RecipeModel extends Model{
    protected $table = 'recipe';
    protected $primaryKey = 'IdR';
    protected $returnType = 'object';
    
    protected $allowedFields = ['name', 'description', 'numofservings', 
                                'preptime', 'strength', 'Rating', 'method', 'creationdate', 'IdU', 'NumberOfRatings'];

    //dohvata sve recepte datog korisnika i vraca niz
       public function getAllRecipesFromUser($user){
       return $this->where('IdU', $user->IdU)->findAll();
   }
}

