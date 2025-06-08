<?php session_start(); ?>

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
        $message = "❌ Všetky polia sú povinné.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "❌ Formát e-mailu je nesprávny.";
    } else {
        $stmt = $mysqli->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "❌ Používateľ alebo e-mail už existuje.";
        } else {
            // Створення нового користувача з роллю "user"
            $stmt->close();
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $role = 'user';

            $stmt = $mysqli->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $email, $hashed, $role);

            if ($stmt->execute()) {
                $message = "✅ Používateľ bol úspešne zaregistrovaný.";
            } else {
                $message = "❌ Chyba pri pridávaní používateľa.";
            }
        }
        $stmt->close();
    }
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-body">
                    <h4 class="text-center mb-4 fw-bold" style="color: #0069ff;">Registračný formulár</h4>

                    <?php if (!empty($message)): ?>
                        <div class="alert alert-info text-center" role="alert">
                            <?= htmlspecialchars($message) ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="username" class="form-label">Login</label>
                            <input type="text" name="username" id="username" class="form-control rounded-3" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control rounded-3" required>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">Heslo</label>
                            <input type="password" name="password" id="password" class="form-control rounded-3" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn" style="background-color: #0069ff; color: white; border-radius: 8px;">Registrácia</button>
                        </div>
                    </form>

                    <p class="mt-4 text-center">
                        Už máte účet?
                        <a href="login.php" class="text-decoration-none" style="color: #00bcd4;">Prihlásiť sa</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once "parts/footer.php" ?>

</body>
</html>