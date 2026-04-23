<?php
$result = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $raw = trim($_POST['nums'] ?? '');
    if ($raw === '') {
        $error = 'Wprowadź przynajmniej jedną liczbę.';
    } else {
        $tokens = preg_split('/\s+/', $raw);
        $converted = [];
        foreach ($tokens as $t) {
            if (!preg_match('/^[0-7]+$/', $t)) {
                $error = "Nieprawidłowa liczba ósemkowa: " . htmlspecialchars($t, ENT_QUOTES, 'UTF-8');
                $converted = [];
                break;
            }
            $converted[] = '0x' . dechex(octdec($t));
        }
        if (!$error) {
            $result = implode(' ', $converted);
        }
    }
}
?>
<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <title>Zadanie 2 - Ósemkowe na szesnastkowe</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 640px; margin: 40px auto; padding: 20px; }
        label { display: block; margin-top: 12px; font-weight: bold; }
        input[type=text] { width: 100%; padding: 8px; box-sizing: border-box; }
        button { margin-top: 16px; padding: 10px 20px; cursor: pointer; }
        .result { margin-top: 20px; padding: 12px; background: #eef; border-radius: 4px; font-family: monospace; }
        .error { margin-top: 20px; padding: 12px; background: #fee; border-radius: 4px; color: #900; }
    </style>
</head>
<body>
<h1>Zadanie 2</h1>
<p>Wprowadź liczby ósemkowe oddzielone spacjami. Zostaną przeliczone na postać szesnastkową.</p>

<form method="post" action="">
    <label for="nums">Liczby ósemkowe:</label>
    <input type="text" id="nums" name="nums" value="<?= htmlspecialchars($_POST['nums'] ?? '717 233', ENT_QUOTES, 'UTF-8') ?>">
    <button type="submit">Konwertuj</button>
</form>

<?php if ($error): ?>
    <div class="error"><?= $error ?></div>
<?php elseif ($result !== null): ?>
    <div class="result"><?= htmlspecialchars($result, ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>
</body>
</html>
