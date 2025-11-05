<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Home | PASSA</title>

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

  <!-- Search -->
  <div class="container mt-4">
    <input id="searchInput" type="text" class="form-control mb-4" placeholder="Search items...">
  </div>

  <!-- Items -->
  <div class="container pb-5">
    <div class="row g-4" id="itemContainer"></div>
  </div>

  <!-- Item Details Modal -->
  <div class="modal fade" id="itemModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 id="modalTitle" class="modal-title"></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <img id="modalImage" class="img-fluid rounded mb-3" style="max-height: 300px; object-fit: cover;">
          <p id="modalDescription" class="mb-3"></p>
          <div class="border-top pt-3">
            <h6 class="fw-semibold">Uploader Information</h6>
            <p class="mb-1"><strong>Name:</strong> <span id="uploaderName"></span></p>
            <p class="mb-1"><strong>Address:</strong> <span id="uploaderAddress"></span></p>
            <p class="mb-0"><strong>Contact:</strong> <span id="uploaderContact"></span></p>
          </div>
        </div>
        <div class="modal-footer" id="modalButtons"></div>
      </div>
    </div>
  </div>

  <!-- Interested Users Modal -->
  <div class="modal fade" id="interestedModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Interested Users</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" id="interestedList">
          <p class="text-muted">Loading...</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Trade Modal -->
<div class="modal fade" id="tradeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Propose a Trade</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>Select one of your items to offer in trade:</p>
        <div id="myTradeItems" class="row g-3"></div>
        <div class="mt-3">
          <textarea id="tradeMessage" class="form-control" rows="3" placeholder="Add a message to your trade..."></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button id="submitTradeBtn" class="btn btn-primary">Send Proposal</button>
      </div>
    </div>
  </div>
</div>



  <!-- Trade Proposals List Modal -->
  <div class="modal fade" id="tradeListModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Trade Proposals</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" id="tradeList">
          <p class="text-muted">Loading trade proposals...</p>
        </div>
      </div>
    </div>
  </div>

  <footer class="bg-dark text-light text-center py-3 mt-auto">
    <p class="mb-0 small">© 2025 PASSA. All Rights Reserved.</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

  <script type="module">
  import { initializeApp } from "https://www.gstatic.com/firebasejs/11.0.0/firebase-app.js";
  import { getAuth, onAuthStateChanged, signOut } from "https://www.gstatic.com/firebasejs/11.0.0/firebase-auth.js";
  import { getDatabase, ref, onValue, get, set, update } from "https://www.gstatic.com/firebasejs/11.0.0/firebase-database.js";
  import { openTradeModal } from "./js/trade.js";
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

  const itemContainer = document.getElementById("itemContainer");
  const searchInput = document.getElementById("searchInput");
  const itemModal = new bootstrap.Modal(document.getElementById("itemModal"));
  const interestedModal = new bootstrap.Modal(document.getElementById("interestedModal"));
  const tradeModal = new bootstrap.Modal(document.getElementById("tradeModal"));
  const tradeListModal = new bootstrap.Modal(document.getElementById("tradeListModal"));
  const tradeMessage = document.getElementById("tradeMessage");
  const submitTradeBtn = document.getElementById("submitTradeBtn");
  const tradeListDiv = document.getElementById("tradeList");

  let allItems = {};
  let selectedItemId = null;

  onAuthStateChanged(auth, (user) => {
    if (!user) return (window.location.href = "login.php");

    const itemsRef = ref(db, "items");
    onValue(itemsRef, (snapshot) => {
      itemContainer.innerHTML = "";
      allItems = snapshot.val() || {};

      Object.entries(allItems).forEach(([id, item]) => {
        const tradeBadge = item.openForTrade
          ? `<span class="badge bg-warning text-dark position-absolute top-0 end-0 m-2">Open for Trade</span>`
          : "";

        const card = document.createElement("div");
        card.className = "col-12 col-sm-6 col-md-4 position-relative";
        card.innerHTML = `
          <div class="card shadow-sm h-100 item-card" data-id="${id}">
            ${tradeBadge}
            <img src="${item.photoURL}" class="card-img-top" style="height: 220px; object-fit: cover;">
            <div class="card-body">
              <h5 class="card-title">${item.name}</h5>
              <p class="text-muted">${item.description.substring(0, 80)}...</p>
              <button class="btn btn-outline-primary btn-sm w-100 view-btn">View Details</button>
            </div>
          </div>`;
        itemContainer.appendChild(card);
      });

      document.querySelectorAll(".view-btn").forEach(btn => {
        btn.addEventListener("click", (e) => {
          const id = e.target.closest(".item-card").dataset.id;
          showItemModal(id, user);
        });
      });
    });

    searchInput.addEventListener("input", (e) => {
      const q = e.target.value.toLowerCase();
      document.querySelectorAll(".item-card").forEach(card => {
        const title = card.querySelector(".card-title").textContent.toLowerCase();
        card.style.display = title.includes(q) ? "" : "none";
      });
    });

    document.getElementById("logoutBtn").addEventListener("click", async () => {
      await signOut(auth);
      window.location.href = "index.php";
    });
  });

  async function showItemModal(id, user) {
    const item = allItems[id];
    selectedItemId = id;
    document.getElementById("modalTitle").textContent = item.name;
    document.getElementById("modalImage").src = item.photoURL;
    document.getElementById("modalDescription").textContent = item.description;

    try {
      const userRef = ref(db, `users/${item.userId}`);
      const userSnap = await get(userRef);
      const userData = userSnap.val() || {};
      document.getElementById("uploaderName").textContent = userData.fullName || "Unknown";
      document.getElementById("uploaderAddress").textContent = userData.address || "Not provided";
      document.getElementById("uploaderContact").textContent = userData.contact || "Not provided";
    } catch (err) {
      console.error("Error fetching uploader info:", err);
    }

    const footer = document.getElementById("modalButtons");
    footer.innerHTML = "";

    if (item.userId === user.uid) {
      const interestedBtn = document.createElement("button");
      interestedBtn.className = "btn btn-primary";
      interestedBtn.textContent = "View Interested Users";
      interestedBtn.onclick = () => loadInterestedUsers(id);
      footer.appendChild(interestedBtn);

      const tradeBtn = document.createElement("button");
      tradeBtn.className = "btn btn-warning ms-2";
      tradeBtn.textContent = "View Trade Proposals";
      tradeBtn.onclick = () => loadTradeProposals(id);
      footer.appendChild(tradeBtn);
    } else {
      const interestBtn = document.createElement("button");
      interestBtn.className = "btn btn-success";
      interestBtn.textContent = "I'm Interested";
      interestBtn.onclick = () => markInterested(id, user);
      footer.appendChild(interestBtn);

      if (item.openForTrade) {
      const tradeBtn = document.createElement("button");
      tradeBtn.className = "btn btn-warning ms-2";
      tradeBtn.textContent = "Propose Trade";
      tradeBtn.onclick = () => openTradeModal(id, user, db);
      footer.appendChild(tradeBtn);
    }

    }

    itemModal.show();
  }

