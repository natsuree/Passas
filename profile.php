<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Profile | PASSA</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
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

  <!-- Profile Info -->
<div class="container mt-5">
  <div class="card shadow-sm mx-auto" style="max-width:950px;">
    <div class="row g-0 align-items-center">
      <!-- Left: avatar + quick stats -->
      <div class="col-12 col-md-4 text-center p-4 bg-dark text-light rounded-start">
        <div class="d-flex flex-column align-items-center">
          <div class="rounded-circle overflow-hidden" style="width:140px; height:140px; border:6px solid #2f2f2f;">
            <img id="userPhoto" src="https://via.placeholder.com/120" alt="User Photo" style="width:100%; height:100%; object-fit:cover;">
          </div>
          <h4 id="usernameDisplay" class="mt-3 mb-1">User</h4>
          <p id="userEmail" class="text-light-50 mb-3 small">email@example.com</p>

          <div class="d-flex gap-2 w-100 justify-content-center">
            <button class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#editProfileModal">
              <i class="bi bi-pencil"></i> Edit
            </button>
            <a id="logoutBtnSmall" href="logout.php" class="btn btn-danger btn-sm">Logout</a>
          </div>

          <div class="d-flex justify-content-between gap-3 mt-4 w-100 px-3">
            <div class="text-center">
              <h6 class="mb-0" id="itemsCount">0</h6>
              <small class="text-light-50">Items</small>
            </div>
            <div class="text-center">
              <h6 class="mb-0" id="tradesCount">0</h6>
              <small class="text-light-50">Trades</small>
            </div>
            <div class="text-center">
              <h6 class="mb-0" id="notifCount">0</h6>
              <small class="text-light-50">Notifs</small>
            </div>
          </div>
        </div>
      </div>

      <!-- Right: details -->
      <div class="col-12 col-md-8 p-4">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <h5 class="mb-1">Profile</h5>
            <p class="text-muted mb-2" id="userRole">Member</p>
          </div>
        </div>

        <div class="mb-3">
          <h6 class="mb-1">About</h6>
          <p id="userAbout" class="text-secondary">No description provided.</p>
        </div>

        <div class="row g-2">
          <div class="col-md-6">
            <h6 class="mb-1">Address</h6>
            <p id="addressDisplay" class="text-muted small">—</p>
          </div>
          <div class="col-md-6">
            <h6 class="mb-1">Contact</h6>
            <p id="contactDisplay" class="text-muted small">—</p>
          </div>
        </div>

        <div class="mt-3">
          <a href="add_item.php" class="btn btn-outline-primary me-2"><i class="bi bi-cloud-plus"></i> Add Item</a>
          <a href="profile.php" class="btn btn-outline-secondary"><i class="bi bi-eye"></i> View Profile</a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Profile</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form id="editForm">
          <div class="text-center mb-3">
            <input type="file" id="photoUpload" accept="image/*" class="form-control" style="max-width:300px; margin:auto;">
          </div>

          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Full Name</label>
              <input type="text" id="username" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Email</label>
              <input type="email" id="email" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Address</label>
              <input type="text" id="address" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Contact</label>
              <input type="text" id="contact" class="form-control" required>
            </div>
          </div>

          <div class="mt-3">
            <label class="form-label">About</label>
            <textarea id="about" class="form-control" rows="3"></textarea>
          </div>

          <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn btn-primary">Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


  <!-- Uploaded Items -->
<div class="container mt-5 mb-5">
  <h4 class="text-center mb-4">Your Uploaded Items</h4>
  <div class="row g-4" id="userItemsContainer"></div>
</div>
<!-- My Trades Section -->
<div class="container mt-5 mb-5">
  <h4 class="text-center mb-4">My Trades</h4>
  <div class="row g-4" id="tradeListContainer"></div>
</div>
<!-- Notifications Section -->
<div class="container mt-5 mb-5">
  <h4 class="text-center mb-4">Notifications</h4>
  <div class="list-group" id="notificationsContainer">
    <p class="text-center text-muted">No notifications yet.</p>
  </div>
</div>


