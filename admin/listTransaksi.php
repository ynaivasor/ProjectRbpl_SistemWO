<?php
session_start();
include '../koneksi.php';

// Cek user login
if (!isset($_SESSION['id_users'])) {
    die("User belum login.");
}

// Ambil id user dari session
$idUser = $_SESSION['id_users'];

// Pesan alert
$success_msg = '';
$error_msg   = '';

// ==============================
// UPDATE STATUS PEMBAYARAN
// ==============================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_termin'])) {

    foreach ($_POST['status'] as $id_termin => $new_status) {

        // Validasi status
        $allowed = ['Pending', 'Process', 'Done'];

        if (!in_array($new_status, $allowed)) {
            continue;
        }

        $id_termin = (int)$id_termin;

        $update = mysqli_query($conn, "
            UPDATE pembayaran_termin
            SET status = '$new_status'
            WHERE id_termin = '$id_termin'
        ");

        if (!$update) {
            $error_msg = "Gagal update data.";
        }
    }

    if (empty($error_msg)) {
        $success_msg = "Data berhasil diperbarui.";
    }
}

// ==============================
// AMBIL DATA TERMIN PEMBAYARAN
// ==============================
$query = mysqli_query($conn, "
    SELECT
        pembayaran_termin.id_termin,
        pembayaran_termin.id_transaksi,
        pembayaran_termin.keterangan,
        pembayaran_termin.nominal,
        pembayaran_termin.deadline,
        pembayaran_termin.status,
        pembayaran_termin.updated_at,

        transaksi.id_users,

        users.username

    FROM pembayaran_termin

    JOIN transaksi
        ON pembayaran_termin.id_transaksi = transaksi.id_transaksi

    JOIN users
        ON transaksi.id_users = users.id_users

    WHERE transaksi.id_users = '$idUser'

    ORDER BY pembayaran_termin.id_termin ASC
");

// Debug query error
if (!$query) {
    die("Query Error: " . mysqli_error($conn));
}

// Simpan ke array
$termins = [];

while ($row = mysqli_fetch_assoc($query)) {
    $termins[] = $row;
}

// ==============================
// CEK SEMUA STATUS DONE
// ==============================
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
    <title>Kelola Keuangan – Praha Agency Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --sand:        #e8e0d5;
            --sand-light:  #f2ede7;
            --sand-dark:   #d4c9ba;
            --walnut:      #8b6e56;
            --walnut-deep: #6b5040;
            --ink:         #2d2520;
            --ink-soft:    #5c4f45;
            --done:        #4a7c59;
            --done-light:  #d6ead9;
            --unpaid:      #c0392b;
            --unpaid-light:#fde8e6;
            --process:     #2a5fa5;
            --process-light:#ddeeff;
            --white:       #faf8f5;
            --shadow:      rgba(45, 37, 32, 0.12);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
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

        .nav-links a:hover { color: var(--walnut-deep); }

        /* ── MAIN ── */
        main {
            max-width: 920px;
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

        /* ── ALERT ── */
        .alert {
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 0.88rem;
            margin-bottom: 24px;
            font-weight: 500;
        }

        .alert-success { background: var(--done-light); color: var(--done); border: 1px solid #b2d8bb; }
        .alert-error   { background: var(--unpaid-light); color: var(--unpaid); border: 1px solid #f0bcb9; }

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

        thead tr { background: var(--walnut); }

        thead th {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1rem;
            font-weight: 700;
            color: var(--white);
            letter-spacing: 0.05em;
            padding: 18px 20px;
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
            padding: 20px;
            text-align: center;
            font-size: 0.92rem;
            color: var(--ink-soft);
            vertical-align: middle;
        }

        .keterangan-cell {
            font-weight: 500;
            color: var(--ink);
        }

        .nominal-cell {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.05rem;
            font-weight: 600;
            color: var(--ink);
        }

        .username-cell {
            font-weight: 400;
            color: var(--ink-soft);
            font-style: italic;
        }

        /* ── DROPDOWN STATUS ── */
        .status-select {
            appearance: none;
            -webkit-appearance: none;
            background-color: var(--white);
            border: 1.5px solid var(--sand-dark);
            border-radius: 8px;
            padding: 7px 32px 7px 14px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--ink);
            cursor: pointer;
            width: 140px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%238b6e56' stroke-width='1.8' fill='none' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            transition: border-color .2s, box-shadow .2s;
        }

        .status-select:focus {
            outline: none;
            border-color: var(--walnut);
            box-shadow: 0 0 0 3px rgba(139,110,86,.15);
        }

        /* warna teks sesuai pilihan (via JS) */
        .status-select.opt-done    { color: var(--done);    border-color: #b2d8bb; background-color: var(--done-light); }
        .status-select.opt-unpaid  { color: var(--unpaid);  border-color: #f0bcb9; background-color: var(--unpaid-light); }
        .status-select.opt-process { color: var(--process); border-color: #b0ccee; background-color: var(--process-light); }
        .status-select.opt-pending { color: var(--ink-soft); }

        /* ── FOOTER FORM ── */
        .form-footer {
            display: flex;
            justify-content: center;
            padding: 24px 20px;
            background: var(--sand);
            border-top: 1px solid var(--sand-dark);
        }

        .btn-update {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 11px 36px;
            background: var(--walnut);
            color: var(--white);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.88rem;
            font-weight: 500;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            border: none;
            border-radius: 9px;
            cursor: pointer;
            transition: background .2s, transform .15s, box-shadow .2s;
            box-shadow: 0 2px 10px rgba(139,110,86,.3);
        }

        .btn-update:hover {
            background: var(--walnut-deep);
            transform: translateY(-1px);
            box-shadow: 0 4px 16px rgba(107,80,64,.35);
        }

        .btn-update:active { transform: translateY(0); }

        /* ── EMPTY STATE ── */
        .empty {
            text-align: center;
            padding: 52px 24px;
            color: var(--ink-soft);
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

<nav>
    <span class="nav-brand">Praha Agency</span>
    <ul class="nav-links">
        <li><a href="logout.php">Log Out</a></li>
    </ul>
</nav>

<main>
    <h1 class="page-title">Kelola Keuangan</h1>

    <?php if ($success_msg): ?>
        <div class="alert alert-success">✓ <?= htmlspecialchars($success_msg) ?></div>
    <?php endif; ?>
    <?php if ($error_msg): ?>
        <div class="alert alert-error">✗ <?= htmlspecialchars($error_msg) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="hidden" name="update_termin" value="1">

        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>Nama Pembayaran</th>
                        <th>Username</th>
                        <th>Jumlah</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($termins)): ?>
                        <tr>
                            <td colspan="4" class="empty">Belum ada data pembayaran.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($termins as $termin): ?>
                            <?php
                            $status = $termin['status']; // Pending | Process | Done
                            $opt_class = match($status) {
                                'Done'    => 'opt-done',
                                'Process' => 'opt-process',
                                'Pending' => 'opt-pending',
                                default   => 'opt-pending',
                            };
                            ?>
                            <tr>
                                <td class="keterangan-cell">
                                    <?= htmlspecialchars($termin['keterangan']) ?>
                                </td>
                                <td class="username-cell">
                                    @<?= htmlspecialchars($termin['username'] ?? '—') ?>
                                </td>
                                <td class="nominal-cell">
                                    Rp <?= number_format($termin['nominal'], 0, ',', '.') ?>
                                </td>
                                <td>
                                    <select
                                        name="status[<?= $termin['id_termin'] ?>]"
                                        class="status-select <?= $opt_class ?>"
                                        onchange="updateSelectStyle(this)"
                                    >
                                        <option value="Pending"  <?= $status === 'Pending'  ? 'selected' : '' ?>>Pending</option>
                                        <option value="Process"  <?= $status === 'Process'  ? 'selected' : '' ?>>Process</option>
                                        <option value="Done"     <?= $status === 'Done'     ? 'selected' : '' ?>>Done</option>
                                    </select>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>

            <?php if (!empty($termins)): ?>
                <div class="form-footer">
                    <button type="submit" class="btn-update">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 6L9 17l-5-5"/>
                        </svg>
                        Update Data
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </form>
</main>

<script>
    // Ubah warna dropdown sesuai nilai yang dipilih
    function updateSelectStyle(sel) {
        sel.className = 'status-select';
        const map = { Done: 'opt-done', Process: 'opt-process', Pending: 'opt-pending' };
        sel.classList.add(map[sel.value] || 'opt-pending');
    }
</script>

</body>
</html>