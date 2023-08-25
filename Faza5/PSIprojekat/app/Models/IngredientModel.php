<?php
namespace App\Models;
use CodeIgniter\Model;


//Andrija Rakojevic 18/0718
//Model za tabelu Ingredient 1.0
class IngredientModel extends Model{
    protected $table = 'ingredient';
    protected $primaryKey = 'IdI';
    protected $returnType = 'object';

    protected $allowedFields = ['IdI', 'Name', 'IdReq'];

    
}