// Send Trade Proposal
submitTradeBtn.addEventListener("click", async () => {
  const message = tradeMessage.value.trim();
  if (!message) return showAlert("Please enter your trade proposal first.", "warning");

  if (!selectedItemId || !auth.currentUser?.uid) {
    console.error("❌ Missing itemId or userId, trade not saved.");
    showAlert("Something went wrong — trade not saved.", "danger");
    return;
  }

  const tradeRef = ref(db, `trades/${selectedItemId}/${auth.currentUser.uid}`);
  await set(tradeRef, {
    message,
    userId: auth.currentUser.uid,
    timestamp: new Date().toISOString(),
    status: "Pending"
  });

  showAlert("✅ Trade proposal sent successfully!", "success");
  tradeMessage.value = "";
  tradeModal.hide();
});

// Load Trade Proposals
async function loadTradeProposals(itemId) {
  tradeListDiv.innerHTML = "<p class='text-muted'>Loading trade proposals...</p>";
  const tradeRef = ref(db, `trades/${itemId}`);
  const snapshot = await get(tradeRef);

  if (!snapshot.exists()) {
    tradeListDiv.innerHTML = "<p class='text-center text-muted'>No trade proposals yet.</p>";
    tradeListModal.show();
    return;
  }

  const proposals = snapshot.val();
  const entries = Object.entries(proposals);

  let content = "";
  for (const [uid, trade] of entries) {
    const userRef = ref(db, `users/${uid}`);
    const userSnap = await get(userRef);
    const userData = userSnap.val() || {};

    const isOwner = auth.currentUser.uid !== uid;
    const isSender = auth.currentUser.uid === uid;

    content += `
      <div class="border rounded p-3 mb-3">
        <p><strong>Message:</strong> ${trade.message}</p>
        <small class="text-muted">Sent: ${new Date(trade.timestamp).toLocaleString()}</small><br>
        <small><strong>Status:</strong> ${trade.status}</small>
        <div class="mt-2">
          <p class="mb-1"><strong>From:</strong> ${userData.username || userData.fullName || "Unknown"}</p>
          <small>📞 ${userData.contact || "No contact info"}</small><br>
          <small>📍 ${userData.address || "No address provided"}</small>
        </div>
        <div class="mt-3 d-flex gap-2">
          ${
            isOwner && trade.status === "Pending"
              ? `
                <button class="btn btn-success btn-sm acceptTrade" data-uid="${uid}" data-item="${itemId}">Accept</button>
                <button class="btn btn-danger btn-sm declineTrade" data-uid="${uid}" data-item="${itemId}">Decline</button>
              `
              : ""
          }
          ${
            isSender && trade.status === "Pending"
              ? `<button class="btn btn-secondary btn-sm cancelTrade" data-uid="${uid}" data-item="${itemId}">Cancel Trade</button>`
              : ""
          }
        </div>
      </div>
    `;
  }

  tradeListDiv.innerHTML = content;
  tradeListModal.show();

  // Accept Trade
  document.querySelectorAll(".acceptTrade").forEach(btn => {
    btn.addEventListener("click", async (e) => {
      const { uid, item } = e.target.dataset;

      await update(ref(db, `trades/${item}/${uid}`), { status: "Accepted" });

      const notifRef = ref(db, `notifications/${uid}`);
      const newNotifKey = push(notifRef);
      await set(newNotifKey, {
        type: "tradeAccepted",
        message: "Your trade proposal was accepted!",
        tradeItemId: item,
        timestamp: new Date().toISOString(),
        read: false
      });

      showAlert("✅ Trade accepted! The proposer has been notified.", "success");
      loadTradeProposals(item);
    });
  });

  // Decline Trade
  document.querySelectorAll(".declineTrade").forEach(btn => {
    btn.addEventListener("click", async (e) => {
      const { uid, item } = e.target.dataset;
      await update(ref(db, `trades/${item}/${uid}`), { status: "Declined" });

      const notifRef = ref(db, `notifications/${uid}`);
      const newNotifKey = push(notifRef);
      await set(newNotifKey, {
        type: "tradeDeclined",
        message: "Your trade proposal was declined.",
        tradeItemId: item,
        timestamp: new Date().toISOString(),
        read: false
      });

      showAlert("🚫 Trade declined and proposer notified.", "warning");
      loadTradeProposals(item);
    });
  });

  // Cancel Trade (for proposer)
  document.querySelectorAll(".cancelTrade").forEach(btn => {
    btn.addEventListener("click", async (e) => {
      const { uid, item } = e.target.dataset;
      const confirmCancel = confirm("Are you sure you want to cancel this trade?");
      if (!confirmCancel) return;

      await update(ref(db, `trades/${item}/${uid}`), { status: "Cancelled" });

      const notifRef = ref(db, `notifications/${item}`); // notify the owner
      const newNotifKey = push(notifRef);
      await set(newNotifKey, {
        type: "tradeCancelled",
        message: "The proposer has cancelled their trade.",
        tradeItemId: item,
        timestamp: new Date().toISOString(),
        read: false
      });

      showAlert("❎ Trade cancelled successfully!", "secondary");
      loadTradeProposals(item);
    });
  });
}

