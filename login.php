<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | PASSA</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="css/login.css">
</head>
<body class="bg-light">

  <div class="center container mt-5">
    <div class="card shadow-sm p-4 mx-auto" style="max-width: 450px;">
      <h3 class="text-center mb-4">Welcome Back</h3>

      <form id="loginForm">
        <div class="mb-3">
          <i class="fa fa-envelope icon"></i>
          <label class="form-label">Email</label>
          <input type="email" id="email" class="form-control" placeholder="Enter your email" required>
        </div>

        <div class="mb-3">
          <i class="fa fa-lock icon"></i>
          <label class="form-label">Password</label>
          <input type="password" id="password" class="form-control" placeholder="Enter password" required>
        </div>

        <button type="submit" class="btn btn-primary w-100 mt-2">Login</button>
      </form>

      <p class="text-center mt-3">
        Don't have an account? <a href="signup.php">Sign up here</a>
      </p>

      <div class="text-center mt-3">
        <button id="googleLoginBtn" class="btn btn-outline-danger w-100">
          <i class="bi bi-google me-2"></i>Sign in with Google
        </button>
      </div>

      <div id="status" class="text-center mt-3 text-muted"></div>
    </div>
  </div>

  <script type="module">
  import { initializeApp } from "https://www.gstatic.com/firebasejs/11.0.0/firebase-app.js";
  import {
    getAuth,
    signInWithEmailAndPassword,
    GoogleAuthProvider,
    signInWithPopup
  } from "https://www.gstatic.com/firebasejs/11.0.0/firebase-auth.js";
  import { getDatabase, ref, get, set } from "https://www.gstatic.com/firebasejs/11.0.0/firebase-database.js";

  const firebaseConfig = {
    apiKey: "AIzaSyAq6TIgqizXPlSs8fw5EUy7DVexM6MlyxQ",
    authDomain: "soft-engr.firebaseapp.com",
    projectId: "soft-engr",
    storageBucket: "soft-engr.firebasestorage.app",
    messagingSenderId: "623763613209",
    appId: "1:623763613209:web:33152fe31ad0b256db6c88",
    databaseURL: "https://soft-engr-default-rtdb.firebaseio.com"
  };

  const app = initializeApp(firebaseConfig);
  const auth = getAuth(app);
  const db = getDatabase(app);

  const statusDiv = document.getElementById("status");
  const loginForm = document.getElementById("loginForm");
  const googleBtn = document.getElementById("googleLoginBtn");

  // Helper to show status
  function showStatus(msg, isError = false) {
    statusDiv.textContent = msg;
    statusDiv.classList.toggle("text-danger", !!isError);
    statusDiv.classList.toggle("text-success", !isError);
  }

  // Email / Password login
  loginForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value;

    if (!email || !password) {
      showStatus("Please enter email and password.", true);
      return;
    }

    try {
      showStatus("Signing in...");
      const userCredential = await signInWithEmailAndPassword(auth, email, password);
      const user = userCredential.user;

      const userRef = ref(db, "users/" + user.uid);
      let snapshot = await get(userRef);

      // create DB record for new users
      if (!snapshot.exists()) {
        await set(userRef, {
          username: user.displayName || "User",
          email: user.email || email,
          photoURL: user.photoURL || "https://via.placeholder.com/120",
          isAdmin: false,
          uid: user.uid
        });
        snapshot = await get(userRef);
      }

      const isAdmin = snapshot.val()?.isAdmin === true;
      showStatus(isAdmin ? "Admin login successful." : "Login successful.");
      setTimeout(() => {
        window.location.href = isAdmin ? "admin/admin_dashboard.php" : "home.php";
      }, 800);
    } catch (err) {
      console.error(err);
      showStatus(err?.message || "Login failed.", true);
    }
  });

  // Google sign-in
  googleBtn.addEventListener("click", async () => {
    const provider = new GoogleAuthProvider();
    try {
      showStatus("Signing in with Google...");
      const result = await signInWithPopup(auth, provider);
      const user = result.user;

      const userRef = ref(db, "users/" + user.uid);
      let snapshot = await get(userRef);

      if (!snapshot.exists()) {
        await set(userRef, {
          username: user.displayName || "User",
          email: user.email,
          photoURL: user.photoURL || "https://via.placeholder.com/120",
          isAdmin: false,
          uid: user.uid
        });
        snapshot = await get(userRef);
      }

      const isAdmin = snapshot.val()?.isAdmin === true;
      showStatus(isAdmin ? "Admin login successful." : "Login successful.");
      setTimeout(() => {
        window.location.href = isAdmin ? "admin/admin_dashboard.php" : "home.php";
      }, 800);
    } catch (err) {
      console.error(err);
      showStatus(err?.message || "Google sign-in failed.", true);
    }
  });
  </script>
</body>
</html>
