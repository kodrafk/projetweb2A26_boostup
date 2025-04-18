<?php
require_once __DIR__ . '/../models/EventModel.php';

class EventController {
    private $model;
    private $viewsPath;

    public function __construct($db) {
        $this->model = new EventModel($db);
        $this->viewsPath = __DIR__ . '/../views/events/';
        
        if (!is_dir($this->viewsPath)) {
            die("Views directory not found: " . $this->viewsPath);
        }
    }

    public function index() {
        $events = $this->model->getAllEvents();
        $viewFile = $this->viewsPath . 'index.php';
        
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            die("View file not found: " . $viewFile);
        }
    }

    public function create() {
        $error = null;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => $_POST['title'],
                'event_date' => $_POST['event_date'],
                'location' => $_POST['location'],
                'link' => $_POST['link'] ?? null
            ];
            
            if ($this->model->createEvent($data)) {
                header("Location: index.php?action=index&success=created");
                exit();
            } else {
                $error = "Failed to create event";
            }
        }
        
        $viewFile = $this->viewsPath . 'create.php';
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            die("View file not found: " . $viewFile);
        }
    }

    public function edit($id) {
        $error = null;
        $event = $this->model->getEventById($id);
        
        if (!$event) {
            header("Location: index.php?action=index");
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => $_POST['title'],
                'event_date' => $_POST['event_date'],
                'location' => $_POST['location'],
                'link' => $_POST['link'] ?? null
            ];
            
            if ($this->model->updateEvent($id, $data)) {
                header("Location: index.php?action=index&success=updated");
                exit();
            } else {
                $error = "Failed to update event";
            }
        }
        
        $viewFile = $this->viewsPath . 'edit.php';
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            die("View file not found: " . $viewFile);
        }
    }

    public function delete($id) {
        $error = null;
        $event = $this->model->getEventById($id);
        
        if (!$event) {
            header("Location: index.php?action=index");
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->model->deleteEvent($id)) {
                header("Location: index.php?action=index&success=deleted");
                exit();
            } else {
                $error = "Failed to delete event";
            }
        }
        
        $viewFile = $this->viewsPath . 'delete.php';
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            die("View file not found: " . $viewFile);
        }
    }
}