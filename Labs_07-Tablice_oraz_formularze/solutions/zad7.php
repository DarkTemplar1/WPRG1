<?php
$errors = [];
$submitted = null;

$imie     = $_POST['imie']     ?? '';
$email    = $_POST['email']    ?? '';
$telefon  = $_POST['telefon']  ?? '';
$temat    = $_POST['temat']    ?? '';
$wiadomosc= $_POST['wiadomosc']?? '';
$opcje    = $_POST['opcje']    ?? [];
$radio    = $_POST['radio']    ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (trim($imie) === '') {
        $errors[] = 'Imię i nazwisko jest wymagane.';
    }
    if (trim($email) === '') {
        $errors[] = 'Email jest wymagany.';
    } elseif (!preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]+$/', $email)) {
        $errors[] = 'Nieprawidłowy format adresu email.';
    }
    if (trim($telefon) === '') {
        $errors[] = 'Telefon jest wymagany.';
    } elseif (!preg_match('/^\+?[0-9 \-]{9,15}$/', $telefon)) {
        $errors[] = 'Nieprawidłowy format numeru telefonu.';
    }
    if ($temat === '' || $temat === 'Wybierz temat') {
        $errors[] = 'Wybierz temat z listy.';
    }
    if (trim($wiadomosc) === '') {
        $errors[] = 'Treść wiadomości jest wymagana.';
    }
    if (!is_array($opcje) || count($opcje) === 0) {
        $errors[] = 'Zaznacz przynajmniej jedną opcję (checkbox).';
    }
    if ($radio === '') {
        $errors[] = 'Wybierz jedną opcję (radio).';
    }

    if (empty($errors)) {
        $submitted = [
            'Imię i nazwisko' => $imie,
            'Email'           => $email,
            'Telefon'         => $telefon,
            'Temat'           => $temat,
            'Wiadomość'       => $wiadomosc,
            'Wybrane opcje'   => implode(', ', (array)$opcje),
            'Wybrana opcja'   => $radio,
        ];
    }
}
?>
<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <title>Formularz kontaktowy</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #000;
            color: #222;
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .card {
            background: #fff;
            border-radius: 10px;
            padding: 24px;
            width: 100%;
            max-width: 380px;
            box-shadow: 0 8px 24px rgba(255, 255, 255, 0.1);
        }
        input[type=text], input[type=email], input[type=tel], select, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 0.95em;
        }
        textarea { resize: vertical; min-height: 90px; }
        .group { margin-bottom: 12px; }
        .group p { margin: 4px 0 6px; font-size: 0.95em; }
        .group label { margin-right: 12px; font-size: 0.95em; }
        button {
            width: 100%;
            background: #2a90d0;
            color: #fff;
            border: none;
            padding: 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
        }
        button:hover { background: #2478b3; }
        .errors {
            background: #fde8e8;
            color: #900;
            padding: 10px 14px;
            border-radius: 4px;
            margin-bottom: 12px;
        }
        .errors ul { margin: 4px 0 0 20px; padding: 0; }
        .submitted {
            background: #e8f4e8;
            color: #225c22;
            padding: 14px 18px;
            border-radius: 4px;
            margin-top: 12px;
        }
        .submitted h2 { margin-top: 0; }
    </style>
</head>
<body>
<div class="card">
    <?php if (!empty($errors)): ?>
        <div class="errors">
            <strong>Popraw błędy:</strong>
            <ul>
                <?php foreach ($errors as $e): ?>
                    <li><?= htmlspecialchars($e, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="">
        <input type="text" name="imie" placeholder="Twoje imię i nazwisko"
               value="<?= htmlspecialchars($imie, ENT_QUOTES, 'UTF-8') ?>">

        <input type="email" name="email" placeholder="Twój email"
               value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>">

        <input type="tel" name="telefon" placeholder="Twój telefon"
               value="<?= htmlspecialchars($telefon, ENT_QUOTES, 'UTF-8') ?>">

        <select name="temat">
            <option value="" <?= $temat===''?'selected':'' ?>>Wybierz temat</option>
            <option value="Temat 1" <?= $temat==='Temat 1'?'selected':'' ?>>Temat 1</option>
            <option value="Temat 2" <?= $temat==='Temat 2'?'selected':'' ?>>Temat 2</option>
        </select>

        <textarea name="wiadomosc" placeholder="Treść wiadomości"><?= htmlspecialchars($wiadomosc, ENT_QUOTES, 'UTF-8') ?></textarea>

        <div class="group">
            <p>Wybierz opcje:</p>
            <label><input type="checkbox" name="opcje[]" value="Opcja 1"
                    <?= in_array('Opcja 1', (array)$opcje, true) ? 'checked' : '' ?>> Opcja 1</label>
            <label><input type="checkbox" name="opcje[]" value="Opcja 2"
                    <?= in_array('Opcja 2', (array)$opcje, true) ? 'checked' : '' ?>> Opcja 2</label>
        </div>

        <div class="group">
            <p>Wybierz jedną opcję:</p>
            <label><input type="radio" name="radio" value="Opcja 1"
                    <?= $radio==='Opcja 1'?'checked':'' ?>> Opcja 1</label>
            <label><input type="radio" name="radio" value="Opcja 2"
                    <?= $radio==='Opcja 2'?'checked':'' ?>> Opcja 2</label>
        </div>

        <button type="submit">Wyślij</button>
    </form>

    <?php if ($submitted): ?>
        <div class="submitted">
            <h2>Otrzymane dane:</h2>
            <ul>
                <?php foreach ($submitted as $key => $val): ?>
                    <li><strong><?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>:</strong>
                        <?= nl2br(htmlspecialchars($val, ENT_QUOTES, 'UTF-8')) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
