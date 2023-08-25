<?php namespace App\Controllers;

use App\Models\UserModel;
use App\Models\RecipeModel;
use App\Models\PossessesModel;
use App\Models\IngredientModel;
use App\Models\IsMadeOfModel;
use App\Models\MessageModel;
use App\Models\CommentModel;
use App\Models\HasRatedModel;
use App\Models\StoreModel;
use DateTime;


//Dusan Terzic 0664/18
//Klasa chat koja je pomocna klasa za funkcionalnosti poruka  verzija 1.0

class Chat {
    //Tipa je array- ima sva polja koja i tabela user iz baze
    public $user;
    
    //niz poruka
    public $messages = array();
    
    //vrijeme poslednje poslate poruke- DateTime
    public $lastMessageTime;
    
    //content poslednje poslate poruke - string
    public $lastMessageContent;

    //konstruktor, postavlja promjenljivu user
    function __construct($user){
        $this->user = $user;
    }

    //dohvata vrijeme poslednje poruke u minutima, vraca int
    function getLastMessMins(){
        $time = new DateTime($this->lastMessageTime);
        $now = new DateTime();
        $mins = ($time->getTimestamp() - $now->getTimestamp())/60;
        return $mins;
    }
    
    //dodaje poruku u chat i azurira vrijeme poslednje poruke
    function addMessage($message){ //dodavanje poruke i azuriranje poslednje
        $now = new DateTime();
        if(!isset($this->lastMessageTime)){
            $this->lastMessageTime = $message->CreationDate;
            $this->lastMessageContent = $message->Content;
        }
        else{
            $prevTime = new DateTime($this->lastMessageTime);
            $newTime = new DateTime($message->CreationDate);
            $prevMins = ($prevTime->getTimestamp() - $now->getTimestamp())/60;
            $newMins = ($newTime->getTimestamp() - $now->getTimestamp())/60;
            if($prevMins<$newMins){
                $this->lastMessageTime = $message->CreationDate;
                $this->lastMessageContent = $message->Content;
            }
        }
            array_push($this->messages, $message);
    }
    
    //vraca id usera 
    function getUserId(){
        return $this->user->IdU;
    }
    
    //sortira poruke po vremenu
    function sortMessages(){
        for($i = 0; $i<count($this->messages); $i++){
            for($j = $i+1; $j<count($this->messages); $j++){
                $now = new DateTime();
                $time1 = new DateTime($this->messages[$i]->CreationDate);
                $time2 = new DateTime($this->messages[$j]->CreationDate);
                $mins1 = ($time1->getTimestamp() - $now->getTimestamp())/60;
                $mins2 = ($time2->getTimestamp() - $now->getTimestamp())/60;
                if($mins2<$mins1){
                    $temp = $this->messages[$i];
                    $this->messages[$i] = $this->messages[$j];
                    $this->messages[$j] = $temp;
                }
            }
        }
    }
}

//Dusan Terzic 0664/18, Andrija Rakojevic 0718/18
//Kontroler za funkcije obicnog korisnika, kao i zajednicke funkcije za njega i ostale tipove korisnika 1.0
class User extends BaseController{
    
    //prikaz za obicnog korisnika
    protected function show($page, $data){
        $data['controller'] = 'User';
        $data['user']=$this->session->get('user');
        $data['category']=$this->session->get('category');
        echo view ('template/toolbarUser', $data);
        echo view ("pages/$page", $data);
        echo view ('template/footer');
    }
    
    //prikaz pocetne stranice
    public function index(){
        $this->show('home', []);
    }
    
    //prikaz stranice za pretragu
    public function searchX($makeable = null, $makeableMissing = null, $recipes = null, $address = null){
        $this->show('search', ['makeable' => $makeable,
                               'makeableMissing' => $makeableMissing,
                               'recipes' => $recipes,
                               'address' => $address]);
    }
    
    //prikaz profilne stranice
    public function profile($message = null){
        $possession = new PossessesModel();
        $ingredient = new IngredientModel();
        $ingredient = $ingredient->orderBy('name');
        $ingredientsId = $possession->getIngredientsFromUser($this->session->get('user'));
        $ingredientTmp = $ingredient->get();
        $i = 0;
        foreach($ingredientTmp->getResult() as $ing){
            $ingredients[$i] = $ing;
            $i++;
        }
        if(empty($ingredientsId)){
            $i = 0;
            if($this->session->get('category') == 'store'){
            $store = new StoreModel();
            $store = $store->where('IdU', $this->session->get('user')->IdU)->get()->getRow(0);
            }else{
                $store = null;
            }
            return $this->show('profileUser', [ 'i' => $i,
                                    'ingredients' => $ingredients,
                                    'message' => $message,
                                    'store' => $store]);
        }
        $numOfIngredients = $possession->getNumOfIngredients($this->session->get('user'));
        for($i = 0; $i < $numOfIngredients; $i++){
            $ingredientsInPossession[$i] = $ingredient->find($ingredientsId[$i]->IdI);
        }
        $i = 1;
        if($this->session->get('category') == 'store'){
            $store = new StoreModel();
            $store = $store->where('IdU', $this->session->get('user')->IdU)->get()->getRow(0);
            }else{
                $store = null;
        }
        return $this->show('profileUser', ['ingredientsInPossession' => $ingredientsInPossession,
                                           'i' => $i, 
                                           'ingredients' => $ingredients,
                                           'message' => $message,
                                           'store' => $store]);
    }

