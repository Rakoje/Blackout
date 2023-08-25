<?php
namespace App\Models;
use CodeIgniter\Model;

//Andrija Rakojevic 0718/2018
//Model za tabelu Bartender
class BartenderModel extends Model{
    protected $table = 'bartender';
    protected $primaryKey = 'IdU';
    protected $returnType = 'object';
    
    protected $allowedFields = ['IdU'];
    
}

