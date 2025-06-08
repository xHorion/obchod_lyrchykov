<?php
session_start();

require_once __DIR__ . '/classes/User.php';
require_once __DIR__ . '/classes/Auth.php';

$mysqli = new mysqli("localhost", "root", "", "obchod");
$auth = new \App\Auth($mysqli);

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $message = "❌ Všetky polia sú povinné.";
    } else {
        if ($auth->login($username, $password)) {
            header("Location: thanks.php");
            exit;
        } else {
            $message = "❌ Nesprávne meno alebo heslo.";
        }
    }
}
?>

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


<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-body">
                    <h4 class="text-center mb-4 fw-bold" style="color: #0069ff;">Prihlasovací formulár</h4>

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

                        <div class="mb-4">
                            <label for="password" class="form-label">Heslo</label>
                            <input type="password" name="password" id="password" class="form-control rounded-3" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn" style="background-color: #0069ff; color: white; border-radius: 8px;">Prihlásiť sa</button>
                        </div>
                    </form>

                    <p class="mt-4 text-center">
                        Ešte nemáte účet?
                        <a href="register.php" class="text-decoration-none" style="color: #00bcd4;">Registrácia</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once "parts/footer.php" ?>

</body>
</html>
