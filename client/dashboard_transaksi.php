<?php
session_start();
include '../koneksi.php';

$idUser = $_SESSION['id_users'];

// Query join transaksi + pembayaran termin
$query = mysqli_query($conn, "
    SELECT 
        pembayaran_termin.*
    FROM pembayaran_termin
    JOIN transaksi 
        ON pembayaran_termin.id_transaksi = transaksi.id_transaksi
    WHERE transaksi.id_users = '$idUser'
    ORDER BY pembayaran_termin.id_termin ASC
");

// Debug kalau query error
if (!$query) {
    die(mysqli_error($conn));
}

// Simpan ke array
$termins = [];

while ($row = mysqli_fetch_assoc($query)) {
    $termins[] = $row;
}

// Cek semua done
$semua_done = false;

if (!empty($termins)) {

    $jumlah_done = 0;

    foreach ($termins as $termin) {

        if ($termin['status'] == 'Done') {
            $jumlah_done++;
        }
    }

    $semua_done = ($jumlah_done == count($termins));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi – Praha Agency</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <style>
        :root {
            --sand:       #e8e0d5;
            --sand-light: #f2ede7;
            --sand-dark:  #d4c9ba;
            --walnut:     #8b6e56;
            --walnut-deep:#6b5040;
            --ink:        #2d2520;
            --ink-soft:   #5c4f45;
            --done:       #4a7c59;
            --done-light: #d6ead9;
            --pending:    #b5733a;
            --white:      #faf8f5;
            --shadow:     rgba(45, 37, 32, 0.12);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Poppins', sans-serif; 
            background: var(--sand-light);
            color: var(--ink);
            min-height: 100vh;
        }

        /* ── NAV ── */
        nav {
            background: var(--sand);
            border-bottom: 1px solid var(--sand-dark);
            padding: 0 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 60px;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 8px var(--shadow);
        }

        .nav-brand {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--ink);
            letter-spacing: 0.03em;
        }

        .nav-links {
            display: flex;
            gap: 32px;
            list-style: none;
        }

        .nav-links a {
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--ink-soft);
            letter-spacing: 0.04em;
            text-transform: uppercase;
            transition: color .2s;
        }

        .nav-links a:hover,
        .nav-links a.active { color: var(--walnut-deep); }

        .nav-links a.active {
            border-bottom: 2px solid var(--walnut);
            padding-bottom: 2px;
        }

        /* ── MAIN ── */
        main {
            max-width: 860px;
            margin: 52px auto 80px;
            padding: 0 24px;
        }

        .page-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2rem;
            font-weight: 600;
            color: var(--ink);
            letter-spacing: 0.01em;
            margin-bottom: 36px;
            text-align: center;
        }

        /* ── TABLE CARD ── */
        .table-card {
            background: var(--white);
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 4px 24px var(--shadow), 0 1px 4px rgba(0,0,0,.06);
            border: 1px solid var(--sand-dark);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead tr {
            background: var(--walnut);
        }

        thead th {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1rem;
            font-weight: 700;
            color: var(--white);
            letter-spacing: 0.05em;
            padding: 18px 24px;
            text-align: center;
        }

        tbody tr {
            border-bottom: 1px solid var(--sand-dark);
            background: var(--sand-light);
            transition: background .18s;
        }

        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: var(--sand); }

        tbody td {
            padding: 22px 24px;
            text-align: center;
            font-size: 0.92rem;
            color: var(--ink-soft);
            vertical-align: middle;
        }

        /* ── STATUS BADGE ── */
        .badge {
            display: inline-block;
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            letter-spacing: 0.04em;
        }

        .badge-done {
            background: var(--done-light);
            color: var(--done);
        }

        .badge-pending {
            background: #fde8d0;
            color: var(--pending);
        }

        .badge-process {
            background: #ddeeff;
            color: #2a5fa5;
        }

        /* ── FEEDBACK ROW ── */
        .feedback-row td {
            background: var(--done-light) !important;
            padding: 28px 24px;
        }

        .feedback-btn {
            display: inline-block;
            padding: 10px 32px;
            background: var(--done);
            color: #fff;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.88rem;
            font-weight: 500;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            transition: background .2s, transform .15s, box-shadow .2s;
            box-shadow: 0 2px 10px rgba(74,124,89,.25);
        }

        .feedback-btn:hover {
            background: #3a6347;
            transform: translateY(-1px);
            box-shadow: 0 4px 16px rgba(74,124,89,.35);
        }

        /* ── EMPTY STATE ── */
        .empty {
            text-align: center;
            padding: 52px 24px;
            color: var(--ink-soft);
            font-size: 0.9rem;
        }

        /* ── NOMINAL FORMAT ── */
        .nominal-cell {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.05rem;
            font-weight: 600;
            color: var(--ink);
        }

        .keterangan-cell {
            font-weight: 500;
            color: var(--ink);
        }
    </style>
</head>
<body>

<nav>
    <span class="nav-brand">Praha Agency</span>
    <ul class="nav-links">
        <li><a href="transaksi.php" class="active">Transaksi</a></li>
        <li><a href="buku_rantaman.php">Buku Rantaman</a></li>
        <li><a href="logout.php">Log Out</a></li>
    </ul>
</nav>

<main>
    <h1 class="page-title">Transaksi Anda</h1>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>Keterangan</th>
                    <th>Nominal</th>
                    <th>Deadline Pembayaran</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($termins)): ?>
                    <tr>
                        <td colspan="4" class="empty">Belum ada data pembayaran.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($termins as $termin): ?>
                        <tr>
                            <td class="keterangan-cell"><?= htmlspecialchars($termin['keterangan']) ?></td>
                            <td class="nominal-cell">
                                Rp <?= number_format($termin['nominal'], 0, ',', '.') ?>
                            </td>
                            <td>
                                <?= date('d/m/Y', strtotime($termin['deadline'])) ?>
                            </td>
                            <td>
                                <?php
                                $badge_class = match($termin['status']) {
                                    'Done'    => 'badge-done',
                                    'Process' => 'badge-process',
                                    default   => 'badge-pending',
                                };
                                ?>
                                <span class="badge <?= $badge_class ?>">
                                    <?= htmlspecialchars($termin['status']) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if ($semua_done): ?>
                        <tr class="feedback-row">
                            <td colspan="4" style="text-align:center;">
                                <a href="feedback.php?id_transaksi=<?= $termins[0]['id_transaksi'] ?>">
                                    ✦ Berikan Feedback
                                </a>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

</body>
</html>