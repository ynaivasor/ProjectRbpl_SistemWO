<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = "SELECT users.*, roles.role_name 
              FROM users
              JOIN roles ON users.id_role = roles.id_role
              WHERE users.username = '$username'
              AND users.password = '$password'";

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {

        $data = mysqli_fetch_assoc($result);

        $_SESSION['id_user'] = $data['id_user'];
        $_SESSION['full_name'] = $data['full_name'];
        $_SESSION['username'] = $data['username'];
        $_SESSION['role'] = $data['role_name'];

        if ($data['role_name'] == "client") {
            header("Location: client/menu.php");
            exit();
        } 
        elseif ($data['role_name'] == "owner" || $data['role_name'] == "staff_wo") {
            header("Location: admin/dashboardAdmin.php");
            exit();
        } 
        elseif ($data['role_name'] == "vendor") {
            header("Location: vendor/dashboardVendor.php");
            exit();
        } 
        else {
            echo "Role tidak dikenali.";
        }

        exit();

    } else {
        echo "<script>
                alert('Username atau password salah!');
                window.location.href='login.php';
              </script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login – Praha Agency</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />

  <style>
    * {
      font-family: 'Poppins', sans-serif;
    }

    body {
      margin: 0;
      padding: 0;
      background-color: #EAEAEA;
      min-height: 100vh;
    }

    .page-wrapper {
      display: flex;
      min-height: 100vh;
    }

    .left-col {
      width: 55%;
      display: flex;
      align-items: center;
      padding: 3rem 5rem;
      background-color: #EAEAEA;
    }

    .form-box {
      width: 100%;
      max-width: 420px;
    }

    .brand-title {
      font-size: 1.7rem;
      font-weight: 700;
      letter-spacing: 0.07em;
      color: #1a1a1a;
      margin-bottom: 2.8rem;
    }

    .input-field {
      width: 100%;
      padding: 0.85rem 1.2rem;
      border: 1.5px solid #d0ccc5;
      border-radius: 50px;
      background-color: #fff;
      font-size: 0.95rem;
      color: #333;
      outline: none;
      transition: border-color 0.2s;
      margin-bottom: 1.4rem;
    }

    .input-field::placeholder {
      color: #aaa;
    }

    .input-field:focus {
      border-color: #b5a98a;
      box-shadow: 0 0 0 3px rgba(181, 169, 138, 0.2);
    }

    .btn-login {
      display: block;
      width: 100%;
      padding: 0.85rem;
      border-radius: 50px;
      font-size: 1.1rem;
      font-weight: 500;
      text-align: center;
      cursor: pointer;
      text-decoration: none;
      transition: background-color 0.25s ease, color 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease;
      margin-top: 0.5rem;

      background-color: #DDD5C0;
      color: #1a1a1a;
      border: 2px solid #b5a98a;
    }

    .btn-login:hover,
    .btn-login:focus {
      background-color: #b5a98a;
      color: #fff;
      border-color: #b5a98a;
      box-shadow: 0 4px 18px rgba(181, 169, 138, 0.4);
    }

    .btn-login:active {
      background-color: #9e9070;
      border-color: #9e9070;
    }

    .register-text {
      margin-top: 1.2rem;
      font-size: 0.82rem;
      color: #555;
      text-align: center;
    }

    .register-link {
      color: #555;
      text-decoration: none;
      font-weight: 500;
      transition: color 0.2s;
    }

    .register-link:hover {
      color: #3a7d44;
    }

    .right-col {
      width: 45%;
      position: relative;
      overflow: hidden;
    }

    .right-col img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      object-position: center;
      display: block;
    }

    .right-wave {
      position: absolute;
      top: 0;
      left: -2px;
      height: 100%;
      width: 80px;
    }

    @media (max-width: 768px) {
      .page-wrapper {
        flex-direction: column;
      }
      .left-col,
      .right-col {
        width: 100%;
      }
      .left-col {
        padding: 3rem 2rem;
        justify-content: center;
      }
      .right-col {
        height: 40vh;
      }
      .right-wave {
        display: none;
      }
    }
  </style>
</head>
<body>

<div class="page-wrapper">
  <div class="left-col">
    <div class="form-box">
      <p class="brand-title">PRAHA AGENCY</p>

      <form method="POST" action="login.php">
        <input
          type="text"
          name="username"
          class="input-field"
          placeholder="Username"
          required
        />
        <input
          type="password"
          name="password"
          class="input-field"
          placeholder="Password"
          required
        />
        <button type="submit" class="btn-login">Log In</button>
      </form>

      <p class="register-text">
        Belum punya akun?
        <a href="register.php" class="register-link">Daftar Sekarang</a>
      </p>
    </div>
  </div>

  <div class="right-col">
    <img src="assets/logreg.jpg" alt="Praha Agency" />
    <svg class="right-wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 80 900" preserveAspectRatio="none">
      <path
        d="M80,0 C50,150 20,300 50,450 C20,600 50,750 80,900 L0,900 L0,0 Z"
        fill="#EAEAEA"
      />
    </svg>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>