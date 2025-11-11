<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Users | PASSA Admin</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/req.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark sticky-top" style="background-color: #2f2f2f;">
    <div class="container-fluid">
      <a class="navbar-brand" href="admin_dashboard.php"><i class="fa-solid fa-hand-holding-heart"></i> PASSA Admin</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link active" href="manage_users.php">Manage Users</a></li>
          <li class="nav-item"><button id="logoutBtnSmall" class="btn btn-danger btn-sm ms-3">Logout</button></li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container mt-5">
    <div class="text-center mb-4">
      <h2>Manage Users</h2>
      <p class="text-muted">View, promote, or remove users.</p>
    </div>

    <div class="table-responsive shadow-sm rounded">
      <table class="table table-hover align-middle">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="usersTable">
          <tr><td colspan="5" class="text-center text-muted">Loading...</td></tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Firebase -->
  <script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/11.0.0/firebase-app.js";
    import { getAuth, signOut, onAuthStateChanged } from "https://www.gstatic.com/firebasejs/11.0.0/firebase-auth.js";
    import { getDatabase, ref, get, update, remove } from "https://www.gstatic.com/firebasejs/11.0.0/firebase-database.js";

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

    const logoutBtn = document.getElementById("logoutBtnSmall");
    logoutBtn.addEventListener("click", async () => {
      await signOut(auth);
      window.location.href = "../index.php";
    });

    onAuthStateChanged(auth, async (user) => {
      if (!user) {
        window.location.href = "../index.php";
        return;
      }

      const snap = await get(ref(db, "users/" + user.uid));
      if (!snap.exists() || snap.val().isAdmin !== true) {
        window.location.href = "../home.php";
        return;
      }

      loadUsers();
    });

    async function loadUsers() {
      const table = document.getElementById("usersTable");
      const snapshot = await get(ref(db, "users"));

      if (!snapshot.exists()) {
        table.innerHTML = `<tr><td colspan="5" class="text-center text-muted">No users found.</td></tr>`;
        return;
      }

      const users = snapshot.val();
      let html = "";
      let i = 1;

      Object.entries(users).forEach(([uid, data]) => {
        const isAdmin = data.isAdmin === true;
        html += `
          <tr>
            <td>${i++}</td>
            <td>${data.fullName || "Unknown"}</td>
            <td>${data.email || "No email"}</td>
            <td>
              <span class="badge ${isAdmin ? 'bg-primary' : 'bg-secondary'}">
                ${isAdmin ? 'Admin' : 'User'}
              </span>
            </td>
            <td>
              ${isAdmin
                ? `<button class="btn btn-sm btn-warning" onclick="changeRole('${uid}', false)">Demote</button>`
                : `<button class="btn btn-sm btn-success" onclick="changeRole('${uid}', true)">Promote</button>`}
              <button class="btn btn-sm btn-danger ms-2" onclick="deleteUser('${uid}')">
                <i class="fa-solid fa-trash"></i>
              </button>
            </td>
          </tr>`;
      });

      table.innerHTML = html;
    }

    window.changeRole = async (uid, makeAdmin) => {
      if (confirm(`Are you sure you want to ${makeAdmin ? 'promote' : 'demote'} this user?`)) {
        await update(ref(db, `users/${uid}`), { isAdmin: makeAdmin });
        loadUsers();
      }
    };

    window.deleteUser = async (uid) => {
      if (confirm("Are you sure you want to delete this user? This cannot be undone.")) {
        await remove(ref(db, `users/${uid}`));
        loadUsers();
      }
    };
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
