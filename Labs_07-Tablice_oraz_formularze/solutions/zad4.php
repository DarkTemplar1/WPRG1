<?php
function parseSet(string $raw): array {
    $raw = trim($raw);
    if ($raw === '') return [];
    $parts = array_map('trim', explode(',', $raw));
    $nums = [];
    foreach ($parts as $p) {
        if ($p === '' || !is_numeric($p)) return ['__invalid__' => true];
        $nums[] = $p + 0;
    }
    return array_values(array_unique($nums, SORT_NUMERIC));
}

function formatSet(array $set): string {
    sort($set, SORT_NUMERIC);
    return '{' . implode(', ', $set) . '}';
}

$a = $b = null;
$op = $_POST['op'] ?? 'unia';
$error = null;
$result = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $a = parseSet($_POST['a'] ?? '');
    $b = parseSet($_POST['b'] ?? '');

    if (isset($a['__invalid__']) || isset($b['__invalid__'])) {
        $error = 'Oba zbiory muszą zawierać wyłącznie liczby oddzielone przecinkami.';
    } else {
        switch ($op) {
            case 'unia':
                $res = array_values(array_unique(array_merge($a, $b), SORT_NUMERIC));
                break;
            case 'przeciecie':
                $res = array_values(array_intersect($a, $b));
                break;
            case 'roznica_ab':
                $res = array_values(array_diff($a, $b));
                break;
            case 'roznica_ba':
                $res = array_values(array_diff($b, $a));
                break;
            case 'sym':
                $res = array_values(array_merge(array_diff($a, $b), array_diff($b, $a)));
                break;
            default:
                $res = [];
        }
        $result = formatSet($res);
    }
}
?>
<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <title>Kalkulator zbiorów</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #22303b;
            color: #eaeef2;
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }
        .wrap { width: 100%; max-width: 420px; }
        h1 {
            text-align: center;
            font-weight: 300;
            letter-spacing: 1px;
            color: #eaeef2;
            margin-bottom: 20px;
        }
        .card {
            background: #2f4353;
            border-radius: 8px;
            padding: 24px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
        }
        label {
            display: block;
            margin-top: 14px;
            margin-bottom: 6px;
            font-size: 0.9em;
        }
        input[type=text], select {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 4px;
            background: #e9eef3;
            color: #222;
            box-sizing: border-box;
            font-size: 1em;
        }
        button {
            margin-top: 20px;
            width: 100%;
            padding: 12px;
            background: #e74c3c;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 1em;
            cursor: pointer;
        }
        button:hover { background: #c0392b; }
        .result, .error {
            margin-top: 20px;
            padding: 12px;
            border-radius: 4px;
            text-align: center;
            font-family: monospace;
            font-size: 1.1em;
        }
        .result { background: #1abc9c; color: #fff; }
        .error  { background: #e74c3c; color: #fff; }
    </style>
</head>
<body>
<div class="wrap">
    <h1>Kalkulator zbiorów</h1>
    <div class="card">
        <form method="post" action="">
            <label for="a">Zbiór A (liczby oddzielone przecinkami):</label>
            <input type="text" id="a" name="a" value="<?= htmlspecialchars($_POST['a'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

            <label for="b">Zbiór B (liczby oddzielone przecinkami):</label>
            <input type="text" id="b" name="b" value="<?= htmlspecialchars($_POST['b'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

            <label for="op">Operacja:</label>
            <select id="op" name="op">
                <option value="unia"        <?= $op==='unia'?'selected':'' ?>>Unia</option>
                <option value="przeciecie"  <?= $op==='przeciecie'?'selected':'' ?>>Przecięcie</option>
                <option value="roznica_ab"  <?= $op==='roznica_ab'?'selected':'' ?>>Różnica A \ B</option>
                <option value="roznica_ba"  <?= $op==='roznica_ba'?'selected':'' ?>>Różnica B \ A</option>
                <option value="sym"         <?= $op==='sym'?'selected':'' ?>>Różnica symetryczna</option>
            </select>

            <button type="submit">Oblicz</button>
        </form>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
        <?php elseif ($result !== null): ?>
            <div class="result"><?= htmlspecialchars($result, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
