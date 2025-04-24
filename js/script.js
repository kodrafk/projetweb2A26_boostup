// Fonction pour afficher les messages d'erreur
function displayMessage(id, message, isError) {
    const element = document.getElementById(id + "_error");
    element.style.color = isError ? "red" : "green";
    element.textContent = message;
}

// Fonction pour valider le titre
function validateTitre() {
    const titreField = document.getElementById("titre");
    const titre = titreField.value.trim();
    if (titre.length < 3) {
        displayMessage("titre", "Le titre doit contenir au moins 3 caractères.", true);
        return false;
    }
    displayMessage("titre", "Correct", false);
    return true;
}

// Fonction pour valider le lien
function validateLien() {
    const lienField = document.getElementById("lien");
    const lien = lienField.value.trim();
    const urlPattern = /^(https?:\/\/)?([\w.-]+)+(:\d+)?(\/([\w/_.]*)?)?$/i;
    if (!urlPattern.test(lien)) {
        displayMessage("lien", "Lien invalide. Exemple : https://exemple.com", true);
        return false;
    }
    displayMessage("lien", "Correct", false);
    return true;
}

// Fonction pour valider la description
function validateDescription() {
    const descriptionField = document.getElementById("description");
    const description = descriptionField.value.trim();
    if (description.length < 10) {
        displayMessage("description", "La description doit contenir au moins 10 caractères.", true);
        return false;
    }
    displayMessage("description", "Correct", false);
    return true;
}

// Fonction principale pour valider le formulaire
function validerFormulaire(event) {
    event.preventDefault(); // Empêche l'envoi automatique du formulaire

    const isValid =
        validateTitre() &&
        validateLien() &&
        validateDescription();

    if (isValid) {
        alert("Formulaire soumis avec succès !");
        // Ici tu peux envoyer le formulaire ou effectuer une autre action
        // form.submit(); // Si tu veux soumettre le formulaire
    }
}
