<?php
$imie = 'Marek';
$nazwisko = 'Molenda';
$numerIndeksu = 's27277';
?>
<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($imie . ' ' . $nazwisko, ENT_QUOTES, 'UTF-8') ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
        }
        .card {
            background: rgba(255, 255, 255, 0.12);
            padding: 40px 60px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        }
        h1 { margin: 0 0 10px; font-size: 2.5em; }
        p  { margin: 4px 0; font-size: 1.1em; }
    </style>
</head>
<body>
<div class="card">
    <h1><?= htmlspecialchars($imie . ' ' . $nazwisko, ENT_QUOTES, 'UTF-8') ?></h1>
    <p>Numer indeksu: <?= htmlspecialchars($numerIndeksu, ENT_QUOTES, 'UTF-8') ?></p>
</div>
</body>
</html>