    //prikaz stranice za promjenu lozinke
    public function changePassword($message = null){
        $this->show('changePassword', ['message' => $message]);
    }
    
    //Promjena lozinke korisnika, vraca odgovarajucu poruku
    public function changePassSubmit(){
        $message = "";
        if(!$this->validate(['old'=>'required', 'new'=>'required']))
           $message = $message.('Please fill all required fields');
        $passwordFormat = "/^[a-zA-Z0-9]{8,20}$/";
        $newPassword =  $_POST['new'];
        $oldPassword = $this->session->get('user')->Pass;
        if(strcmp($oldPassword, $_POST['old']) != 0)
                $message = $message."Wrong password </br>";
        if(preg_match($passwordFormat, $newPassword) != 1)
           $message = $message.'New password must contain between 8 and 20 numbers or letters </br>';
        if(strcmp($oldPassword, $newPassword) == 0)
            $message = $message.'Your new password can\'t be the same as your old password </br>';
        if(strcmp($message,  "")){
            return $this->changePassword($message);
        }
        $user = new UserModel();
        $user->changePassword($this->session->get('user'), $newPassword);
        $this->session->get('user')->Pass = $newPassword;
        return $this->profile('Password changed successfully.');
    }
    
    //prikaz koktela
    public function viewCocktail(){
        $recipe = new RecipeModel();
        $imo = new IsMadeOfModel();
        $ing = new IngredientModel();
        $comments = new CommentModel();
        $recipeName = $_GET['rn'];
        $recipe = $recipe->where('Name', $recipeName)->get();
        $recipe = $recipe->getRow(0);
        $ingredientsIMO = $imo->getIngredientsForRecipe($recipe);
        $i = 0;
        foreach($ingredientsIMO as $tmp){
            $ingredients[$i] = $ing->find($ingredientsIMO[$i]->IdI);
            $i++;
        }
        $bartenderUsername = new UserModel();
        $bartenderUsername = $bartenderUsername->getUsernameOfBartender($recipe);
        $this->show('recipe',['recipe' => $recipe,
                              'ingredients' => $ingredients,
                              'imo' => $ingredientsIMO,
                              'bartenderUsername' => $bartenderUsername]);
    }
    //Logout korisnika, vraca redirekt na tip korisnika guest
    public function logout(){
        $this->session->destroy();
        return redirect()->to(site_url('Guest'));
    }
    //ucitavanje sastojaka
    public function loadIngredients(){
       $possession = new PossessesModel();
       $ingredient = new IngredientModel();
       $ing = $possession->getIngredientsFromUser($this->session->get('user'));
       for($i = 0; $i < $possession->getNumOfIngredients($this->session->get('user')); $i++)
            $ingredients[$i] = $ingredient->find($ing[$i]->IdI);
       echo json_encode($ingredients);
    }
    
    //uklanjanje sastojka 
    public function removeIngredient(){
        $possession = new PossessesModel();
        return $possession->removePossession($this->session->get('user'), $this->request->getVar('idIng'));
    }
    
    //dodavanje sastojka
    public function addIngredient(){
        $ingredient = new IngredientModel();
        $ingredient = $ingredient->where('Name', $this->request->getVar('name'))->find();
        $id = $ingredient[0]->IdI; 
        $possesion = new PossessesModel();
        return $possesion->addPossession($this->session->get('user')->IdU, $id);
    }
    
    //vraca json niz preko ajaxa, funkcija za provjeru postojanja poruka
    public function messagesExist(){
        $username = $_GET['username'];
        $db      = \Config\Database::connect();
        $builder = $db->table('message');
        $userModel = new UserModel();
        $messageModel = new MessageModel();
        $myId = $this->session->get("user")->IdU;
        $userId = $userModel->where('username', $username)->find()[0]->IdU;
        $where  = "(IdReciever = $userId AND IdSender = $myId)  OR (IdSender = $myId AND IdReciever = $userId)";
        $messages = $builder->where($where)->get()->getResult();
        $array = ["messages"=>$messages,"myId"=> $myId, "userId"=>$userId];
        echo json_encode($array);
    }
    
