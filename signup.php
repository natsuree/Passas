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
      <div class="mb-3">
        <label class="form-label"><i class="fa fa-user icon"></i>Full Name</label>
        <input type="text" id="fullName" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label"><i class="fa fa-envelope icon"></i>Email</label>
        <input type="email" id="email" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label"><i class="fa fa-lock icon"></i>Password</label>
        <input type="password" id="password" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label"><i class="fa fa-venus-mars icon"></i>Gender</label>
        <select id="gender" class="form-select" required>
          <option value="" disabled selected>Select gender</option>
          <option value="Male">Male</option>
          <option value="Female">Female</option>
          <option value="Other">Other</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label"><i class="fa fa-hourglass icon"></i>Age</label>
        <input type="number" id="age" class="form-control" required min="1">
      </div>

      <div class="mb-3">
        <label class="form-label"><i class="fa fa-calendar icon"></i>Birth Date</label>
        <input type="date" id="birthDate" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label"><i class="fa fa-map-marker-alt icon"></i>Address</label>
        <input type="text" id="address" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label"><i class="fa fa-phone icon"></i>Contact Number</label>
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
        message.textContent = "Signup successful! Redirecting...";

        setTimeout(() => {
          window.location.href = "login.php";
        }, 2000);

      } catch (error) {
        console.error(error);
        message.textContent = error.message;
      }
    });
  </script>

</body>
</html>
