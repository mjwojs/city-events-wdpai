<?php

require_once 'AppController.php';
require_once 'src/models/Event.php';
require_once 'src/repository/EventRepository.php';

class EventController extends AppController {
    private $eventRepository;

    public function __construct() {
        parent::__construct();
        $this->eventRepository = new EventRepository();
    }

    public function dashboard() {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit();
        }

        $events = $this->eventRepository->findAll();
        $this->render('dashboard', ['events' => $events, 'title' => 'DASHBOARD']);
    }

    public function addEvent() {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit();
        }

        if ($this->isPost()) {
            $title = $_POST['title'];
            $description = $_POST['description'];
            $location = $_POST['location'];
            $date = $_POST['date'];

            $event = new Event($title, $description, $location, $date);
            $this->eventRepository->save($event);

            header('Location: /dashboard');
            exit();
        } else {
            $this->render('add-event');
        }
    }
}
