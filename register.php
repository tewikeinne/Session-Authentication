<?php
require "db.php";
$error = "";

if (isset($_POST['register'])) {

   $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";

    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters.";

    } else {

        $hashed_password = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $hash = bin2hex(random_bytes(32));
        $stmt = $conn->prepare(
            "INSERT INTO users (username, email, password, hash)
             VALUES (?, ?, ?, ?)"
        );
 $stmt->bind_param(
            "ssss",
            $username,
            $email,
            $hashed_password,
            $hash
        );

        if ($stmt->execute()) {
            header("Location: index.php?registered=1");
            exit;
             } else {
            if ($stmt->errno == 1062) {
                $error = "Username or email already exists.";
} else {
                $error = "Registration failed. Please try again.";
            }
        }

 $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register</title>
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
        .register-card {
            background: #FFF7FB;
            width: 100%;
            max-width: 420px;
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
            background: #C084FC;
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
            color: #A855A0;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="register-card">

    <h2 class="title text-center">Create Account</h2>

    <p class="text-center text-muted mb-4">
        Register your account
    </p>

    <?php if ($error != ""): ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form method="POST">
<div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username"
                   class="form-control"
                   placeholder="Enter username"
                   maxlength="50" required>
        </div>

<div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email"
                   class="form-control"
                   placeholder="Enter email"
                   maxlength="100" required>
        </div>

<div class="mb-4">
 <label class="form-label">Password</label>
<input type="password" name="password"
                   class="form-control"
                   placeholder="Create password"
                   minlength="8" required>

<small class="text-muted">
                Minimum 8 characters
            </small>
        </div>


        <button type="submit" name="register"
                class="btn btn-pastel w-100">
            Register
        </button>


    </form>


    <p class="text-center mt-4 mb-0">
        Already have an account?
        <a href="index.php">Login here</a>
    </p>


</div>


</body>
</html>