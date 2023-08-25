<?php

namespace App\Models;
use CodeIgniter\Model;


//Dusan Terzic 0664/18 model za tabelu HasRated
//verzija 1.0
class HasRatedModel extends Model{
    protected $table = 'hasrated';
    protected $primaryKey = 'IdR, IdU';
    protected $returnType = 'object';
    
    protected $allowedFields = ['IdU', 'Rating' , 'IdR'];
}
