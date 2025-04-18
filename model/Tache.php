<?php
require_once __DIR__ . '/../config/db.php';

class Tache {
    private ?int $id = null;
    private string $titre;
    private string $description;

    public function __construct(?int $id, string $titre, string $description) {
        $this->id = $id;
        $this->titre = $titre;
        $this->description = $description;
    }

    // Getters
    public function getId(): ?int {
        return $this->id;
    }

    public function getTitre(): string {
        return $this->titre;
    }

    public function getDescription(): string {
        return $this->description;
    }

    // Setters
    public function setTitre(string $titre): void {
        $this->titre = $titre;
    }

    public function setDescription(string $description): void {
        $this->description = $description;
    }

    // Static methods for database operations
    public static function getAll($pdo): array {
        $stmt = $pdo->query("SELECT * FROM tache");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($pdo, int $id): ?array {
        $stmt = $pdo->prepare("SELECT * FROM tache WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function insert($pdo, string $titre, string $description): void {
        $stmt = $pdo->prepare("INSERT INTO tache (titre, description) VALUES (?, ?)");
        $stmt->execute([$titre, $description]);
    }

    public static function update($pdo, int $id, string $titre, string $description): void {
        $stmt = $pdo->prepare("UPDATE tache SET titre = ?, description = ? WHERE id = ?");
        $stmt->execute([$titre, $description, $id]);
    }

    public static function delete($pdo, int $id): void {
        $stmt = $pdo->prepare("DELETE FROM tache WHERE id = ?");
        $stmt->execute([$id]);
    }
}

