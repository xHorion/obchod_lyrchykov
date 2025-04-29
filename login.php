<!DOCTYPE html>
<html lang="en">

<body>

<!-- ***** Preloader Start ***** -->
<div id="js-preloader" class="js-preloader">
    <div class="preloader-inner">
        <span class="dot"></span>
        <div class="dots">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
</div>


<!-- ***** Preloader End ***** -->

<!-- ***** Header Area Start ***** -->
<?php include_once "parts/header.php" ?>
<!-- ***** Header Area End ***** -->

<div class="page-heading header-text">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h3>Autorizácia</h3>
                <span class="breadcrumb"><a href="index.php">Domov</a>  >  Autorizácia</span>
            </div>
        </div>
    </div>
</div>

<?php

session_start();

$mysqli = new mysqli("localhost", "root", "", "obchod");

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = trim($_POST['password'] ?? ''); // Видалення зайвих пробілів

    if (empty($username) || empty($password)) {
        $message = "❌ Усі поля обов'язкові.";
    } else {
        $stmt = $mysqli->prepare("SELECT id, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);

        if ($stmt->execute()) {
            $stmt->store_result();

            if ($stmt->num_rows === 1) {
                $stmt->bind_result($id, $hashedPassword);
                $stmt->fetch();

                // Логування для дебагу
                error_log("Password entered: " . $password);
                error_log("Hashed password from DB: " . $hashedPassword);

                // Перевірка пароля
                if (password_verify($password, $hashedPassword)) {
                    $_SESSION['user_id'] = $id;
                    $_SESSION['username'] = $username;
                    $message = "✅ Авторизація успішна. Вітаємо, $username!";
                } else {
                    $message = "❌ Невірний пароль.";
                }
            } else {
                $message = "❌ Користувача не знайдено.";
            }
        } else {
            $message = "❌ Помилка запиту до бази даних.";
        }
        $stmt->close();
    }
}
?>

<div>
    <h2>Форма входу</h2>

    <?php if (!empty($message)): ?>
        <p><strong><?= htmlspecialchars($message) ?></strong></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Логін:<br>
            <input type="text" name="username" required>
        </label><br><br>

        <label>Пароль:<br>
            <input type="password" name="password" required>
        </label><br><br>

        <button type="submit">Увійти</button>
    </form>
    <p>Ще не маєте акаунт? <a href="register.php">Зареєструватись</a></p>
</div>

<?php include_once "parts/footer.php" ?>

</body>
</html>