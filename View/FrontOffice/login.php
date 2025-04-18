<?php
require_once __DIR__ . '/../../controller/UserC.php';
require_once __DIR__ . '/../../Model/User.php';
require_once __DIR__ . '/../../config.php';
ob_start();
// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_Add'])) {
    $firstName = htmlspecialchars(trim($_POST['firstName']));
    $lastName = htmlspecialchars(trim($_POST['lastName']));
    $numtel = htmlspecialchars(trim($_POST['numtel']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));
    $type = htmlspecialchars(trim($_POST['type']));

    // Hachage du mot de passe
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Créer un objet utilisateur
    $user = new User($email, $hashedPassword, $type, $numtel, $firstName, $lastName);

    // Appeler la méthode ajouterUser
    $userC = new UserC();
    try {
      $userC->ajouterUser($user);
      ob_end_clean(); // Nettoyer le buffer
      header("Location: login.php?success=1");
      exit();
  } catch (Exception $e) {
      $error = "Erreur lors de l'enregistrement : " . $e->getMessage();
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login / Sign Up</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <style>
    @import url('https://fonts.googleapis.com/css?family=Montserrat:400,800');

    * {
      box-sizing: border-box;
    }

    body {
  margin: 0;
  font-family: 'Montserrat', sans-serif;
  background: linear-gradient(120deg, #4e54c8, #8f94fb);
  color: #000;
  transition: 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100vh;
}
body.dark {
  background: linear-gradient(120deg, #1a1a2e, #16213e);
  color: white;
}

/* Canvas */
canvas {
  position: fixed;
  top: 0;
  left: 0;
  z-index: -1;
}

/* Theme Toggle */
.theme-toggle {
  position: absolute;
  top: 20px;
  right: 20px;
  font-size: 32px;
  cursor: pointer;
  z-index: 1000;
}

/* Auth Container */
.container {
  background: #fff;
  border-radius: 20px;
  box-shadow: 0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22);
  width: 900px;
  max-width: 100%;
  min-height: 600px;
  overflow: hidden;
  position: relative;
}
body.dark .container {
  background: #2e2e3a;
  color: white;
}
.form-container {
  position: absolute;
  top: 0;
  height: 100%;
  transition: all 0.6s ease-in-out;
}
.sign-in-container {
  left: 0;
  width: 50%;
  z-index: 2;
}
.sign-up-container {
  left: 0;
  width: 50%;
  opacity: 0;
  z-index: 1;
}
.container.right-panel-active .sign-up-container {
  transform: translateX(100%);
  opacity: 1;
  z-index: 5;
}
.container.right-panel-active .sign-in-container {
  transform: translateX(100%);
  opacity: 0;
  z-index: 1;
}

/* Forms */
form {
  background-color: transparent;
  display: flex;
  flex-direction: column;
  padding: 0 50px;
  justify-content: center;
  align-items: center;
  height: 100%;
  text-align: center;
}
input {
  background-color: #eee;
  border: none;
  padding: 12px 15px;
  margin: 8px 0;
  width: 100%;
  border-radius: 5px;
}
button {
  border-radius: 20px;
  border: 1px solid #4e54c8;
  background-color: #4e54c8;
  color: #fff;
  font-size: 14px;
  padding: 12px 45px;
  letter-spacing: 1px;
  cursor: pointer;
  transition: 0.3s ease-in-out;
}
button.ghost {
  background-color: transparent;
  border-color: #fff;
}

/* Overlay */
.overlay-container {
  position: absolute;
  top: 0;
  left: 50%;
  width: 50%;
  height: 100%;
  overflow: hidden;
  z-index: 100;
  transition: transform 0.6s ease-in-out;
}
.container.right-panel-active .overlay-container {
  transform: translateX(-100%);
}
.overlay {
  background: linear-gradient(120deg, #4e54c8, #8f94fb);
  color: #fff;
  position: relative;
  left: -100%;
  height: 100%;
  width: 200%;
  transform: translateX(0);
  transition: transform 0.6s ease-in-out;
}
.container.right-panel-active .overlay {
  transform: translateX(50%);
}
.overlay-panel {
  position: absolute;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  padding: 0 40px;
  text-align: center;
  top: 0;
  height: 100%;
  width: 50%;
}
.overlay-left {
  transform: translateX(10%);
  left: 0;
}
.overlay-right {
  right: 0;
  transform: translateX(0);
}

/* Role Selector */
.role-select {
  display: flex;
  justify-content: space-around;
  width: 100%;
  margin: 15px 0;
}
.role-option {
  display: flex;
  align-items: center;
  gap: 8px;
  cursor: pointer;
  font-weight: bold;
  background: #eee;
  padding: 8px 15px;
  border-radius: 20px;
  transition: all 0.3s ease;
}
.role-option:hover {
  background-color: #4e54c8;
  color: white;
}
.role-option input {
  display: none;
}

.error-message {
  color: red;
  font-size: 12px;
  margin-top: 5px;
  text-align: left;
}

  </style>
</head>

<body>
  <!-- Floating Particles Canvas -->
  <canvas id="particles"></canvas>

  <!-- Theme Toggle -->
  <div class="theme-toggle" onclick="toggleDarkMode()" id="themeIcon">☀️</div>

  <!-- Container -->
  <div class="container" id="container">
    <!-- Sign-Up Form -->
    <div class="form-container sign-up-container">
      <form id="signUpForm" method="POST" action="login.php">
        <h1>Create Account</h1>

        <?php if (isset($error)): ?>
          <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>

        <input type="text" id="firstName" name="firstName" placeholder="First Name" required />
        <div id="firstNameError" class="error-message"></div>

        <input type="text" id="lastName" name="lastName" placeholder="Last Name" required />
        <div id="lastNameError" class="error-message"></div>

        <input type="text" id="phone" name="numtel" placeholder="Phone Number" required />
        <div id="phoneError" class="error-message"></div>

        <input type="text" id="email" name="email" placeholder="Email" required />
        <div id="emailError" class="error-message"></div>

        <input type="password" id="password" name="password" placeholder="Password" required />
        <div id="passwordError" class="error-message"></div>

        <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" required />
        <div id="confirmPasswordError" class="error-message"></div>

        <!-- Combo box for selecting role -->
        <select id="role" name="type" required style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; margin-bottom: 10px;">
          <option value="" disabled selected>Choose Role</option>
          <option value="admin">👑 Admin</option>
          <option value="entrepreneur">🚀 Entrepreneur</option>
          <option value="investor">💼 Investor</option>
        </select>

        <button type="submit" name="submit_Add">Sign Up</button>
      </form>
    </div>

    <!-- Sign-In Form -->
    <div class="form-container sign-in-container">
      <form id="signInForm">
        <h1>Sign In</h1>
        <input type="email" placeholder="Email" required />
        <input type="password" placeholder="Password" required />
        <button type="submit">Sign In</button>
      </form>
    </div>

    <!-- Overlay Panels -->
    <div class="overlay-container">
      <div class="overlay">
        <div class="overlay-panel overlay-left">
          <h1>Welcome Back!</h1>
          <p>To keep connected, please login with your personal info</p>
          <button class="ghost" id="signIn">Sign In</button>
        </div>
        <div class="overlay-panel overlay-right">
          <h1>Hello, Friend!</h1>
          <p>Enter your details and start your journey with us</p>
          <button class="ghost" id="signUp">Sign Up</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    const container = document.getElementById("container");

    document.getElementById("signUp").addEventListener("click", () => {
      container.classList.add("right-panel-active");
    });

    document.getElementById("signIn").addEventListener("click", () => {
      container.classList.remove("right-panel-active");
    });

    // Dark mode toggle
    function toggleDarkMode() {
      const body = document.body;
      const icon = document.getElementById("themeIcon");
      body.classList.toggle('dark');
      icon.textContent = body.classList.contains('dark') ? '🌕' : '☀️';
    }

    // Particle animation
    const canvas = document.getElementById("particles");
    const ctx = canvas.getContext("2d");
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;

    let particles = [];
    for (let i = 0; i < 80; i++) {
      particles.push({
        x: Math.random() * canvas.width,
        y: Math.random() * canvas.height,
        r: Math.random() * 2 + 1,
        dx: (Math.random() - 0.5) * 0.5,
        dy: (Math.random() - 0.5) * 0.5,
      });
    }

    function animateParticles() {
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      particles.forEach(p => {
        ctx.beginPath();
        ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
        ctx.fillStyle = "rgba(255,255,255,0.6)";
        ctx.fill();

        p.x += p.dx;
        p.y += p.dy;

        if (p.x < 0 || p.x > canvas.width) p.dx *= -1;
        if (p.y < 0 || p.y > canvas.height) p.dy *= -1;
      });
      requestAnimationFrame(animateParticles);
    }
    animateParticles();

    // Form validation
    const signUpForm = document.getElementById('signUpForm');

    signUpForm.addEventListener('submit', function(event) {
      event.preventDefault();

      // Get inputs
      const firstName = document.getElementById('firstName');
      const lastName = document.getElementById('lastName');
      const phone = document.getElementById('phone');
      const email = document.getElementById('email');
      const password = document.getElementById('password');
      const confirmPassword = document.getElementById('confirmPassword');

      // Reset errors
      document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
      document.querySelectorAll('input, select').forEach(input => input.classList.remove('invalid'));

      let valid = true;

      if (!/^[a-zA-Z]+$/.test(firstName.value)) {
        document.getElementById('firstNameError').textContent = 'First name should contain only letters';
        firstName.classList.add('invalid');
        valid = false;
      }

      if (!/^[a-zA-Z]+$/.test(lastName.value)) {
        document.getElementById('lastNameError').textContent = 'Last name should contain only letters';
        lastName.classList.add('invalid');
        valid = false;
      }

      if (!/^\d{8}$/.test(phone.value)) {
        document.getElementById('phoneError').textContent = 'Phone number should be 8 digits';
        phone.classList.add('invalid');
        valid = false;
      }

      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email.value)) {
        document.getElementById('emailError').textContent = 'Enter a valid email';
        email.classList.add('invalid');
        valid = false;
      }

      if (password.value.length < 6) {
        document.getElementById('passwordError').textContent = 'Password should be at least 6 characters';
        password.classList.add('invalid');
        valid = false;
      }
      if (password.value !== confirmPassword.value) {
    document.getElementById('confirmPasswordError').textContent = 'Passwords do not match';
    confirmPassword.classList.add('invalid');
    valid = false;
  }

  if (valid) this.submit();
    });
  </script>
</body>
</html>