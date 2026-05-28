<?php
include '../koneksi.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "ID tidak ditemukan!";
    exit;
}

$query = mysqli_query($conn, "
    SELECT * FROM perubahan 
    WHERE id_perubahan = '$id'
");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "Data tidak ditemukan!";
    exit;
}

if (isset($_POST['hapus_perubahan'])) {

    $hapus = mysqli_query($conn, "
        DELETE FROM perubahan
        WHERE id_perubahan = '$id'
    ");

    if ($hapus) {
        header("Location: listperubahan.php");
        exit;
    } else {
        echo "Gagal menghapus perubahan!";
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Praha Agency – Masukkan Perubahan</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet"/>
  <style>
    :root {
      --bg:          #f5f2ed;
      --surface:     #ddd8ce;
      --accent:      #a89880;
      --accent-dark: #7a6e61;
      --text:        #1e1c1a;
      --text-muted:  #6b6259;
      --border:      #b8b0a4;
      --input-bg:    #ffffff;
      --radius-card: 18px;
      --radius-input: 12px;
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
    .nav-right {
      display: flex;
      align-items: center;
      gap: 1.5rem;
    }
    .nav-link {
      font-size: 0.85rem;
      font-weight: 500;
      color: var(--text);
      text-decoration: none;
      letter-spacing: 0.01em;
      transition: color 0.15s;
    }
    .nav-link:hover { color: var(--accent-dark); }
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

    /* ── PAGE TITLE ── */
    .page-title-wrap {
      text-align: center;
      padding: 1.8rem 1rem 1rem;
    }
    .page-title-wrap h1 {
      font-size: 1rem;
      font-weight: 700;
    }

    /* ── MAIN ── */
    main {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 0.4rem 1.2rem 3rem;
    }

    /* ── CARD ── */
    .card {
      background: var(--surface);
      border-radius: var(--radius-card);
      padding: 2rem 1.8rem;
      width: 100%;
      max-width: 860px;
      display: flex;
      flex-direction: column;
      gap: 1.4rem;
    }

    /* ── FORM GROUP ── */
    .form-group {
      display: flex;
      flex-direction: column;
      gap: 0.4rem;
    }

    label {
      font-size: 0.78rem;
      font-weight: 400;
      color: var(--text-muted);
    }

    input[type="text"],
    textarea {
      font-family: 'Poppins', sans-serif;
      font-size: 1rem;
      font-weight: 400;
      color: var(--text);
      background: var(--input-bg);
      border: 1.5px solid var(--accent);
      border-radius: var(--radius-input);
      padding: 0.75rem 1rem;
      outline: none;
      transition: border-color 0.15s, box-shadow 0.15s;
      resize: none;
      width: 100%;
    }
    input[type="text"]:focus,
    textarea:focus {
      border-color: var(--accent-dark);
      box-shadow: 0 0 0 3px rgba(122,110,97,0.12);
    }

    textarea.detail  { height: 160px; }
    textarea.file-area { height: 140px; }

    /* ── FILE UPLOAD AREA ── */
    .file-upload-box {
      background: var(--input-bg);
      border: 1.5px solid var(--accent);
      border-radius: var(--radius-input);
      height: 140px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      position: relative;
      gap: 0.4rem;
      transition: border-color 0.15s, background 0.15s;
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
    .upload-hint {
      font-size: 0.75rem;
      color: var(--text-muted);
    }
    .upload-icon svg { width: 28px; height: 28px; fill: var(--border); }
    #file-name {
      font-size: 0.73rem;
      color: var(--accent-dark);
      font-weight: 500;
    }

    /* ── SUBMIT ── */
    .btn-submit-wrap {
      display: flex;
      justify-content: center;
      margin-top: 0.6rem;
    }
    .btn-submit {
      font-family: 'Poppins', sans-serif;
      font-size: 0.9rem;
      font-weight: 700;
      color: var(--text);
      background: var(--input-bg);
      border: 2px solid var(--border);
      border-radius: 999px;
      padding: 0.75rem 3.5rem;
      cursor: pointer;
      transition: background 0.18s, color 0.18s, border-color 0.18s, box-shadow 0.18s;
    }
    .btn-submit:hover {
      background: var(--accent-dark);
      color: #fff;
      border-color: var(--accent-dark);
      box-shadow: 0 4px 14px rgba(0,0,0,0.12);
    }
    .alert {
    width: 80%;
    margin: 20px auto;
    padding: 15px;
    border-radius: 8px;
    font-weight: bold;
    text-align: center;
    margin-top: 40px; 
}

.success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}
  </style>
</head>
<body>
<nav>
  <span class="nav-brand">Praha Agency</span>
  <div class="nav-right">
    <a href="transaksi.php" class="nav-link">Transaksi</a>
    <a href="rantamanUser.php" class="nav-link">Buku Rantaman</a>
    <a href="logout.php" class="nav-logout">Log Out</a>
  </div>
</nav>
<!-- PAGE TITLE -->
<div class="page-title-wrap">
  <h1>Masukkan Perubahan</h1>
</div>

<!-- MAIN -->
<main>
  <div class="card">
    <form method="POST" action="perubahan.php?id=<?= $id; ?> enctype="multipart/form-data"
          style="display:flex; flex-direction:column; gap:1.4rem;">
<div class="form-group">
  <label for="komponen">Komponen yang ingin diubah</label>
  <input
    type="text"
    id="komponen"
    name="komponen"
    value="<?= htmlspecialchars($data['komponen']); ?>"
    readonly
  />
</div>
      <!-- Jelaskan detail revisi -->
    <!-- Jelaskan detail revisi -->
<div class="form-group">
  <label for="detail_revisi">Jelaskan detail revisi</label>

  <textarea
    id="detail_revisi"
    name="detail_revisi"
    class="detail"
    readonly
  ><?= htmlspecialchars($data['detail_revisi']); ?></textarea>

</div>

      <!-- File pendukung (Opsional) -->
      <div class="form-group">
        <label>File pendukung (Opsional)</label>
        <div class="file-upload-box">
        <?php if (!empty($data['file_pendukung'])): ?>

<div class="form-group">
  <label>File Pendukung</label>

  <a 
    href="../uploads/perubahan/<?= $data['file_pendukung']; ?>" 
    target="_blank"
    class="btn-submit"
    style="text-decoration:none; text-align:center;"
  >
    Lihat File
  </a>
</div>

<?php endif; ?>
      </div>

      <!-- Submit -->
      <div class="btn-submit-wrap">
        <button type="submit" name="hapus_perubahan" class="btn-submit">Selesaikan Perubahan</button>
      </div>

    </form>
  </div>
</main>

<script>
  function showFileName(input) {
    document.getElementById('file-name').textContent =
      input.files.length > 0 ? input.files[0].name : '';
  }
</script>

</body>
</html>