    //prikaz poruka
    public function messages($users = null){
        $user = new UserModel();
        $user = $user->get();
        $i = 0;
        foreach($user->getResult() as $u){
            $users[$i++] = $u;
        }
        $this->show('messages', ['users'=> $users]); 
    }
    
    //fja ucitava poruke i vraca ih u odgovarajucem formatu, poredjane po chatovima izmedju korisnika, preko ajaxa
    public function loadMessages(){
        $messageModel = new MessageModel();
        $thisUserMessages = $messageModel->getMessagesFromUser($this->session->get('user'));
        //sad kad imamo sve poruke, dodajemo chat za svakog korisnika sa kojim imamo poruke i dodajemo poruke u taj chat
        $i = 0;
        $chats = array();
        foreach($thisUserMessages as $message){ //prolaz kroz sve poruke trenutnog usera
            if($this->session->get('user')->IdU == $message->IdReciever){
                 $otherUserId = $message->IdSender;}
            else{
                $otherUserId = $message->IdReciever;
            }
                //trazim izmedju koja 2 usera je poruka ->otherUserId je id drugog usera
            $set = false;
            $userModel = new UserModel();
            $user = $userModel->find($otherUserId);
            foreach($chats as $chat){ //u dosadasnjim chatovima trazim usera koji ima taj id
                
                if($chat->user->IdU == $otherUserId){ //
                    $set = true;
                    $chat->addMessage($message);
                    
                }
            }
            if(!$set && isset($user)){ //ako ga ne nadjem, pravim novi chat
                $chat = new Chat($user);
                $chat->addMessage($message);
                array_push($chats, $chat);
            }
        }
        foreach($chats as $chat){
            $chat->sortMessages();
        }
         for($i = 0; $i<count($chats); $i++){
            for($j = $i+1; $j<count($chats); $j++){
                if($chats[$i]->getLastMessMins()<$chats[$j]->getLastMessMins()){
                    $temp = $chats[$i];
                    $chats [$i] = $chats[$j];
                    $chats[$j] = $temp;
                }
            }
        }
        $value = array(
            "chats" => $chats,
            "user" =>$this->session->get('user')
        );
        
        echo json_encode($value);
    }
    
    //kreiranje nove poruke, vraca 1
    public function newMessage(){

        $messageModel = new MessageModel();
        $messageContent = $this->request->getVar('content');
        $messageIdReciever = $this->request->getVar('idReciever');
        $messageIdSender = $this->session->get('user')->IdU;
        //$creationDate = new DateTime();
        if($messageContent!=""){
            $messageModel->insert(['IdReciever' => $messageIdReciever,
                                'IdSender' => $messageIdSender,
                                'Content' => $messageContent,
                                //'CreationDate' => $creationDate->format('Y-m-d H:i:s')
            ]);
        }
        return 1;
        
    }
    
    //funkcija za pretragu koktela, vraca rezultate pretrage
    public function search(){
        $imo = new IsMadeOfModel();
        $recipe = new RecipeModel();
        $possession = new PossessesModel();
        $recipes = $recipe->get();
        $possession = $possession->getIngredientsFromUser($this->session->get('user'));
        $i = 0;
        $j = 0;
        foreach($recipes->getResult() as $recipe){
            $count = 0;
            $ingredients = $imo->getIngredientsForRecipe($recipe);
            foreach($ingredients as $ingredient){
                foreach($possession as $userIngredient){
                    if($userIngredient->IdI == $ingredient->IdI)
                        $count++;
                }
            }
            $numOfIngIMO = $imo->where('IdR', $recipe->IdR)->countAllResults();
            if($numOfIngIMO - $count == 0)
                $makeable[$i++] = $recipe;
            elseif(!$i)
                $makeable = null;
            if($numOfIngIMO - $count <= 3 && $numOfIngIMO - $count != 0)
                $makeableMissing[$j++] = $recipe;
            elseif(!$j)
                $makeableMissing = null;
        }
        $address = $this->session->get('user')->Address;
        return $this->searchX($makeable, $makeableMissing, $recipes->getResult(), $address);
    }
    
    //ucitava komentare i vraca ih preko ajaxa u json formatu
    public function loadComments(){
        $IdR = $this->request->getVar('IdRecipe');
        $commentModel = new CommentModel();
        
        //treba da dobijem sve komentare na recept
        //da ih sortiram po vremenu
        //da ih posaljem
        $userModel = new UserModel();
        $comments = $commentModel->where('IdR', $IdR)->find();
        $i = 0;
        $data = array();
        foreach($comments as $comment){
            $IdUser = $comment->IdU;
            $username = $userModel->find($IdUser)->Username;
            $data[$i] = array("comment"=>$comment, "username"=>$username);
            $i++;
        }
        //sortiranje po vremenu
        for($i = 0; $i<count($data); $i++){
            for($j = $i+1; $j<count($data); $j++){
                if($data[$i]["comment"]->CreationDate <$data[$j]["comment"]->CreationDate){
                    $temp = $data[$i];
                    $data [$i] = $data[$j];
                    $data[$j] = $temp;
                }
            }
        }
        if(isset($this->session->get('user')->Username)){
            $result = array("data"=>$data, "myUsername" => $this->session->get('user')->Username);}
        else{
            $result = array( "data" =>$data, "");
        }
        echo json_encode($result);
    }
    
