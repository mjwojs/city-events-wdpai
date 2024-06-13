<?php

require_once 'AppController.php';
require_once 'src/models/User.php';
require_once 'src/repository/UserRepository.php';

class UserController extends AppController {
    private $userRepository;

    public function __construct() {
        parent::__construct();
        $this->userRepository = new UserRepository();
    }

    public function register() {
        if ($this->isPost()) {
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

            $user = new User($email, $password);
            $this->userRepository->save($user);

            $this->render('login', ['message' => 'Successfully registered!']);
        } else {
            $this->render('register');
        }
    }

    public function login() {
        if ($this->isPost()) {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $user = $this->userRepository->findByEmail($email);

            if ($user && password_verify($password, $user->getPassword())) {
                $_SESSION['user'] = $user->getId();
                header('Location: /dashboard');
            } else {
                $this->render('login', ['message' => 'Invalid credentials!']);
            }
        } else {
            $this->render('login');
        }
    }

    public function logout() {
        session_destroy();
        header('Location: /login');
    }

    public function profile() {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
        }

        $user = $this->userRepository->find($_SESSION['user']);
        $this->render('profile', ['user' => $user]);
    }
}
