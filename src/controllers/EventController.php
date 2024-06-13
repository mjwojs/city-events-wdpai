<?php

require_once 'AppController.php';
require_once 'src/repository/EventRepository.php';
require_once 'src/repository/UserRepository.php';

class EventController extends AppController {
    private $eventRepository;
    private $userRepository;

    public function __construct() {
        parent::__construct();
        $this->eventRepository = new EventRepository();
        $this->userRepository = new UserRepository();
    }

    public function dashboard() {
        $user = $this->userRepository->find($_SESSION['user']);
        $projects = $this->eventRepository->getAllProjects();
        $this->render('dashboard', ['projects' => $projects, 'user' => $user]);
    }

    public function addEvent() {
        if ($this->isPost()) {
            $title = $_POST['title'];
            $description = $_POST['description'];
            $location = $_POST['location'];
            $date = $_POST['date'];
            $emails = explode(',', $_POST['emails']); // Assuming emails are provided as a comma-separated string
            $creatorId = $_SESSION['user'];

            $eventId = $this->eventRepository->addEvent($title, $description, $location, $date, $creatorId);

            foreach ($emails as $email) {
                $this->eventRepository->addEventAttendee($eventId, trim($email));
            }

            header('Location: /dashboard');
            exit();
        } else {
            $user = $this->userRepository->find($_SESSION['user']);
            $this->render('add-event', ['user' => $user]);
        }
    }
}