    //postavljanje komentara
    public function postComment(){
         $IdR = $this->request->getVar('IdR');
          $content = $this->request->getVar('content');
          
          $commentModel = new CommentModel();
          $commentModel->insert(['Content' => $content,
                                                          'IdR' => $IdR,
                                                          'IdU' => $this->session->get('user')->IdU]);

    }
    
    //brisanje komentara
    public function deleteComment(){
        $IdC = $this->request->getVar('IdC');
        $commentModel = new CommentModel();
        $commentModel->where('IdC', $IdC)->delete();
    }
    
    //ocjenjivanje recepta
    public function rateRecipe(){
        $IdR = $this->request->getVar('IdR');
        $rating = $this->request->getVar('rating');
        $user = $this->session->get('user');
        $recipeModel = new RecipeModel();
        $hasRatedModel = new HasRatedModel();
        $recipeRating = $recipeModel->find($IdR)->Rating;
        $recipeNo = $recipeModel->find($IdR)->NumberOfRatings;
        if(count($hasRatedModel->where('IdR', $IdR)->where('IdU',$user->IdU)->find())==0){
            $hasRatedModel->insert(['IdR'=>$IdR, 'IdU'=>$user->IdU, 'Rating'=>$rating]);
            $recipeRating = ($recipeRating*$recipeNo + $rating)/($recipeNo+1);
            $recipeNo++;
            $data = [
                 'Rating' => $recipeRating,
                 'NumberOfRatings'=>$recipeNo
            ];
            $recipeModel->update($IdR, $data);
        }
        else{
            $currRating = $hasRatedModel->where('IdR', $IdR)->where('IdU',$user->IdU)->find()[0]->Rating;
            $recipeRating = ($recipeRating*$recipeNo + $rating - $currRating)/$recipeNo;
            $recipeModel->where('IdR', $IdR)->set(['Rating' => $recipeRating])->update();
            $hasRatedModel->where('IdR', $IdR)->where('IdU', $user->IdU)->set(['Rating' => $rating])->update();
        }
    }
    
    //ucitavanje ratinga i vracanje preko ajaxa, u json formatu
    public function loadRating(){
        $IdR = $this->request->getVar('IdRecipe');
        $user = $this->session->get('user');
        $recipeModel = new RecipeModel();
        $recipeRating = $recipeModel->find($IdR)->Rating;
        $recipeNo = $recipeModel->find($IdR)->NumberOfRatings;
        $data = array([ "recipeNo" => $recipeNo, "recipeRating"=>$recipeRating ]);
        echo json_encode($data);
    }
    
    //sortiranje po broju sastojaka i vracanje niza preko ajaxa u json formatu
    public function sortByNum(){
        $order = $this->request->getVar('order');
        $makeable = json_decode($this->request->getVar('makeable'));
        $makeableMissing = json_decode($this->request->getVar('makeableMissing'));
        if(isset($makeable)){
            for($i = 0; $i<count($makeable); $i++){
               for($j=$i+1; $j<count($makeable);$j++){
                   if(($this->noIng($makeable[$i])>$this->noIng($makeable[$j]) && $order=="ascending") || ($this->noIng($makeable[$i])<$this->noIng($makeable[$j]) && $order=="descending")){
                       $temp = $makeable[$i];
                       $makeable[$i] = $makeable[$j];
                       $makeable[$j] = $temp;
                   }
               }
           }
        }
        if(isset($makeableMissing)){
            for($i = 0; $i<count($makeableMissing); $i++){
                for($j=$i+1; $j<count($makeableMissing);$j++){
                    if(($this->noIng($makeableMissing[$i])>$this->noIng($makeableMissing[$j]) && $order=="ascending") || ($this->noIng($makeableMissing[$i])<$this->noIng($makeableMissing[$j]) && $order=="descending")){
                        $temp = $makeableMissing[$i];
                        $makeableMissing[$i] = $makeableMissing[$j];
                        $makeableMissing[$j] = $temp;
                    }
                }
            }
        }
        $data = array("makeable"=>$makeable, "makeableMissing"=>$makeableMissing);
        echo json_encode($data);
    }
    
    //vraca broj sastojaka recepta iz ismadeof modela
    private function noIng($recipe){
        $isMadeOfModel = new IsMadeOfModel();
        return $isMadeOfModel->getIngredientCountForRecipe($recipe);

    }
}