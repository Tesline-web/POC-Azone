<?php
require_once MODELS_PATH . 'User.php';

class UsersController extends BaseController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            // Validation
            $errors = [];
            if (empty($username)) $errors[] = "Le nom d'utilisateur est requis";
            if (empty($email)) $errors[] = "L'email est requis";
            if (empty($password)) $errors[] = "Le mot de passe est requis";
            if ($password !== $confirmPassword) $errors[] = "Les mots de passe ne correspondent pas";

            if (empty($errors)) {
                if ($this->userModel->register($username, $email, $password)) {
                    $_SESSION['success'] = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
                    $this->redirect('users/login');
                } else {
                    $errors[] = "Une erreur est survenue lors de l'inscription";
                }
            }

            $this->render('users/register', ['errors' => $errors]);
        } else {
            $this->render('users/register');
        }
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = $this->userModel->login($email, $password);
            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $this->redirect('');
            } else {
                $this->render('users/login', ['error' => 'Email ou mot de passe incorrect']);
            }
        } else {
            $this->render('users/login');
        }
    }

    public function logout() {
        session_destroy();
        $this->redirect('');
    }

    public function profile() {
        $this->requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';

            if ($this->userModel->updateProfile($_SESSION['user_id'], $username, $email)) {
                $_SESSION['success'] = "Profil mis à jour avec succès";
            } else {
                $_SESSION['error'] = "Erreur lors de la mise à jour du profil";
            }
        }

        $user = $this->userModel->getById($_SESSION['user_id']);
        $this->render('users/profile', ['user' => $user]);
    }
}
