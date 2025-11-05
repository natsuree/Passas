<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Edit Profile | PASSA</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">

  <nav class="navbar navbar-expand-lg navbar-dark sticky-top" style="background-color: #2f2f2f;">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center fw-semibold" href="home.php">
      <img src="image/logo.png" alt="PASSA Logo" width="175" height="55" class="me-2">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link active" href="home.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="add_item.php">Add Item</a></li>
        <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
        <li class="nav-item"><a class="nav-link text-danger" href="#" id="logoutBtn">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

  <div class="container mt-5">
    <div class="card shadow p-4 mx-auto" style="max-width: 600px;">
      <h3 class="text-center mb-4 fw-semibold">Edit Profile</h3>
      <form id="editForm">
        <div class="text-center mb-3">
          <img id="profilePreview" src="https://via.placeholder.com/120" class="rounded-circle border mb-2" width="120" height="120" alt="Profile Image">
          <input type="file" id="photoInput" class="form-control mt-2" accept="image/*">
        </div>

        <div class="mb-3">
          <label for="fullName" class="form-label">Full Name</label>
          <input type="text" class="form-control" id="fullName" required>
        </div>

        <div class="mb-3">
          <label for="birthDate" class="form-label">Birthdate</label>
          <input type="date" class="form-control" id="birthDate" required>
        </div>

        <div class="mb-3">
          <label for="gender" class="form-label">Gender</label>
          <select class="form-select" id="gender" required>
            <option value="">Select Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
          </select>
        </div>

        <div class="mb-3">
          <label for="address" class="form-label">Address</label>
          <input type="text" class="form-control" id="address" required>
        </div>

        <div class="mb-3">
          <label for="contact" class="form-label">Contact Number</label>
          <input type="text" class="form-control" id="contact" required>
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">New Password (Optional)</label>
          <input type="password" class="form-control" id="password" placeholder="Leave blank to keep current password">
        </div>

        <button type="submit" class="btn btn-primary w-100">Save Changes</button>
      </form>

      <p id="status" class="text-center mt-3 text-muted"></p>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

  <script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/11.0.0/firebase-app.js";
    import { getAuth, onAuthStateChanged, signOut, updatePassword } from "https://www.gstatic.com/firebasejs/11.0.0/firebase-auth.js";
    import { getDatabase, ref, get, update } from "https://www.gstatic.com/firebasejs/11.0.0/firebase-database.js";

    const firebaseConfig = {
      apiKey: "AIzaSyAq6TIgqizXPlSs8fw5EUy7DVexM6MlyxQ",
      authDomain: "soft-engr.firebaseapp.com",
      databaseURL: "https://soft-engr-default-rtdb.firebaseio.com",
      projectId: "soft-engr",
      storageBucket: "soft-engr.firebasestorage.app",
      messagingSenderId: "623763613209",
      appId: "1:623763613209:web:33152fe31ad0b256db6c88"
    };

    const app = initializeApp(firebaseConfig);
    const auth = getAuth(app);
    const db = getDatabase(app);

    const CLOUDINARY_UPLOAD_PRESET = "images";
    const CLOUDINARY_CLOUD_NAME = "dgaidhktu";

    const photoInput = document.getElementById("photoInput");
    const profilePreview = document.getElementById("profilePreview");
    const status = document.getElementById("status");

    let uploadedPhotoURL = "";

    // preview photo before upload
    photoInput.addEventListener("change", (e) => {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = () => (profilePreview.src = reader.result);
        reader.readAsDataURL(file);
      }
    });

    // load existing data
    onAuthStateChanged(auth, async (user) => {
      if (!user) return (window.location.href = "login.php");

      const userRef = ref(db, "users/" + user.uid);
      const snap = await get(userRef);
      if (snap.exists()) {
        const data = snap.val();
        document.getElementById("fullName").value = data.fullName || "";
        document.getElementById("birthDate").value = data.birthDate || "";
        document.getElementById("gender").value = data.gender || "";
        document.getElementById("address").value = data.address || "";
        document.getElementById("contact").value = data.contact || "";
        profilePreview.src = data.photoURL || "https://via.placeholder.com/120";
        uploadedPhotoURL = data.photoURL || "";
      }

      document.getElementById("editForm").addEventListener("submit", async (e) => {
        e.preventDefault();
        status.textContent = "⏳ Updating...";

        const fullName = document.getElementById("fullName").value.trim();
        const birthDate = document.getElementById("birthDate").value;
        const gender = document.getElementById("gender").value;
        const address = document.getElementById("address").value.trim();
        const contact = document.getElementById("contact").value.trim();
        const newPassword = document.getElementById("password").value.trim();

        // upload to Cloudinary if new photo chosen
        const file = photoInput.files[0];
        if (file) {
          const formData = new FormData();
          formData.append("file", file);
          formData.append("upload_preset", CLOUDINARY_UPLOAD_PRESET);
          const res = await fetch(`https://api.cloudinary.com/v1_1/${CLOUDINARY_CLOUD_NAME}/image/upload`, {
            method: "POST",
            body: formData
          });
          const data = await res.json();
          uploadedPhotoURL = data.secure_url;
        }

        const updates = { fullName, birthDate, gender, address, contact, photoURL: uploadedPhotoURL };

        try {
          await update(ref(db, "users/" + user.uid), updates);
          if (newPassword) await updatePassword(user, newPassword);
          status.textContent = "✅ Profile updated successfully!";
          setTimeout(() => (window.location.href = "profile.php"), 1200);
        } catch (err) {
          console.error(err);
          status.textContent = "❌ " + err.message;
        }
      });
    });

    document.getElementById("logoutBtn").addEventListener("click", async () => {
      await signOut(auth);
      window.location.href = "index.php";
    });
  </script>
</body>
</html>
