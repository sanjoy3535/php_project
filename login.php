<?php
session_start();
require_once('database.php');

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        try {
            $stmt = $smtp->prepare("SELECT * FROM register WHERE email = :email");
            $stmt->execute([':email' => $email]);

            if ($stmt->rowCount() === 1) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                if (password_verify($password, $row['password'])) {
                    session_regenerate_id(true);
                    $_SESSION["email"] = $row['email'];
                    $_SESSION["status"] = $row['status'];

                    if ($row['status'] == "approve") {
                        header("Location: user-dashboard.php");
                        exit;
                    } elseif ($row['status'] == "pending") {
                        echo '<script>alert("Aapka account abhi approval ke liye pending hai!");</script>';
                     
                        exit;
                    }
                } else {
                    echo '<script>alert("Password galat hai!");</script>';
                }
            } else {
                echo '<script>alert("Email address galat hai!");</script>';
            }
        } catch (PDOException $e) {
            error_log($e->getMessage(), 3, 'errors.log');
            echo '<script>alert("Internal Server Error!");</script>';
        }
    } else {
        echo '<script>alert("Email aur password bharna zaroori hai!");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Login Form</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background-color: #f4f4f9;
    }

    .login-container {
      width: 100%;
      max-width: 400px;
      padding: 20px;
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .login-form h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #333;
    }

    .input-group {
      margin-bottom: 15px;
    }

    .input-group label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
      color: #555;
    }

    .input-group input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 16px;
    }

    .login-btn {
      width: 100%;
      padding: 10px;
      background-color: #007BFF;
      color: #fff;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .login-btn:hover {
      background-color: #0056b3;
    }

    .register-link {
      text-align: center;
      margin-top: 15px;
      font-size: 14px;
    }

    .register-link a {
      color: #007BFF;
      text-decoration: none;
    }

    .register-link a:hover {
      text-decoration: underline;
    }

    .show-password {
      margin-top: 10px;
      display: flex;
      align-items: center;
    }

    .show-password input {
      margin-right: 5px;
    }
  </style>
  <script>
    function togglePassword() {
      const passwordField = document.getElementById('password');
      const checkbox = document.getElementById('show-password');
      passwordField.type = checkbox.checked ? 'text' : 'password';
    }
  </script>
</head>
<body>
  <div class="login-container">
    <form class="login-form" method="POST">
      <h2>Login</h2>
      <div class="input-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Enter your email" required>
      </div>
      <div class="input-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Enter your password" required>
      </div>
      <div class="show-password">
        <input type="checkbox" id="show-password" onclick="togglePassword()">
        <label for="show-password">Show Password</label>
      </div>
      <button type="submit" class="login-btn" name="login">Login</button>
      <p class="register-link">Don't have an account? <a href="#">Register here</a></p>
    </form>
  </div>
</body>
</html>