<!-- Edit Item Modal -->
<div class="modal fade" id="editItemModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Item</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="editItemForm">
          <input type="hidden" id="editItemId">
          <div class="mb-3">
            <label class="form-label">Item Name</label>
            <input type="text" id="editItemName" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea id="editItemDescription" class="form-control" rows="3" required></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Item Image</label>
            <input type="file" id="editItemImage" class="form-control" accept="image/*">
          </div>
          <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="editOpenForTrade">
            <label class="form-check-label" for="editOpenForTrade">Open for Trade</label>
          </div>
          <button type="submit" class="btn btn-success w-100">Save Changes</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Trade Details Modal (ADDED) -->
<div class="modal fade" id="tradeDetailsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Trade Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="tradeDetailsBody">
        <!-- populated by JS -->
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

  <footer class="bg-dark text-light text-center py-3 mt-auto">
    <p class="mb-0 small">© 2025 PASSA. All Rights Reserved.</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Firebase + Cloudinary -->
  <script type="module">
  import { initializeApp } from "https://www.gstatic.com/firebasejs/11.0.0/firebase-app.js";
  import { getAuth, onAuthStateChanged, signOut } from "https://www.gstatic.com/firebasejs/11.0.0/firebase-auth.js";
  import { getDatabase, ref, get, update, onValue, remove } from "https://www.gstatic.com/firebasejs/11.0.0/firebase-database.js";
  import { uploadToCloudinary } from "./js/upload_cloudinary.js";

  const firebaseConfig = {
    apiKey: "AIzaSyAq6TIgqizXPlSs8fw5EUy7DVexM6MlyxQ",
    authDomain: "soft-engr.firebaseapp.com",
    databaseURL: "https://soft-engr-default-rtdb.firebaseio.com",
    projectId: "soft-engr",
    storageBucket: "soft-engr.appspot.com",
    messagingSenderId: "623763613209",
    appId: "1:623763613209:web:33152fe31ad0b256db6c88"
  };

  const app = initializeApp(firebaseConfig);
  const auth = getAuth(app);
  const db = getDatabase(app);

  const userPhoto = document.getElementById("userPhoto");
  const username = document.getElementById("username");
  const email = document.getElementById("email");
  const address = document.getElementById("address");
  const contact = document.getElementById("contact");
  const about = document.getElementById("about");
  const usernameDisplay = document.getElementById("usernameDisplay");
  const userEmail = document.getElementById("userEmail");
  const userAbout = document.getElementById("userAbout");
  const userItemsContainer = document.getElementById("userItemsContainer");
  const editForm = document.getElementById("editForm");
  const photoUpload = document.getElementById("photoUpload");

  // Trade details modal elements (ADDED)
  const tradeDetailsBody = document.getElementById("tradeDetailsBody");
  const tradeDetailsModal = new bootstrap.Modal(document.getElementById("tradeDetailsModal"));
  
  // compatibility: alias used elsewhere in the file
  function showBootstrapAlert(message, type = "info") {
    showAlert(message, type);
  }
  
  let currentUser;

  onAuthStateChanged(auth, async (user) => {
    if (!user) return (window.location.href = "login.php");
    currentUser = user;

    const userRef = ref(db, "users/" + user.uid);
    let userSnap = await get(userRef);

    if (!userSnap.exists()) {
      await update(userRef, {
        username: user.displayName || "User",
        email: user.email || "",
        photoURL: user.photoURL || "https://via.placeholder.com/120",
        address: "",
        contact: "",
        about: "No description provided.",
        createdAt: new Date().toISOString(),
      });
      console.log("✅ User safely initialized for Google login.");
    }

    // Always load user data fresh
    userSnap = await get(userRef);
    const data = userSnap.val() || {};

    username.value = data.username || "";
    email.value = data.email || user.email;
    address.value = data.address || "";
    contact.value = data.contact || "";
    about.value = data.about || "";
    userPhoto.src = data.photoURL || "https://via.placeholder.com/120";
    usernameDisplay.textContent = data.username || "User";
    userEmail.textContent = data.email || user.email;
    userAbout.textContent = data.about || "No description provided.";

    // Keep your "uploaded items" section fully functional
    const itemsRef = ref(db, "items");
    onValue(itemsRef, (snapshot) => {
      userItemsContainer.innerHTML = "";
      if (!snapshot.exists()) {
        userItemsContainer.innerHTML = "<p class='text-center text-muted'>You haven't uploaded any items yet.</p>";
        return;
      }

      const allItems = snapshot.val();
      Object.entries(allItems).forEach(([id, item]) => {
        if (item.userId === currentUser.uid) {
          const card = document.createElement("div");
          card.className = "col-12 col-sm-6 col-md-4";
          card.innerHTML = `
            <div class="card shadow-sm h-100">
              <img src="${item.photoURL}" class="card-img-top" style="height: 200px; object-fit: cover;">
              <div class="card-body">
                <h5 class="card-title">${item.name}</h5>
                <p class="text-muted">${item.description.substring(0, 80)}...</p>
                <div class="d-flex gap-2">
                  <button class="btn btn-warning btn-sm w-50 btn-edit-item" data-id="${id}">Edit</button>
                  <button class="btn btn-danger btn-sm w-50 btn-delete-item" data-id="${id}">Delete</button>
                </div>
              </div>
            </div>`;
          userItemsContainer.appendChild(card);
        }
      });

      // Edit item modal
      document.querySelectorAll(".btn-edit-item").forEach(btn => {
        btn.addEventListener("click", (e) => {
          const id = e.target.dataset.id;
          const item = snapshot.val()[id];
          document.getElementById("editItemId").value = id;
          document.getElementById("editItemName").value = item.name;
          document.getElementById("editItemDescription").value = item.description;
          document.getElementById("editOpenForTrade").checked = item.openForTrade || false;
          new bootstrap.Modal(document.getElementById("editItemModal")).show();
        });
      });

      // Delete item
      document.querySelectorAll(".btn-delete-item").forEach(btn => {
        btn.addEventListener("click", async (e) => {
          const id = e.target.dataset.id;
          if (confirm("Are you sure you want to delete this item?")) {
            await remove(ref(db, "items/" + id));
            alert("🗑️ Item deleted successfully!");
          }
        });
      });
    });

    // Handle item edit form
    document.getElementById("editItemForm").addEventListener("submit", async (e) => {
      e.preventDefault();
      const id = document.getElementById("editItemId").value;
      const updates = {
        name: document.getElementById("editItemName").value.trim(),
        description: document.getElementById("editItemDescription").value.trim(),
        openForTrade: document.getElementById("editOpenForTrade").checked,
      };

      const file = document.getElementById("editItemImage").files[0];
      if (file) {
        const photoURL = await uploadToCloudinary(file);
        updates.photoURL = photoURL;
      }

      await update(ref(db, "items/" + id), updates);
      alert("✅ Item updated successfully!");
      bootstrap.Modal.getInstance(document.getElementById("editItemModal")).hide();
    });
  });

  // Save profile changes
  editForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    if (!currentUser) return;

    const updates = {
      username: username.value.trim(),
      email: email.value.trim(),
      address: address.value.trim(),
      contact: contact.value.trim(),
      about: about.value.trim(),
    };

    try {
      if (photoUpload.files[0]) {
        const photoURL = await uploadToCloudinary(photoUpload.files[0]);
        updates.photoURL = photoURL;
        userPhoto.src = photoURL;
      }

      await update(ref(db, "users/" + currentUser.uid), updates);

      alert("✅ Profile updated successfully!");
      window.location.reload();
    } catch (err) {
      console.error(err);
      alert("❌ Failed to update profile.");
    }
  });

  // Logout
  document.getElementById("logoutBtn").addEventListener("click", async () => {
    await signOut(auth);
    window.location.href = "index.php";
  });

  // --- My Trades Section ---
