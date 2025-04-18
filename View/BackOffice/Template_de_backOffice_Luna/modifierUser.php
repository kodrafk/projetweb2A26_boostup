<?php
// Initialisation des messages d'erreur
$errorMessages = [];

// Inclusion des fichiers nécessaires
include_once("../../../config.php");
require_once("../../../controller/UserC.php");
require_once("../../../model/User.php");

// Récupérer l'ID de l'utilisateur
$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($id) {
    $userC = new UserC();
    $user = $userC->getUserById($id);
} else {
    header('Location: /Users/View/BackOffice/Template_de_backOffice_Luna/index.php');
    exit();
}

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['firstName'];
    $lastName  = $_POST['lastName'];
    $email     = $_POST['email'];
    $password  = $_POST['password'];
    $type      = $_POST['type'];
    $numtel    = $_POST['numtel'];

    if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($type) || empty($numtel)) {
        $errorMessages[] = "Tous les champs sont requis.";
    }

    if (empty($errorMessages)) {
        $userC = new UserC();
        $updated = $userC->modifierUser($id, $firstName, $lastName, $email, $password, $type, $numtel);
        if ($updated) {
            header('Location: /Users/View/BackOffice/Template_de_backOffice_Luna/index.php');
            exit();
        } else {
            $errorMessages[] = "Une erreur s'est produite lors de la mise à jour.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Utilisateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(to right, #667eea, #764ba2);
            font-family: 'Segoe UI', sans-serif;
            padding: 40px 0;
        }
        .card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            padding: 30px;
        }
        .section-title {
            font-weight: bold;
            border-bottom: 2px solid #6c63ff;
            padding-bottom: 10px;
            margin-bottom: 20px;
            font-size: 22px;
        }
        .form-label {
            font-weight: 500;
        }
        .btn-success {
            background-color: #6c63ff;
            border-color: #6c63ff;
        }
        .btn-success:hover {
            background-color: #5548d9;
            border-color: #5548d9;
        }
        .btn-secondary:hover {
            background-color: #aaa;
            border-color: #aaa;
        }
        .error-message {
            color: red;
            font-size: 0.9em;
        }
        .invalid {
            border-color: red;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card mx-auto" style="max-width: 800px;">
        <h2 class="text-center mb-4">Modifier l'Utilisateur</h2>

        <form id="userForm" action="modifierUser.php?id=<?php echo $id; ?>" method="POST">
            <div class="form-section">
                <h5 class="section-title">Informations de l'Utilisateur</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="firstName" class="form-label">Prénom</label>
                        <input type="text" id="firstName" name="firstName" class="form-control" value="<?php echo isset($user['firstName']) ? $user['firstName'] : ''; ?>" required>
                        <div id="firstName_error" class="error-message"></div>
                    </div>

                    <div class="col-md-6">
                        <label for="lastName" class="form-label">Nom</label>
                        <input type="text" id="lastName" name="lastName" class="form-control" value="<?php echo isset($user['lastName']) ? $user['lastName'] : ''; ?>" required>
                        <div id="lastName_error" class="error-message"></div>
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" id="email" name="email" class="form-control" value="<?php echo isset($user['email']) ? $user['email'] : ''; ?>" required>
                        <div id="email_error" class="error-message"></div>
                    </div>

                    <div class="col-md-6">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" id="password" name="password" class="form-control" value="<?php echo isset($user['password']) ? $user['password'] : ''; ?>" required>
                        <div id="password_error" class="error-message"></div>
                    </div>

                    <div class="col-md-6">
                        <label for="type" class="form-label">Type</label>
                        <select id="type" name="type" class="form-control" required>
                            <option value="" disabled selected>Choisissez un type</option>
                            <option value="Admin" <?php echo (isset($user['type']) && $user['type'] === 'Admin') ? 'selected' : ''; ?>>Admin</option>
                            <option value="Entrepreneur" <?php echo (isset($user['type']) && $user['type'] === 'Entrepreneur') ? 'selected' : ''; ?>>Entrepreneur</option>
                            <option value="Investor" <?php echo (isset($user['type']) && $user['type'] === 'Investor') ? 'selected' : ''; ?>>Investor</option>
                        </select>
                        <div id="type_error" class="error-message"></div>
                    </div>

                    <div class="col-md-6">
                        <label for="numtel" class="form-label">Numéro de téléphone</label>
                        <input type="text" id="numtel" name="numtel" class="form-control" value="<?php echo isset($user['numtel']) ? $user['numtel'] : ''; ?>" required>
                        <div id="numtel_error" class="error-message"></div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Annuler</a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-1"></i> Enregistrer
                    </button>
                </div>
            </div>

            <input type="hidden" name="iduser" value="<?php echo $id; ?>">
        </form>
    </div>
</div>

<script>
    document.getElementById('userForm').addEventListener('submit', function (event) {
        event.preventDefault();

        let valid = true;

        // Réinitialiser les messages d'erreur
        document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
        document.querySelectorAll('.form-control').forEach(el => el.classList.remove('invalid'));

        // Validation des champs
        const firstName = document.getElementById('firstName');
        if (!/^[a-zA-Z]+$/.test(firstName.value)) {
            document.getElementById('firstName_error').textContent = 'Le prénom doit contenir uniquement des lettres.';
            firstName.classList.add('invalid');
            valid = false;
        }

        const lastName = document.getElementById('lastName');
        if (!/^[a-zA-Z]+$/.test(lastName.value)) {
            document.getElementById('lastName_error').textContent = 'Le nom doit contenir uniquement des lettres.';
            lastName.classList.add('invalid');
            valid = false;
        }

        const email = document.getElementById('email');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email.value)) {
            document.getElementById('email_error').textContent = 'Veuillez entrer une adresse email valide.';
            email.classList.add('invalid');
            valid = false;
        }

        const password = document.getElementById('password');
        if (password.value.length < 6) {
            document.getElementById('password_error').textContent = 'Le mot de passe doit contenir au moins 6 caractères.';
            password.classList.add('invalid');
            valid = false;
        }

        const type = document.getElementById('type');
        if (type.value.trim() === '') {
            document.getElementById('type_error').textContent = 'Le type est requis.';
            type.classList.add('invalid');
            valid = false;
        }

        const numtel = document.getElementById('numtel');
        if (!/^\d{8}$/.test(numtel.value)) {
            document.getElementById('numtel_error').textContent = 'Le numéro de téléphone doit contenir 8 chiffres.';
            numtel.classList.add('invalid');
            valid = false;
        }

        if (valid) {
            this.submit();
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>
</html>