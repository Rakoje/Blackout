<?php
namespace App\Models;
use CodeIgniter\Model;


//Andrija Rakojevic 18/0718
//Model za tabelu Store 1.0
class StoreModel extends Model{
    protected $table = 'store';
    protected $primaryKey = 'IdU';
    protected $returnType = 'object';
    
    
    protected $allowedFields = ['IdU', 'Owner', 'OpeningHours', 'ClosingHours', 'Description', 'Phone'];
    
}

