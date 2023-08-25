<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;
use CodeIgniter\Model;

//Dusan Terzic 0664/2018 
//Model za tabelu Comment, verzija 1.0
class CommentModel extends Model{
    protected $table = 'comment';
    protected $primaryKey = 'IdC';
    protected $returnType = 'object';
    
    protected $allowedFields = ['IdC', 'Content', 'IdU', 'IdR', 'CreationDate'];
    
    
    
}
