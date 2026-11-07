<?php
session_start();
require "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare(
    "SELECT id, username, email, created_at
     FROM users
     WHERE id = ?"
);

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    $_SESSION = [];
    session_destroy();


    header("Location: index.php");
    exit;
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f19ec8;
            font-family: Arial, sans-serif;
            min-height: 100vh;
        }
        .navbar {
            background: #d6afff;
        }
        .navbar-brand {
            color: #9333A8;
            font-weight: bold;
        }
        .welcome-card {
            background: linear-gradient(135deg, #ffaedc, #E9D5FF);
            border: none;
            border-radius: 20px;
            padding: 40px 25px;
            box-shadow: 0 8px 25px rgba(150,80,160,.12);
        }
        .welcome-title {
            color: #9333A8;
            font-weight: bold;
        }
        .info-card {
            background: white;
            border: 1px solid #F3D8F0;
            border-radius: 15px;
            padding: 25px;
            height: 100%;
        }
        .info-label {
            color: #A855A0;
            font-weight: bold;
        }
        .btn-pastel {
            background: #C084FC;
            color: white;
            border: none;
            border-radius: 10px;
            padding: 10px 25px;
        }
        .btn-pastel:hover {
            background: #A855F7;
            color: white;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg">
    <div class="container">

        <a class="navbar-brand" href="dashboard.php">
        </a>

        <a href="logout.php" class="btn btn-pastel">
            Logout
        </a>

    </div>
</nav>

<div class="container py-5">

    <div class="welcome-card text-center mb-4">

        <h1 class="welcome-title">
            Welcome, <?php echo htmlspecialchars($user['username']); ?>! ♡
        </h1>

        <p class="text-secondary mt-3 mb-2">
            You have successfully logged into your account.
        </p>

        <span class="badge rounded-pill text-bg-light">
            Session Active
        </span>


    </div>


    <h4 class="mb-3" style="color:#A855A0;">
        Account Information
    </h4>


    <div class="row g-3">


        <div class="col-md-6">
            <div class="info-card">
                <p class="info-label">User ID</p>
                <h5><?php echo (int)$user['id']; ?></h5>
            </div>
        </div>


        <div class="col-md-6">
            <div class="info-card">
                <p class="info-label">Username</p>
                <h5><?php echo htmlspecialchars($user['username']); ?></h5>
            </div>
        </div>


        <div class="col-md-6">
            <div class="info-card">
                <p class="info-label">Email Address</p>
                <h5><?php echo htmlspecialchars($user['email']); ?></h5>
            </div>
        </div>


        <div class="col-md-6">
            <div class="info-card">
                <p class="info-label">Created At</p>
                <h5><?php echo htmlspecialchars($user['created_at']); ?></h5>
            </div>
        </div>


    </div>


    <p class="text-center text-muted mt-5">
        Thank you for visiting! Have a lovely day ♡
    </p>


</div>


</body>
</html>