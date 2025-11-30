<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard | PASSA</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/req.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark sticky-top" style="background-color: #2f2f2f;">
    <div class="container-fluid">
      <a class="navbar-brand" href="#"><i class="fa-solid fa-hand-holding-heart"></i> PASSA Admin</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link active" href="admin_dashboard.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="manage_users.php">Manage Users</a></li>
          <li class="nav-item"><button id="logoutBtnSmall" href="logout.php" class="btn btn-danger btn-sm ms-3">Logout</button></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="container mt-5 pt-4">
    <div class="text-center mb-4">
      <h2>Welcome, Admin!</h2>
      <p class="text-muted">View and manage all user activities.</p>
    </div>

    <!-- Overview -->
    <div class="row g-4 mb-5 text-center">
      <div class="col-md-3">
        <div class="card shadow-sm p-3">
          <i class="fa-solid fa-users fa-3x text-primary mb-2"></i>
          <h6>Total Users</h6>
          <h3 id="totalUsers">0</h3>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card shadow-sm p-3">
          <i class="fa-solid fa-box-open fa-3x text-success mb-2"></i>
          <h6>Total Posts</h6>
          <h3 id="totalPosts">0</h3>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card shadow-sm p-3">
          <i class="fa-solid fa-hand-holding-heart fa-3x text-warning mb-2"></i>
          <h6>Total Requests</h6>
          <h3 id="totalRequests">0</h3>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card shadow-sm p-3">
          <i class="fa-solid fa-repeat fa-3x text-danger mb-2"></i>
          <h6>Total Trades</h6>
          <h3 id="totalTrades">0</h3>
        </div>
      </div>
    </div>

    <!-- Accordion for Data Sections -->
    <div class="accordion" id="adminAccordion">

      <!-- Users -->
      <div class="accordion-item">
        <h2 class="accordion-header" id="headingUsers">
          <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseUsers">
            👥 All Users
          </button>
        </h2>
        <div id="collapseUsers" class="accordion-collapse collapse show" data-bs-parent="#adminAccordion">
          <div class="accordion-body">
            <div id="usersList" class="list-group"></div>
          </div>
        </div>
      </div>

      <!-- Posts -->
      <div class="accordion-item">
        <h2 class="accordion-header" id="headingPosts">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePosts">
            📦 Posted Items
          </button>
        </h2>
        <div id="collapsePosts" class="accordion-collapse collapse" data-bs-parent="#adminAccordion">
          <div class="accordion-body">
            <div id="postsList" class="list-group"></div>
          </div>
        </div>
      </div>

      <!-- Requests -->
      <div class="accordion-item">
        <h2 class="accordion-header" id="headingRequests">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRequests">
            📝 Requests
          </button>
        </h2>
        <div id="collapseRequests" class="accordion-collapse collapse" data-bs-parent="#adminAccordion">
          <div class="accordion-body">
            <div id="requestsList" class="list-group"></div>
          </div>
        </div>
      </div>

      <!-- Trades -->
      <div class="accordion-item">
        <h2 class="accordion-header" id="headingTrades">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTrades">
            🔄 Trades
          </button>
        </h2>
        <div id="collapseTrades" class="accordion-collapse collapse" data-bs-parent="#adminAccordion">
          <div class="accordion-body">
            <div id="tradesList" class="list-group"></div>
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- Details Modal -->
  <div class="modal fade" id="detailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="detailsModalLabel">Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div id="modalContent" class="row g-3"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Firebase & JS -->
<script type="module">
import { initializeApp } from "https://www.gstatic.com/firebasejs/11.0.0/firebase-app.js";
import { getAuth, signOut, onAuthStateChanged } from "https://www.gstatic.com/firebasejs/11.0.0/firebase-auth.js";
import { getDatabase, ref, onValue, remove } from "https://www.gstatic.com/firebasejs/11.0.0/firebase-database.js";

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

// Logout
document.getElementById("logoutBtnSmall").addEventListener("click", async () => {
  await signOut(auth);
  window.location.href = "../index.php";
});

