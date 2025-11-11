<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel | PASSA</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
  <h2 class="mb-3">Admin Control Panel</h2>
  <p class="text-muted">Manage requests and users.</p>

  <ul class="nav nav-tabs" id="adminTabs">
    <li class="nav-item">
      <button class="nav-link active" data-section="requests">Requests</button>
    </li>
    <li class="nav-item">
      <button class="nav-link" data-section="users">Users</button>
    </li>
  </ul>

  <!-- Requests List -->
  <div id="adminRequests" class="mt-3"></div>

  <!-- Users List -->
  <div id="adminUsers" class="mt-3 d-none"></div>
</div>

<script type="module">
import { initializeApp } from "https://www.gstatic.com/firebasejs/11.0.0/firebase-app.js";
import { getAuth, onAuthStateChanged } from "https://www.gstatic.com/firebasejs/11.0.0/firebase-auth.js";
import { getDatabase, ref, onValue, remove, update } from "https://www.gstatic.com/firebasejs/11.0.0/firebase-database.js";

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
const auth = getAuth();
const db = getDatabase(app);

onAuthStateChanged(auth, async (user) => {
  if (!user) return (window.location.href = "login.php");

  const userRef = ref(db, "users/" + user.uid);
  onValue(userRef, snap => {
    const data = snap.val();
    if (!data || data.role !== "admin") {
      alert("Access Denied. Admins only.");
      window.location.href = "home.php";
    }
  });
});

// Load requests
const reqRef = ref(db, "requests");
onValue(reqRef, snap => {
  const container = document.getElementById("adminRequests");
  container.innerHTML = "";

  const requests = snap.val();
  if (!requests) return container.innerHTML = "<p>No requests found.</p>";

  Object.entries(requests).forEach(([id, req]) => {
    container.innerHTML += `
      <div class="card mb-3">
        <div class="card-body">
          <h5>${req.title}</h5>
          <p>${req.description}</p>
          <small>By: ${req.name} (${req.email})</small>
          <br><br>
          <button class="btn btn-danger btn-sm" data-delete="${id}">Delete</button>
        </div>
      </div>
    `;
  });

  document.querySelectorAll("[data-delete]").forEach(btn => {
    btn.onclick = () => remove(ref(db, "requests/" + btn.dataset.delete));
  });
});

// Load users
const usersRef = ref(db, "users");
onValue(usersRef, snap => {
  const container = document.getElementById("adminUsers");
  container.innerHTML = "";

  const users = snap.val();
  if (!users) return container.innerHTML = "<p>No users found.</p>";

  Object.entries(users).forEach(([id, u]) => {
    container.innerHTML += `
      <div class="card mb-2 p-2">
        <b>${u.name}</b> — ${u.email}<br>
        Role: ${u.role || "user"}
        <button class="btn btn-outline-primary btn-sm mt-2" data-promote="${id}">Promote to Admin</button>
      </div>
    `;
  });

  document.querySelectorAll("[data-promote]").forEach(btn => {
    btn.onclick = () => update(ref(db, "users/" + btn.dataset.promote), { role: "admin" });
  });
});

// Tab Switch
document.querySelectorAll("#adminTabs .nav-link").forEach(btn => {
  btn.addEventListener("click", () => {
    document.getElementById("adminRequests").classList.toggle("d-none", btn.dataset.section !== "requests");
    document.getElementById("adminUsers").classList.toggle("d-none", btn.dataset.section !== "users");
    document.querySelectorAll("#adminTabs .nav-link").forEach(n => n.classList.remove("active"));
    btn.classList.add("active");
  });
});
</script>

</body>
</html>
