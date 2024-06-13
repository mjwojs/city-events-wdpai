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
        }

        $events = $this->eventRepository->findAll();
        $this->render('dashboard', ['events' => $events, 'title' => 'DASHBOARD']);
    }
}
