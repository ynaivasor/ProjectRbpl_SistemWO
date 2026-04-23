<?php
include '../koneksi.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Praha Agency – Menu</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <style>
    :root {
      --bg:        #f5f2ed;
      --surface:   #ddd8ce;
      --surface-hover: #cec9be;
      --accent:    #a89880;
      --accent-dark: #7a6e61;
      --text:      #1e1c1a;
      --text-muted:#6b6259;
      --border:    #c8c1b5;
      --icon-bg:   #a89880;
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Poppins', sans-serif;
      background: var(--bg);
      color: var(--text);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* ── NAVBAR ── */
    nav {
      background: var(--surface);
      border-bottom: 1px solid var(--border);
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0 2.5rem;
      height: 60px;
    }

    .nav-brand {
       font-family: 'Poppins', sans-serif;
      font-weight: 700;
      font-size: 1.15rem;
      letter-spacing: 0.04em;
      color: var(--text);
    }

    .nav-logout {
      font-family: 'Poppins', sans-serif;
      font-size: 0.85rem;
      font-weight: 500;
      color: var(--text);
      text-decoration: none;
      letter-spacing: 0.03em;
      padding: 6px 16px;
      border: 1px solid var(--border);
      border-radius: 4px;
      transition: background 0.18s, color 0.18s;
    }
    .nav-logout:hover {
      background: var(--accent-dark);
      color: #fff;
      border-color: var(--accent-dark);
    }

    /* ── MAIN ── */
    main {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 3.5rem 1.5rem 4rem;
    }

    /* ── GREETING ── */
    .greeting {
      text-align: center;
      margin-bottom: 3rem;
    }
    .greeting-sub {
      font-size: 0.9rem;
      font-weight: 400;
      color: var(--text-muted);
      letter-spacing: 0.06em;
      text-transform: uppercase;
      margin-bottom: 0.35rem;
    }
    .greeting-name {
      font-family: 'Poppins', sans-serif;
      font-size: 2.6rem;
      font-weight: 700;
      color: var(--text);
      line-height: 1.1;
    }

    /* ── MENU SECTION ── */
    .menu-section {
      width: 100%;
      max-width: 780px;
    }

    .menu-label {
      font-size: 0.8rem;
      font-weight: 500;
      color: var(--text-muted);
      letter-spacing: 0.08em;
      text-transform: uppercase;
      margin-bottom: 1rem;
    }

    .menu-list {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    /* ── MENU CARD ── */
    .menu-card {
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 8px;
      padding: 1.4rem 1.6rem;
      text-decoration: none;
      color: var(--text);
      cursor: pointer;
      transition: background 0.18s, transform 0.15s, box-shadow 0.18s;
      position: relative;
      overflow: hidden;
    }

    .menu-card::before {
      content: '';
      position: absolute;
      left: 0; top: 0; bottom: 0;
      width: 3px;
      background: var(--accent-dark);
      transform: scaleY(0);
      transform-origin: center;
      transition: transform 0.18s;
    }

    .menu-card:hover {
      background: var(--surface-hover);
      transform: translateY(-2px);
      box-shadow: 0 6px 18px rgba(0,0,0,0.08);
    }
    .menu-card:hover::before { transform: scaleY(1); }

    .menu-title {
      font-family: 'Poppins', sans-serif;
      font-size: 1.3rem;
      font-weight: 600;
      letter-spacing: 0.01em;
    }

    .menu-icon {
      width: 46px;
      height: 46px;
      background: var(--icon-bg);
      border-radius: 6px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }
    .menu-icon svg {
      width: 22px;
      height: 22px;
      fill: var(--text);
    }
  </style>
</head>
<body>

<!-- ── NAVBAR ── -->
<nav>
  <span class="nav-brand">Praha Agency</span>
  <a href="#" class="nav-logout">Log Out</a>
</nav>

<!-- ── MAIN ── -->
<main>

  <!-- Greeting -->
  <div class="greeting">
    <p class="greeting-sub">Selamat Datang</p>
    <h1 class="greeting-name">
      <?php echo $username ?>
    </h1>
  </div>

  <!-- Menu -->
  <section class="menu-section">
    <p class="menu-label">Pilih Menu:</p>

    <div class="menu-list">

      <!-- Kelola Buku Rantaman -->
      <a href="../admin/listBukuRantaman.php" class="menu-card">
        <span class="menu-title">Kelola Buku Rantaman</span>
        <span class="menu-icon">
          <!-- list / edit icon -->
          <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M3 5h12v2H3V5zm0 4h12v2H3V9zm0 4h8v2H3v-2zm13.41 2.83L18 14.24l1.59 1.59-5.66 5.66L12 21.07l.01-1.66 4.4-4.4zm3.17-1.41a1 1 0 0 0-1.42 0l-1.06 1.06 2.83 2.83 1.06-1.06a1 1 0 0 0 0-1.42l-1.41-1.41z"/>
          </svg>
        </span>
      </a>

      <!-- Kirim Briefing Rancangan ke Vendor -->
      <a href="" class="menu-card">
        <span class="menu-title">Kirim Briefing Rancangan ke Vendor</span>
        <span class="menu-icon">
          <!-- send / paper plane icon -->
          <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
          </svg>
        </span>
      </a>

      <!-- Kelola Keuangan -->
      <a href="" class="menu-card">
        <span class="menu-title">Kelola Keuangan</span>
        <span class="menu-icon">
          <!-- finance / transfer icon -->
          <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 14H4V6h16v12zM6 10h2v2H6v-2zm0 4h8v2H6v-2zm10 0h2v2h-2v-2zm-6-4h8v2h-8v-2z"/>
          </svg>
        </span>
      </a>

    </div>
  </section>

</main>

</body>
</html>