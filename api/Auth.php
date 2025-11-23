<?php
include_once '../config/config.php';
include_once '../config/database.php';
include_once '../models/User.php';
include_once '../controllers/AuthController.php';

// Headers CORS pour permettre les requêtes depuis le frontend
header("Access-Control-Allow-Origin: http://localhost");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");

// Gérer les requêtes preflight OPTIONS
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

$database = new Database();
$db = $database->getConnection();
$auth = new AuthController($db);

$method = $_SERVER['REQUEST_METHOD'];
$response = [];

if($method == 'POST') {
    $input = json_decode(file_get_contents("php://input"), true);
    
    if(!$input) {
        $input = $_POST;
    }
    
    if(isset($input['action'])) {
        switch($input['action']) {
            case 'register':
                if(!empty($input['email']) && !empty($input['password']) && !empty($input['full_name'])) {
                    // Validation de l'email
                    if(!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
                        $response = ["success" => false, "message" => "Format d'email invalide"];
                        break;
                    }
                    
                    // Validation du mot de passe
                    if(strlen($input['password']) < 8) {
                        $response = ["success" => false, "message" => "Le mot de passe doit contenir au moins 8 caractères"];
                        break;
                    }
                    
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

            case 'logout':
                $response = $auth->logout();
                break;

            default:
                $response = ["success" => false, "message" => "Action non valide"];
        }
    } else {
        $response = ["success" => false, "message" => "Action non spécifiée"];
    }
} else {
    $response = ["success" => false, "message" => "Méthode non autorisée"];
}

echo json_encode($response);
?>
