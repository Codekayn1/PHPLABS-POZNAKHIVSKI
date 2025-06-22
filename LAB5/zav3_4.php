<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Варіант 8 - Множення та Валідація URL</title>
</head>
<body>
    <h1>Варіант 8</h1>

    <!-- Задача 1. Обробка форми з множенням (GET) -->
    <h2>Задача 1. Множення двох чисел (GET)</h2>
    <form method="get" action="">
        <label>Число 1: <input type="number" name="num1" required></label><br><br>
        <label>Число 2: <input type="number" name="num2" required></label><br><br>
        <input type="submit" name="multiply" value="Множити">
    </form>

    <?php
    if (isset($_GET['multiply'])) {
        $num1 = (float) $_GET['num1'];
        $num2 = (float) $_GET['num2'];
        $result = $num1 * $num2;
        echo "<p><strong>Результат множення:</strong> $num1 × $num2 = $result</p>";
    }
    ?>

    <hr>

    <!-- Задача 2. Валідація URL (POST) -->
    <h2>Задача 2. Валідація URL (POST)</h2>
    <form method="post" action="">
        <label>Введіть URL: <input type="text" name="url" required></label><br><br>
        <input type="submit" name="validate" value="Перевірити URL">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['validate'])) {
        $url = trim($_POST['url']);

        if (filter_var($url, FILTER_VALIDATE_URL)) {
            echo "<p style='color:green;'><strong>URL є валідним:</strong> $url</p>";
        } else {
            echo "<p style='color:red;'><strong>URL невалідний!</strong></p>";
        }
    }
    ?>
</body>
</html>
