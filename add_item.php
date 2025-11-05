<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Add Item | PASSA</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">

  <!-- Navbar -->
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

  <!-- Add Item Form -->
  <div class="container mt-5">
    <div class="card p-4 shadow-sm mx-auto" style="max-width:600px;">
      <h4 class="text-center mb-3">Add New Item</h4>

      <!-- ✅ Bootstrap Alert -->
      <div id="alertBox" class="alert d-none" role="alert"></div>

      <form id="itemForm">
        <div class="mb-3">
          <label class="form-label">Item Name</label>
          <input type="text" id="itemName" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Description</label>
          <textarea id="itemDescription" class="form-control" rows="3" required></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Item Image</label>
          <input type="file" id="itemImage" class="form-control" accept="image/*" required>
        </div>

        <!-- ✅ Open for Trade checkbox -->
        <div class="form-check mb-3">
          <input class="form-check-input" type="checkbox" id="openForTrade">
          <label class="form-check-label" for="openForTrade">Open for Trade</label>
        </div>

        <button type="submit" class="btn btn-primary w-100">Upload Item</button>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

  <script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/11.0.0/firebase-app.js";
    import { getAuth, onAuthStateChanged, signOut } from "https://www.gstatic.com/firebasejs/11.0.0/firebase-auth.js";
    import { getDatabase, ref, push, set } from "https://www.gstatic.com/firebasejs/11.0.0/firebase-database.js";

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

    const form = document.getElementById("itemForm");
    const alertBox = document.getElementById("alertBox");

    // ✅ Cloudinary Config
    const CLOUD_NAME = "dgaidhktu"; 
    const UPLOAD_PRESET = "images";

    onAuthStateChanged(auth, (user) => {
      if (!user) return (window.location.href = "login.php");

      form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const file = document.getElementById("itemImage").files[0];
        const name = document.getElementById("itemName").value.trim();
        const description = document.getElementById("itemDescription").value.trim();
        const openForTrade = document.getElementById("openForTrade").checked;

        if (!file) return showAlert("Please upload an image!", "warning");

        try {
          // Upload to Cloudinary
          const formData = new FormData();
          formData.append("file", file);
          formData.append("upload_preset", UPLOAD_PRESET);

          const res = await fetch(`https://api.cloudinary.com/v1_1/${CLOUD_NAME}/image/upload`, {
            method: "POST",
            body: formData,
          });

          const data = await res.json();
          if (!data.secure_url) throw new Error("Cloudinary upload failed.");

          // Save item to Firebase
          const newItemRef = push(ref(db, "items"));
          await set(newItemRef, {
            name,
            description,
            photoURL: data.secure_url,
            userId: user.uid,
            username: user.displayName || "Anonymous",
            openForTrade,
            timestamp: new Date().toISOString()
          });

          showAlert("✅ Item uploaded successfully! Redirecting...", "success");
          form.reset();

          setTimeout(() => {
            window.location.href = "home.php";
          }, 1500);

        } catch (err) {
          console.error(err);
          showAlert("❌ Failed to upload item. Please try again.", "danger");
        }
      });

      //  Logout
      document.getElementById("logoutBtn").addEventListener("click", async () => {
        await signOut(auth);
        window.location.href = "index.php";
      });
    });

    //  Bootstrap Alert Helper
    function showAlert(message, type = "info") {
      alertBox.className = `alert alert-${type}`;
      alertBox.textContent = message;
      alertBox.classList.remove("d-none");
      setTimeout(() => alertBox.classList.add("d-none"), 4000);
    }
  </script>
</body>
</html>
