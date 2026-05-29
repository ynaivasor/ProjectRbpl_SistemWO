<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['id_users'])) {
    die("User belum login.");
}

$idUser = $_SESSION['id_users'];

$success_msg = '';
$error_msg   = '';
$getTransaksi = mysqli_query($conn, "
    SELECT id_transaksi
    FROM transaksi
    WHERE id_users = '$idUser'
    LIMIT 1
");

$dataTransaksi = mysqli_fetch_assoc($getTransaksi);

if (!$dataTransaksi) {
    die("Transaksi tidak ditemukan.");
}

$id_transaksi = $dataTransaksi['id_transaksi'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_feedback'])) {

    $rating_wo = isset($_POST['rating_wo'])
        ? (int)$_POST['rating_wo']
        : 0;

    $feedback_wo = isset($_POST['feedback_wo'])
        ? trim($_POST['feedback_wo'])
        : '';

    // VALIDASI
    if ($rating_wo < 1 || $rating_wo > 5) {

        $error_msg = 'Mohon pilih rating terlebih dahulu.';

    } elseif (empty($feedback_wo)) {

        $error_msg = 'Mohon isi feedback terlebih dahulu.';

    } else {

        // Simpan feedback
        $insert = mysqli_query($conn, "
            INSERT INTO feedback (
                id_users,
                id_transaksi,
                rating_wo,
                feedback_wo
            )
            VALUES (
                '$idUser',
                '$id_transaksi',
                '$rating_wo',
                '$feedback_wo'
            )
        ");

        if ($insert) {

            $success_msg = 'Feedback berhasil dikirim.';

        } else {

            $error_msg = 'Gagal menyimpan feedback.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback – Praha Agency</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --sand:        #e8e0d5;
            --sand-light:  #f2ede7;
            --sand-dark:   #d4c9ba;
            --card-bg:     #ddd5c8;
            --walnut:      #8b6e56;
            --walnut-deep: #6b5040;
            --ink:         #2d2520;
            --ink-soft:    #5c4f45;
            --star-off:    #c4b8aa;
            --star-on:     #8b6e56;
            --done:        #4a7c59;
            --done-light:  #d6ead9;
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
            height: 64px;
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
        .nav-links a.active {
            color: var(--walnut-deep);
            border-bottom: 2px solid var(--walnut);
            padding-bottom: 2px;
        }

        /* ── MAIN ── */
        main {
            max-width: 820px;
            margin: 52px auto 80px;
            padding: 0 24px;
        }

        .page-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2rem;
            font-weight: 600;
            color: var(--ink);
            letter-spacing: 0.01em;
            margin-bottom: 40px;
            text-align: center;
        }

        /* ── ALERT ── */
        .alert {
            padding: 13px 20px;
            border-radius: 9px;
            font-size: 0.88rem;
            margin-bottom: 28px;
            font-weight: 500;
        }
        .alert-success { background: var(--done-light); color: var(--done); border: 1px solid #b2d8bb; }
        .alert-error   { background: #fde8e6; color: #c0392b; border: 1px solid #f0bcb9; }

        /* ── CARD ── */
        .feedback-card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 40px 44px 44px;
            box-shadow: 0 4px 24px var(--shadow);
            border: 1px solid var(--sand-dark);
        }

        /* ── SECTION LABEL ── */
        .section-label {
            font-size: 0.92rem;
            font-weight: 500;
            color: var(--ink-soft);
            margin-bottom: 16px;
            letter-spacing: 0.02em;
        }

        /* ── STAR RATING ── */
        .star-group {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 28px;
        }

        /* Hide actual radio buttons */
        .star-group input[type="radio"] {
            display: none;
        }

        .star-group label {
            cursor: pointer;
            font-size: 2.4rem;
            color: var(--star-off);
            transition: color .15s, transform .15s;
            line-height: 1;
            user-select: none;
        }

        .star-group label:hover,
        .star-group label:hover ~ label { color: var(--star-off); }

        /* Highlight stars on hover (right-to-left trick with flex-direction) */
        .star-wrapper {
            display: flex;
            flex-direction: row-reverse;
            justify-content: center;
            gap: 10px;
            margin-bottom: 28px;
        }

        .star-wrapper input[type="radio"] { display: none; }

        .star-wrapper label {
            cursor: pointer;
            font-size: 2.4rem;
            color: var(--star-off);
            transition: color .15s, transform .12s;
            line-height: 1;
            user-select: none;
        }

        /* Hover: this star and all to its left (= higher value, come after in reverse) */
        .star-wrapper label:hover,
        .star-wrapper label:hover ~ label {
            color: var(--star-on);
            transform: scale(1.12);
        }

        /* Checked: fill this star and all previous (= higher value) */
        .star-wrapper input:checked ~ label {
            color: var(--star-on);
        }

        /* ── TEXTAREA ── */
        .field-label {
            font-size: 0.88rem;
            font-weight: 500;
            color: var(--ink-soft);
            margin-bottom: 10px;
            display: block;
        }

        textarea {
            width: 100%;
            height: 180px;
            padding: 16px 18px;
            border: 1.5px solid var(--walnut);
            border-radius: 10px;
            background: var(--white);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.92rem;
            color: var(--ink);
            resize: vertical;
            transition: border-color .2s, box-shadow .2s;
            line-height: 1.6;
        }

        textarea::placeholder { color: #b0a496; }

        textarea:focus {
            outline: none;
            border-color: var(--walnut-deep);
            box-shadow: 0 0 0 3px rgba(139,110,86,.15);
        }

        /* ── SUBMIT BUTTON ── */
        .btn-wrap {
            display: flex;
            justify-content: center;
            margin-top: 36px;
        }

        .btn-submit {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 44px;
            background: var(--walnut);
            color: var(--white);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            font-weight: 500;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            border: none;
            border-radius: 9px;
            cursor: pointer;
            transition: background .2s, transform .15s, box-shadow .2s;
            box-shadow: 0 2px 12px rgba(139,110,86,.3);
        }

        .btn-submit:hover {
            background: var(--walnut-deep);
            transform: translateY(-1px);
            box-shadow: 0 4px 18px rgba(107,80,64,.35);
        }

        .btn-submit:active { transform: translateY(0); }
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
    <h1 class="page-title">Masukkan Feedback</h1>

    <?php if ($success_msg): ?>
        <div class="alert alert-success">✓ <?= htmlspecialchars($success_msg) ?></div>
    <?php endif; ?>
    <?php if ($error_msg): ?>
        <div class="alert alert-error">✗ <?= htmlspecialchars($error_msg) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="hidden" name="submit_feedback" value="1">
        <input type="hidden" name="id_transaksi" value="<?= $id_transaksi ?>">

        <div class="feedback-card">

            <!-- RATING BINTANG -->
            <p class="section-label">Beri rating untuk Staff WO</p>

            <div class="star-wrapper">
                <input type="radio" id="star5" name="rating_wo" value="5">
                <label for="star5" title="5 bintang">★</label>

                <input type="radio" id="star4" name="rating_wo" value="4">
                <label for="star4" title="4 bintang">★</label>

                <input type="radio" id="star3" name="rating_wo" value="3">
                <label for="star3" title="3 bintang">★</label>

                <input type="radio" id="star2" name="rating_wo" value="2">
                <label for="star2" title="2 bintang">★</label>

                <input type="radio" id="star1" name="rating_wo" value="1">
                <label for="star1" title="1 bintang">★</label>
            </div>

            <!-- TEXTAREA FEEDBACK -->
            <label class="field-label" for="feedback_wo">Masukkan feedback untuk WO</label>
            <textarea
                id="feedback_wo"
                name="feedback_wo"
                placeholder="Tuliskan pengalaman dan kesan kamu terhadap pelayanan Staff WO..."
            ></textarea>

            <!-- SUBMIT -->
            <div class="btn-wrap">
                <button type="submit" class="btn-submit">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="22" y1="2" x2="11" y2="13"/>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                    </svg>
                    Kirim Feedback
                </button>
            </div>

        </div>
    </form>
</main>

</body>
</html>