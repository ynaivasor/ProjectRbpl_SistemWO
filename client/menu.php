<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pilihan Paket – Praha Agency</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />

  <style>
    * { font-family: 'Poppins', sans-serif; }

    body {
      margin: 0;
      padding: 0;
      background-color: #fff;
    }

    .navbar-Praha {
      background-color: #b5a98a;
      padding: 1rem 2.5rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .navbar-brand-text {
      font-size: 1.15rem;
      font-weight: 600;
      color: #1a1a1a;
      text-decoration: none;
    }

    .navbar-links {
      display: flex;
      gap: 2.5rem;
      list-style: none;
      margin: 0;
      padding: 0;
    }

    .navbar-links a {
      font-size: 0.95rem;
      font-weight: 500;
      color: #1a1a1a;
      text-decoration: none;
      transition: color 0.2s;
    }

    .navbar-links a:hover {
      color: #fff;
    }

    .hero-section {
      text-align: center;
      padding: 3rem 1rem 1.5rem;
    }

    .hero-section .subtitle {
      font-size: 1rem;
      font-weight: 400;
      color: #555;
      margin-bottom: 0.2rem;
    }

    .hero-section .title {
      font-size: 1.8rem;
      font-weight: 700;
      color: #1a1a1a;
    }

    .paket-section {
      padding: 2rem 3rem 4rem;
    }

    .paket-section h2 {
      text-align: center;
      font-size: 1.5rem;
      font-weight: 700;
      color: #1a1a1a;
      margin-bottom: 2.5rem;
    }

    .paket-cards {
      display: flex;
      gap: 1.5rem;
      justify-content: center;
      flex-wrap: wrap;
    }

    .paket-card {
      border: 1.5px solid #d0ccc5;
      border-radius: 4px;
      overflow: hidden;
      width: 320px;
      flex-shrink: 0;
    }

    .card-label {
      background-color: #DDD5C0;
      text-align: center;
      padding: 0.65rem 1rem;
      font-size: 1rem;
      font-weight: 600;
      color: #1a1a1a;
    }

    .card-img {
      width: 100%;
      height: 320px;
      object-fit: cover;
      display: block;
    }

    .card-img-placeholder {
      width: 100%;
      height: 320px;
      background-color: #e8e2d8;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #aaa;
      font-size: 0.85rem;
    }

    .card-actions {
      border-top: 1.5px solid #d0ccc5;
    }

    .card-action-btn {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0.75rem 1.1rem;
      font-size: 0.9rem;
      font-weight: 500;
      color: #1a1a1a;
      text-decoration: none;
      transition: background-color 0.2s, color 0.2s;
      border: none;
      background: transparent;
      width: 100%;
      cursor: pointer;
    }

    .card-action-btn:hover {
      background-color: #DDD5C0;
      color: #1a1a1a;
    }

    .card-action-btn + .card-action-btn {
      border-top: 1.5px solid #d0ccc5;
    }

    .card-action-btn .chevron {
      font-size: 1rem;
      color: #888;
    }

    .card-action-btn.booking {
      background-color: #e8e0d0;
    }

    .card-action-btn.booking:hover {
      background-color: #b5a98a;
      color: #fff;
    }

    .card-action-btn.booking:hover .chevron {
      color: #fff;
    }

    @media (max-width: 768px) {
      .paket-section { padding: 2rem 1rem 3rem; }
      .paket-card { width: 100%; max-width: 380px; }
      .navbar-Praha { padding: 1rem 1.5rem; }
      .navbar-links { gap: 1.2rem; }
    }
  </style>
</head>
<body>

  <nav class="navbar-Praha">
    <a href="#" class="navbar-brand-text">Praha Agency</a>
    <ul class="navbar-links">
      <li><a href="dashboard_transaksi.php">Transaksi</a></li>
      <li><a href="bukurantaman.php">Buku Rantaman</a></li>
      <li><a href="login.php">Log Out</a></li>
    </ul>
  </nav>

  <div class="hero-section">
    <p class="subtitle">Selamat Datang</p>
    <p class="title">Praha Agency</p>
  </div>

  <div class="paket-section">
    <h2>Pilihan Paket kami</h2>

    <div class="paket-cards">

      <div class="paket-card">
        <div class="card-label">ALL IN</div>
    
        <img src="../assets/allin.jpg" alt="Paket ALL IN" class="card-img" onerror="this.style.display='none';this.nextElementSibling.style.display='flex';" />
        <div class="card-img-placeholder" style="display:none;">Foto Paket ALL IN</div>
        <div class="card-actions">
          <a href="allin.php" class="card-action-btn">
            <span>Lihat Detail</span>
            <span class="chevron">&#8250;</span>
          </a>
          <a href="payment.php?paket=allin" class="card-action-btn booking">
            <span>Booking sekarang</span>
            <span class="chevron">&#8250;</span>
          </a>
        </div>
      </div>

      <!-- Card Premium -->
      <div class="paket-card">
        <div class="card-label">Premium</div>
        <img src="../assets/premium.jpg" alt="Paket Premium" class="card-img" onerror="this.style.display='none';this.nextElementSibling.style.display='flex';" />
        <div class="card-img-placeholder" style="display:none;">Foto Paket Premium</div>
        <div class="card-actions">
          <a href="premium.php" class="card-action-btn">
            <span>Lihat Detail</span>
            <span class="chevron">&#8250;</span>
          </a>
          <a href="payment.php?paket=premium" class="card-action-btn booking">
            <span>Booking sekarang</span>
            <span class="chevron">&#8250;</span>
          </a>
        </div>
      </div>

      <!-- Card Hemat -->
      <div class="paket-card">
        <div class="card-label">Hemat</div>
        <img src="../assets/hemat.jpg" alt="Paket Hemat" class="card-img" onerror="this.style.display='none';this.nextElementSibling.style.display='flex';" />
        <div class="card-img-placeholder" style="display:none;">Foto Paket Hemat</div>
        <div class="card-actions">
          <a href="hemat.php" class="card-action-btn">
            <span>Lihat Detail</span>
            <span class="chevron">&#8250;</span>
          </a>
          <a href="payment.php?paket=hemat" class="card-action-btn booking">
            <span>Booking sekarang</span>
            <span class="chevron">&#8250;</span>
          </a>
        </div>
      </div>

    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>