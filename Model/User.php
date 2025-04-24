<?php
require_once(__DIR__ . '/../config.php');

class User {
    private ?int $iduser = null;
    private ?string $email = null;
    private ?string $password = null;
    private ?string $type = null;
    private ?string $numtel = null;
    private ?string $firstName = null;
    private ?string $lastName = null;

    public function __construct($id, $email, $password, $type, $numtel, $firstName, $lastName) {
        $this->iduser = $id;
        $this->email = $email;
        $this->password = $password;
        $this->type = $type;
        $this->numtel = $numtel;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    // Getters
    public function getIdUser() {
        return $this->iduser;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getType() {
        return $this->type;
    }

    public function getNumTel() {
        return $this->numtel;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    // Setters
    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    public function setPassword($password) {
        $this->password = $password;
        return $this;
    }

    public function setType($type) {
        $this->type = $type;
        return $this;
    }

    public function setNumTel($numtel) {
        $this->numtel = $numtel;
        return $this;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
        return $this;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;
        return $this;
    }
    public function getUserByEmail($email) {
        $db = config::getConnexion();
        $stmt = $db->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->execute([$email]);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        var_dump($userData); // Vérifiez les données récupérées
        if ($userData) {
            return new User(
                $userData['iduser'],
                $userData['email'],
                $userData['password'],
                $userData['type'],
                $userData['numtel'],
                $userData['firstName'],
                $userData['lastName']
            );
        }
        
        echo "Aucun utilisateur trouvé avec cet email.";
        return false;
    }
}
