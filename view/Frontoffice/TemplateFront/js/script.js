if (!window.projectFormKeyupValidator) {
    window.projectFormKeyupValidator = true;
  
    document.addEventListener("DOMContentLoaded", function () {
      const nomProjet = document.getElementById('nom_projet');
      const description = document.getElementById('description');
  
      // Fonction pour créer un message d'erreur sous un champ
      const setupErrorElement = (inputElement) => {
        const errorId = inputElement.id + '_error';
        let errorElement = document.getElementById(errorId);
  
        if (!errorElement) {
          errorElement = document.createElement('div');
          errorElement.id = errorId;
          errorElement.className = 'error-message';
          inputElement.insertAdjacentElement('afterend', errorElement);
        }
  
        return document.getElementById(errorId);
      };
  
      const nomProjetError = setupErrorElement(nomProjet);
      const descriptionError = setupErrorElement(description);
  
      // Fonction de validation
      const validateField = (value, rules) => {
        let isValid = true;
        let message = '✅ Correct';
  
        if (rules.required && value.trim() === '') {
          isValid = false;
          message = '❌ Champ obligatoire';
        } else if (rules.minLength && value.trim().length < rules.minLength) {
          isValid = false;
          message = `❌ Minimum ${rules.minLength} caractères`;
        } else if (rules.regex && !rules.regex.test(value)) {
          isValid = false;
          message = rules.customError || '❌ Format invalide';
        }
  
        return { isValid, message };
      };
  
      const rules = {
        nomProjet: { required: true, minLength: 3 },
        description: {
          required: true,
          minLength: 3,
          regex: /^[A-Za-z\s]+$/,
          customError: '❌ Lettres et espaces uniquement'
        }
      };
  
      nomProjet.addEventListener('keyup', () => {
        const result = validateField(nomProjet.value, rules.nomProjet);
        nomProjetError.textContent = result.message;
        nomProjetError.style.color = result.isValid ? 'green' : 'red';
      });
  
      description.addEventListener('keyup', () => {
        const result = validateField(description.value, rules.description);
        descriptionError.textContent = result.message;
        descriptionError.style.color = result.isValid ? 'green' : 'red';
      });
    });
  }
  