// Vérification anti-double exécution
if (!window.projectFormValidator) {
    window.projectFormValidator = true;
  
    document.addEventListener("DOMContentLoaded", function() {
      // Fonction pour créer/gérer les messages d'erreur
      const setupErrorElement = (inputElement) => {
        const errorId = inputElement.id + '_error';
        let errorElement = document.getElementById(errorId);
        
        if (!errorElement) {
          errorElement = document.createElement('div');
          errorElement.id = errorId;
          errorElement.className = 'error-message';
          inputElement.insertAdjacentElement('afterend', errorElement);
        }
        return errorElement;
      };
  
      // Éléments du formulaire
      const elements = {
        nomProjet: document.getElementById('nom_projet'),
        description: document.getElementById('description'),
        dateDebut: document.getElementById('date_debut'),
        dateFin: document.getElementById('date_fin')
      };
  
      // Éléments d'erreur
      const errorElements = {
        nomProjet: setupErrorElement(elements.nomProjet),
        description: setupErrorElement(elements.description),
        dateDebut: setupErrorElement(elements.dateDebut),
        dateFin: setupErrorElement(elements.dateFin)
      };
  
      // Fonction de validation générique
      const validateField = (field, rules) => {
        const value = field.value.trim();
        let isValid = true;
        let message = '✅ Correct';
  
        if (rules.required && !value) {
          isValid = false;
          message = '❌ Champ obligatoire';
        } else if (rules.minLength && value.length < rules.minLength) {
          isValid = false;
          message = `❌ Minimum ${rules.minLength} caractères`;
        } else if (rules.regex && !rules.regex.test(value)) {
          isValid = false;
          message = rules.customError || '❌ Format invalide';
        }
  
        return { isValid, message };
      };
  
      // Règles de validation
      const validationRules = {
        nomProjet: { required: true, minLength: 3 },
        description: { 
          required: true, 
          minLength: 5, 
          regex: /^[A-Za-z\s]+$/,
          customError: '❌ Lettres et espaces uniquement (10+ caractères)'
        }
      };
  
      // Validation des dates
      const validateDates = () => {
        const start = elements.dateDebut.value ? new Date(elements.dateDebut.value) : null;
        const end = elements.dateFin.value ? new Date(elements.dateFin.value) : null;
  
        // Validation date début
        if (!start) {
          errorElements.dateDebut.textContent = '❌ Date requise';
          errorElements.dateDebut.style.color = 'red';
        } else {
          errorElements.dateDebut.textContent = '✅ Correct';
          errorElements.dateDebut.style.color = 'green';
        }
  
        // Validation date fin (UN SEUL MESSAGE)
        if (!end) {
          errorElements.dateFin.textContent = '❌ Date requise';
          errorElements.dateFin.style.color = 'red';
        } else if (start && end < start) {
          errorElements.dateFin.textContent = '❌ Doit être après la date début';
          errorElements.dateFin.style.color = 'red';
        } else {
          errorElements.dateFin.textContent = '✅ Correct';
          errorElements.dateFin.style.color = 'green';
        }
      };
  
      // Configuration des écouteurs
      const setupListeners = () => {
        // Validation standard
        elements.nomProjet.addEventListener('input', () => {
          const validation = validateField(elements.nomProjet, validationRules.nomProjet);
          errorElements.nomProjet.textContent = validation.message;
          errorElements.nomProjet.style.color = validation.isValid ? 'green' : 'red';
        });
  
        elements.description.addEventListener('input', () => {
          const validation = validateField(elements.description, validationRules.description);
          errorElements.description.textContent = validation.message;
          errorElements.description.style.color = validation.isValid ? 'green' : 'red';
        });
  
        // Validation des dates avec debounce
        let dateTimeout;
        const dateFields = [elements.dateDebut, elements.dateFin];
        
        dateFields.forEach(field => {
          field.addEventListener('input', () => {
            clearTimeout(dateTimeout);
            dateTimeout = setTimeout(validateDates, 300);
          });
        });
      };
  
      // Initialisation
      setupListeners();
    });
  }