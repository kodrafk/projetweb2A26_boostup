
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation de mot de passe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../View/FrontOffice/assets/css/style.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="card-title text-center mb-4">Mot de passe oublié</h2>
                        
                        <?php if (isset($_GET['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <?php 
                                switch($_GET['error']) {
                                    case 'email_not_found': 
                                        echo "Si cet email existe dans notre système, vous recevrez un lien de réinitialisation.";
                                        break;
                                    case 'invalid_email':
                                        echo "Format d'email invalide.";
                                        break;
                                    default: 
                                        echo "Une erreur est survenue. Veuillez réessayer.";
                                }
                                ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($_GET['message']) && $_GET['message'] == 'reset_email_sent'): ?>
                            <div class="alert alert-success alert-dismissible fade show">
                                Un email de réinitialisation a été envoyé si l'adresse existe dans notre système.
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="/Users/controller/UserC.php?action=forgotPassword" id="forgotPasswordForm">
                            <div class="mb-3">
                                <label for="email" class="form-label">Adresse email</label>
                                <input type="email" 
                                       class="form-control" 
                                       id="email" 
                                       name="email" 
                                       required
                                       pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                                       title="Veuillez entrer une adresse email valide">
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    Envoyer le lien de réinitialisation
                                </button>
                            </div>
                        </form>

                        <div class="text-center mt-3">
                            <a href="login.php" class="text-decoration-none">
                                ← Retour à la connexion
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Validation basique côté client
    document.getElementById('forgotPasswordForm').addEventListener('submit', function(e) {
        const email = document.getElementById('email').value;
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            e.preventDefault();
            alert('Veuillez entrer une adresse email valide');
        }
    });
    </script>
</body>
</html>