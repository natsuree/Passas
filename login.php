<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | PASSA</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">

  <link rel="stylesheet" href="css/login.css">

  <style>
    /* Small fix for floating icon alignment */
    .icon {
      position: absolute;
      margin-left: 10px;
      margin-top: 40px;
      color: #777;
    }
  </style>
</head>

<body class="bg-light">

  <div class="center container mt-5">
    <div class="card shadow-sm p-4 mx-auto" style="max-width: 450px;">
      <h3 class="text-center mb-4">Welcome Back</h3>

      <form id="loginForm">
        <div class="mb-3 position-relative">
          <i class="fa fa-envelope icon"></i>
          <label class="form-label">Email</label>
          <input type="email" id="email" class="form-control ps-4" placeholder="Enter your email" required>
        </div>

        <div class="mb-3 position-relative">
          <i class="fa fa-lock icon"></i>
          <label class="form-label">Password</label>
          <input type="password" id="password" class="form-control ps-4" placeholder="Enter password" required>
        </div>

        <a href="#" id="forgotPasswordLink" class="small d-block mb-2 text-end" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">Forgot Password?</a>

        <button type="submit" class="btn btn-primary w-100 mt-2">Login</button>
      </form>

      <p class="text-center mt-3">
        Don't have an account? <a href="signup.php">Sign up here</a>
      </p>

      <div class="text-center mt-3">
        <button id="googleLoginBtn" class="btn btn-outline-danger w-100">
          <i class="bi bi-google me-2"></i>Sign in with Google
        </button>
      </div>

      <div id="status" class="text-center mt-3 text-muted"></div>
    </div>
  </div>

  <!-- SUCCESS MODAL (UPDATED - Larger & Better Design) -->
  <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg">
        <div class="modal-header bg-success text-white border-0 py-4">
          <div class="w-100 text-center">
            <i class="bi bi-check-circle" style="font-size: 3rem;"></i>
            <h5 class="modal-title mt-2">Success!</h5>
          </div>
          <button class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-center py-5">
          <p id="successMessage" class="fs-5 mb-0" style="color: #333; font-weight: 500;">
            Action completed successfully.
          </p>
        </div>
      </div>
    </div>
  </div>

  <!-- Forgot Password Modal (UPDATED) -->
  <div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Reset Password</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Step 1: Verify Email & Contact -->
          <div id="forgotStep1">
            <div class="mb-3">
              <label class="form-label">Enter your email</label>
              <input type="email" id="forgotEmail" class="form-control" placeholder="you@example.com" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Enter your contact number</label>
              <input type="text" id="forgotContact" class="form-control" placeholder="Your contact number" required>
            </div>
            <div id="forgotStatus" class="alert alert-info d-none small"></div>
          </div>

          <!-- Step 2: New Password -->
          <div id="forgotStep2" class="d-none">
            <div class="alert alert-success mb-3">✅ Verified! Now enter your new password.</div>
            <div class="mb-3">
              <label class="form-label">New Password</label>
              <input type="password" id="newPassword" class="form-control" placeholder="Enter new password" minlength="6" required>
              <small class="form-text text-muted">Minimum 6 characters</small>
            </div>
            <div class="mb-3">
              <label class="form-label">Confirm Password</label>
              <input type="password" id="confirmPassword" class="form-control" placeholder="Confirm new password" minlength="6" required>
            </div>
            <div id="resetStatus" class="alert alert-info d-none small"></div>
          </div>
        </div>
        <div class="modal-footer">
          <div id="footerStep1">
            <button type="button" id="forgotSubmit" class="btn btn-primary">Verify Account</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
          <div id="footerStep2" class="d-none w-100">
            <button type="button" id="resetPasswordBtn" class="btn btn-success">Update Password</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Firebase Script -->
  <script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/11.0.0/firebase-app.js";
    import {
      getAuth,
      signInWithEmailAndPassword,
      GoogleAuthProvider,
      signInWithPopup,
      updatePassword,
      reauthenticateWithCredential,
      EmailAuthProvider
    } from "https://www.gstatic.com/firebasejs/11.0.0/firebase-auth.js";

    import { getDatabase, ref, get, set, update } from "https://www.gstatic.com/firebasejs/11.0.0/firebase-database.js";

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

    const statusDiv = document.getElementById("status");
    const loginForm = document.getElementById("loginForm");
    const googleBtn = document.getElementById("googleLoginBtn");

    function showStatus(msg, isError = false) {
      statusDiv.textContent = msg;
      statusDiv.classList.toggle("text-danger", isError);
      statusDiv.classList.toggle("text-success", !isError);
    }

    function showSuccessPopup(message) {
      document.getElementById("successMessage").textContent = message;
      const modal = new bootstrap.Modal(document.getElementById("successModal"));
      modal.show();
    }

    // Login handler
    loginForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      const email = document.getElementById("email").value.trim();
      const password = document.getElementById("password").value;

      if (!email || !password) {
        showStatus("Please enter email and password.", true);
        return;
      }

      try {
        showStatus("Signing in...");
        const userCredential = await signInWithEmailAndPassword(auth, email, password);
        const user = userCredential.user;

        const userRef = ref(db, "users/" + user.uid);
        let snapshot = await get(userRef);

        if (!snapshot.exists()) {
          await set(userRef, {
            username: user.displayName || "User",
            email: user.email,
            photoURL: user.photoURL || "https://via.placeholder.com/120",
            isAdmin: false,
            uid: user.uid
          });
          snapshot = await get(userRef);
        }

        const isAdmin = snapshot.val()?.isAdmin === true;

        showSuccessPopup(isAdmin ? "Admin login successful." : "Login successful.");

        setTimeout(() => {
          window.location.href = isAdmin ? "admin/admin_dashboard.php" : "home.php";
        }, 1000);

      } catch (err) {
        showStatus(err.message, true);
      }
    });

    // Google Login
    googleBtn.addEventListener("click", async () => {
      try {
        showStatus("Signing in with Google...");
        const provider = new GoogleAuthProvider();
        const result = await signInWithPopup(auth, provider);
        const user = result.user;

        const userRef = ref(db, "users/" + user.uid);
        let snapshot = await get(userRef);

        if (!snapshot.exists()) {
          await set(userRef, {
            username: user.displayName || "User",
            email: user.email,
            photoURL: user.photoURL || "https://via.placeholder.com/120",
            isAdmin: false,
            uid: user.uid
          });
        }

        const isAdmin = snapshot.val()?.isAdmin === true;

        showSuccessPopup(isAdmin ? "Admin login successful." : "Login successful.");

        setTimeout(() => {
          window.location.href = isAdmin ? "admin/admin_dashboard.php" : "home.php";
        }, 1000);

      } catch (err) {
        showStatus(err.message, true);
      }
    });

    // Forgot Password Handler (UPDATED)
    const forgotSubmit = document.getElementById('forgotSubmit');
    const resetPasswordBtn = document.getElementById('resetPasswordBtn');
    const backBtn = document.getElementById('backBtn');
    const forgotEmail = document.getElementById('forgotEmail');
    const forgotContact = document.getElementById('forgotContact');
    const newPassword = document.getElementById('newPassword');
    const confirmPassword = document.getElementById('confirmPassword');
    const forgotStatus = document.getElementById('forgotStatus');
    const resetStatus = document.getElementById('resetStatus');
    const forgotStep1 = document.getElementById('forgotStep1');
    const forgotStep2 = document.getElementById('forgotStep2');
    const footerStep1 = document.getElementById('footerStep1');
    const footerStep2 = document.getElementById('footerStep2');
    const forgotModal = document.getElementById('forgotPasswordModal');

    let verifiedUserEmail = null;

    // Step 1: Verify email and contact
    if (forgotSubmit) {
      forgotSubmit.addEventListener('click', async (e) => {
        e.preventDefault();
        const email = (forgotEmail.value || '').trim();
        const contact = (forgotContact.value || '').trim();

        if (!email || !contact) {
          showForgotError('Please enter both email and contact number.', forgotStatus);
          return;
        }

        const originalText = forgotSubmit.textContent;
        forgotSubmit.disabled = true;
        forgotSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Verifying...';
        forgotStatus.classList.remove('d-none', 'alert-danger', 'alert-success');
        forgotStatus.classList.add('alert-info');
        forgotStatus.textContent = 'Verifying account...';

        try {
          // Query database for user with matching email and contact
          const usersSnap = await get(ref(db, 'users'));
          let foundUser = null;

          if (usersSnap.exists()) {
            Object.entries(usersSnap.val()).forEach(([uid, userData]) => {
              if (userData.email === email && userData.contact === contact) {
                foundUser = { uid, ...userData };
              }
            });
          }

          if (!foundUser) {
            showForgotError('Email or contact number does not match our records.', forgotStatus);
            forgotSubmit.textContent = originalText;
            forgotSubmit.disabled = false;
            return;
          }

          // Verification successful
          verifiedUserEmail = email;
          forgotStatus.classList.remove('alert-info', 'alert-danger');
          forgotStatus.classList.add('alert-success');
          forgotStatus.textContent = ' Account verified! Proceed to reset password.';

          // Switch to step 2
          setTimeout(() => {
            forgotStep1.classList.add('d-none');
            forgotStep2.classList.remove('d-none');
            footerStep1.classList.add('d-none');
            footerStep2.classList.remove('d-none');
          }, 800);

        } catch (error) {
          console.error('Verification error:', error);
          showForgotError(error.message || 'Verification failed.', forgotStatus);
          forgotSubmit.textContent = originalText;
          forgotSubmit.disabled = false;
        }
      });
    }

    // Step 2: Reset password (FIXED - use Firebase Auth)
    if (resetPasswordBtn) {
      resetPasswordBtn.addEventListener('click', async (e) => {
        e.preventDefault();
        const pwd = newPassword.value;
        const confirmPwd = confirmPassword.value;

        if (!pwd || !confirmPwd) {
          showForgotError('Please enter both passwords.', resetStatus);
          return;
        }

        if (pwd !== confirmPwd) {
          showForgotError('Passwords do not match.', resetStatus);
          return;
        }

        if (pwd.length < 6) {
          showForgotError('Password must be at least 6 characters.', resetStatus);
          return;
        }

        const originalText = resetPasswordBtn.textContent;
        resetPasswordBtn.disabled = true;
        resetPasswordBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Updating...';
        resetStatus.classList.remove('d-none', 'alert-danger', 'alert-success');
        resetStatus.classList.add('alert-info');
        resetStatus.textContent = 'Updating password...';

        try {
          // Find user UID by email
          const usersSnap = await get(ref(db, 'users'));
          let userUid = null;

          if (usersSnap.exists()) {
            Object.entries(usersSnap.val()).forEach(([uid, userData]) => {
              if (userData.email === verifiedUserEmail) {
                userUid = uid;
              }
            });
          }

          if (!userUid) {
            throw new Error('User not found.');
          }

          // Get the Firebase user object and update password via Firebase Auth
          const user = auth.currentUser;
          
          // If no current user, need to re-authenticate first
          if (!user || user.email !== verifiedUserEmail) {
            // Create temporary auth user to update password
            // This requires the old password, so use sendPasswordResetEmail instead
            throw new Error('Please sign in first to change password.');
          }

          // Update password in Firebase Auth
          await updatePassword(user, pwd);

          // Also remove plain-text password from database if it exists
          await update(ref(db, `users/${userUid}`), {
            password: null
          });

          resetStatus.classList.remove('alert-info', 'alert-danger');
          resetStatus.classList.add('alert-success');
          resetStatus.textContent = '✅ Password updated successfully!';

          // Auto-close modal
          setTimeout(() => {
            const modal = bootstrap.Modal.getInstance(forgotModal);
            modal.hide();
            // Reset form
            forgotEmail.value = '';
            forgotContact.value = '';
            newPassword.value = '';
            confirmPassword.value = '';
            forgotStep1.classList.remove('d-none');
            forgotStep2.classList.add('d-none');
            footerStep1.classList.remove('d-none');
            footerStep2.classList.add('d-none');
            resetPasswordBtn.textContent = originalText;
            resetPasswordBtn.disabled = false;
            verifiedUserEmail = null;
          }, 1500);

        } catch (error) {
          console.error('Password update error:', error);
          showForgotError(error.message || 'Failed to update password. Try signing in first.', resetStatus);
          resetPasswordBtn.textContent = originalText;
          resetPasswordBtn.disabled = false;
        }
      });
    }

    // Back button (ADD IF MISSING)
    if (backBtn) {
      backBtn.addEventListener('click', () => {
        forgotStep1.classList.remove('d-none');
        forgotStep2.classList.add('d-none');
        footerStep1.classList.remove('d-none');
        footerStep2.classList.add('d-none');
        newPassword.value = '';
        confirmPassword.value = '';
        resetStatus.classList.add('d-none');
      });
    }

    function showForgotError(msg, statusEl) {
      statusEl.classList.remove('d-none', 'alert-info', 'alert-success');
      statusEl.classList.add('alert-danger');
      statusEl.textContent = '❌ ' + msg;
    }
  </script>

</body>
</html>
