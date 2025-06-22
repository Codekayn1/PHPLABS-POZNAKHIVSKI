<?php
session_start();

// Ініціалізація даних у сесії
if (!isset($_SESSION['library_members'])) {
    $_SESSION['library_members'] = [];
}

// Обробка форми
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_member'])) {
        $new_member = [
            'id' => uniqid(),
            'name' => $_POST['name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'join_date' => $_POST['join_date'] ?? date('Y-m-d')
        ];
        
        // Валідація даних
        $valid = true;
        if (empty($new_member['name']) || strlen($new_member['name']) < 2) {
            $error = "Ім'я повинно містити щонайменше 2 символи";
            $valid = false;
        }
        
        if (!filter_var($new_member['email'], FILTER_VALIDATE_EMAIL)) {
            $error = "Будь ласка, введіть коректний email";
            $valid = false;
        }
        
        // Перевірка унікальності email
        foreach ($_SESSION['library_members'] as $member) {
            if ($member['email'] === $new_member['email']) {
                $error = "Користувач з таким email вже існує";
                $valid = false;
                break;
            }
        }
        
        if ($valid) {
            $_SESSION['library_members'][] = $new_member;
            $success = "Нового члена бібліотеки успішно додано!";
        }
    }
}

// Отримання недавніх членів (останні 30 днів)
$recent_members = array_filter($_SESSION['library_members'], function($member) {
    $join_date = new DateTime($member['join_date']);
    $month_ago = new DateTime('-1 month');
    return $join_date >= $month_ago;
});

// Визначення поточної сторінки
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Бібліотечна система</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
        .nav { margin-bottom: 20px; }
        .message { padding: 10px; margin: 10px 0; border-radius: 4px; }
        .error { background-color: #ffebee; color: #c62828; }
        .success { background-color: #e8f5e9; color: #2e7d32; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f5f5f5; }
        form { margin-top: 20px; }
        label { display: block; margin-bottom: 5px; }
        input { margin-bottom: 10px; padding: 8px; width: 100%; box-sizing: border-box; }
        button { padding: 10px 15px; background: #2196F3; color: white; border: none; cursor: pointer; }
        button:hover { background: #0b7dda; }
    </style>
</head>
<body>
    <h1>Бібліотечна система</h1>
    
    <div class="nav">
        <a href="?page=home">Головна</a> |
        <a href="?page=add">Додати члена</a> |
        <a href="?page=recent">Недавні члени</a> |
        <a href="?page=reset">Скинути дані</a>
    </div>
    
    <?php if (isset($error)): ?>
        <div class="message error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <?php if (isset($success)): ?>
        <div class="message success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    
    <?php if ($page === 'home'): ?>
        <h2>Ласкаво просимо!</h2>
        <p>Кількість зареєстрованих членів: <?= count($_SESSION['library_members']) ?></p>
        
    <?php elseif ($page === 'add'): ?>
        <h2>Додати нового члена</h2>
        <form method="POST" action="?page=add">
            <label>Ім'я:</label>
            <input type="text" name="name" required minlength="2">
            
            <label>Email:</label>
            <input type="email" name="email" required>
            
            <label>Дата приєднання:</label>
            <input type="date" name="join_date" required value="<?= date('Y-m-d') ?>">
            
            <button type="submit" name="add_member">Додати</button>
        </form>
        
    <?php elseif ($page === 'recent'): ?>
        <h2>Недавно зареєстровані члени</h2>
        <?php if (count($recent_members) > 0): ?>
            <table>
                <tr>
                    <th>Ім'я</th>
                    <th>Email</th>
                    <th>Дата приєднання</th>
                </tr>
                <?php foreach ($recent_members as $member): ?>
                <tr>
                    <td><?= htmlspecialchars($member['name']) ?></td>
                    <td><?= htmlspecialchars($member['email']) ?></td>
                    <td><?= htmlspecialchars($member['join_date']) ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>Немає нових членів за останній місяць.</p>
        <?php endif; ?>
        
    <?php elseif ($page === 'reset'): ?>
        <?php
        $_SESSION['library_members'] = [];
        header('Location: ?page=home');
        exit;
        ?>
    <?php endif; ?>
</body>
</html>
