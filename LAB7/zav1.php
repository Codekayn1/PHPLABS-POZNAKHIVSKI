<?php
$mysqli = new mysqli("localhost", "root", "", "learning_system");

// Створення бази та таблиць (виконати лише раз)
$mysqli->query("CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
)");
$mysqli->query("CREATE TABLE IF NOT EXISTS authors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
)");
$mysqli->query("CREATE TABLE IF NOT EXISTS materials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT,
    author_id INT,
    category_id INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES authors(id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
)");

// Додати авторів і категорії для тесту (лише якщо таблиці порожні)
$mysqli->query("INSERT INTO authors (name) SELECT * FROM (SELECT 'Іван Петренко') AS tmp WHERE NOT EXISTS (SELECT 1 FROM authors)");
$mysqli->query("INSERT INTO authors (name) SELECT * FROM (SELECT 'Олена Іванова') AS tmp WHERE NOT EXISTS (SELECT 1 FROM authors WHERE name = 'Олена Іванова')");
$mysqli->query("INSERT INTO categories (name) SELECT * FROM (SELECT 'Математика') AS tmp WHERE NOT EXISTS (SELECT 1 FROM categories)");
$mysqli->query("INSERT INTO categories (name) SELECT * FROM (SELECT 'Інформатика') AS tmp WHERE NOT EXISTS (SELECT 1 FROM categories WHERE name = 'Інформатика')");

// Обробка форми
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $content = $_POST["content"];
    $author_id = $_POST["author_id"];
    $category_id = $_POST["category_id"];

    $stmt = $mysqli->prepare("INSERT INTO materials (title, content, author_id, category_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssii", $title, $content, $author_id, $category_id);
    $stmt->execute();
    echo "<p style='color:green;'>✅ Матеріал успішно додано!</p>";
}

// Отримання даних для select
$authors = $mysqli->query("SELECT * FROM authors");
$categories = $mysqli->query("SELECT * FROM categories");
?>

<h2>Додати новий матеріал</h2>
<form method="post">
    Назва: <input type="text" name="title" required><br><br>
    Зміст:<br><textarea name="content" rows="4" cols="50" required></textarea><br><br>
    Автор:
    <select name="author_id">
        <?php while($a = $authors->fetch_assoc()): ?>
            <option value="<?= $a['id'] ?>"><?= htmlspecialchars($a['name']) ?></option>
        <?php endwhile; ?>
    </select><br><br>
    Категорія:
    <select name="category_id">
        <?php while($c = $categories->fetch_assoc()): ?>
            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
        <?php endwhile; ?>
    </select><br><br>
    <button type="submit">Додати матеріал</button>
</form>

<hr>

<h2>📊 Найпопулярніші категорії</h2>
<?php
$result = $mysqli->query("
    SELECT categories.name AS category, COUNT(materials.id) AS total
    FROM materials
    JOIN categories ON materials.category_id = categories.id
    GROUP BY category_id
    ORDER BY total DESC
");

echo "<ul>";
while ($row = $result->fetch_assoc()) {
    echo "<li><b>" . htmlspecialchars($row['category']) . "</b>: " . $row['total'] . " матеріал(ів)</li>";
}
echo "</ul>";
?>