const tradeListContainer = document.getElementById("tradeListContainer");

onAuthStateChanged(auth, async (user) => {
  if (!user) return (window.location.href = "login.php");

  const tradesRef = ref(db, "trades");

  onValue(tradesRef, async (snapshot) => {
    tradeListContainer.innerHTML = "";

    if (!snapshot.exists()) {
      tradeListContainer.innerHTML = "<p class='text-center text-muted'>No trades yet.</p>";
      return;
    }

    let hasTrades = false;

    // Loop through each receiver
    const receivers = snapshot.val();
    for (const [receiverId, proposers] of Object.entries(receivers)) {
      for (const [proposerId, tradeGroup] of Object.entries(proposers)) {
        for (const [tradeKey, trade] of Object.entries(tradeGroup)) {
          if (receiverId === user.uid || proposerId === user.uid) {
            hasTrades = true;

            const isProposer = proposerId === user.uid;
            const otherUserId = isProposer ? receiverId : proposerId;

            const userSnap = await get(ref(db, `users/${otherUserId}`));
            const otherUser = userSnap.exists() ? userSnap.val() : {};

            // Get item info
            const itemSnap = await get(ref(db, `items/${trade.itemId}`));
            const item = itemSnap.exists() ? itemSnap.val() : {};

            const card = document.createElement("div");
            card.className = "col-12 col-md-6";
            card.innerHTML = `
              <div class="card shadow-sm p-3">
                <div class="d-flex align-items-center mb-2">
                  <img src="${item.photoURL || 'https://via.placeholder.com/80'}" 
                       alt="Item Image" 
                       class="rounded me-3" 
                       width="80" height="80"
                       style="object-fit: cover;">
                  <div>
                    <h5 class="mb-1">${item.name || 'Unnamed Item'}</h5>
                    <p class="mb-0 text-muted small">${item.description?.substring(0, 50) || ''}</p>
                  </div>
                </div>
                <hr>
                <p><strong>Status:</strong> ${trade.status}</p>
                <p><strong>Message:</strong> ${trade.message || 'No message'}</p>
                <p><strong>With:</strong> ${otherUser.username || 'Unknown User'}</p>
                <small class="text-muted">${new Date(trade.timestamp).toLocaleString()}</small>
                <div class="mt-3 text-center">
                  <button class="btn btn-outline-primary btn-sm view-details" 
                          data-receiver="${receiverId}" 
                          data-proposer="${proposerId}" 
                          data-key="${tradeKey}">
                    View Details
                  </button>
                </div>
              </div>`;
            tradeListContainer.appendChild(card);
          }
        }
      }
    }

    if (!hasTrades) {
      tradeListContainer.innerHTML = "<p class='text-center text-muted'>No trades yet.</p>";
    }

    // View Details
    document.querySelectorAll(".view-details").forEach(btn => {
      btn.addEventListener("click", (e) => {
        const receiverId = e.target.dataset.receiver;
        const proposerId = e.target.dataset.proposer;
        const tradeKey = e.target.dataset.key;
        loadTradeDetails(receiverId, proposerId, tradeKey);
      });
    });
  });
});


