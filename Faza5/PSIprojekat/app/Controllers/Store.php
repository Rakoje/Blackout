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
//Andrija Rakojevic 2018/0718
//Kontroler za funkcionalnosti prodavnice 1.0
class Store extends User{
    //funckija za prikaz stranica prodavnice
    protected function show($page, $data){
        $data['controller'] = 'Store';
        $data['user']=$this->session->get('user');
        $data['category']=$this->session->get('category');
        echo view ('template/toolbarStore', $data);
        echo view ("pages/$page", $data);
        echo view ('template/footer');
    }
    // funckija za pocetnu stranicu
    public function index($message = null){
        $ingredient = new IngredientModel();
        $ingredient = $ingredient->orderBy('name');
        $ingredientTmp = $ingredient->get();
        $i = 0;
        foreach($ingredientTmp->getResult() as $ing){
            $ingredients[$i] = $ing;
            $i++;
        }
        $this->show('homeStore', ['ingredients' => $ingredients,
                                  'message' => $message]);
    }
    //funckija koja prosledjuje sastojke ajax funckiji koja ih prikazuje na stranici prodavnice
    public function loadIngredients(){
       $possession = new PossessesModel();
       $ingredient = new IngredientModel();
       $ing = $possession->getIngredientsFromUser($this->session->get('user'));
       for($i = 0; $i < $possession->getNumOfIngredients($this->session->get('user')); $i++){
            $ingredients[$i] = $ingredient->find($ing[$i]->IdI);
            $price[$i] = $ing[$i]->Price;
       }
       echo json_encode(array($ingredients, $price));
    }
    
    //funckija koja dodaje sastojak u prodavnicu, vraca funckiju modela koja stvara novu vezu
    public function addIngredient(){
        $floatVal=floatval($this->request->getVar('price'));
        if($this->request->getVar('price') == null){
            echo json_encode('Specify the price first');
        }
        elseif(!$floatVal){
            $message = 'Price must be a number!';
            echo json_encode($message);
        }
        elseif($floatVal<=0){
            $message = 'Price must be greater than 0';
            echo json_encode($message);
        }
        else{
            $ingredient = new IngredientModel();
            $ingredient = $ingredient->where('Name', $this->request->getVar('name'))->find();
            $id = $ingredient[0]->IdI; 
            $possesion = new PossessesModel();
            $possesion->addPossession($this->session->get('user')->IdU, $id, $this->request->getVar('price'));
            echo json_encode('');
        }
    }
    //funckija koja mijenja cijenu sastojka, vraca funckiju modela koja mijenja cijenu sastojka
    public function changePrice(){
            $floatVal=floatval($this->request->getVar('priceChange'));
        if(!$floatVal){
            $message = 'Price must be a number!';
        }
        elseif($floatVal<=0){
            $message = 'Price must be greater than 0';
        }
        else{
            $possession = new PossessesModel();
            $possession->set('Price', $this->request->getVar('priceChange'))
            ->where('IdU', $this->session->get('user')->IdU)->where('IdI', $this->request->getVar('id'))->update();
            echo json_encode("");
        }
        if(isset($message)){
            echo json_encode($message);
        }
    }
}


