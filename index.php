<?php
session_start();
require "db.php";


$error = "";
$success = "";


if (isset($_GET['registered'])) {
    $success = "Registration successful! You can now log in.";
}

if (isset($_POST['login'])) {

    $account = trim($_POST['account']);
    $password = $_POST['password'];
    $stmt = $conn->prepare(
        "SELECT id, username, password
         FROM users
         WHERE username = ? OR email = ?"
    );

    $stmt->bind_param("ss", $account, $account);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid username/email or password.";
        }

    } else {
        $error = "Invalid username/email or password.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #FCE7F3, #E9D5FF);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
        }
        .login-card {
            background: #FFF7FB;
            width: 100%;
            max-width: 400px;
            padding: 35px;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(150,80,160,.15);
        }
        .title {
            color: #A855A0;
            font-weight: bold;
        }
        .form-control {
            border-radius: 10px;
            padding: 11px;
        }
        .form-control:focus {
            border-color: #C084FC;
            box-shadow: 0 0 0 0.2rem rgba(192,132,252,.2);
        }
        .btn-pastel {
            background: #f7efff;
            color: white;
            border: none;
            border-radius: 10px;
            padding: 11px;
        }
        .btn-pastel:hover {
            background: #A855F7;
            color: white;
        }
        a {
            color: #ffe5ed;
            text-decoration: none;
        }
    </style>
</head>

<body>

<div class="login-card">
    <h2 class="title text-center">Welcome Back ♡</h2>
    <p class="text-center text-muted mb-4">
        Login to your account
    </p>

    <?php if ($success != ""): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>
    <?php if ($error != ""): ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <div class="mb-3">
            <label class="form-label">Username or Email</label>
            <input type="text" name="account"
                   class="form-control"
                   placeholder="Enter username or email"
                   required>
        </div>
        <div class="mb-4">
            <label class="form-label">Password</label>
            <input type="password" name="password"
                   class="form-control"
                   placeholder="Enter password"
                   required>
        </div>
        <button type="submit" name="login"
                class="btn btn-pastel w-100">
            Login
        </button>
    </form>
    <p class="text-center mt-4 mb-0">
        Don't have an account?
        <a href="register.php">Register here</a>
    </p>

</div>
</body>
</html>