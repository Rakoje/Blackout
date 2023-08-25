<?php
namespace App\Models;
use CodeIgniter\Model;


//Anastasija Tomic 2018/0255
//Model za tabelu GenericUser 1.0
class GenericUserModel extends Model{
    protected $table = 'genericuser';
    protected $primaryKey = 'IdU';
    protected $returnType = 'object';
    protected $allowedFields = ['IdU'];
}