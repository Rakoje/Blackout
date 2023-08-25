<?php

namespace App\Models;
use CodeIgniter\Model;

//Dusan Terzic 18/0664 
//Model za tabelu Message 1.0
class MessageModel extends Model{
    protected $table = 'message';
    protected $primaryKey = 'IdM';
    protected $returnType = 'object';
    
    protected $allowedFields = ['IdM', 'IdSender', 'IdReciever', 'Content', 'CreationDate', 'IdReq'];
    
    //dohvata poruke usera, vraca niz
    public function getMessagesFromUser($user){
        $where = "IdReciever = $user->IdU OR IdSender =$user->IdU ";
        return $this->where($where)->findAll(); //vraca sve poruke poslate ili primljene od strane korisnika
    }
    
    //dohvata sve poslate poruke usera, vraca niz
    public function getSent($user){
        return $this->where('IdSender',$user->IdU);
    }
    
    //dohvata sve primljene poruke usera, vraca niz
    public function getReceived($user){
        return $this->where('IdReciever', $user->IdU);
    }
    
    //dohvata poruke izmedju 2 usera koje je poslao user me, vraca niz
    public function getSentBetween2($me, $otherUser){ //kad se gledaju poruke izmedju dvoje ljudi, vrati one koje sam ja poslao
        return $this->where('IdSender', $me->IdU)->where('IdReciever', $otherUser->IdU);
    }
    
    //dohvtata primljene poruke izmedju 2 usera koje je primio user me, vraca niz
    public function getReceivedBetween2($me, $otherUser){ //kad se gledaju poruke izmedju dvoje ljudi, vrati one koje sam ja poslao
        return $this->where('IdSender', $otherUser->IdU)->where('IdReciever', $me->IdU);
    }
}

