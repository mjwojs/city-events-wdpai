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
        try {
            $user = $this->userRepository->find($_SESSION['user']);
            $projects = $this->eventRepository->getAllProjectsForUser($user->getId());
            $this->render('dashboard', ['projects' => $projects, 'user' => $user]);
        } catch (Exception $e) {
            error_log('Error in dashboard: ' . $e->getMessage());
            $this->render('dashboard', ['projects' => [], 'user' => null]);
        }
    }

    public function addEvent() {
        if ($this->isPost()) {
            try {
                $title = $_POST['title'];
                $description = $_POST['description'];
                $location = $_POST['location'];
                $date = $_POST['date'];
                $emails = explode(',', $_POST['emails']);
                $creatorId = $_SESSION['user'];

                $eventId = $this->eventRepository->addEvent($title, $description, $location, $date, $creatorId, $emails);

                header('Location: /dashboard');
                exit();
            } catch (Exception $e) {
                error_log('Error adding event: ' . $e->getMessage());
                $this->render('add-event', ['user' => $this->userRepository->find($_SESSION['user']), 'message' => 'Error adding event.']);
            }
        } else {
            $user = $this->userRepository->find($_SESSION['user']);
            $this->render('add-event', ['user' => $user]);
        }
    }

    public function viewEvent($id) {
        try {
            $event = $this->eventRepository->getEventById($id);
            if ($event) {
                $this->render('view-event', ['event' => $event]);
            } else {
                header('Location: /dashboard');
                exit();
            }
        } catch (Exception $e) {
            error_log('Error fetching event: ' . $e->getMessage());
            header('Location: /dashboard');
            exit();
        }
    }
}
