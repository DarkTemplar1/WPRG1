<?php
$simpleResult = $simpleError = null;
$advResult = $advError = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simple_submit'])) {
    $a = trim($_POST['a'] ?? '');
    $b = trim($_POST['b'] ?? '');
    $op = $_POST['sop'] ?? '';

    if ($a === '' || $b === '' || !is_numeric($a) || !is_numeric($b)) {
        $simpleError = 'Oba pola muszą być liczbami.';
    } else {
        $a = $a + 0; $b = $b + 0;
        switch ($op) {
            case 'add': $simpleResult = $a + $b; break;
            case 'sub': $simpleResult = $a - $b; break;
            case 'mul': $simpleResult = $a * $b; break;
            case 'div':
                if ($b == 0) { $simpleError = 'Nie można dzielić przez zero.'; }
                else { $simpleResult = $a / $b; }
                break;
            default: $simpleError = 'Nieprawidłowa operacja.';
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adv_submit'])) {
    $x  = trim($_POST['x'] ?? '');
    $aop = $_POST['aop'] ?? '';

    if ($x === '') {
        $advError = 'Pole jest wymagane.';
    } else {
        switch ($aop) {
            case 'cos': case 'sin': case 'tan':
            if (!is_numeric($x)) { $advError = 'Wprowadź liczbę (radiany).'; break; }
            $v = $x + 0;
            if ($aop === 'cos') $advResult = cos($v);
            elseif ($aop === 'sin') $advResult = sin($v);
            else $advResult = tan($v);
            break;
            case 'bin2dec':
                if (!preg_match('/^[01]+$/', $x)) { $advError = 'Liczba binarna może zawierać tylko 0 i 1.'; break; }
                $advResult = bindec($x);
                break;
            case 'dec2bin':
                if (!preg_match('/^\d+$/', $x)) { $advError = 'Podaj nieujemną liczbę całkowitą.'; break; }
                $advResult = decbin((int)$x);
                break;
            case 'dec2hex':
                if (!preg_match('/^\d+$/', $x)) { $advError = 'Podaj nieujemną liczbę całkowitą.'; break; }
                $advResult = dechex((int)$x);
                break;
            case 'hex2dec':
                if (!preg_match('/^[0-9a-fA-F]+$/', $x)) { $advError = 'Nieprawidłowa liczba szesnastkowa.'; break; }
                $advResult = hexdec($x);
                break;
            default:
                $advError = 'Nieprawidłowa operacja.';
        }
    }
}
?>
<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <title>Kalkulator prosty i zaawansowany</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px auto; max-width: 540px; padding: 20px; background: #f4f6f8; }
        .card { background: #fff; border: 3px solid #6b9bd2; border-radius: 6px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
        h1 { margin-top: 0; }
        h2 { border-bottom: 1px solid #bbb; padding-bottom: 6px; margin-top: 24px; }
        fieldset { border: none; padding: 0; margin: 0; }
        .row { display: flex; gap: 8px; align-items: center; margin-top: 10px; flex-wrap: wrap; }
        input[type=text] { padding: 8px; border: 1px solid #bbb; border-radius: 3px; width: 120px; }
        select { padding: 8px; border: 1px solid #bbb; border-radius: 3px; }
        button { margin-top: 12px; padding: 8px 16px; cursor: pointer; background: #e8e8e8; border: 1px solid #aaa; border-radius: 3px; }
        button:hover { background: #d8d8d8; }
        .result { margin-top: 12px; padding: 10px; background: #e8f4e8; border-radius: 4px; font-family: monospace; }
        .error  { margin-top: 12px; padding: 10px; background: #fbecec; color: #a00; border-radius: 4px; }
    </style>
</head>
<body>
<div class="card">
    <h1>Kalkulator</h1>

    <h2>Prosty</h2>
    <form method="post" action="">
        <fieldset>
            <div class="row">
                <input type="text" name="a" value="<?= htmlspecialchars($_POST['a'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                <select name="sop">
                    <?php $sop = $_POST['sop'] ?? 'add'; ?>
                    <option value="add" <?= $sop==='add'?'selected':'' ?>>Dodawanie</option>
                    <option value="sub" <?= $sop==='sub'?'selected':'' ?>>Odejmowanie</option>
                    <option value="mul" <?= $sop==='mul'?'selected':'' ?>>Mnożenie</option>
                    <option value="div" <?= $sop==='div'?'selected':'' ?>>Dzielenie</option>
                </select>
                <input type="text" name="b" value="<?= htmlspecialchars($_POST['b'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
            </div>
            <button type="submit" name="simple_submit" value="1">Oblicz</button>
        </fieldset>
    </form>
    <?php if ($simpleError): ?>
        <div class="error"><?= htmlspecialchars($simpleError, ENT_QUOTES, 'UTF-8') ?></div>
    <?php elseif ($simpleResult !== null): ?>
        <div class="result">Wynik: <?= htmlspecialchars((string)$simpleResult, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <h2>Zaawansowany</h2>
    <form method="post" action="">
        <fieldset>
            <div class="row">
                <input type="text" name="x" value="<?= htmlspecialchars($_POST['x'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                <select name="aop">
                    <?php $aop = $_POST['aop'] ?? 'cos'; ?>
                    <option value="cos"     <?= $aop==='cos'?'selected':'' ?>>cos</option>
                    <option value="sin"     <?= $aop==='sin'?'selected':'' ?>>sin</option>
                    <option value="tan"     <?= $aop==='tan'?'selected':'' ?>>tan</option>
                    <option value="bin2dec" <?= $aop==='bin2dec'?'selected':'' ?>>Binarne → Dziesiętne</option>
                    <option value="dec2bin" <?= $aop==='dec2bin'?'selected':'' ?>>Dziesiętne → Binarne</option>
                    <option value="dec2hex" <?= $aop==='dec2hex'?'selected':'' ?>>Dziesiętne → Szesnastkowe</option>
                    <option value="hex2dec" <?= $aop==='hex2dec'?'selected':'' ?>>Szesnastkowe → Dziesiętne</option>
                </select>
            </div>
            <button type="submit" name="adv_submit" value="1">oblicz</button>
        </fieldset>
    </form>
    <?php if ($advError): ?>
        <div class="error"><?= htmlspecialchars($advError, ENT_QUOTES, 'UTF-8') ?></div>
    <?php elseif ($advResult !== null): ?>
        <div class="result">Wynik: <?= htmlspecialchars((string)$advResult, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>
</div>
</body>
</html>
