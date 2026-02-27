<?php
include '../koneksi.php'; 
$query = "SELECT * FROM paket WHERE nama_paket = 'Hemat' LIMIT 1";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    die("Data paket tidak ditemukan.");
}

$data = mysqli_fetch_assoc($result);

$nama_paket = $data['nama_paket'];
$harga = $data['harga'];
$min_pax = $data['minimum_pax'];
$fasilitas = $data['facility'];
$contact = $data['contact_person'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Detail Paket – Praha Agency</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />

  <style>
    * { font-family: 'Poppins', sans-serif; }

    body {
      margin: 0;
      padding: 0;
      background-color: #EDEADE;
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
    .navbar-links a:hover { color: #fff; }

    .subheader {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1.2rem 2.5rem;
      background-color: #fff;
    }
    .subheader-title {
      font-size: 1.05rem;
      font-weight: 700;
      color: #1a1a1a;
      margin: 0;
    }

    .main-content {
      padding: 2rem 2.5rem 3rem;
    }

    .detail-card {
      background-color: #DDD5C0;
      border-radius: 16px;
      padding: 2rem 2rem 2rem;
      display: flex;
      gap: 2.5rem;
      align-items: stretch;
    }

    .info-col {
      flex: 1;
      min-width: 0;
      display: flex;
      flex-direction: column;
    }

    .photo-col {
      width: 420px;
      flex-shrink: 0;
      display: flex;
      flex-direction: column;
    }
    .photo-col img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 16px;
      display: block;
    }
    .photo-placeholder {
      width: 100%;
      flex: 1;
      background-color: #c9bfa8;
      border-radius: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #888;
      font-size: 0.85rem;
    }

    .section-label {
      font-size: 0.95rem;
      font-weight: 600;
      color: #1a1a1a;
      margin-bottom: 0.5rem;
      margin-top: 1.4rem;
    }
    .section-label:first-child { margin-top: 0; }

    .field-box {
      background-color: #fff;
      border: 1.5px solid #c9bfa8;
      border-radius: 50px;
      padding: 0.7rem 1.2rem;
      font-size: 0.95rem;
      color: #1a1a1a;
      width: 100%;
    }

    .price-row {
      display: flex;
      gap: 1rem;
    }
    .price-row .field-group {
      flex: 1;
    }

    .facility-box {
      background-color: #fff;
      border: 1.5px solid #c9bfa8;
      border-radius: 16px;
      padding: 1rem 1.4rem;
      font-size: 0.88rem;
      color: #333;
      line-height: 2;
      flex: 1;
    }

    @media (max-width: 900px) {
      .detail-card { flex-direction: column; }
      .photo-col { width: 100%; }
      .photo-col img, .photo-placeholder { height: 280px; }
    }
    @media (max-width: 600px) {
      .main-content { padding: 1.5rem 1rem 2rem; }
      .navbar-Praha { padding: 1rem 1.2rem; }
      .price-row { flex-direction: column; gap: 0; }
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

  <div class="subheader">
    <p class="subheader-title">
      <?php echo "Detail Paket " . $nama_paket; ?>
    </p>
  </div>

  <div class="main-content">
    <div class="detail-card">

      <div class="info-col">

        <div class="price-row">
          <div class="field-group">
            <p class="section-label">Price</p>
            <div class="field-box">
            <?php echo "Rp. " . number_format($harga, 0, ',', '.'); ?>
            </div>
          </div>
          <div class="field-group">
            <p class="section-label">Minimum Pax</p>
            <div class="field-box">
           <?php echo $min_pax . " pax"; ?>
            </div>
          </div>
        </div>

        <p class="section-label">Facility</p>
        <div class="facility-box">
         <?php echo nl2br($fasilitas); ?>
        </div>

        <p class="section-label">Contact Person (Whatsapp)</p>
        <div class="field-box">
       <?php echo $contact; ?>
        </div>

      </div>

      <div class="photo-col">
        <img src="YOUR_IMAGE_PATH_HERE" alt="Foto Paket"
          onerror="this.style.display='none';this.nextElementSibling.style.display='flex';" />
        <div class="photo-placeholder" style="display:none;">Foto Paket</div>
      </div>

    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>