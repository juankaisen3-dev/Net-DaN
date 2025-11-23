<?php
class AuthController {
    private $conn;
    private $user;

    public function __construct($db) {
        $this->conn = $db;
        $this->user = new User($db);
    }

    public function register($data) {
        $this->user->email = $data['email'];
        $this->user->password = $data['password'];
        $this->user->full_name = $data['full_name'];

        if($this->user->emailExists()) {
            return ["success" => false, "message" => "Email déjà utilisé"];
        }

        if($this->user->register()) {
            $_SESSION['user_id'] = $this->user->id;
            $_SESSION['user_email'] = $this->user->email;
            $_SESSION['user_name'] = $this->user->full_name;
            $_SESSION['subscription'] = $this->user->subscription;

            return ["success" => true, "message" => "Inscription réussie"];
        }
        return ["success" => false, "message" => "Erreur lors de l'inscription"];
    }

    public function login($data) {
        $this->user->email = $data['email'];
        $this->user->password = $data['password'];

        if($this->user->login()) {
            $_SESSION['user_id'] = $this->user->id;
            $_SESSION['user_email'] = $this->user->email;
            $_SESSION['user_name'] = $this->user->full_name;
            $_SESSION['subscription'] = $this->user->subscription;
            $_SESSION['avatar'] = $this->user->avatar;

            return ["success" => true, "message" => "Connexion réussie"];
        }
        return ["success" => false, "message" => "Email ou mot de passe incorrect"];
    }

    public function logout() {
        session_destroy();
        return ["success" => true, "message" => "Déconnexion réussie"];
    }

    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
}
?>
