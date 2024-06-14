<?php

require_once 'src/controllers/EventController.php';
require_once 'src/controllers/UserController.php';

session_start();

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);
$action = explode("/", $path)[0];
$id = explode("/", $path)[1] ?? null;

$eventController = new EventController();
$userController = new UserController();

switch($action) {
    case "dashboard":
        $eventController->dashboard();
        break;
    case "add-event":
        $eventController->addEvent();
        break;
    case "event":
        if ($id) {
            $eventController->viewEvent($id);
        } else {
            header('Location: /dashboard');
        }
        break;
    case "edit-event":
        if ($id) {
            $eventController->editEvent($id);
        } else {
            header('Location: /dashboard');
        }
        break;
    case "delete-event":
        if ($id) {
            $eventController->deleteEvent($id);
        } else {
            header('Location: /dashboard');
        }
        break;
    case "login":
        $userController->login();
        break;
    case "register":
        $userController->register();
        break;
    case "profile":
        $userController->profile();
        break;
    case "logout":
        $userController->logout();
        break;
    default:
        header('Location: /login');
        break;
}
