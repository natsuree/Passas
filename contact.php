<?php
<?php
session_start();
include("connection.php");
if (!isset($_SESSION['username'])) {
    header("location:login.php");
    exit;
}

$sent = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name && filter_var($email, FILTER_VALIDATE_EMAIL) && $subject && $message) {
        // Optionally store in DB or send email here.
        $sent = true;
    } else {
        $error = 'Please complete all fields with valid information.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - PASSA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="home.php"><i class="bi bi-chat"></i> PASSA</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="home.php#home">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="home.php#about">About Us</a></li>
                <li class="nav-item"><a class="nav-link" href="home.php#projects">Projects</a></li>
                <li class="nav-item"><a class="nav-link active" href="contact.php">Contact</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdownMenuLink" data-bs-toggle="dropdown"
                        aria-expanded="false"><i class="bi bi-person"></i></a>
                    <ul class="dropdown-menu mt-2" aria-labelledby="dropdownMenuLink">
                        <?php
                        $id = $_SESSION['id'];
                        $query = mysqli_query($conn, "SELECT * FROM users WHERE id = $id");
                        while ($result = mysqli_fetch_assoc($query)) {
                            $res_id = $result['id'];
                        }
                        echo "<li><a class='dropdown-item' href='edit.php?id=$res_id'>Change Profile</a></li>";
                        ?>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
</header>

<main class="py-5">
    <div class="container">
        <?php if ($sent): ?>
            <div class="alert alert-success">Message sent successfully. Thank you, <?= htmlspecialchars($name) ?>.</div>
        <?php elseif ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="row gy-4">
            <h1>Contact us</h1>

            <div class="col-lg-6">
                <div class="row gy-4">
                    <div class="col-md-6">
                        <div class="info-box">
                            <i class="bi bi-geo-alt"></i>
                            <h3>Address</h3>
                            <p>A108 Adam Street,<br>New Delhi, 535022</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box">
                            <i class="bi bi-telephone"></i>
                            <h3>Call Us</h3>
                            <p>+91 9876545672<br>+91 8763456243</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box">
                            <i class="bi bi-envelope"></i>
                            <h3>Email Us</h3>
                            <p>bragspot@gmail.com<br>brag@gmail.com</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box">
                            <i class="bi bi-clock"></i>
                            <h3>Open Hours</h3>
                            <p>Monday - Friday<br>9:00AM - 05:00PM</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 form">
                <form action="contact.php" method="post" class="php-email-form">
                    <div class="row gy-4">
                        <div class="col-md-6">
                            <input type="text" name="name" class="form-control" placeholder="Your Name" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <input type="email" class="form-control" name="email" placeholder="Your Email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                        </div>
                        <div class="col-md-12">
                            <input type="text" class="form-control" name="subject" placeholder="Subject" required value="<?= htmlspecialchars($_POST['subject'] ?? '') ?>">
                        </div>
                        <div class="col-md-12">
                            <textarea class="form-control" name="message" rows="5" placeholder="Message" required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                        </div>
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn">Send Message</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</main>

<footer>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-12 col-sm-12">
                <p class="logo"><i class="bi bi-chat"></i> Brag Spot</p>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12">
                <ul class="d-flex justify-content-center list-unstyled gap-3 m-0">
                    <li><a href="home.php#home">Home</a></li>
                    <li><a href="home.php#projects">Projects</a></li>
                    <li><a href="home.php#about">About Us</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-12 col-sm-12 text-end">
                <p>&copy; 2023 Brag Spot</p>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>