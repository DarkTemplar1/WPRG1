<?php
$result = null;
$error = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $raw = trim($_POST['arr'] ?? '');
    $nRaw = trim($_POST['n'] ?? '');

    if ($raw === '' || $nRaw === '') {
        $error = true;
    } else {
        $tokens = preg_split('/\s+/', $raw);
        $allNumeric = true;
        foreach ($tokens as $t) {
            if (!is_numeric($t)) { $allNumeric = false; break; }
        }

        if (!$allNumeric || !preg_match('/^-?\d+$/', $nRaw)) {
            $error = true;
        } else {
            $n = (int)$nRaw;
            if ($n < 0 || $n > count($tokens)) {
                $error = true;
            } else {
                array_splice($tokens, $n, 0, '$');
                $result = implode(' ', $tokens);
            }
        }
    }
}
?>
<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <title>Zadanie 1 - Tablica ze znakiem $</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 640px; margin: 40px auto; padding: 20px; }
        label { display: block; margin-top: 12px; font-weight: bold; }
        input[type=text], input[type=number] { width: 100%; padding: 8px; box-sizing: border-box; }
        button { margin-top: 16px; padding: 10px 20px; cursor: pointer; }
        .result { margin-top: 20px; padding: 12px; background: #eef; border-radius: 4px; font-family: monospace; }
        .error { margin-top: 20px; padding: 12px; background: #fee; border-radius: 4px; color: #900; font-weight: bold; }
    </style>
</head>
<body>
<h1>Zadanie 1</h1>
<p>Podaj tablicę liczb (oddzielonych spacjami) oraz pozycję <code>n</code>. Na <em>n</em>-tej pozycji zostanie wstawiony znak <code>$</code>, a tablica zostanie "rozepchnięta".</p>

<form method="post" action="">
    <label for="arr">Tablica liczb:</label>
    <input type="text" id="arr" name="arr" value="<?= htmlspecialchars($_POST['arr'] ?? '1 2 3 4 5', ENT_QUOTES, 'UTF-8') ?>">

    <label for="n">Pozycja n:</label>
    <input type="number" id="n" name="n" value="<?= htmlspecialchars($_POST['n'] ?? '3', ENT_QUOTES, 'UTF-8') ?>">

    <button type="submit">Oblicz</button>
</form>

<?php if ($error): ?>
    <div class="error">BŁĄD</div>
<?php elseif ($result !== null): ?>
    <div class="result"><?= htmlspecialchars($result, ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>
</body>
</html>
c