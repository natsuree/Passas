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
          <li class="nav-item"><a class="nav-link active" href="home.php"><span>Home</span></a></li>
          <li class="nav-item"><a class="nav-link" href="add_item.php">Add Item</a></li>
          <li class="nav-item"><a class="nav-link" href="request.html">Request</a></li>
          <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
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

// Elements
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

// Listen for auth
onAuthStateChanged(auth, (user) => {
  if (!user) return (window.location.href = "login.php");

  // Apply flexible layout to container
itemContainer.classList.add(
  "d-flex",
  "flex-wrap",
  "gap-3",
  "justify-content-start"
);

// Keep track of original order
let originalOrder = [];

// Load all items
const itemsRef = ref(db, "items");
onValue(itemsRef, (snapshot) => {
  itemContainer.innerHTML = "";
  allItems = snapshot.val() || {};
  originalOrder = [];

  Object.entries(allItems).forEach(([id, item]) => {
    const tradeBadge = item.openForTrade
      ? `<span class="badge bg-warning text-dark position-absolute top-0 end-0 m-2">Open for Trade</span>`
      : "";

    const card = document.createElement("div");
    card.className = "item-card position-relative";
    card.style.flex = "1 1 calc(30% - 1rem)"; // wider cards (3 per row)
    card.style.minWidth = "360px"; // matches your layout
    card.style.maxWidth = "400px";

    card.innerHTML = `
      <div class="card shadow-sm h-100" data-id="${id}">
        ${tradeBadge}
        <img src="${item.photoURL || 'https://via.placeholder.com/350'}"
             class="card-img-top"
             style="height: 220px; object-fit: cover;">
        <div class="card-body">
          <h5 class="card-title">${item.name}</h5>
          <p class="text-muted">${item.description?.substring(0, 80) || ''}...</p>
          <button class="btn btn-outline-primary btn-sm w-100 view-btn">View Details</button>
        </div>
      </div>
    `;

    itemContainer.appendChild(card);
    originalOrder.push(card);
  });

  // View Details button
  document.querySelectorAll(".view-btn").forEach((btn) => {
    btn.addEventListener("click", (e) => {
      const id = e.target.closest(".card").dataset.id;
      showItemModal(id, user);
    });
  });
});

//  Search with smooth reordering + restore
searchInput.addEventListener("input", (e) => {
  const q = e.target.value.toLowerCase().trim();
  const cards = Array.from(document.querySelectorAll(".item-card"));

  if (q === "") {
    // Restore original order
    itemContainer.innerHTML = "";
    originalOrder.forEach((card) => {
      card.style.display = "";
      itemContainer.appendChild(card);
    });
    return;
  }

  // Filter and reorder
  const matches = [];
  const nonMatches = [];

  cards.forEach((card) => {
    const title = card.querySelector(".card-title").textContent.toLowerCase();
    if (title.includes(q)) {
      matches.push(card);
    } else {
      nonMatches.push(card);
    }
  });

  // Show matches on top-left, hide the rest
  itemContainer.innerHTML = "";
  matches.forEach((card) => {
    card.style.display = "";
    itemContainer.appendChild(card);
  });
  nonMatches.forEach((card) => {
    card.style.display = "none";
    itemContainer.appendChild(card);
  });
});

  // Search
  searchInput.addEventListener("input", (e) => {
  const q = e.target.value.toLowerCase();
  document.querySelectorAll(".item-card").forEach(card => {
    const title = card.querySelector(".card-title").textContent.toLowerCase();
    if (title.includes(q)) {
      card.classList.remove("d-none");
    } else {
      card.classList.add("d-none");
    }
  });
});


  // Logout
  document.getElementById("logoutBtn").addEventListener("click", async () => {
    await signOut(auth);
    window.location.href = "index.php";
  });
});

// Show item modal
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
    // Owner’s options
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
    // Other users’ options
    const interestBtn = document.createElement("button");
    interestBtn.className = "btn btn-success";
    interestBtn.textContent = "I'm Interested";
    interestBtn.onclick = () => markInterested(id, user);
    footer.appendChild(interestBtn);

    if (item.openForTrade) {
      const tradeBtn = document.createElement("button");
      tradeBtn.className = "btn btn-warning ms-2";
      tradeBtn.textContent = "Propose Trade";
      tradeBtn.onclick = () => openTradeModal(id, user);
      footer.appendChild(tradeBtn);
    }
  }

  itemModal.show();
}

// Open Trade Modal
function openTradeModal(itemId, user) {
  selectedItemId = itemId;
  tradeMessage.value = "";
  tradeModal.show();
}

