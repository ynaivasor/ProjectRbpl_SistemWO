<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['id_users'])) {
    header('Location: ../login.php');
    exit;
}

$idUser = $_SESSION['id_users'];

$paketSlug = isset($_GET['paket']) ? strtolower(trim($_GET['paket'])) : '';
if (empty($paketSlug)) {
    header('Location: menu.php');
    exit;
}

$paketSlugEsc = mysqli_real_escape_string($conn, $paketSlug);
$qPaket = mysqli_query($conn, "SELECT * FROM paket WHERE LOWER(REPLACE(nama_paket, ' ', '')) = '$paketSlugEsc' LIMIT 1");

if (!$qPaket || mysqli_num_rows($qPaket) === 0) {
    $qPaket = mysqli_query($conn, "SELECT * FROM paket WHERE LOWER(nama_paket) LIKE '%$paketSlugEsc%' LIMIT 1");
}

if ($qPaket && mysqli_num_rows($qPaket) > 0) {
    $paket = mysqli_fetch_assoc($qPaket);
    $idPaket = $paket['id_paket'];
    $namaPaket = htmlspecialchars($paket['nama_paket']);
    $harga = $paket['harga'];
    $minPax = $paket['minimum_pax'];
    $nominalFmt = 'Rp. ' . number_format($harga, 0, ',', '.');
} else {
    echo "<div style='padding: 30px; font-family: Poppins, sans-serif; text-align: center; background: #fdecea; color: #c0392b; border: 1px solid #f5c6c2; margin: 50px auto; max-width: 600px; border-radius: 8px;'>";
    echo "<h2>Paket Tidak Ditemukan!</h2>";
    echo "<p>Sistem mencari paket dengan keyword: <strong>" . htmlspecialchars($paketSlug) . "</strong> namun data tersebut tidak ada di database.</p>";
    echo "<p style='color: #555; font-size: 0.9rem;'>Solusi: Buka phpMyAdmin, cek tabel <strong>paket</strong>, dan pastikan kolom <strong>nama_paket</strong> sudah diisi dengan nama 'ALL IN', 'Premium', atau 'Hemat'.</p>";
    echo "<br><a href='menu.php' style='padding: 10px 20px; background: #a89880; color: white; text-decoration: none; border-radius: 5px; font-weight: bold;'>Kembali ke Menu</a>";
    echo "</div>";
    exit;
}

$errorMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selesaikan'])) {

    $stmtTrx = mysqli_prepare($conn, "INSERT INTO transaksi (id_users, id_paket, total_kontrak, status_utama, created_at) VALUES (?, ?, ?, 'pending', NOW())");
    mysqli_stmt_bind_param($stmtTrx, 'iid', $idUser, $idPaket, $harga);

    if (mysqli_stmt_execute($stmtTrx)) {
        
        $id_transaksi_baru = mysqli_insert_id($conn);
        mysqli_stmt_close($stmtTrx);

        $termin_data = [
            ['keterangan' => 'DP 10%', 'nominal' => $harga * 0.10, 'deadline' => date('Y-m-d', strtotime('+3 days'))],
            ['keterangan' => 'DP 80%', 'nominal' => $harga * 0.80, 'deadline' => date('Y-m-d', strtotime('+1 month'))],
            ['keterangan' => 'Pelunasan 10%', 'nominal' => $harga * 0.10, 'deadline' => date('Y-m-d', strtotime('+2 month'))]
        ];

        foreach ($termin_data as $t) {
            $stmtTermin = mysqli_prepare($conn, "INSERT INTO pembayaran_termin (id_transaksi, keterangan, nominal, deadline, status) VALUES (?, ?, ?, ?, 'Pending')");
            mysqli_stmt_bind_param($stmtTermin, 'isds', $id_transaksi_baru, $t['keterangan'], $t['nominal'], $t['deadline']);
            mysqli_stmt_execute($stmtTermin);
            mysqli_stmt_close($stmtTermin);
        }

        $stmtBuku = mysqli_prepare($conn, "INSERT INTO bukuRantaman (namaPengantinPria, namaPengantinWanita, tanggalPelaksanaan, contactPerson, lokasi, dataStaffWo, dataVendor, fileRancangan, idUser, status) VALUES ('', '', '', '', '', '', '', '', ?, 'on going')");
        mysqli_stmt_bind_param($stmtBuku, 'i', $idUser);

        if (mysqli_stmt_execute($stmtBuku)) {
            mysqli_stmt_close($stmtBuku);
            header('Location: rantamanUser.php?');
            exit;
        } else {
            $errorMsg = 'Transaksi berhasil, tapi gagal membuat buku rantaman. Hubungi admin.';
            mysqli_stmt_close($stmtBuku);
        }
    } else {
        $errorMsg = 'Gagal membuat transaksi. Silakan coba lagi.';
        mysqli_stmt_close($stmtTrx);
    }
}

