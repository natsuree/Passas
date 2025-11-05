<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up | PASSA</title>

  <!-- Bootstrap & Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="css/signup.css">
</head>
<body>

  <div class="signup-card">
    <h3 class="text-center mb-4">Create an Account</h3>

    <form id="signupForm">
      <!-- Full Name -->
      <div class="mb-3">
        <i class="fa fa-user icon"></i>
        <label class="form-label">Full Name</label>
        <input type="text" id="fullName" class="form-control" required>
      </div>

      <!-- Email -->
      <div class="mb-3">
        <i class="fa fa-envelope icon"></i>
        <label class="form-label">Email</label>
        <input type="email" id="email" class="form-control" required>
      </div>

      <!-- Password -->
      <div class="mb-3">
        <i class="fa fa-lock icon"></i>
        <label class="form-label">Password</label>
        <input type="password" id="password" class="form-control" required>
      </div>

      <!-- Gender -->
      <div class="mb-3">
        <i class="fa fa-venus-mars icon"></i>
        <label class="form-label">Gender</label>
        <select id="gender" class="form-select" required>
          <option value="" disabled selected>Select gender</option>
          <option value="Male">Male</option>
          <option value="Female">Female</option>
          <option value="Other">Other</option>
        </select>
      </div>

      <!-- Age -->
      <div class="mb-3">
        <i class="fa fa-hourglass icon"></i>
        <label class="form-label">Age</label>
        <input type="number" id="age" class="form-control" required min="1">
      </div>

      <!-- Birth Date -->
      <div class="mb-3">
        <i class="fa fa-calendar icon"></i>
        <label class="form-label">Birth Date</label>
        <input type="date" id="birthDate" class="form-control" required>
      </div>

      <!-- Address -->
      <div class="mb-3">
        <i class="fa fa-map-marker-alt icon"></i>
        <label class="form-label">Address</label>
        <input type="text" id="address" class="form-control" required>
      </div>

      <!-- Contact -->
      <div class="mb-3">
        <i class="fa fa-phone icon"></i>
        <label class="form-label">Contact Number</label>
        <input type="text" id="contact" class="form-control" required>
      </div>

      <button type="submit" class="btn btn-primary w-100 mt-2">Sign Up</button>
      <p class="text-center mt-3">
        Already have an account? <a href="login.php">Login here</a>
      </p>
    </form>

    <div id="message" class="text-center mt-3 text-danger"></div>
  </div>

  <!-- Firebase Signup Logic -->
  <script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/11.0.0/firebase-app.js";
    import { getAuth, createUserWithEmailAndPassword, updateProfile } from "https://www.gstatic.com/firebasejs/11.0.0/firebase-auth.js";
    import { getDatabase, ref, set } from "https://www.gstatic.com/firebasejs/11.0.0/firebase-database.js";

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

    const form = document.getElementById("signupForm");
    const message = document.getElementById("message");

    form.addEventListener("submit", async (e) => {
      e.preventDefault();

      const fullName = document.getElementById("fullName").value.trim();
      const email = document.getElementById("email").value.trim();
      const password = document.getElementById("password").value;
      const gender = document.getElementById("gender").value;
      const age = document.getElementById("age").value;
      const birthDate = document.getElementById("birthDate").value;
      const address = document.getElementById("address").value.trim();
      const contact = document.getElementById("contact").value.trim();

      try {
        const userCredential = await createUserWithEmailAndPassword(auth, email, password);
        const user = userCredential.user;

        await updateProfile(user, { displayName: fullName });

        // Save to Realtime Database
        await set(ref(db, "users/" + user.uid), {
          fullName,
          email,
          gender,
          age,
          birthDate,
          address,
          contact,
          uid: user.uid
        });

        message.classList.remove("text-danger");
        message.classList.add("text-success");
        message.textContent = "Signup successful!";

        setTimeout(() => {
          window.location.href = "login.php";
        }, 2000);

      } catch (error) {
        console.error(error);
        message.textContent = "" + error.message;
      }
    });
  </script>

</body>
</html>