// Check admin
onAuthStateChanged(auth, async (user) => {
  if (!user) {
    window.location.href = "../index.php";
    return;
  }

  onValue(ref(db, "users/" + user.uid), (snap) => {
    if (!snap.exists() || snap.val().isAdmin !== true) {
      window.location.href = "../home.php";
    }
  });

  startRealtimeListeners();
});


// 🔥 REALTIME LISTENERS FOR EVERYTHING
function startRealtimeListeners() {
  onValue(ref(db, "users"), (snap) => {
    document.getElementById("totalUsers").textContent = snap.exists()
      ? Object.keys(snap.val()).length
      : 0;

    renderList(snap, "usersList", "fullName", "email", "users");
  });

  onValue(ref(db, "items"), (snap) => {
    document.getElementById("totalPosts").textContent = snap.exists()
      ? Object.keys(snap.val()).length
      : 0;

    renderList(snap, "postsList", "name", "description", "items");
  });

  onValue(ref(db, "requests"), (snap) => {
    document.getElementById("totalRequests").textContent = snap.exists()
      ? Object.keys(snap.val()).length
      : 0;

    renderList(snap, "requestsList", "title", "description", "requests");
  });

  onValue(ref(db, "trades"), (snap) => {
    document.getElementById("totalTrades").textContent = snap.exists()
      ? Object.keys(snap.val()).length
      : 0;

    renderList(snap, "tradesList", "itemID", "details", "trades");
  });
}


// 🔥 Auto-render lists
function renderList(snapshot, listId, keyField, subField, dbPath) {
  const list = document.getElementById(listId);
  list.innerHTML = "";

  if (snapshot && snapshot.exists()) {
    Object.entries(snapshot.val()).forEach(([key, data]) => {
      const imageUrl =
        data.photoURL || data.imageURL || data.image || "https://via.placeholder.com/100";

      const item = document.createElement("div");
      item.className =
        "list-group-item d-flex justify-content-between align-items-center flex-wrap";
      item.style.cursor = "pointer";

      item.innerHTML = `
        <div class="d-flex align-items-center gap-3">
          <img src="${imageUrl}" class="rounded" style="width:60px;height:60px;object-fit:cover;">
          <div>
            <strong>${data[keyField] || "Unnamed"}</strong><br>
            <small>${data[subField] || "No details"}</small>
          </div>
        </div>
        <button class="btn btn-sm btn-danger btn-delete" data-path="${dbPath}/${key}">
          <i class="fa-solid fa-trash"></i>
        </button>
      `;

      item.querySelector("div.d-flex").addEventListener("click", () =>
        showDetailsModal(data, dbPath)
      );

      list.appendChild(item);
    });

    // Delete button logic
    list.querySelectorAll(".btn-delete").forEach((btn) => {
      btn.addEventListener("click", async (e) => {
        e.stopPropagation();
        if (!confirm("Are you sure you want to delete this?")) return;

        try {
          await remove(ref(db, btn.dataset.path));
          alert("Deleted successfully.");
        } catch (err) {
          alert("Delete failed: " + err.message);
        }
      });
    });
  } else {
    list.innerHTML = `<p class="text-muted">No data found.</p>`;
  }
}


// Modal
function showDetailsModal(data, type) {
  const modalTitle = document.getElementById("detailsModalLabel");
  const modalBody = document.getElementById("modalContent");

  modalTitle.textContent =
    type === "users"
      ? "User Details"
      : type === "items"
      ? "Posted Item Details"
      : type === "requests"
      ? "Request Details"
      : type === "trades"
      ? "Trade Details"
      : "Details";

  let content = "";

  for (const [key, value] of Object.entries(data)) {
    content += `
      <div class="col-12 col-md-6">
        <strong>${key}:</strong> <span>${value || "N/A"}</span>
      </div>
    `;
  }

  modalBody.innerHTML = content;
  new bootstrap.Modal(document.getElementById("detailsModal")).show();
}
</script>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
