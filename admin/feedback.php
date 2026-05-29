<?php
session_start();
include '../koneksi.php';

$query = mysqli_query($conn, "
    SELECT feedback.*, users.username
    FROM feedback
    JOIN users ON feedback.id_transaksi = (
        SELECT id_transaksi FROM transaksi WHERE transaksi.id_users = users.id_users LIMIT 1
    )
    ORDER BY feedback.created_at DESC
");

$feedbacks = [];
while ($row = mysqli_fetch_assoc($query)) {
    $feedbacks[] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Praha Agency – Feedback</title>
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
      --star-on:       #c49a3c;
      --star-off:      #c8c0b6;
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

    /* ── COUNT META ── */
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

    /* ── FEEDBACK LIST ── */
    .feedback-list {
      width: 100%;
      max-width: 900px;
      display: flex;
      flex-direction: column;
      gap: 0.85rem;
    }

    /* ── FEEDBACK CARD ── */
    .feedback-card {
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
    .feedback-card::before {
      content: '';
      position: absolute;
      left: 0; top: 0; bottom: 0;
      width: 3px;
      background: var(--accent-dark);
      border-radius: 3px 0 0 3px;
    }
    .feedback-card:hover {
      background: var(--surface-hover);
      transform: translateY(-2px);
      box-shadow: 0 6px 18px rgba(0,0,0,0.08);
    }

    /* Card left */
    .card-info { flex: 1; min-width: 0; }

    .card-title {
      font-size: 0.95rem;
      font-weight: 600;
      color: var(--text);
      margin-bottom: 0.3rem;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    /* Stars */
    .stars {
      display: inline-flex;
      gap: 2px;
      margin-bottom: 0.3rem;
    }
    .star {
      font-size: 1rem;
      line-height: 1;
    }
    .star.on  { color: var(--star-on); }
    .star.off { color: var(--star-off); }

    .card-date {
      font-size: 0.7rem;
      color: var(--text-muted);
      margin-top: 2px;
    }

    /* Card right: dropdown toggle */
    .card-actions { flex-shrink: 0; }

    .btn-toggle {
      font-family: 'Poppins', sans-serif;
      font-size: 0.73rem;
      font-weight: 600;
      padding: 0.42rem 1rem;
      border-radius: 6px;
      border: 1.5px solid var(--border);
      cursor: pointer;
      background: var(--input-bg);
      color: var(--text);
      display: inline-flex;
      align-items: center;
      gap: 0.35rem;
      transition: background 0.15s, color 0.15s, border-color 0.15s;
      white-space: nowrap;
    }
    .btn-toggle:hover {
      background: var(--accent);
      color: #fff;
      border-color: var(--accent);
    }
    .btn-toggle .arrow {
      display: inline-block;
      transition: transform 0.2s;
      font-style: normal;
    }
    .btn-toggle.open .arrow { transform: rotate(180deg); }

    /* ── DROPDOWN PANEL ── */
    .feedback-dropdown {
      display: none;
      background: var(--input-bg);
      border: 1px solid var(--border);
      border-top: none;
      border-radius: 0 0 var(--radius-card) var(--radius-card);
      padding: 1rem 1.4rem 1.2rem;
      margin-top: -1px;
      /* negative margin to attach under card */
    }
    .feedback-dropdown.open { display: block; }

    .dropdown-label {
      font-size: 0.72rem;
      font-weight: 600;
      color: var(--text-muted);
      text-transform: uppercase;
      letter-spacing: 0.06em;
      margin-bottom: 0.45rem;
    }
    .dropdown-text {
      font-size: 0.88rem;
      color: var(--text);
      line-height: 1.65;
      white-space: pre-wrap;
    }

    /* wrap card + dropdown together */
    .card-wrap {
      display: flex;
      flex-direction: column;
    }
    .card-wrap .feedback-card {
      border-radius: var(--radius-card);
      transition: border-radius 0.01s;
    }
    .card-wrap.expanded .feedback-card {
      border-radius: var(--radius-card) var(--radius-card) 0 0;
      border-bottom-color: transparent;
    }

    /* ── EMPTY STATE ── */
    .empty-state {
      width: 100%;
      max-width: 900px;
      text-align: center;
      padding: 4rem 1rem;
      color: var(--text-muted);
      font-size: 0.85rem;
    }

    @media (max-width: 580px) {
      .feedback-card { flex-direction: column; align-items: flex-start; }
      .card-actions { width: 100%; display: flex; justify-content: flex-end; }
    }
  </style>
</head>
<body>

<!-- NAVBAR -->
<nav>
  <span class="nav-brand">Praha Agency</span>
  <a href="logout.php" class="nav-logout">Log Out</a>
</nav>

<!-- PAGE HEADER -->
<div class="page-header">
  <a href="javascript:history.back()" class="btn-back">&#8249;</a>
  <h1 class="page-title">Feedback dari User</h1>
</div>

<main>

  <div class="event-meta">
    <span class="event-count">Menampilkan <strong><?= count($feedbacks); ?></strong> feedback</span>
  </div>

  <div class="feedback-list">

    <?php if (!empty($feedbacks)): ?>
      <?php foreach ($feedbacks as $fb): ?>

        <?php
          $rating = (int)$fb['rating_wo'];
          $rating = max(1, min(5, $rating));
          $date   = isset($fb['created_at'])
                      ? date('d M Y, H:i', strtotime($fb['created_at']))
                      : '—';
        ?>

        <div class="card-wrap" id="wrap-<?= $fb['id_feedback'] ?>">

          <div class="feedback-card">

            <!-- Info kiri -->
            <div class="card-info">
              <p class="card-title">
                Feedback dari: <?= htmlspecialchars($fb['username']); ?>
              </p>

              <!-- Bintang -->
              <div class="stars">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                  <span class="star <?= $i <= $rating ? 'on' : 'off' ?>">★</span>
                <?php endfor; ?>
                <span style="font-size:0.72rem;color:var(--text-muted);margin-left:5px;align-self:center;">
                  <?= $rating ?>/5
                </span>
              </div>

              <p class="card-date">📅 <?= $date ?></p>
            </div>

            <!-- Tombol kanan -->
            <div class="card-actions">
              <button
                class="btn-toggle"
                onclick="toggleFeedback(<?= $fb['id_feedback'] ?>)"
                id="btn-<?= $fb['id_feedback'] ?>"
              >
                Lihat Feedback <i class="arrow">▾</i>
              </button>
            </div>

          </div><!-- /.feedback-card -->

          <!-- Dropdown isi feedback -->
          <div class="feedback-dropdown" id="drop-<?= $fb['id_feedback'] ?>">
            <p class="dropdown-label">Isi Feedback</p>
            <p class="dropdown-text"><?= nl2br(htmlspecialchars($fb['feedback_wo'])); ?></p>
          </div>

        </div><!-- /.card-wrap -->

      <?php endforeach; ?>

    <?php else: ?>
      <div class="empty-state">
        <p>Belum ada feedback masuk.</p>
      </div>
    <?php endif; ?>

  </div><!-- /.feedback-list -->

</main>

<script>
  function toggleFeedback(id) {
    const wrap = document.getElementById('wrap-' + id);
    const drop = document.getElementById('drop-' + id);
    const btn  = document.getElementById('btn-'  + id);

    const isOpen = drop.classList.contains('open');

    if (isOpen) {
      drop.classList.remove('open');
      btn.classList.remove('open');
      wrap.classList.remove('expanded');
      btn.innerHTML = 'Lihat Feedback <i class="arrow">▾</i>';
    } else {
      drop.classList.add('open');
      btn.classList.add('open');
      wrap.classList.add('expanded');
      btn.innerHTML = 'Tutup <i class="arrow">▾</i>';
    }
  }
</script>

</body>
</html>