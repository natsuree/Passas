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

          <script>
          const toggle = document.querySelector(".toggle");
          const input = document.querySelector(".password");

          toggle.addEventListener("click", () => {
            if (input.type === "password") {
              input.type = "text";
              toggle.classList.replace("fa-eye", "fa-eye-slash");
            } else {
              input.type = "password";
              toggle.classList.replace("fa-eye-slash", "fa-eye");
            }
          });
        </script>
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
    import { getDatabase, ref, set, get, update } from "https://www.gstatic.com/firebasejs/11.0.0/firebase-database.js";

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

    // Email + Password login
    document.getElementById("loginForm").addEventListener("submit", async (e) => {
      e.preventDefault();

      const email = document.getElementById("email").value;
      const password = document.getElementById("password").value;

      try {
        const userCredential = await signInWithEmailAndPassword(auth, email, password);
        const user = userCredential.user;

        statusDiv.textContent = " Login successful!";
        setTimeout(() => (window.location.href = "home.php"), 1000);
      } catch (error) {
        console.error(error);
        statusDiv.textContent = " " + error.message;
      }
    });

    // Google login
const googleProvider = new GoogleAuthProvider();
document.getElementById("googleLoginBtn").addEventListener("click", async () => {
  try {
    const result = await signInWithPopup(auth, googleProvider);
    const user = result.user;

    const userRef = ref(db, "users/" + user.uid);
    const snapshot = await get(userRef);

    // ✅ Only create if user record doesn't exist
    if (!snapshot.exists()) {
      await set(userRef, {
        username: user.displayName || "User",
        email: user.email || "",
        photoURL: user.photoURL || "https://via.placeholder.com/120",
        address: "",
        contact: "",
        about: "No description provided.",
        uid: user.uid,
        createdAt: new Date().toISOString(),
      });
      console.log("✅ Created new user record in database.");
    } else {
      // ✅ If user exists, just refresh their Google info (don’t delete anything)
      await update(userRef, {
        email: user.email,
        photoURL: user.photoURL || snapshot.val().photoURL,
        username: user.displayName || snapshot.val().username,
      });
      console.log("✅ Existing user logged in via Google; data preserved.");
    }

    statusDiv.textContent = "Google login successful!";
    setTimeout(() => (window.location.href = "home.php"), 1000);
  } catch (error) {
    console.error(error);
    statusDiv.textContent = " " + error.message;
  }
});
  </script>
</body>
</html>
