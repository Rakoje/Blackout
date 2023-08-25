<?php


namespace App\Controllers;

use App\Models\UserModel;
use App\Models\RecipeModel;
use App\Models\GenericUserModel;
use App\Models\AdminModel;
use App\Models\BartenderModel;
use App\Models\IsMadeOfModel;
use App\Models\IngredientModel;
use App\Models\MessageModel;
use App\Models\RequestModel;
use App\Models\StoreModel;

/*
Guest kontroler verzija 1.0 
 Klasa sadrzi sve funkcionalnosti tipa korisnika - gost
 Anastasija Tomic 0255/18, Andrija Rakojevic 0255/18
 Verzija 1.0
 */
class Guest extends BaseController{
    
    //funkcija koja prikazuje gosta
    protected function show($page, $data){
        $data['controller'] = 'Guest';
        echo view('template/toolbarGuest');
        echo view("pages/$page", $data);
        echo view('template/footer');
    }
    
    //prikaz pocetne stranice
    public function index(){
        $this->show('home', []);
    }
    
    //prikaz stranice za login, uz proslijedjenu poruku
    public function login($poruka=null){
        $this->show('login', ['poruka'=>$poruka]);
        
    }

    
    //funkcija provjerava sesiju i vodi na pravi tip korisnika
    public function checkSession() {
        if (!isset($_SESSION['user'])){
           
            return redirect()->to(site_url('Guest/login'));
        }
        return redirect()->to(site_url('User/profile'));
    }

    
    //prikazuje stranicu za izbor tipa korisnika prilikom pravljenja profila
    public function signupChoice(){
        $this->show('signupChoice', []);
    }
    
    //funkcija za login formu, provjera validnost podataka u bazi


