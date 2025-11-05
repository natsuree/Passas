<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Welcome to PASSA</title>

  <!-- Bootstrap + Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="css/style1.css">
</head>
<body>

  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg navbar-dark sticky-top" style="background-color: #2f2f2f;">
    <div class="container-fluid">
      <!-- Logo -->
      <a class="navbar-brand d-flex align-items-center fw-semibold" href="home.php">
        <img src="image/logo.png" alt="PASSA Logo" width="175" height="55" class="me-2">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav align-items-center gap-3">
          <li class="nav-item"><a href="index.php" class="nav-link">About</a></li>
          <li class="nav-item"><a href="team.php" class="nav-link">Our Team</a></li>
          <li class="nav-item"><a href="#" class="nav-link">Contact</a></li>
          <li class="nav-item">
            <a href="login.php" class="btn btn-outline-light rounded-pill px-4">Log In</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- HERO SECTION -->
  <section class="hero position-relative text-light d-flex align-items-center" style="height: 100vh; background: url('image/1.jpg') center/cover no-repeat;">
    <div class="overlay position-absolute top-0 start-0 w-100 h-100" style="background: rgba(0, 0, 0, 0.6);"></div>
    
    <div class="container position-relative z-1 text-start">
      <h1 class="display-4 fw-bold">Building a Caring Community,<br><span class="text-warning">One Item at a Time</span></h1>
      <p class="mt-3 fs-5">Join PASSA today and explore a platform that brings community and opportunities together anytime, anywhere.</p>
      <a href="signup.php" class="btn btn-warning text-dark mt-4 px-4 py-2 rounded-pill fw-semibold">
        <i class="bi bi-person-plus"></i> Get Started
      </a>
    </div>
  </section>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
