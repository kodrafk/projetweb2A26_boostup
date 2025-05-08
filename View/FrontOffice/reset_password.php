<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation de mot de passe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-center">Réinitialiser votre mot de passe</h2>
                    </div>
                    
                    <div class="card-body">
                        <?php if (isset($_GET['error'])): ?>
                            <div class="alert alert-danger">
                                <?php 
                                if ($_GET['error'] === 'password_too_short') {
                                    echo "Le mot de passe doit contenir au moins 8 caractères.";
                                } elseif ($_GET['error'] === 'password_mismatch') {
                                    echo "Les mots de passe ne correspondent pas.";
                                } elseif ($_GET['error'] === 'invalid_token') {
                                    echo "Lien invalide ou expiré. Veuillez demander un nouveau lien.";
                                } elseif ($_GET['error'] === 'update_failed') {
                                    echo "Échec de la mise à jour du mot de passe. Veuillez réessayer.";
                                } else {
                                    echo "Une erreur est survenue. Veuillez réessayer.";
                                }
                                ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="post" action="/Users/controller/UserC.php?action=resetPassword">
                            <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token'] ?? ''); ?>">
                            
                            <div class="mb-3">
                                <label for="new_password" class="form-label">Nouveau mot de passe (min 8 caractères)</label>
                                <input type="password" class="form-control" id="new_password" name="new_password" required minlength="8">
                            </div>
                            
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirmer le nouveau mot de passe</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required minlength="8">
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Changer le mot de passe</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>