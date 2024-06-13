<?php

require_once 'src/controllers/AppController.php';
require_once 'src/controllers/UserController.php';
require_once 'src/controllers/EventController.php';

session_start();

$controller = new AppController();

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);
$action = explode("/", $path)[0];
$action = $action == null ? 'login' : $action;

switch ($action) {
    case "dashboard":
        $eventController = new EventController();
        $eventController->dashboard();
        break;
    case "register":
        $userController = new UserController();
        $userController->register();
        break;
    case "login":
        $userController = new UserController();
        $userController->login();
        break;
    case "logout":
        $userController = new UserController();
        $userController->logout();
        break;
    case "profile":
        $userController = new UserController();
        $userController->profile();
        break;
    case "update-profile-picture":
        $userController = new UserController();
        $userController->updateProfilePicture();
        break;
    case "add-event":
        $eventController = new EventController();
        $eventController->addEvent();
        break;
    default:
        $controller->render('404');
}