    public function loginSubmit(){
        $db = \Config\Database::connect();
           if(!$this->validate(['username'=>'required', 'pass'=>'required'])){
           return $this->login('Please fill all required fields');
        }
        $builder = $db->table('usertemplate');
        $query = $builder->getWhere(['username' => $this->request->getVar('username')]); //dobijam ako postoji user sa tim usernamom
        if($query->getRow(0) == null){
            return $this->login('Username not found.');
        }
        $builderUser = $db->table('genericuser');
        $builderBar = $db->table('bartender');
        $builderAdmin = $db->table('administrator');
        $builderStore = $db->table('store');
        $queryStore = $builderStore->getWhere(['IdU'=>$query->getRow()->IdU]);
        $queryUser = $builderUser->getWhere(['IdU' => $query->getRow()->IdU]);
        $queryBar = $builderBar->getWhere(['IdU' => $query->getRow()->IdU]);
        $queryAdmin = $builderAdmin->getWhere(['IdU' => $query->getRow()->IdU]);
        if($queryUser->getRow()!=null){
           $category = "user";
       }
       if($queryBar->getRow()!=null){
           $category = "bartender";
       }
       if($queryAdmin->getRow()!=null){
           $category = "admin";
       }    
           
        if($queryStore->getRow()!=null){
            $category = "store";
        }
        if(!isset($category)){
            return $this->login('Your account request has been denied');
        }
       $id = $query->getRow(0)->IdU;
       $userModel = new UserModel();
       $user = $userModel->find($id);
       if($user->Pass != $this->request->getVar('pass'))
           return $this->login('Wrong password!');
       $this->session->set('user', $user);
       $this->session->set('category', $category);
       if($category=='admin'){
           $thisId = $query->getRow()->IdU;
           $requestModel = new RequestModel();
           $request = $requestModel->where('IdU', $thisId)->find()[0];
           if(isset($request)){
                if($request->Approved == 1){
                         return redirect()->to(site_url('Admin'));
                }
                else{
                    return $this->login("Your admin request has been denied!");
                }
           }
           
        if($category=='store'){
            $thisId = $query->getRow()->IdU;
            $requestModel = new RequestModel();
            $request = $requestModel->where('IdU', $thisId)->find()[0];
            if(isset($request)){
                 if($request->Approved == 1){
                          return redirect()->to(site_url('Store'));
                }
                 else{
                     return $this->login("Your store request has been denied!");
                 }
            }
            else{
                return $this->login('No store request for your account has been found');
            }
        }
           else{
               return $this->login('No admin request for your account has been found');
           }
       }
       if($category=='user')
            return redirect()->to(site_url('User'));
       if($category=='bartender')
            return redirect()->to(site_url('Bartender'));
       if($category=='store')
            return redirect()->to(site_url('Store'));
    }

    
    //funkcija za registraciju prodavnice, provjerava regex, postojanje usernamea, salje request za odobravanje prodavnice, vodi na login stranicu
    public function regSubmitStore(){
        $message = "</br>";
        if(!$this->validate(['username'=>'required', 'pass'=>'required', 'name'=>'required',
                  'description'=>'required', 'e_mail'=>'required', 'pass2'=>'required',
                   'address' => 'required', 'startHours'=>'required', 'endHours'=>'required'])){
                   $message .= 'Please fill all required fields';
        }
        
        $emailFormat =  filter_var($_POST['e_mail'], FILTER_VALIDATE_EMAIL);
        $nameFormat = "/^[a-z ,.'-]+$/i" ;
        $passwordFormat = "/^[a-zA-Z0-9]{8,20}$/"; 
        $addressFormat = "/^[A-Za-z0-9\-\\,.\s]+$/";
        $usernameFormat = "/^[A-Za-z0-9]{5,20}$/";
        $phoneFormat = "/^[+]{0,1}[0-9 \s-]{9,}$/";
        $start = strtotime($_POST['startHours']);
        $end = strtotime($_POST['endHours']);
        if($emailFormat == false){
            $message = $message.'Bad email format</br>';
        }
        if(preg_match($nameFormat, $_POST['name'])!=1){
               $message = $message.'Invalid name </br>';
        }
        if(preg_match($passwordFormat, $_POST['pass'])!=1){
               $message = $message.'Password must contain between 8 and 20 numbers or letters </br>';
        }
        if($_POST['pass']!=$_POST['pass2']){
                $message = $message.'Passwords dont match</br>';
        }
        if(preg_match($addressFormat, $_POST['address'])!=1){
               $message = $message.'Invalid address format!</br>';
        }
        if(preg_match($usernameFormat, $_POST['username'])!=1){
               $message = $message.'Username must be at least 5 and a maximum of 20 characters and must contain only numbers and letters</br>';
        }
        if(preg_match($phoneFormat, $_POST['phone'])!=1){
               $message = $message.'Phone number may only contain numbers, spaces,- and +</br>';
        }
        if($start > $end){
               $message = $message.'Starting hours must be before ending hours';
        }
        $userModel = new UserModel();
        if($userModel->where('username', $this->request->getVar('username'))->find()){
            $message = $message.'Username already in use</br>';
       }
       if(strcmp($message,  '</br>') ){
            return $this->signupStore($message);
        }
        $adminModel = new AdminModel();
        $admins = $adminModel->get()->getResult();
       $userModel->insert(['username' => $this->request->getVar('username'),
                                'name' => $this->request->getVar('name'),
                                'pass' => $this->request->getVar('pass'),
                                'e_mail' => $this->request->getVar('e_mail'),
                                'address' => $this->request->getVar('address')]);
       $id = $userModel->getInsertID();
       $requestModel = new RequestModel();
       $requestModel->addRequest($this->request->getVar('description'), $id, $this->request->getVar('username'));
       $messageModel = new MessageModel();
       foreach($admins as $admin){
               $idReciever = $admin->IdU;
               $idSender = $id;
               $content = "I, ".$this->request->getVar('username')." wish to get my store approved with the description down below: \n";
               $content .= $this->request->getVar('description');
               $messageModel->insert(['IdSender' => $idSender,
                                                            'IdReciever' => $idReciever,
                                                            'Content' => $content, 
                                                            'IdReq' => $requestModel->getInsertId()]);
           }
       $storeModel = new StoreModel();
       $storeModel->insert(['IdU' => $id, 'OpeningHours'=>$this->request->getVar('startHours'),
                                                'ClosingHours' =>$this->request->getVar('endHours'),
                                                'Phone' =>$this->request->getVar('phone'),
                                                'Description' =>$this->request->getVar('description'),
                                                'Owner' =>$this->request->getVar('ownerName')]);
        return redirect()->to(site_url('Guest/login'));
    }
    
