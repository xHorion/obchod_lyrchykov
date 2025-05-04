<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
?>

<div>
    <h2>Вітаємо, <?= htmlspecialchars($username) ?>!</h2>
    <p>Ви успішно авторизовані!</p>
    <a href="index.php">Перейти на головну</a>
</div>