<?php
// --- Обробка форми конвертації ---
$rate = 40;
$usdResult = '';

if (isset($_GET['uah']) && $_GET['uah'] !== '') {
    $uah = floatval($_GET['uah']);
    $usd = $uah / $rate;
    $usdResult = "💵 $uah грн = " . round($usd, 2) . " USD";
}

// --- Обробка форми перевірки довжини тексту ---
$textResult = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['text'])) {
    $text = trim($_POST['text']);
    $length = mb_strlen($text);
    if ($length <= 100) {
        $textResult = "✅ Довжина тексту — $length символів (у межах норми)";
    } else {
        $textResult = "❌ Довжина тексту — $length символів (перевищено 100)";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>PHP Задачі - Варіант 5</title>
</head>
<body>

<h2>1. Конвертація гривень у долари</h2>
<form method="get">
    <label>Введіть суму в гривнях:</label><br>
    <input type="number" name="uah" step="0.01" required><br><br>
    <button type="submit">Конвертувати</button>
</form>

<?php if ($usdResult): ?>
    <p><?php echo $usdResult; ?></p>
<?php endif; ?>

<hr>

<h2>2. Перевірка довжини тексту</h2>
<form method="post">
    <label>Введіть текст:</label><br>
    <textarea name="text" rows="4" cols="50" required></textarea><br><br>
    <button type="submit">Перевірити</button>
</form>

<?php if ($textResult): ?>
    <p><?php echo $textResult; ?></p>
<?php endif; ?>

</body>
</html>
