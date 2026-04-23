<?php
session_start();
include '../koneksi.php';

$query = mysqli_query($conn, "SELECT * FROM bukuRantaman");
$events = [];

while ($row = mysqli_fetch_assoc($query)) {
    $events[] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Praha Agency – Event On Going</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet"/>
  <style>
    :root {
      --bg:          #f5f2ed;
      --surface:     #ddd8ce;
      --surface-hover: #cec9be;
      --accent:      #a89880;
      --accent-dark: #7a6e61;
      --text:        #1e1c1a;
      --text-muted:  #6b6259;
      --border:      #b8b0a4;
      --input-bg:    #ffffff;
      --radius-card: 14px;
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
    .nav-brand { font-weight: 700; font-size: 1rem; letter-spacing: 0.02em; }
    .nav-logout {
      font-size: 0.82rem; font-weight: 500; color: var(--text);
      text-decoration: none; padding: 5px 14px;
      border: 1px solid var(--border); border-radius: 5px;
      transition: background 0.15s, color 0.15s;
    }
    .nav-logout:hover { background: var(--accent-dark); color: #fff; border-color: var(--accent-dark); }

    /* ── PAGE HEADER ── */
    .page-header {
      display: flex;
      align-items: center;
      padding: 1.2rem 1.5rem 0.8rem;
      max-width: 900px;
      width: 100%;
      margin: 0 auto;
      position: relative;
    }
    .btn-back {
      font-size: 1.3rem; color: var(--text); text-decoration: none;
      line-height: 1; padding: 4px 8px; border-radius: 6px;
      transition: background 0.15s;
    }
    .btn-back:hover { background: var(--surface); }
    .page-title {
      font-size: 1rem; font-weight: 700;
      position: absolute; left: 50%; transform: translateX(-50%);
    }

    /* ── MAIN ── */
    main {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 0.4rem 1.2rem 3rem;
    }

    /* ── EVENT COUNT BADGE ── */
    .event-meta {
      width: 100%;
      max-width: 900px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 0.9rem;
    }
    .event-count {
      font-size: 0.75rem;
      font-weight: 500;
      color: var(--text-muted);
    }
    .badge-ongoing {
      font-size: 0.7rem;
      font-weight: 600;
      background: var(--accent-dark);
      color: #fff;
      padding: 3px 10px;
      border-radius: 999px;
      letter-spacing: 0.03em;
    }

    /* ── EVENT LIST ── */
    .event-list {
      width: 100%;
      max-width: 900px;
      display: flex;
      flex-direction: column;
      gap: 0.85rem;
    }

    /* ── EVENT CARD ── */
    /*
      PHP LOOP INSTRUCTIONS:
      Wrap the .event-card div inside:
        <?php foreach ($events as $event): ?>
          ... card HTML ...
        <?php endforeach; ?>
    */
    .event-card {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: var(--radius-card);
      padding: 1.1rem 1.4rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
      transition: background 0.15s, transform 0.15s, box-shadow 0.15s;
      position: relative;
      overflow: hidden;
    }
    .event-card::before {
      content: '';
      position: absolute;
      left: 0; top: 0; bottom: 0;
      width: 3px;
      background: var(--accent-dark);
      border-radius: 3px 0 0 3px;
    }
    .event-card:hover {
      background: var(--surface-hover);
      transform: translateY(-2px);
      box-shadow: 0 6px 18px rgba(0,0,0,0.08);
    }

    /* Card left: info */
    .event-info { flex: 1; min-width: 0; }

    .event-title {
      font-size: 0.95rem;
      font-weight: 600;
      color: var(--text);
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      margin-bottom: 0.25rem;
    }
    /*
      PHP: ganti teks di dalam .event-title dengan:
        Mr. <?php echo htmlspecialchars($event['nama_pengantin_pria']); ?>
        &amp;
        Mrs. <?php echo htmlspecialchars($event['nama_pengantin_wanita']); ?>
        Wedding
    */

    .event-meta-row {
      display: flex;
      gap: 1.2rem;
      flex-wrap: wrap;
    }
    .event-meta-item {
      font-size: 0.72rem;
      color: var(--text-muted);
      display: flex;
      align-items: center;
      gap: 0.3rem;
    }
    .event-meta-item svg { width: 12px; height: 12px; fill: var(--text-muted); flex-shrink: 0; }
    /*
      PHP: isi value meta dengan:
        tanggal  → <?php echo htmlspecialchars($event['tanggal_pelaksanaan']); ?>
        lokasi   → <?php echo htmlspecialchars($event['lokasi']); ?>
    */

    /* Card right: action buttons */
    .event-actions {
      display: flex;
      gap: 0.6rem;
      flex-shrink: 0;
    }

    .btn-action {
      font-family: 'Poppins', sans-serif;
      font-size: 0.73rem;
      font-weight: 600;
      padding: 0.42rem 1rem;
      border-radius: 6px;
      border: 1.5px solid var(--border);
      cursor: pointer;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.3rem;
      transition: background 0.15s, color 0.15s, border-color 0.15s;
      white-space: nowrap;
    }
    .btn-action svg { width: 13px; height: 13px; }

    /* Lihat — ghost style */
    .btn-lihat {
      background: var(--input-bg);
      color: var(--text);
      fill: var(--text);
    }
    .btn-lihat:hover {
      background: var(--accent);
      color: #fff;
      border-color: var(--accent);
      fill: #fff;
    }
    .btn-lihat svg { fill: inherit; }

    /* Edit — filled style */
    .btn-edit {
      background: var(--accent-dark);
      color: #fff;
      border-color: var(--accent-dark);
      fill: #fff;
    }
    .btn-edit:hover {
      background: #5e5448;
      border-color: #5e5448;
    }
    .btn-edit svg { fill: #fff; }

    /*
      PHP LINK INSTRUCTIONS:
        btn-lihat href  → lihatBuku.php?id=<?php echo $event['id']; ?>
        btn-edit  href  → kelola_buku_rantaman.php?id=<?php echo $event['id']; ?>
    */

    /* ── EMPTY STATE ── */
    .empty-state {
      width: 100%;
      max-width: 900px;
      text-align: center;
      padding: 4rem 1rem;
      color: var(--text-muted);
      font-size: 0.85rem;
    }
    .empty-state svg { width: 48px; height: 48px; fill: var(--border); margin-bottom: 1rem; }

    @media (max-width: 580px) {
      .event-card { flex-direction: column; align-items: flex-start; }
      .event-actions { width: 100%; justify-content: flex-end; }
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
  <h1 class="page-title">Event On Going</h1>
</div>

<main>

  <div class="event-meta">
    <span class="event-count">Menampilkan <strong><?= count($events); ?></strong> event</span>
    <span class="badge-ongoing">● On Going</span>
  </div>

  <!-- Event List -->
  <div class="event-list">

  <?php foreach ($events as $event): ?>
<div class="event-card">
  <div class="event-info">
    <p class="event-title">
      Mr. <?= htmlspecialchars($event['namaPengantinPria']); ?>
      &amp;
      Mrs. <?= htmlspecialchars($event['namaPengantinWanita']); ?> Wedding
    </p>

    <div class="event-meta-row">
      <span class="event-meta-item">
        📅 <?= htmlspecialchars($event['tanggalPelaksanaan']); ?>
      </span>
      <span class="event-meta-item">
        📍 <?= htmlspecialchars($event['lokasi']); ?>
      </span>
    </div>
  </div>

  <div class="event-actions">
    <a href="detailBuku.php?id=<?= $event['idBukuRantaman']; ?>" class="btn-action btn-lihat">
      Lihat Buku
    </a>
    <a href="editBuku.php?id=<?= $event['idBukuRantaman']; ?>" class="btn-action btn-edit">
      Edit
    </a>
  </div>
</div>
<?php endforeach; ?>
    </div>

   <?php if (empty($events)): ?>
<div class="empty-state">
  <p>Belum ada event.</p>
</div>
<?php endif; ?>

  </div>
</main>

</body>
</html>