<?php namespace App\Controllers;
use App\Models\UserModel;
use App\Models\RecipeModel;
use App\Models\GenericUserModel;
use App\Models\PossessesModel;
use App\Models\IngredientModel;
use App\Models\RequestModel;
use App\Models\MessageModel;
use App\Models\AdminModel;
use App\Models\StoreModel;
use App\Models\IsMadeOfModel;
//Dusan Terzic 2018/0718
//Kontroler za funckionalnosti admina 1.0
class Admin extends User{
    //funckija za prikaz stranica admina
    protected function show($page, $data){
        $data['controller'] = 'Admin';
        $data['user']=$this->session->get('user');
        $data['category']=$this->session->get('category');
        echo view ('template/toolbarUser', $data);
        echo view ("pages/$page", $data);
        echo view ('template/footer');
        
    }
    //funckija za odobravanje zahtjeva
    function approveRequest(){
        $idRequest = $this->request->getVar('idReq');
        $requestModel = new RequestModel();
        $approvedUserId = $requestModel->find($idRequest)->IdU;
        $adminModel = new AdminModel();
        $isAdmin = $adminModel->find($approvedUserId);
        $storeModel = new StoreModel();
        $isStore = $storeModel->find($approvedUserId);
        $ingredientModel = new IngredientModel();
        $ingredient = $ingredientModel->getWhere(['IdReq'=> $idRequest])->getRow(0);
        if(isset($isAdmin)){
            $this->approveAdminRequest($idRequest);
            return;
        }
        if(isset($isStore)){
            $this->approveStoreRequest($idRequest);
            return;
        }
        if(isset($ingredient)){
            $this->approveIngredientRequest($ingredient, $idRequest);
            return;
        }
        
        
    }
    //funkcija za odbijanje zahtjeva
    function denyRequest(){
        $idRequest = $this->request->getVar('idReq');
        $requestModel = new RequestModel();
        $approvedUserId = $requestModel->find($idRequest)->IdU;
        $adminModel = new AdminModel();
        $isAdmin = $adminModel->find($approvedUserId);
        $storeModel = new StoreModel();
        $isStore = $storeModel->find($approvedUserId);
        $ingredientModel = new IngredientModel();
        $ingredient = $ingredientModel->getWhere(['IdReq'=> $idRequest])->getRow(0);
        if(isset($isAdmin)){
            $this->denyAdminRequest($idRequest);
            return;
        }
        if(isset($isStore)){
            $this->denyStoreRequest($idRequest);
            return;
        }
        if(isset($ingredient)){
            $this->denyIngredientRequest($ingredient, $idRequest);
            return;
        }


        
    }
    //funckija za odbijanje sastojka kreiranim putem zahtjeva
    function denyIngredientRequest($ingredient, $idRequest){
        $messageModel = new MessageModel();
        $thisRequestMessages = $messageModel->where('IdReq', $idRequest)->findAll();
        $myUsername = $this->session->get('user')->Username;
        foreach($thisRequestMessages as $message){
             $data = [
                'IdSender' =>$message->IdSender,
                'IdReciever' =>$message->IdReciever,
                'IdReq' =>null,
                'CreationDate' =>$message->CreationDate,
                'Content' => "This request for a new ingredient for $ingredient->Name has already been denied by $myUsername"];
             $messageModel->update($message->IdM, $data);
        }
        $isMadeOfModel = new IsMadeOfModel();
        $recipeList = $isMadeOfModel->where('IdI', $ingredient->IdI)->findAll(); //lista svih recepata sa ovim sastojkom
        $recipeModel = new RecipeModel();
        foreach($recipeList as $recipe){
            $IdU = $recipeModel->find($recipe->IdR)->IdU;
            $messageModel = new MessageModel();
            $IdReciever = $IdU;
            $IdSender = $this->session->get('user')->IdU;
            $r = $recipeModel->getWhere(['IdR' =>  $recipe->IdR])->getRow(0); 
            $data = [
                "IdSender"=>$IdSender,
                 "IdReq" => null,
                "Content" => 'Your recipe'.$r->Name.'has been deleted, because ingredient'.$ingredient->Name.' has been denied',
                "IdReciever" =>$IdReciever
               ];
           $messageModel->insert($data); //salje se poruka svima kojima je izbrisan recept
            $recipeModel->where('IdR', $recipe->IdR)->delete();
            $isMadeOfModel->where('IdR', $recipe->IdR)->delete();
            
        }
        unlink("assets/$ingredient->Name.png");
        $ingredientModel = new IngredientModel();
        $ingredientModel->where("IdI", $ingredient->IdI)->delete();
        $requestModel = new requestModel();
        $requestModel->where("IdReq", $idRequest)->delete();
    }
    //funkcija za odbijanje zahtjeva za profil nove prodavnice
     function denyStoreRequest($idRequest){
        //treba da se korisnik izbrise iz kolone UserModel
        //svim adminima se mijenja poruka
        //pri pokusaju logovanja ovog usera treba da se ispise posebna poruka : admin request denied
         $requestModel = new RequestModel();
         $deniedUser = $requestModel->find($idRequest);
         $deniedUserId = $deniedUser->IdU;
         $userModel = new UserModel();
         $deniedUsername = $userModel->find($deniedUserId)->Username;
         $requestModel->denyRequest($idRequest);
         $storeModel = new StoreModel();
         $storeModel->where('IdU', $deniedUserId)->delete();
         $myUsername = $this->session->get('user')->Username ;
         $messageModel = new MessageModel();
         $thisRequestMessages = $messageModel->where('IdReq', $idRequest)->findAll();
         foreach($thisRequestMessages as $message){
             $data = [
                'IdSender' =>$message->IdSender,
                'IdReciever' =>$message->IdReciever,
                'IdReq' =>null,
                'CreationDate' =>$message->CreationDate,
                'Content' => "This request for a new store for $deniedUsername has already been denied by $myUsername"];
             $messageModel->update($message->IdM, $data);
        }
     }
     //funckija za odbijanje zahtjeva za novog admina
    function denyAdminRequest($idRequest){
        //treba da se korisnik izbrise iz kolone UserModel
        //svim adminima se mijenja poruka
        //pri pokusaju logovanja ovog usera treba da se ispise posebna poruka : admin request denied
        $requestModel = new RequestModel();
        $deniedUser = $requestModel->find($idRequest);
        $deniedUserId = $deniedUser->IdU;
        $userModel = new UserModel();
        $deniedUsername = $userModel->find($deniedUserId)->Username;
        $requestModel->denyRequest($idRequest);
        $adminModel = new AdminModel();
        $adminModel->where('IdU', $deniedUserId)->delete();
         $myUsername = $this->session->get('user')->Username ;
         $messageModel = new MessageModel();
         $thisRequestMessages = $messageModel->where('IdReq', $idRequest)->findAll();
        foreach($thisRequestMessages as $message){
             $data = [
                'IdSender' =>$message->IdSender,
                'IdReciever' =>$message->IdReciever,
                'IdReq' =>null,
                'CreationDate' =>$message->CreationDate,
                'Content' => "This request for a new admin for $deniedUsername has already been denied by $myUsername"];
             $messageModel->update($message->IdM, $data);
        }
    }
    //funckija za odobravanje zahtjeva za novi profil prodavnice
     function approveStoreRequest($idRequest){
        $requestModel = new RequestModel();
        $approvedUserRow = $requestModel->find($idRequest);
        $approvedUserId = $approvedUserRow->IdU;
        $userModel = new UserModel();
        $approvedUser = $userModel->find($approvedUserId)->Username;
        $requestModel->approveRequest($idRequest);
        
        $idU = $requestModel->find($idRequest)->IdU;
        $messageModel = new MessageModel();
        $thisRequestMessages = $messageModel->where('IdReq', $idRequest)->findAll();
        $myUsername = $this->session->get('user')->Username ;
        foreach($thisRequestMessages as $message){
             $data = [
                'IdSender' =>$message->IdSender,
                'IdReciever' =>$message->IdReciever,
                'IdReq' =>null,
                'CreationDate' =>$message->CreationDate,
                'Content' => "This request for a new store for $approvedUser has already been approved by $myUsername"];
             $messageModel->update($message->IdM, $data);
        }
        $data = [
                'IdSender' =>$this->session->get('user')->IdU,
                'IdReciever' =>$approvedUserRow->IdU,
                'IdReq' =>null,
                'Content' => "Welcome to Blackout $approvedUser"];
        $messageModel->insert($data);
    }
    //funckija za odobravanje novog admina
    function approveAdminRequest($idRequest){
        $requestModel = new RequestModel();
        $approvedUserRow = $requestModel->find($idRequest);
        $approvedUserId = $approvedUserRow->IdU;
        $userModel = new UserModel();
        $approvedUser = $userModel->find($approvedUserId)->Username;
        $requestModel->approveRequest($idRequest);
        
        $idU = $requestModel->find($idRequest)->IdU;
        $messageModel = new MessageModel();
        $thisRequestMessages = $messageModel->where('IdReq', $idRequest)->findAll();
        $myUsername = $this->session->get('user')->Username ;
        foreach($thisRequestMessages as $message){
             $data = [
                'IdSender' =>$message->IdSender,
                'IdReciever' =>$message->IdReciever,
                'IdReq' =>null,
                'CreationDate' =>$message->CreationDate,
                'Content' => "This request for a new admin for $approvedUser has already been approved by $myUsername"];
             $messageModel->update($message->IdM, $data);
        }
        $data = [
                'IdSender' =>$this->session->get('user')->IdU,
                'IdReciever' =>$approvedUserRow->IdU,
                'IdReq' =>null,
                'Content' => "Welcome to our admin team $approvedUser"];
        $messageModel->insert($data);
    }
    //funckija za odobravanje novog sastojka
    function approveIngredientRequest($ingredient, $idRequest){
        $requestModel = new RequestModel();
        $requestModel->approveRequest($idRequest);
        $messageModel = new MessageModel();
        $thisRequestMessages = $messageModel->where('IdReq', $idRequest)->findAll();
        $myUsername = $this->session->get('user')->Username ;
        foreach($thisRequestMessages as $message){
             $data = [
                'IdSender' =>$message->IdSender,
                'IdReciever' =>$message->IdReciever,
                'IdReq' =>null,
                'CreationDate' =>$message->CreationDate,
                'Content' => "This request for a new ingredient $ingredient->Name has already been approved by $myUsername"];
             $messageModel->update($message->IdM, $data);
        }
        $data = [
                'IdSender' =>$this->session->get('user')->IdU,
                'IdReciever' =>$requestModel->find($idRequest)->IdU,
                'IdReq' =>null,
                'Content' => "Your ingredient $ingredient->Name has been approved"];
        $messageModel->insert($data);
    }
}

