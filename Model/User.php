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
    private $signup_time;
    private $otp;
    private $status;
    private $token;
    private $token_expire;
    
  // Constructeur de la classe User
  public function __construct($iduser, $email, $password, $type, $numtel, $firstName, $lastName, $signup_time, $otp, $status,  $token = null, $token_expire = null) {
    $this->iduser = $iduser;
    $this->email = $email;
    $this->password = $password;
    $this->type = $type;
    $this->numtel = $numtel;
    $this->firstName = $firstName;
    $this->lastName = $lastName;
    $this->signup_time = $signup_time;
    $this->otp = $otp;
    $this->status = $status;
    $this->token = $token;
    $this->token_expire = $token_expire;
}


// Getters pour récupérer les valeurs
public function getSignupTime() {
    return $this->signup_time;
}
public function getOtp() {
    return $this->otp;
}
public function getStatus() {
    return $this->status;
}

    
    /*public function __construct($id, $email, $password, $type, $numtel, $firstName, $lastName) {
        $this->iduser = $id;
        $this->email = $email;
        $this->password = $password;
        $this->type = $type;
        $this->numtel = $numtel;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }*/

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
    public function getToken() {
        return $this->token;
    }
    public function getTokenExpire() {
        return $this->token_expire;
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
   
    public function setToken(?string $token): self {
        $this->token = $token;
        return $this;
    }

    public function setTokenExpire(?string $token_expire): self {
        $this->token_expire = $token_expire;
        return $this;
    }
    public static function getUserByEmail($email) {
        $db = config::getConnexion();
        $stmt = $db->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->execute([$email]);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($userData) {
            return new User(
                $userData['iduser'],
                $userData['email'],
                $userData['password'],
                $userData['type'],
                $userData['numtel'],
                $userData['firstName'],
                $userData['lastName'],
                $userData['signup_time'] ?? null,
                $userData['otp'] ?? null,
                $userData['status'] ?? null,
                $userData['token'] ?? null,
                $userData['token_expire'] ?? null
            );
        }
    
        return false;
    }

   
}
