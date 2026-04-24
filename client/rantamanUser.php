<?php
session_start();
include '../koneksi.php';

// ambil id user dari session
$id_users = $_SESSION['id_users'];

// query data milik user
$query = mysqli_query($conn, "
    SELECT * FROM bukuRantaman 
    WHERE idUser = '$id_users'
");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "Data tidak ditemukan!";
    exit;
}?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Praha Agency – Buku Rantaman</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet"/>
  <style>
    :root {
      --bg:           #f5f2ed;
      --surface:      #ddd8ce;
      --surface-inner:#f0ece5;
      --accent:       #a89880;
      --accent-dark:  #7a6e61;
      --text:         #1e1c1a;
      --text-muted:   #6b6259;
      --border:       #b8b0a4;
      --input-bg:     #ffffff;
      --radius-card:  18px;
      --radius-input: 8px;
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
      padding: 0 2rem;
      height: 58px;
      flex-shrink: 0;
    }
    .nav-brand {
      font-weight: 700;
      font-size: 1rem;
      letter-spacing: 0.02em;
    }
    .nav-logout {
      font-size: 0.82rem;
      font-weight: 500;
      color: var(--text);
      text-decoration: none;
      padding: 5px 14px;
      border: 1px solid var(--border);
      border-radius: 5px;
      transition: background 0.15s, color 0.15s;
    }
    .nav-logout:hover { background: var(--accent-dark); color: #fff; border-color: var(--accent-dark); }

    /* ── PAGE HEADER ── */
    .page-header {
      display: flex;
      align-items: center;
      padding: 1.2rem 1.5rem 0.6rem;
      max-width: 860px;
      width: 100%;
      margin: 0 auto;
      position: relative;
    }
    .btn-back {
      font-size: 1.3rem;
      color: var(--text);
      text-decoration: none;
      line-height: 1;
      padding: 4px 8px;
      border-radius: 6px;
      transition: background 0.15s;
    }
    .btn-back:hover { background: var(--surface); }
    .page-title {
      font-size: 1rem;
      font-weight: 700;
      position: absolute;
      left: 50%;
      transform: translateX(-50%);
    }

    /* ── MAIN ── */
    main {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 0.8rem 1.2rem 3rem;
    }

    /* ── CARD ── */
    .card {
      background: var(--surface);
      border-radius: var(--radius-card);
      padding: 2rem 1.8rem;
      width: 100%;
      max-width: 860px;
    }

    /* ── FORM ── */
    .form-grid-2 {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1.2rem 1.4rem;
      margin-bottom: 1.4rem;
    }
    .form-group {
      display: flex;
      flex-direction: column;
      gap: 0.35rem;
    }
    /* Lokasi spans full height in the 2-col grid via row-span */
    .form-group.span-rows {
      grid-row: span 2;
    }

    label {
      font-size: 0.75rem;
      font-weight: 500;
      color: var(--text-muted);
      letter-spacing: 0.01em;
    }

    input[type="text"],
    input[type="date"],
    input[type="tel"],
    textarea {
      font-family: 'Poppins', sans-serif;
      font-size: 0.85rem;
      color: var(--text);
      background: var(--input-bg);
      border: 1.5px solid var(--border);
      border-radius: var(--radius-input);
      padding: 0.55rem 0.75rem;
      outline: none;
      transition: border-color 0.15s, box-shadow 0.15s;
      resize: none;
      width: 100%;
    }
    input[type="text"]:focus,
    input[type="date"]:focus,
    input[type="tel"]:focus,
    textarea:focus {
      border-color: var(--accent-dark);
      box-shadow: 0 0 0 3px rgba(122,110,97,0.12);
    }

    /* Lokasi textarea tall */
    textarea.tall { height: 110px; }
    /* Data Staff / Vendor textarea very tall */
    textarea.xtall { height: 200px; }

    /* Section separator */
    .section-gap { margin-bottom: 1.4rem; }

    /* ── FILE UPLOAD ── */
    .file-upload-label {
      font-size: 0.75rem;
      font-weight: 500;
      color: var(--text-muted);
      margin-bottom: 0.35rem;
      display: block;
    }
    .file-upload-box {
      background: var(--input-bg);
      border: 1.5px solid var(--border);
      border-radius: var(--radius-input);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 2.5rem 1rem;
      cursor: pointer;
      transition: border-color 0.15s, background 0.15s;
      position: relative;
      gap: 0.5rem;
    }
    .file-upload-box:hover { border-color: var(--accent-dark); background: #faf8f5; }
    .file-upload-box input[type="file"] {
      position: absolute;
      inset: 0;
      opacity: 0;
      cursor: pointer;
      width: 100%;
      height: 100%;
    }
    .upload-icon svg { width: 40px; height: 40px; fill: var(--text); }
    .upload-hint {
      font-size: 0.75rem;
      color: var(--text-muted);
      text-align: center;
    }
    #file-name-display {
      font-size: 0.75rem;
      color: var(--accent-dark);
      font-weight: 500;
      text-align: center;
    }

    /* ── SUBMIT BUTTON ── */
    .btn-submit-wrap {
      display: flex;
      justify-content: center;
      margin-top: 2rem;
    }
    .btn-submit {
      font-family: 'Poppins', sans-serif;
      font-size: 0.95rem;
      font-weight: 700;
      color: var(--text);
      background: var(--input-bg);
      border: 2px solid var(--border);
      border-radius: 999px;
      padding: 0.8rem 3.5rem;
      cursor: pointer;
      transition: background 0.18s, color 0.18s, border-color 0.18s, box-shadow 0.18s;
      letter-spacing: 0.01em;
    }
    .btn-submit:hover {
      background: var(--accent-dark);
      color: #fff;
      border-color: var(--accent-dark);
      box-shadow: 0 4px 14px rgba(0,0,0,0.12);
    }

    @media (max-width: 600px) {
      .form-grid-2 { grid-template-columns: 1fr; }
      .form-group.span-rows { grid-row: span 1; }
      textarea.tall { height: 90px; }
    }
  </style>
</head>
<body>

<!-- NAVBAR -->
<nav>
  <span class="nav-brand">Praha Agency</span>
  <a href="#" class="nav-logout">Log Out</a>
</nav>

<!-- PAGE HEADER -->
<div class="page-header">
  <a href="#" class="btn-back">&#8249;</a>
  <h1 class="page-title">Buku Rantaman</h1>
</div>

<!-- MAIN -->
<main>
  <div class="card">
    <form method="POST" action="laporPerubahan.php" enctype="multipart/form-data">

      <!-- ROW 1: Nama Pengantin -->
      <div class="form-grid-2">
        <div class="form-group">
          <label for="namaPengantinPria">Nama Pengantin Pria</label>
        <input
  type="text"
  name="namaPengantinPria"
  value="<?= htmlspecialchars($data['namaPengantinPria']); ?>"
  readonly
/>        </div>
        <div class="form-group">
          <label for="nama_pengantin_wanita">Nama Pengantin Wanita</label>
          <input
  type="text"
  name="nama_pengantin_pria"
  value="<?= htmlspecialchars($data['namaPengantinWanita']); ?>"
  readonly
/>
        </div>
      </div>

      <!-- ROW 2: Tanggal + Contact | Lokasi -->
      <div class="form-grid-2 section-gap">
        <div class="form-group">
          <label for="tanggal_pelaksanaan">Tanggal Pelaksanaan</label>
          <input
            type="date"
            id="tanggal_pelaksanaan"
            name="tanggal_pelaksanaan"
            value="<?php echo htmlspecialchars($data['tanggalPelaksanaan'] ?? ''); ?>"
            readonly
          />
        </div>

        <!-- Lokasi: span 2 rows -->
        <div class="form-group span-rows">
          <label for="lokasi">Lokasi</label>
          <textarea readonly
            id="lokasi"
            name="lokasi"
            class="tall"
            style="height:100%; min-height:110px;"
          ><?= htmlspecialchars($data['lokasi'] ?? ''); ?></textarea>
        </div>

        <div class="form-group">
          <label for="contact_person">Contact Person (Whatsapp)</label>
          <input
            type="tel"
            id="contact_person"
            name="contact_person"
            placeholder=""
            value="<?php echo htmlspecialchars($data['contactPerson'] ?? ''); ?>"
            readonly
          />
        </div>
      </div>

      <!-- ROW 3: Data Staff WO + Data Vendor -->
      <div class="form-grid-2 section-gap">
        <div class="form-group">
          <label for="data_staff_wo">Data Staff WO</label>
          <textarea readonly
            id="data_staff_wo"
            name="data_staff_wo"
            class="xtall"
          ><?php echo htmlspecialchars($data['dataStaffWo'] ?? ''); ?></textarea>
        </div>
        <div class="form-group">
          <label for="data_vendor">Data Vendor</label>
          <textarea readonly
            id="data_vendor"
            name="data_vendor"
            class="xtall"
          ><?php echo htmlspecialchars($data['dataVendor'] ?? ''); ?></textarea>
        </div>
      </div>

      <!-- File Rancangan -->
      <div class="section-gap">
        <span class="file-upload-label">File Rancangan</span>
        <div class="file-upload-box">
          <a href="../<?= $data['fileRancangan']; ?>" target="_blank">
     Lihat File Rancangan
    </a>
        </div>
      </div>

      <!-- Submit -->
      <div class="btn-submit-wrap">
        <button type="submit" name="lapor" class="btn-submit">
          Lapor Perubahan
        </button>
      </div>

    </form>
  </div>
</main>

<script>
  function showFileName(input) {
    const display = document.getElementById('file-name-display');
    display.textContent = input.files.length > 0 ? input.files[0].name : '';
  }
</script>

</body>
</html>