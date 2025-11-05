// js/trade.js
import { ref, get, set } from "https://www.gstatic.com/firebasejs/11.0.0/firebase-database.js";

export function openTradeModal(itemId, user, db) {
  const tradeModal = new bootstrap.Modal(document.getElementById("tradeModal"));
  const myTradeItems = document.getElementById("myTradeItems");
  const tradeMessage = document.getElementById("tradeMessage");
  const submitTradeBtn = document.getElementById("submitTradeBtn");

  myTradeItems.innerHTML = "<p class='text-muted'>Loading your items...</p>";

  const itemsRef = ref(db, "items");
  get(itemsRef).then(snapshot => {
    if (!snapshot.exists()) {
      myTradeItems.innerHTML = "<p class='text-muted'>You have no items to trade.</p>";
      return;
    }

    const allItems = snapshot.val();
    myTradeItems.innerHTML = "";

    let selectedItemId = null;

    Object.entries(allItems).forEach(([id, item]) => {
      if (item.userId === user.uid) {
        const card = document.createElement("div");
        card.className = "col-6";
        card.innerHTML = `
          <div class="card border p-2 trade-select" data-id="${id}" style="cursor:pointer;">
            <img src="${item.photoURL}" class="card-img-top rounded" style="height:120px; object-fit:cover;">
            <p class="small text-center mt-1">${item.name}</p>
          </div>`;
        myTradeItems.appendChild(card);
      }
    });

    // highlight the selected item
    document.querySelectorAll(".trade-select").forEach(card => {
      card.addEventListener("click", () => {
        document.querySelectorAll(".trade-select").forEach(c => c.classList.remove("border-primary"));
        card.classList.add("border-primary");
        selectedItemId = card.dataset.id;
      });
    });

    // handle the send proposal button
    submitTradeBtn.onclick = async () => {
      const message = tradeMessage.value.trim();
      if (!selectedItemId) {
        showAlert("Please select one of your items to trade.", "warning");
        return;
      }
      if (!message) {
        showAlert("Please enter a message for your trade proposal.", "warning");
        return;
      }

      await set(ref(db, `trades/${itemId}/${user.uid}`), {
        offeredItemId: selectedItemId,
        userId: user.uid,
        timestamp: new Date().toISOString(),
        status: "Pending",
        message
      });

      showAlert("✅ Trade proposal sent successfully!", "success");
      tradeMessage.value = "";
      tradeModal.hide();
    };
  });

  tradeModal.show();
}

// helper for Bootstrap alerts
function showAlert(message, type = "info") {
  const alertDiv = document.createElement("div");
  alertDiv.className = `alert alert-${type} position-fixed top-0 start-50 translate-middle-x mt-3`;
  alertDiv.style.zIndex = "9999";
  alertDiv.textContent = message;
  document.body.appendChild(alertDiv);
  setTimeout(() => alertDiv.remove(), 3000);
}
