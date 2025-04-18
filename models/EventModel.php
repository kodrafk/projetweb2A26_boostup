<?php
class EventModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllEvents() {
        $query = "SELECT * FROM events ORDER BY event_date DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEventById($id) {
        $query = "SELECT * FROM events WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createEvent($data) {
        $query = "INSERT INTO events (title, event_date, location, link) VALUES (:title, :event_date, :location, :link)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }

    public function updateEvent($id, $data) {
        $query = "UPDATE events SET title = :title, event_date = :event_date, location = :location, link = :link WHERE id = :id";
        $data['id'] = $id;
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }

    public function deleteEvent($id) {
        $query = "DELETE FROM events WHERE id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id]);
    }
}
?>