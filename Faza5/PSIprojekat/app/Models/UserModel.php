<?php
namespace App\Models;
use CodeIgniter\Model;


//Anastasija Tomic 18/0255
//Model za tabelu User 1.0
class UserModel extends Model{
    protected $table = 'usertemplate';
    protected $primaryKey = 'IdU';
    protected $returnType = 'object';
    protected $allowedFields = ['IdU', 'username', 'pass', 'name', 'surname', 'e_mail', 'address'];
    
    //promjena lozinke, vraca da li je uspjesan update na tabelu(boolean)
    public function changePassword($user, $password){
       return $this->set('pass', $password)->where('IdU', $user->IdU)->update();
    }
    
    //dohvata username onoga ko je napravio recept, vraca username(string)
    public function getUsernameOfBartender($recipe){
        return $this->getWhere(['IdU' => $recipe->IdU])->getRow(0)->Username;
    }
    
}