// Bootstrap Alert Helper
function showAlert(message, type = "info") {
  const alertDiv = document.createElement("div");
  alertDiv.className = `alert alert-${type} position-fixed top-0 start-50 translate-middle-x mt-3 shadow`;
  alertDiv.style.zIndex = "9999";
  alertDiv.textContent = message;
  document.body.appendChild(alertDiv);
  setTimeout(() => alertDiv.remove(), 3000);
}

// Mark user as interested
async function markInterested(itemId, user) {
  const userRef = ref(db, `users/${user.uid}`);
  const userSnap = await get(userRef);
  const userData = userSnap.val() || {};
  const interestRef = ref(db, `interests/${itemId}/${user.uid}`);

  await set(interestRef, {
    username: userData.username || user.displayName || "Unknown",
    contact: userData.contact || "No contact info",
    address: userData.address || "No address provided",
    timestamp: new Date().toISOString(),
  });

  showAlert("✅ Interest recorded! The owner will be notified.", "success");
}

// Load interested users
async function loadInterestedUsers(itemId) {
  const listDiv = document.getElementById("interestedList");
  listDiv.innerHTML = "<p class='text-muted'>Loading...</p>";

  const interestRef = ref(db, `interests/${itemId}`);
  const snapshot = await get(interestRef);

  if (!snapshot.exists()) {
    listDiv.innerHTML = "<p class='text-center text-muted'>No one is interested yet.</p>";
    interestedModal.show();
    return;
  }

  const users = snapshot.val();
  listDiv.innerHTML = Object.values(users)
    .map(u => `
      <div class="border rounded p-2 mb-2">
        <strong>${u.username}</strong><br>
        <small>📍 ${u.address}</small><br>
        <small>📞 ${u.contact}</small>
      </div>
    `)
    .join("");
  interestedModal.show();
}

</script>
     
</body>
</html>