$qrisPath = '../assets/img/qris.png';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Praha Agency – Bayar Tagihan</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet"/>
  <style>
    :root {
      --bg:            #f5f2ed;
      --surface:       #ddd8ce;
      --surface-hover: #cec9be;
      --accent:        #a89880;
      --accent-dark:   #7a6e61;
      --text:          #1e1c1a;
      --text-muted:    #6b6259;
      --border:        #b8b0a4;
      --input-bg:      #ffffff;
      --radius-card:   14px;
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

    /* NAVBAR */
    nav {
      background: var(--surface);
      border-bottom: 1px solid var(--border);
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0 2rem;
      height: 58px;
    }
    .nav-brand { font-weight: 700; font-size: 1rem; }
    .nav-links  { display: flex; gap: 1.6rem; list-style: none; }
    .nav-links a {
      font-size: 0.85rem; font-weight: 500; color: var(--text);
      text-decoration: none; transition: color 0.15s;
    }
    .nav-links a:hover { color: var(--accent-dark); }
    .nav-logout {
      font-size: 0.82rem; font-weight: 500; color: var(--text);
      text-decoration: none; padding: 5px 14px;
      border: 1px solid var(--border); border-radius: 5px;
      transition: background 0.15s;
    }
    .nav-logout:hover { background: var(--accent-dark); color: #fff; border-color: var(--accent-dark); }

    /* PAGE TITLE */
    .page-title-wrap {
      text-align: center;
      padding: 1.5rem 1rem 0.6rem;
      font-weight: 700;
      font-size: 1rem;
    }

    /* MAIN */
    main {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      padding: 1rem 1.2rem 3rem;
    }

    /* CARD */
    .card {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: var(--radius-card);
      padding: 2rem 2.4rem;
      width: 100%;
      max-width: 820px;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 2.4rem;
      align-items: start;
    }

    /* QRIS */
    .qris-section { display: flex; flex-direction: column; gap: 0.8rem; }
    .qris-label { font-size: 0.88rem; font-weight: 700; }
    .qris-img-wrap {
      background: var(--input-bg);
      border: 1px solid var(--border);
      border-radius: 10px;
      padding: 0.6rem;
      display: flex;
      justify-content: center;
    }
    .qris-img-wrap img { width: 100%; max-width: 240px; height: auto; display: block; }

    /* INFO */
    .info-section { display: flex; flex-direction: column; gap: 1.2rem; }
    .info-block .label { font-size: 0.78rem; font-weight: 700; margin-bottom: 0.15rem; }
    .info-block .value { font-size: 1.4rem; font-weight: 400; line-height: 1.2; }
    .info-block .value.small { font-size: 1rem; }

    .badge-paket {
      display: inline-block;
      font-size: 0.72rem; font-weight: 700;
      background: var(--accent-dark); color: #fff;
      padding: 3px 12px; border-radius: 999px;
      letter-spacing: 0.04em; text-transform: uppercase;
    }

    .alert {
      padding: 0.6rem 1rem; border-radius: 7px;
      font-size: 0.78rem; font-weight: 500;
      background: #fdecea; color: #c0392b;
      border: 1px solid #f5c6c2;
    }

    .btn-selesaikan {
      font-family: 'Poppins', sans-serif;
      font-size: 0.85rem; font-weight: 700;
      padding: 0.75rem 1.4rem; border-radius: 8px;
      border: 1.5px solid var(--accent-dark);
      background: var(--accent-dark); color: #fff;
      cursor: pointer; width: 100%;
      transition: background 0.15s; letter-spacing: 0.02em;
    }
    .btn-selesaikan:hover  { background: #5e5448; border-color: #5e5448; }
    .btn-selesaikan:active { transform: scale(0.98); }

    .btn-kembali {
      display: block; text-align: center;
      font-family: 'Poppins', sans-serif;
      font-size: 0.82rem; font-weight: 600;
      padding: 0.65rem 1.4rem; border-radius: 8px;
      border: 1.5px solid var(--border);
      background: var(--input-bg); color: var(--text);
      text-decoration: none; transition: background 0.15s; width: 100%;
    }
    .btn-kembali:hover { background: var(--surface-hover); }

    /* CONFIRM OVERLAY */
    .overlay {
      display: none; position: fixed; inset: 0;
      background: rgba(0,0,0,0.45); z-index: 100;
      justify-content: center; align-items: center;
    }
    .overlay.active { display: flex; }
    .confirm-box {
      background: var(--bg); border: 1px solid var(--border);
      border-radius: 14px; padding: 2rem;
      width: min(420px, 90vw); text-align: center;
      display: flex; flex-direction: column; gap: 1rem;
    }
    .confirm-box h2 { font-size: 1rem; font-weight: 700; }
    .confirm-box p  { font-size: 0.82rem; color: var(--text-muted); line-height: 1.5; }
    .confirm-actions { display: flex; gap: 0.8rem; }
    .confirm-actions button {
      font-family: 'Poppins', sans-serif;
      font-size: 0.82rem; font-weight: 600;
      padding: 0.6rem 1.2rem; border-radius: 7px;
      cursor: pointer; flex: 1; transition: background 0.15s;
    }
    .btn-confirm-ya {
      background: var(--accent-dark); color: #fff;
      border: 1.5px solid var(--accent-dark);
    }
    .btn-confirm-ya:hover { background: #5e5448; }
    .btn-confirm-tidak {
      background: var(--input-bg); color: var(--text);
      border: 1.5px solid var(--border);
    }
    .btn-confirm-tidak:hover { background: var(--surface); }

    @media (max-width: 600px) {
      .card { grid-template-columns: 1fr; gap: 1.4rem; padding: 1.4rem; }
    }
  </style>
</head>
<body>

<nav>
  <span class="nav-brand">Praha Agency</span>
  <ul class="nav-links">
    <li><a href="dashboard_transaksi.php">Transaksi</a></li>
    <li><a href="listBukuRantaman.php">Buku Rantaman</a></li>
  </ul>
  <a href="../login.php" class="nav-logout">Log Out</a>
</nav>

<div class="page-title-wrap">Bayar Tagihan</div>

<main>
  <div class="card">

    <!-- KIRI: QRIS -->
    <div class="qris-section">
      <p class="qris-label">Scan kode (QRIS) dibawah ini</p>
      <div class="qris-img-wrap">
        <img src="<?= htmlspecialchars($qrisPath); ?>" alt="QRIS Praha Agency"/>
      </div>
    </div>

    <!-- KANAN: INFO + ACTIONS -->
    <div class="info-section">

      <?php if ($errorMsg): ?>
        <div class="alert"><?= htmlspecialchars($errorMsg); ?></div>
      <?php endif; ?>

      <div class="info-block">
        <p class="label">Paket</p>
        <span class="badge-paket"><?= $namaPaket; ?></span>
      </div>

      <div class="info-block">
        <p class="label">Keterangan</p>
        <p class="value small">DP Booking Paket <?= $namaPaket; ?></p>
      </div>

      <div class="info-block">
        <p class="label">Nominal</p>
        <p class="value"><?= $nominalFmt; ?></p>
      </div>

      <?php if ($minPax): ?>
      <div class="info-block">
        <p class="label">Minimum Pax</p>
        <p class="value small"><?= htmlspecialchars($minPax); ?> orang</p>
      </div>
      <?php endif; ?>

      <button type="button" class="btn-selesaikan" id="btnSelesaikan">
        Selesaikan Transaksi
      </button>

      <a href="menu.php" class="btn-kembali">Kembali ke Menu</a>

    </div>
  </div>
</main>

<!-- CONFIRM DIALOG -->
<div class="overlay" id="confirmOverlay">
  <div class="confirm-box">
    <h2>Konfirmasi Pembayaran</h2>
    <p>
      Pastikan kamu sudah transfer sebesar <strong><?= $nominalFmt; ?></strong>
      untuk Paket <strong><?= $namaPaket; ?></strong>.<br><br>
      Setelah dikonfirmasi, buku rantaman akan otomatis dibuat dan admin akan segera melengkapi datanya.
    </p>
    <div class="confirm-actions">
      <button type="button" class="btn-confirm-tidak" id="btnTidak">Batal</button>
      <button type="button" class="btn-confirm-ya"   id="btnYa">Ya, Sudah Bayar</button>
    </div>
  </div>
</div>

<!-- Hidden form POST -->
<form id="formSelesaikan" method="POST" action="" style="display:none;">
  <input type="hidden" name="selesaikan" value="1"/>
</form>

<script>
  const overlay       = document.getElementById('confirmOverlay');
  const btnSelesaikan = document.getElementById('btnSelesaikan');
  const btnTidak      = document.getElementById('btnTidak');
  const btnYa         = document.getElementById('btnYa');
  const form          = document.getElementById('formSelesaikan');

  btnSelesaikan.addEventListener('click', () => overlay.classList.add('active'));
  btnTidak.addEventListener('click',      () => overlay.classList.remove('active'));
  overlay.addEventListener('click', e => { if (e.target === overlay) overlay.classList.remove('active'); });

  btnYa.addEventListener('click', () => {
    btnYa.textContent = 'Memproses...';
    btnYa.disabled    = true;
    form.submit();
  });
</script>

</body>
</html>