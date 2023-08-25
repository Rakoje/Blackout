<?php namespace App\Controllers;

use App\Models\UserModel;
use App\Models\RecipeModel;
use App\Models\GenericUserModel;
use App\Models\PossessesModel;
use App\Models\IngredientModel;
use App\Models\IsMadeOfModel;
use App\Models\MessageModel;
use App\Models\RequestModel;
use App\Models\AdminModel;
use DateTime;
//Andrija Rakojevic 2018/0718
//Dusan Terzic 2018/0664
//Kontroler za funckionalnosti koktel majstora 1.0
class Bartender extends User{
    //funckija za prikaz stranica
    protected function show($page, $data){
        $data['controller'] = 'Bartender';
        $data['user']=$this->session->get('user');
        $data['category']=$this->session->get('category');
        echo view ('template/toolbarUser', $data);
        echo view ("pages/$page", $data);
        echo view ('template/footer');
    }
    //funckija za dodavanje novog recepta, vraca poruku o uspjehu funckije
    public function addRecipe() {
        $message = '</br>';
        if(!$this->validate(['name'=>'required', 'description'=>'required', 'numOfServings'=>'required',
                'prepTime'=>'required', 'strength'=>'required', 'method'=>'required'])){
           $message = $message."Please fill out all the fields</br>";
        }
        $numOfServ=intval($_GET['numOfServings']);
        $prepTime=intval($_GET['prepTime']);
        $strength=intval($_GET['strength']);
        $nameFormat = "/^[A-Za-z0-9\-\\,.\s]+$/";
        $numberFormat = "/^[0-9]{1,}$/";
        $quantity = $_GET['quantity'];
        $ingredients = $_GET['ingredients'];
        if(preg_match($nameFormat, $_GET['name'])!=1){
                $message = $message.'Invalid name </br>';
        }
        if(preg_match($numberFormat, $_GET['numOfServings'])!=1){
            $message = $message.'Invalid number of servings </br>';
        } 
        if(preg_match($numberFormat, $_GET['prepTime'])!=1){
                $message = $message.'Invalid preparation time </br>';
        }
        if(preg_match($numberFormat, $_GET['strength'])!=1){
               $message = $message.'Invalid strength number </br>';
        }
        if($numOfServ< 0){
            $message = $message.'Number of servings must be greater than 0 </br>';
        }
        if($prepTime < 0){
            $message = $message.'Preparation time must be greater than 0 </br>';
        }
        if($strength < 0){
            $message = $message.'Strength must be greater than 0 </br>';
        }
        if($message != '</br>'){
            return json_encode($message);
        }
        $recipeModel = new RecipeModel();
        $imo = new IsMadeOfModel();
        $ing = new IngredientModel();
        $i = 0;
        foreach($ingredients as $ingredient){
            $ingTmp = $ing->getWhere(['name' => $ingredient]);
            $ingr[$i++] = $ingTmp->getRow(0);
        }
        $i = 0;
        foreach($ingredients as $ingredient){
            $ingTmp = $ing->getWhere(['name' => $ingredient]);
            $ingr[$i++] = $ingTmp->getRow(0);
        }        
        $recipeModel->insert(['name' => $_GET['name'],
                            'description' => $_GET['description'],
                            'numofservings' => $_GET['numOfServings'],
                            'preptime' => $_GET['prepTime'],
                            'strength' => $_GET['strength'],
                            'method' => $_GET['method'],
                            'IdU' => $this->session->get('user')->IdU ]);
        /*if ( 0 < $_FILES['file']['error'] ) {
            echo 'Error: ' . $_FILES['file']['error'] . '<br>';
        }
        else {
            move_uploaded_file($_FILES['file']['tmp_name'], 'assets/' . $_FILES['file']['name']);
            rename("assets/".$_FILES['file']['name'], "assets/".$_GET["name"].".".pathInfo("assets/".$_FILES['file']['name'], PATHINFO_EXTENSION));
        }*/
        $id = $recipeModel->getInsertID();
        $imo->insertIngredientsToRecipe($ingr, $id , $quantity);
        echo json_encode("success");
    }
    //funkcija za prosledjivanje recepata ajax funckiji koja ih prikazuje na profilu koktel majstora
    public function loadRecipes() {
        $recipe = new RecipeModel();
        $recipes = $recipe->getAllRecipesFromUser($this->session->get('user'));
        echo json_encode($recipes);
    }
    //funckija za prikaz stranice za pravljenje novog recepta
    public function addNewRecipe($message = null){
        $ingredient = new IngredientModel();
        $ingredient = $ingredient->orderBy('name');
        $ingredientTmp = $ingredient->get();
        $i = 0;
        foreach($ingredientTmp->getResult() as $ing){
            $ingredients[$i] = $ing;
            $i++;
        }
        $this->show('addNewRecipe', ['message' => $message,
                                     'ingredients' => $ingredients]);
    }
    //funckija za prikaz stranice za dodavanje novog sastojka
    public function addNewIngredient(){
        $this->show('addNewIngredient', []);
    }
    //funckija za dodavanje novog sastojka
    public function addIngredientNew(){
        if ( 0 < $_FILES['file']['error'] ) {
            echo 'Error: ' . $_FILES['file']['error'] . '<br>';
        }
        else {
            move_uploaded_file($_FILES['file']['tmp_name'], 'assets/' . $_FILES['file']['name']);
            rename("assets/".$_FILES['file']['name'], "assets/".$_POST["name"].".".pathInfo("assets/".$_FILES['file']['name'], PATHINFO_EXTENSION));
            $requestModel = new RequestModel();
            $messageModel = new MessageModel();
            $ingredientModel = new IngredientModel();
            $adminModel = new AdminModel();
            $content = "I, ".$this->session->get('user')->Username."wish to add the ingredient ".$_POST["name"]." to my recipe, so please approve this ingredient!";
            $requestModel->insert([
                'Name' => $_POST['name'],
                'IdU' =>$this->session->get('user')->IdU,
                'Approved' => 0
            ]);
            $ingredientModel->insert([
                'IdReq' => $requestModel->getInsertID(),
                'Name' => $_POST["name"]
            ]);
            $admins = $adminModel->get()->getResult();
            foreach($admins as $admin){
                   $idReciever = $admin->IdU;
                   $idSender = $this->session->get('user')->IdU;
                   $messageModel->insert(['IdSender' => $idSender,
                                                                'IdReciever' => $idReciever,
                                                                'Content' => $content, 
                                                                'IdReq' => $requestModel->getInsertID()]);

              }
        }
       
    }
}

