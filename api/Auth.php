<?php
// Active l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once '../config/config.php';
include_once '../config/database.php';
include_once '../models/User.php';
include_once '../controllers/AuthController.php';

$database = new Database();
$db = $database->getConnection();
$auth = new AuthController($db);

// Récupére les données JSON
$input = json_decode(file_get_contents("php://input"), true);

// donné json
if (!$input) {
    $input = $_POST;
}

$response = [];

if(isset($input['action'])) {
    switch($input['action']) {
        case 'register':
            if(!empty($input['email']) && !empty($input['password']) && !empty($input['full_name'])) {
                $response = $auth->register($input);
            } else {
                $response = ["success" => false, "message" => "Tous les champs sont requis"];
            }
            break;

        case 'login':
            if(!empty($input['email']) && !empty($input['password'])) {
                $response = $auth->login($input);
            } else {
                $response = ["success" => false, "message" => "Email et mot de passe requis"];
            }
            break;

        default:
            $response = ["success" => false, "message" => "Action non valide"];
    }
} else {
    $response = ["success" => false, "message" => "Action non spécifiée"];
}

echo json_encode($response);
?>
