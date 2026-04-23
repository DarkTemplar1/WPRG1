<?php
function datawielkanocy(int $r): ?string {
    if     ($r >= 1    && $r <= 1582) { $x = 15; $y = 6; }
    elseif ($r >= 1583 && $r <= 1699) { $x = 22; $y = 2; }
    elseif ($r >= 1700 && $r <= 1799) { $x = 23; $y = 3; }
    elseif ($r >= 1800 && $r <= 1899) { $x = 23; $y = 4; }
    elseif ($r >= 1900 && $r <= 2099) { $x = 24; $y = 5; }
    elseif ($r >= 2100 && $r <= 2199) { $x = 24; $y = 6; }
    else { return null; }

    $a = $r % 19;
    $b = $r % 4;
    $c = $r % 7;
    $d = (19 * $a + $x) % 30;
    $e = (2 * $b + 4 * $c + 6 * $d + $y) % 7;

    if ($e == 6 && $d == 29) {
        return '26 kwietnia';
    }
    if ($e == 6 && $d == 28 && ((11 * $x + 11) % 30 < 19)) {
        return '18 kwietnia';
    }
    if (($d + $e) < 10) {
        return (22 + $d + $e) . ' marca';
    }
    return (($d + $e) - 9) . ' kwietnia';
}

$result = null;
$error  = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rokRaw = trim($_POST['rok'] ?? '');
    if ($rokRaw === '' || !preg_match('/^\d+$/', $rokRaw)) {
        $error = 'Nieprawidłowy rok';
    } else {
        $rok = (int)$rokRaw;
        $data = datawielkanocy($rok);
        if ($data === null) {
            $error = 'Nieprawidłowy rok';
        } else {
            $result = "Wielkanoc w roku $rok przypada $data";
        }
    }
}
?>
<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <title>Data Wielkanocy</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .card {
            background: #fff;
            border-radius: 12px;
            padding: 36px 40px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            max-width: 440px;
            width: 100%;
            text-align: center;
        }
        h1 { margin-top: 0; font-size: 1.6em; }
        p.intro { color: #555; margin-bottom: 24px; }
        .field {
            display: flex;
            align-items: center;
            gap: 10px;
            justify-content: center;
            margin-bottom: 18px;
        }
        label { font-weight: bold; }
        input[type=number] {
            padding: 10px;
            border: 1px solid #bbb;
            border-radius: 4px;
            width: 160px;
            font-size: 1em;
        }
        button {
            background: linear-gradient(90deg, #0071e3, #005ec3);
            color: #fff;
            border: none;
            padding: 12px 20px;
            width: 100%;
            font-size: 1em;
            font-weight: bold;
            letter-spacing: 1px;
            border-radius: 24px;
            cursor: pointer;
        }
        button:hover { filter: brightness(1.05); }
        .result, .error {
            margin-top: 20px;
            padding: 12px;
            border-radius: 6px;
        }
        .result { background: #e7f6e7; color: #225c22; }
        .error  { background: #fbecec; color: #a00; }
    </style>
</head>
<body>
<div class="card">
    <h1>Data Wielkanocy</h1>
    <p class="intro">Aby obliczyć datę Wielkanocy dla podanego roku, wprowadź rok poniżej:</p>

    <form method="post" action="">
        <div class="field">
            <label for="rok">Wprowadź rok:</label>
            <input type="number" id="rok" name="rok" min="1" max="2199"
                   value="<?= htmlspecialchars($_POST['rok'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
        </div>
        <button type="submit">OBLICZ</button>
    </form>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php elseif ($result !== null): ?>
        <div class="result"><?= htmlspecialchars($result, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>
</div>
</body>
</html>
