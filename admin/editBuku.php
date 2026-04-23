<?php
session_start();
include '../koneksi.php';

// ambil id dari URL
$id = $_GET['id'] ?? null;

if (!$id) {
    echo "ID tidak ditemukan!";
    exit;
}

// ambil data lama
$stmt = $conn->prepare("SELECT * FROM bukuRantaman WHERE idBukuRantaman = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    echo "Data tidak ditemukan!";
    exit;
}

if (isset($_POST['simpan_perubahan'])) {

    $namaPria = $_POST['namaPengantinPria'];
    $namaWanita = $_POST['namaPengantinWanita'];
    $tanggal = $_POST['tanggalPelaksanaan'];
    $contact = $_POST['contactPerson'];
    $lokasi = $_POST['lokasi'];
    $staff = $_POST['dataStaffWo'];
    $vendor = $_POST['dataVendor'];

    // default file lama
    $filePath = $data['fileRancangan'];

    // cek apakah upload file baru
    if (!empty($_FILES['file_rancangan']['name'])) {

        $file = $_FILES['file_rancangan'];
        $namaFile = $file['name'];
        $tmpFile = $file['tmp_name'];

        $ext = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

        if ($ext == 'pdf') {

            $namaBaru = time() . '_' . $namaFile;
            $path = "../uploads/" . $namaBaru;

            if (move_uploaded_file($tmpFile, $path)) {
                $filePath = "uploads/" . $namaBaru;
            }
        }
    }

    // update ke database
    $stmt = $conn->prepare("
        UPDATE bukuRantaman SET
            namaPengantinPria = ?,
            namaPengantinWanita = ?,
            tanggalPelaksanaan = ?,
            contactPerson = ?,
            lokasi = ?,
            dataStaffWo = ?,
            dataVendor = ?,
            fileRancangan = ?
        WHERE idBukuRantaman = ?
    ");

    $stmt->bind_param(
        "ssssssssi",
        $namaPria,
        $namaWanita,
        $tanggal,
        $contact,
        $lokasi,
        $staff,
        $vendor,
        $filePath,
        $id
    );

    if ($stmt->execute()) {
        header("Location: listBukuRantaman.php");
        exit;
    } else {
        echo "Gagal update data!";
    }
}
?>


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
    <form method="POST" action="" enctype="multipart/form-data">

      <!-- ROW 1: Nama Pengantin -->
      <div class="form-grid-2">
        <div class="form-group">
          <label for="namaPengantinPria">Nama Pengantin Pria</label>
          <input
            type="text"
            id="nama_pengantin_pria"
            name="namaPengantinPria"
            placeholder=""
            value="<?php echo htmlspecialchars($data['namaPengantinPria'] ?? ''); ?>"
          />
        </div>
        <div class="form-group">
          <label for="namaPengantinWanita">Nama Pengantin Wanita</label>
          <input
            type="text"
            id="namaPengantinWanita"
            name="namaPengantinWanita"
            placeholder=""
            value="<?php echo htmlspecialchars($data['namaPengantinWanita'] ?? ''); ?>"
          />
        </div>
      </div>

      <!-- ROW 2: Tanggal + Contact | Lokasi -->
      <div class="form-grid-2 section-gap">
        <div class="form-group">
          <label for="tanggalPelaksanaan">Tanggal Pelaksanaan</label>
          <input
            type="date"
            id="tanggalPelaksanaan"
            name="tanggalPelaksanaan"
            value="<?php echo htmlspecialchars($data['tanggalPelaksanaan'] ?? ''); ?>"
          />
        </div>

        <!-- Lokasi: span 2 rows -->
        <div class="form-group span-rows">
          <label for="lokasi">Lokasi</label>
          <textarea
            id="lokasi"
            name="lokasi"
            class="tall"
            style="height:100%; min-height:110px;"
          ><?php echo htmlspecialchars($data['lokasi'] ?? ''); ?></textarea>
        </div>

        <div class="form-group">
          <label for="contactPerson">Contact Person (Whatsapp)</label>
          <input
            type="tel"
            id="contactPerson"
            name="contactPerson"
            placeholder=""
            value="<?php echo htmlspecialchars($data['contactPerson'] ?? ''); ?>"
          />
        </div>
      </div>

      <!-- ROW 3: Data Staff WO + Data Vendor -->
      <div class="form-grid-2 section-gap">
        <div class="form-group">
          <label for="dataStaffWo">Data Staff WO</label>
          <textarea
            id="dataStaffWo"
            name="dataStaffWo"
            class="xtall"
          ><?php echo htmlspecialchars($data['dataStaffWo'] ?? ''); ?></textarea>
        </div>
        <div class="form-group">
          <label for="data_vendor">Data Vendor</label>
          <textarea
            id="dataVendor"
            name="dataVendor"
            class="xtall"
          ><?php echo htmlspecialchars($data['dataVendor'] ?? ''); ?></textarea>
        </div>
      </div>

      <!-- File Rancangan -->
      <div class="section-gap">
        <span class="file-upload-label">File Rancangan</span>
        <div class="file-upload-box">
          <input
            type="file"
            id="fileRancangan"
            name="fileRancangan"
            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
            onchange="showFileName(this)"
          />
          <span class="upload-icon">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path d="M19.35 10.04A7.49 7.49 0 0 0 12 4C9.11 4 6.6 5.64 5.35 8.04A5.994 5.994 0 0 0 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM14 13v4h-4v-4H7l5-5 5 5h-3z"/>
            </svg>
          </span>
          <p class="upload-hint">Klik untuk upload file</p>
          <p id="file-name-display"></p>
        </div>
      </div>

      <!-- Submit -->
      <div class="btn-submit-wrap">
        <button type="submit" name="simpan_perubahan" class="btn-submit">
          Simpan Perubahan
        </button>
      </div>
            <div class="btn-submit-wrap">
        <button type="submit" name="simpan_perubahan" class="btn-submit">
          Selesaikan Event
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