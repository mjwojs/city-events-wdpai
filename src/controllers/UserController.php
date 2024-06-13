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
            $firstName = $_POST['first_name'];
            $lastName = $_POST['last_name'];
            $username = $_POST['username'];
            $city = $_POST['city'];

            $user = new User($email, $password, $firstName, $lastName, $username, $city);

            try {
                $this->userRepository->save($user);

                // Automatyczne logowanie po pomyślnej rejestracji
                $user = $this->userRepository->findByEmail($email);
                $_SESSION['user'] = $user->getId();

                header('Location: /dashboard');
                exit();
            } catch (PDOException $e) {
                if ($e->getCode() == 23505) { // 23505 jest kodem błędu dla zduplikowanego wpisu w PostgreSQL
                    $this->render('register', ['message' => 'Email is already taken!']);
                } else {
                    $this->render('register', ['message' => 'An error occurred. Please try again.']);
                }
            }
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
                exit();
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
        exit();
    }

    public function profile() {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit();
        }

        $user = $this->userRepository->find($_SESSION['user']);
        $this->render('profile', ['user' => $user]);
    }
}