    //registracija svih tipova korisnika osim prodavnice, za admina salje request, za ostale pravi account, vodi na stranicu za login
    public function regSubmit(){ //dodati za prodavnicu
          $message = '</br>';  
        if(!$this->validate(['username'=>'required', 'pass'=>'required', 'name'=>'required',
                  'surname'=>'required', 'e_mail'=>'required', 'pass2'=>'required'])){
           $message = $message.'Please fill all required fields';
        }
        
       $emailFormat =  filter_var($_POST['e_mail'], FILTER_VALIDATE_EMAIL);
       if($emailFormat == false){
           $message = $message.'Bad email format</br>';
       }
       $nameFormat = "/^[a-z ,.'-]+$/i" ;
       $passwordFormat = "/^[a-zA-Z0-9]{8,20}$/"; 
       $addressFormat = "/^[A-Za-z0-9\-\\,.\s]+$/";
       $usernameFormat = "/^[A-Za-z0-9]{5,20}$/";
     
       
        if(preg_match($nameFormat, $_POST['name'])!=1){
               $message = $message.'Invalid name </br>';
        }
        if(preg_match($nameFormat, $_POST['surname'])!=1){
           $message = $message.'Invalid surname </br>';
        }
        if(preg_match($passwordFormat, $_POST['pass'])!=1){
               $message = $message.'Password must contain a between 8 and 20 numbers or letters </br>';
        }
        if($_POST['pass']!=$_POST['pass2']){
                $message = $message.'Passwords dont match</br>';
        }
        if(preg_match($addressFormat, $_POST['address'])!=1){
               $message = $message.'Invalid address format!</br>';
        }
         if(preg_match($usernameFormat, $_POST['username'])!=1){
               $message = $message.'Username must be between 5 and 20 characters and must contain only numbers and letters</br>';
        }
        $userModel = new UserModel();
        if($userModel->where('username', $this->request->getVar('username'))->find()){
            $message = $message.'Username already in use</br>';
        }
        if (strcmp($message,  '</br>') && ($_POST['category'] == "user")){
            return $this->signupUser($message);
        }
        if(strcmp($message,  '</br>') && ($_POST['category'] == "admin")){
            return $this->signupAdmin($message);
        }
        if(strcmp($message,  '</br>') && ($_POST['category'] == "bartender")){
            return $this->signupBartender($message);
        }if(strcmp($message,  '</br>') && ($_POST['category'] == "store")){
            return $this->signupStore($message);
        }
        if($_POST['category']!="admin"){
            $userModel->insert(['username' => $this->request->getVar('username'),
                                'name' => $this->request->getVar('name'),
                                'surname' => $this->request->getVar('surname'),
                                'pass' => $this->request->getVar('pass'),
                                'e_mail' => $this->request->getVar('e_mail'),
                                'address' => $this->request->getVar('address')]);
            $id = $userModel->getInsertID();
        }
        if($_POST['category'] == "user"){
            $user = new GenericUserModel();
            $user->insert(['IdU' => $id]);
        }
        if($_POST['category'] == "admin"){ //pravi se request za dodavanje, ne dodaje se automatski vec se ceka da drugi admin to odobri
           //validacija
           $adminModel = new AdminModel();
           $admins = $adminModel->get()->getResult(); //salje se poruka svakom adminu
           //prvo se dobije profil obicnog usera, koji moze biti unaprijedjen u admina
           $userModel->insert(['username' => $this->request->getVar('username'),
                                'name' => $this->request->getVar('name'),
                                'surname' => $this->request->getVar('surname'),
                                'pass' => $this->request->getVar('pass'),
                                'e_mail' => $this->request->getVar('e_mail'),
                                'address' => $this->request->getVar('address')]);
            $id = $userModel->getInsertId();
            $requestModel = new RequestModel();
            $requestModel->addRequest($this->request->getVar('description'), $id, $this->request->getVar('username'));
            $messageModel = new MessageModel();
            foreach($admins as $admin){
               $idReciever = $admin->IdU;
               $idSender = $id;
               $content = "I, ".$this->request->getVar('username')." wish to become an administrator for these reasons: \n";
               $content .= $this->request->getVar('description');
               $messageModel->insert(['IdSender' => $idSender,
                                                            'IdReciever' => $idReciever,
                                                            'Content' => $content, 
                                                            'IdReq' => $requestModel->getInsertId()]);
            }
        
            
            $user = new AdminModel();
            $user->insert(['IdU' => $id,
                           'description'=>$this->request->getVar('description')]);
        }
        if($_POST['category'] == "bartender"){
            $user = new BartenderModel();
            $user->insert(['IdU' => $id]);
        }
        return redirect()->to(site_url('Guest/login'));
    }
    