// Send Trade Proposal
submitTradeBtn.addEventListener("click", async () => {
  const message = tradeMessage.value.trim();
  if (!message) return showAlert("Please enter your trade proposal first.", "warning");

  if (!selectedItemId || !auth.currentUser?.uid) {
    console.error("Missing itemId or userId.");
    showAlert("Something went wrong — trade not saved.", "danger");
    return;
  }

  const targetItem = allItems[selectedItemId];
  if (!targetItem) return showAlert("Item not found.", "danger");

  const ownerId = targetItem.userId;
  const proposerId = auth.currentUser.uid;

  // Save under owner’s node so both users can track
  const tradeRef = ref(db, `trades/${ownerId}/${proposerId}/${selectedItemId}`);
  await set(tradeRef, {
    message,
    proposerId,
    itemId: selectedItemId,
    timestamp: new Date().toISOString(),
    status: "Pending"
  });

  showAlert("✅ Trade proposal sent successfully!", "success");
  tradeMessage.value = "";
  tradeModal.hide();
});

// Load Trade Proposals
async function loadTradeProposals(itemId) {
  const item = allItems[itemId];
  if (!item) return;
  const ownerId = item.userId;

  tradeListDiv.innerHTML = "<p class='text-muted'>Loading trade proposals...</p>";
  const tradeRef = ref(db, `trades/${ownerId}`);
  const snapshot = await get(tradeRef);

  if (!snapshot.exists()) {
    tradeListDiv.innerHTML = "<p class='text-center text-muted'>No trade proposals yet.</p>";
    tradeListModal.show();
    return;
  }

  let content = "";
  const proposers = snapshot.val();

  for (const [proposerId, trades] of Object.entries(proposers)) {
    for (const [tradeItemId, trade] of Object.entries(trades)) {
      if (trade.itemId !== itemId) continue;

      const userRef = ref(db, `users/${proposerId}`);
      const userSnap = await get(userRef);
      const userData = userSnap.val() || {};

      const isOwner = auth.currentUser.uid === ownerId;
      const isSender = auth.currentUser.uid === proposerId;

      content += `
        <div class="border rounded p-3 mb-3">
          <p><strong>Message:</strong> ${trade.message}</p>
          <small class="text-muted">Sent: ${new Date(trade.timestamp).toLocaleString()}</small><br>
          <small><strong>Status:</strong> ${trade.status}</small>
          <div class="mt-2">
            <p class="mb-1"><strong>User:</strong> ${userData.fullName || "Unknown"}</p>
            <small>📞 ${userData.contact || "No contact info"}</small><br>
            <small>📍 ${userData.address || "No address provided"}</small>
          </div>
          <div class="mt-3 d-flex gap-2">
            ${
              isOwner && trade.status === "Pending"
                ? `
                  <button class="btn btn-success btn-sm acceptTrade" data-owner="${ownerId}" data-proposer="${proposerId}" data-item="${itemId}">Accept</button>
                  <button class="btn btn-danger btn-sm declineTrade" data-owner="${ownerId}" data-proposer="${proposerId}" data-item="${itemId}">Decline</button>
                `
                : ""
            }
            ${
              isSender && trade.status === "Pending"
                ? `<button class="btn btn-secondary btn-sm cancelTrade" data-owner="${ownerId}" data-proposer="${proposerId}" data-item="${itemId}">Cancel</button>`
                : ""
            }
          </div>
        </div>`;
    }
  }

  tradeListDiv.innerHTML = content || "<p class='text-center text-muted'>No trades for this item yet.</p>";
  tradeListModal.show();

  document.querySelectorAll(".acceptTrade").forEach(btn => {
    btn.addEventListener("click", async (e) => {
      const { owner, proposer, item } = e.target.dataset;
      await update(ref(db, `trades/${owner}/${proposer}/${item}`), { status: "Accepted" });
      showAlert("✅ Trade accepted!", "success");
      loadTradeProposals(item);
    });
  });

  document.querySelectorAll(".declineTrade").forEach(btn => {
    btn.addEventListener("click", async (e) => {
      const { owner, proposer, item } = e.target.dataset;
      await update(ref(db, `trades/${owner}/${proposer}/${item}`), { status: "Declined" });
      showAlert("🚫 Trade declined.", "warning");
      loadTradeProposals(item);
    });
  });

  document.querySelectorAll(".cancelTrade").forEach(btn => {
    btn.addEventListener("click", async (e) => {
      const { owner, proposer, item } = e.target.dataset;
      await update(ref(db, `trades/${owner}/${proposer}/${item}`), { status: "Cancelled" });
      showAlert("❎ Trade cancelled.", "secondary");
      loadTradeProposals(item);
    });
  });
}

// Helper — Alert
function showAlert(message, type = "info") {
  const alertDiv = document.createElement("div");
  alertDiv.className = `alert alert-${type} position-fixed top-0 start-50 translate-middle-x mt-3 shadow`;
  alertDiv.style.zIndex = "9999";
  alertDiv.textContent = message;
  document.body.appendChild(alertDiv);
  setTimeout(() => alertDiv.remove(), 3000);
}

// Interested Users
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