// ✅ Load Trade Details + Item Info
async function loadTradeDetails(receiverId, proposerId, tradeKey) {
  tradeDetailsBody.innerHTML = "<p class='text-muted'>Loading trade details...</p>";

  const tradePath = `trades/${receiverId}/${proposerId}/${tradeKey}`;
  const tradeSnap = await get(ref(db, tradePath));

  if (!tradeSnap.exists()) {
    tradeDetailsBody.innerHTML = "<p class='text-center text-muted'>Trade not found.</p>";
    tradeDetailsModal.show();
    return;
  }

  const trade = tradeSnap.val();
  const currentUid = currentUser.uid;
  const isProposer = proposerId === currentUid;
  const otherUserId = isProposer ? receiverId : proposerId;

  // Get user details
  const userSnap = await get(ref(db, `users/${otherUserId}`));
  const otherUser = userSnap.exists() ? userSnap.val() : {};

  // Get item details
  const itemSnap = await get(ref(db, `items/${trade.itemId}`));
  const item = itemSnap.exists() ? itemSnap.val() : {};

  // Display trade and item info
  tradeDetailsBody.innerHTML = `
  <div class="text-center mb-3">
    <img src="${item.photoURL || 'https://via.placeholder.com/200'}" 
         alt="Item Image" 
         class="rounded mb-2" 
         width="200" height="200" 
         style="object-fit: cover;">
    <h5>${item.name || 'Unnamed Item'}</h5>
    <p class="text-muted">${item.description || 'No description provided.'}</p>
  </div>
  <hr>
  <p><strong>Message:</strong> ${trade.message}</p>
  <p><strong>Status:</strong> ${trade.status}</p>
  <hr>
  <h6>Other User Details</h6>
  <p><strong>Name:</strong> ${otherUser.fullName || otherUser.username || "N/A"}</p>
  <p><strong>Email:</strong> ${otherUser.email || "N/A"}</p>
  <p><strong>Address:</strong> ${otherUser.address || "N/A"}</p>
  <p><strong>Contact:</strong> ${otherUser.contact || "N/A"}</p>
  <div class="d-flex justify-content-between mt-3">
    ${
      trade.status === "Pending"
        ? isProposer
          ? `<button class="btn btn-secondary cancel-btn">Cancel</button>`
          : `
              <button class="btn btn-success accept-btn">Accept</button>
              <button class="btn btn-danger decline-btn">Decline</button>`
        : ""
    }
  </div>
`;
  tradeDetailsModal.show();

  // Action buttons
  document.querySelector(".accept-btn")?.addEventListener("click", async () => {
    await update(ref(db, tradePath), { status: "Accepted" });
    showAlert("✅ Trade accepted!", "success");
    tradeDetailsModal.hide();
  });

  document.querySelector(".decline-btn")?.addEventListener("click", async () => {
    await update(ref(db, tradePath), { status: "Declined" });
    showAlert("🚫 Trade declined.", "warning");
    tradeDetailsModal.hide();
  });

  document.querySelector(".cancel-btn")?.addEventListener("click", async () => {
    await update(ref(db, tradePath), { status: "Cancelled" });
    showAlert("❎ Trade cancelled.", "secondary");
    tradeDetailsModal.hide();
  });
}

