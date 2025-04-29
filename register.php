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
                <h3>Registracia</h3>
                <span class="breadcrumb"><a href="index.php">Domov</a>  >  Registracia</span>
            </div>
        </div>
    </div>
</div>

<?php
$mysqli = new mysqli("localhost", "root", "", "obchod");

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password) || empty($email)) {
        $message = "❌ Усі поля обов’язкові.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "❌ Невірний формат електронної пошти.";
    } else {
        // Перевірка, чи користувач або email вже існує
        $stmt = $mysqli->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "❌ Користувач або email вже існує.";
        } else {
            // Створення нового користувача
            $stmt->close();
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $mysqli->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashed);

            if ($stmt->execute()) {
                $message = "✅ Користувача успішно зареєстровано.";
            } else {
                $message = "❌ Помилка при додаванні користувача.";
            }
        }
        $stmt->close();
    }
}
?>

<div>
<h2>Форма реєстрації</h2>

<?php if (!empty($message)): ?>
    <p><strong><?= htmlspecialchars($message) ?></strong></p>
<?php endif; ?>

<form method="POST" action="">
    <label>Логін:<br>
        <input type="text" name="username" required>
    </label><br><br>

    <label>Електронна пошта:<br>
        <input type="email" name="email" required>
    </label><br><br>

    <label>Пароль:<br>
        <input type="password" name="password" required>
    </label><br><br>

    <button type="submit">Зареєструватись</button>
</form>

<p>Вже маєте акаунт? <a href="login.php">Увійти</a></p>
</div>
<?php include_once "parts/footer.php" ?>

</body>
</html>