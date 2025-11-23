<?php
include_once '../config/config.php';
include_once '../config/database.php';
include_once '../models/Video.php';

$database = new Database();
$db = $database->getConnection();
$video = new Video($db);

$method = $_SERVER['REQUEST_METHOD'];
$response = [];

switch($method) {
    case 'GET':
        if(isset($_GET['featured'])) {
            $stmt = $video->readFeatured();
        } elseif(isset($_GET['category'])) {
            $stmt = $video->readByCategory($_GET['category']);
        } elseif(isset($_GET['search'])) {
            $stmt = $video->search($_GET['search']);
        } else {
            $stmt = $video->read();
        }

        $videos = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $videos[] = $row;
        }
        $response = ["success" => true, "data" => $videos];
        break;

    default:
        $response = ["success" => false, "message" => "Méthode non autorisée"];
}

echo json_encode($response);
?>