    //prikaz signup stranice za admina
    public function signupAdmin($poruka = null){
        $this->show('signupAdmin', ['poruka' => $poruka]);
    }
    
    //prikaz signup stranice za usera
    public function signupUser($poruka = null){
        $this->show('signupUser', ['poruka' => $poruka]);
    }
    
    //prikaz signup stranice za bartendera
    public function signupBartender($poruka = null){
        $this->show('signupBartender', ['poruka' => $poruka]);
    }
    
    //prikaz signup stranice za prodavnicu
    public function signupStore($poruka = null){
        $this->show('signupStore', ['poruka' => $poruka]);
    }
    
    //funkcija za prikaz stranice koktela
    public function viewCocktail(){
        $recipe = new RecipeModel();
        $imo = new IsMadeOfModel();
        $ing = new IngredientModel();
        if(isset($_GET['rn']))
            $recipeName = $_GET['rn'];
        else
            $recipeName = $this->request->getVar('name');
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
        return $this->show('recipe',['recipe' => $recipe,
                              'ingredients' => $ingredients,
                              'imo' => $ingredientsIMO,
                              'bartenderUsername' => $bartenderUsername]);
    }
    
    
    //funkcija za izbor sastojaka
    public function chooseIngredients(){
        $ingredient = new IngredientModel();
        $ingredient = $ingredient->orderBy('name');
        $recipe = new RecipeModel();
        $recipe = $recipe->orderBy('name');
        $recipeTmp = $recipe->get();
        $ingredientTmp = $ingredient->get();
        $i = 0;
        foreach($ingredientTmp->getResult() as $ing){
            $ingredients[$i] = $ing;
            $i++;
        }
        $i = 0;
        foreach($recipeTmp->getResult() as $rec){
            $recipes[$i] = $rec;
            $i++;
        }
        $this->show('chooseIngredients', ['ingredients' => $ingredients,
                                          'recipes' => $recipes]);
    }
    
    public function searchX($makeable = null, $makeableMissing = null){
        $this->show('search', ['makeable' => $makeable,
                               'makeableMissing' => $makeableMissing]);
    }
    
    //funkcija za pretragu recepata, vraca recepte koji mogu da se naprave i one kojima fali 0-3 sastojka
    public function search(){
        $imo = new IsMadeOfModel();
        $recipe = new RecipeModel();
        $ingM = new IngredientModel();
        $recipes = $recipe->get();
        $i = 0;
        $myIng = $_GET['myIng'];
        $myIng = explode(",", $myIng);
        foreach($myIng as $name){
            $ing = $ingM->getWhere(['Name' => $name]);
            $myIngredients[$i++] = $ing->getRow(0);
        }
        $i = 0;
        $j = 0;
        foreach($recipes->getResult() as $recipe){
            $count = 0;
            $ingredients = $imo->getIngredientsForRecipe($recipe);
            foreach($ingredients as $ingredient){
                foreach($myIngredients as $guestIngredient){
                    if($guestIngredient->IdI == $ingredient->IdI)
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
        return $this->searchX($makeable, $makeableMissing);
    }
}