// --- Notifications Logic ---
const notificationsContainer = document.getElementById("notificationsContainer");

onAuthStateChanged(auth, async (user) => {
  if (!user) return;

  const notifRef = ref(db, `notifications/${user.uid}`);
  onValue(notifRef, (snapshot) => {
    notificationsContainer.innerHTML = "";

    if (!snapshot.exists()) {
      notificationsContainer.innerHTML = "<p class='text-center text-muted'>No notifications yet.</p>";
      return;
    }

    const notifications = snapshot.val();
    const entries = Object.entries(notifications).reverse(); // newest first

    entries.forEach(([id, notif]) => {
      const notifItem = document.createElement("div");
      notifItem.className = `list-group-item list-group-item-action d-flex justify-content-between align-items-center ${
        notif.read ? "bg-light" : "bg-white"
      }`;

      notifItem.innerHTML = `
        <div>
          <strong>${notif.type === "tradeAccepted" ? "✅ Trade Accepted" :
            notif.type === "tradeDeclined" ? "🚫 Trade Declined" :
            notif.type === "tradeCancelled" ? "❎ Trade Cancelled" :
            "📩 Notification"}</strong><br>
          <small>${notif.message}</small><br>
          <small class="text-muted">${new Date(notif.timestamp).toLocaleString()}</small>
        </div>
        ${
          !notif.read
            ? `<button class="btn btn-sm btn-outline-primary mark-read" data-id="${id}">Mark as Read</button>`
            : ""
        }
      `;
      notificationsContainer.appendChild(notifItem);
    });

    // handle mark as read
    document.querySelectorAll(".mark-read").forEach((btn) => {
      btn.addEventListener("click", async (e) => {
        const notifId = e.target.dataset.id;
        await update(ref(db, `notifications/${user.uid}/${notifId}`), { read: true });
      });
    });
  });
});

// Utility: show Bootstrap alerts
function showAlert(message, type = "info") {
  const alertDiv = document.createElement("div");
  alertDiv.className = `alert alert-${type} position-fixed top-0 start-50 translate-middle-x mt-3`;
  alertDiv.style.zIndex = "9999";
  alertDiv.textContent = message;
  document.body.appendChild(alertDiv);
  setTimeout(() => alertDiv.remove(), 3000);
}

  </script>

</body>
</html>