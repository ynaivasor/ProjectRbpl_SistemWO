<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $username  = mysqli_real_escape_string($conn, $_POST['username']);
    $role_name = mysqli_real_escape_string($conn, $_POST['role']);
    $password  = mysqli_real_escape_string($conn, $_POST['password']);

    $role_query = mysqli_query($conn, "SELECT id_role FROM roles WHERE role_name = '$role_name'");
    $role_data  = mysqli_fetch_assoc($role_query);

    if (!$role_data) {
        die("Role tidak ditemukan!");
    }

    $role_id = $role_data['id_role'];

    $query = "INSERT INTO users (full_name, username, id_role, password)
              VALUES ('$full_name', '$username', '$role_id', '$password')";

    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('Registrasi berhasil!');
                window.location.href='login.php';
              </script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register – Praha Agency</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />

  <style>
    * {
      font-family: 'Poppins', sans-serif;
    }

    body {
      margin: 0;
      padding: 0;
      background-color: #fff;
      min-height: 100vh;
    }

    .page-wrapper {
      display: flex;
      min-height: 100vh;
    }

    .left-col {
      width: 45%;
      position: relative;
      overflow: hidden;
      background-color: #d4c9b0;
    }

    .left-col img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      object-position: center;
      display: block;
    }

    .left-wave {
      position: absolute;
      top: 0;
      right: -2px;
      height: 100%;
      width: 80px;
    }

    .right-col {
      width: 55%;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 3rem 4rem;
      background-color: #fff;
    }

    .form-box {
      width: 100%;
      max-width: 460px;
    }

    .brand-title {
      font-size: 1.7rem;
      font-weight: 700;
      letter-spacing: 0.07em;
      color: #1a1a1a;
      margin-bottom: 2.5rem;
      text-align: center;
    }

    .input-field,
    .select-field {
      width: 100%;
      padding: 0.85rem 1.2rem;
      border: 1.5px solid #d0ccc5;
      border-radius: 50px;
      background-color: #fff;
      font-size: 0.95rem;
      color: #333;
      outline: none;
      transition: border-color 0.2s, box-shadow 0.2s;
      margin-bottom: 1.3rem;
      appearance: none;
      -webkit-appearance: none;
    }

    .input-field::placeholder {
      color: #aaa;
    }

    .input-field:focus,
    .select-field:focus {
      border-color: #b5a98a;
      box-shadow: 0 0 0 3px rgba(181, 169, 138, 0.2);
    }

    .select-wrapper {
      position: relative;
      margin-bottom: 1.3rem;
    }

    .select-wrapper .select-field {
      margin-bottom: 0;
      padding-right: 2.8rem;
      cursor: pointer;
      color: #aaa; 
    }

    .select-wrapper .select-field.selected {
      color: #333;
    }

    .select-arrow {
      position: absolute;
      right: 1.2rem;
      top: 50%;
      transform: translateY(-50%);
      pointer-events: none;
      color: #b5a98a;
      font-size: 0.85rem;
    }

    .btn-signup {
      display: block;
      width: 60%;
      margin: 0.5rem auto 0;
      padding: 0.85rem;
      border-radius: 50px;
      font-size: 1.1rem;
      font-weight: 500;
      text-align: center;
      cursor: pointer;
      border: 2px solid #b5a98a;
      background-color: #DDD5C0;
      color: #1a1a1a;
      text-decoration: none;
      transition: background-color 0.25s, color 0.25s, border-color 0.25s, box-shadow 0.25s;
    }

    .btn-signup:hover,
    .btn-signup:focus {
      background-color: #b5a98a;
      color: #fff;
      border-color: #b5a98a;
      box-shadow: 0 4px 18px rgba(181, 169, 138, 0.4);
    }

    .btn-signup:active {
      background-color: #9e9070;
      border-color: #9e9070;
    }

    .login-text {
      margin-top: 1.2rem;
      font-size: 0.82rem;
      color: #555;
      text-align: center;
    }

    .login-link {
      color: #555;
      text-decoration: none;
      font-weight: 500;
      transition: color 0.2s;
    }

    .login-link:hover {
      color: #3a7d44;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .page-wrapper { flex-direction: column; }
      .left-col, .right-col { width: 100%; }
      .left-col { height: 40vh; }
      .right-col { padding: 3rem 2rem; }
      .left-wave { display: none; }
      .btn-signup { width: 80%; }
    }
  </style>
</head>
<body>

<div class="page-wrapper">

  <div class="left-col">
    <img src="assets/logreg.jpg" alt="Praha Agency" />
    <svg class="left-wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 80 900" preserveAspectRatio="none">
      <path
        d="M0,0 C30,150 60,300 30,450 C60,600 30,750 0,900 L80,900 L80,0 Z"
        fill="#ffffff"
      />
    </svg>
  </div>
  <div class="right-col">
    <div class="form-box">
      <p class="brand-title">PRAHA AGENCY</p>

      <form method="POST" action="register.php">

        <input
          type="text"
          name="full_name"
          class="input-field"
          placeholder="Full Name"
          required
        />

        <input
          type="text"
          name="username"
          class="input-field"
          placeholder="Username"
          required
        />

        <div class="select-wrapper">
          <select
            name="role"
            id="roleSelect"
            class="select-field"
            required
            onchange="this.classList.add('selected')"
          >
            <option value="" disabled selected hidden>Role</option>
            <option value="client">Client</option>
            <option value="staff_wo">Staff</option>
            <option value="vendor">Vendor</option>
          </select>
          <span class="select-arrow">&#8964;</span>
        </div>

        <input
          type="password"
          name="password"
          class="input-field"
          placeholder="Password"
          required
        />

        <button type="submit" class="btn-signup">Sign Up</button>
      </form>

      <p class="login-text">
        Sudah punya akun?
        <a href="login.php" class="login-link">Masuk Sekarang</a>
      </p>
    </div>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>