<?php

namespace App\Controllers;

use App\Models\Articles;
use App\Utility\Upload;
use \Core\View;

/**
 * Product controller
 */
class Product extends \Core\Controller
{

    /**
     * Affiche la page d'ajout
     * @return void
     */
    public function indexAction()
    {

        if(isset($_POST['submit'])) {

            try {
                $f = $_POST;

                // TODO: Validation
                // Liste des champs obligatoires
                $requiredFields = [
                    'name' => 'Titre',
                    'description' => 'Description',
                    'city' => 'Ville',
                ];

                $missingFields = [];
                foreach ($requiredFields as $field => $label) {
                    if (empty(trim($f[$field] ?? ''))) {
                        $missingFields[] = $label;
                    }
                }

                // Vérifie si une image a bien été envoyée
                if (!isset($_FILES['picture']) || $_FILES['picture']['error'] !== UPLOAD_ERR_OK || empty($_FILES['picture']['tmp_name'])) {
                    $missingFields[] = 'Image';
                }

                if (!empty($missingFields)) {
                    $errors = "Tous les champs sont requis. Il manque : " . implode(', ', $missingFields) . ".";
                    View::renderTemplate('Product/Add.html', [
                        'error' => $errors,
                        'old' => $f // Pour garder les valeurs déjà remplies
                    ]);
                    return;
                }

                $f['user_id'] = $_SESSION['user']['id'];
                $id = Articles::save($f);

                $pictureName = Upload::uploadFile($_FILES['picture'], $id);
                Articles::attachPicture($id, $pictureName);

                header('Location: /product/' . $id);
            } catch (\Exception $e){
                    var_dump($e);
            }
        }

        View::renderTemplate('Product/Add.html');
    }

    /**
     * Affiche la page d'un produit
     * @return void
     */
    public function showAction()
    {
        $id = $this->route_params['id'];

        try {
            Articles::addOneView($id);
            $suggestions = Articles::getSuggest();
            $article = Articles::getOne($id);
        } catch(\Exception $e){
            var_dump($e);
        }

        View::renderTemplate('Product/Show.html', [
            'article' => $article[0],
            'suggestions' => $suggestions
        ]);
    }
}
