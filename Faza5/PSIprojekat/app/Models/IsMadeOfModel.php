<?php
namespace App\Models;
use CodeIgniter\Model;

//Andrija Rakojevic 18/0718 
//Model za tabelu IsMadeOf 1.0
class IsMadeOfModel extends Model{
    protected $table = 'ismadeof';
    protected $primaryKey = 'IdR, IdI';
    protected $returnType = 'object';
    
    protected $allowedFields = ['IdI', 'quantity' , 'IdR'];
    
    //dohvata sve sastojke datog recepta, vraca niz sastojaka
    public function getIngredientsForRecipe($recipe){
        $query = $this->where('IdR', $recipe->IdR)->findAll();
        $i = 0;
        foreach($query as $ingredient){
            $ingredients[$i] = $ingredient;
            $i++;
        }
        return $ingredients;
    }
    
    //dohvata broj sastojaka recepta, vraca int
    public function getIngredientCountForRecipe($recipe){
        $query = $this->where('IdR', $recipe->IdR)->findAll();
        $i = 0;
        foreach($query as $ingredient){
            $i++;
        }
        return $i;
    }

    //dodaje proslijedjene sastojke u recept, vraca broj njih(int)
    public function insertIngredientsToRecipe($ingredients, $id, $quantity){
        $i = 0;
        foreach($ingredients as $ingredient){
            $this->insert([ 'IdR' => $id, 
                            'quantity' => $quantity[$i++],
                            'IdI' => $ingredient->IdI]);
        }
        return $i;
    }
}
?>
