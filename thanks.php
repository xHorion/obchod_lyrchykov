<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: Auth.php");
    exit;
}

include('functions.php');

$username = $_SESSION['username'] ?? null;

?>

<!DOCTYPE html>
<html lang="en">

<body>
<!-- ***** Header Area Start ***** -->
<?php include_once "parts/header.php" ?>
<!-- ***** Header Area End ***** -->
<div class="page-heading header-text">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h3>Ďakujeme</h3>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-body text-center">


                    <h4 class="mb-3">Ďakujeme, <?= htmlspecialchars($username) ?>!</h4>
                    <p class="mb-4">Ste úspešne prihlásený!</p>

                    <a href="index.php" class="btn" style="background-color: #0069ff; color: white; border-radius: 8px; text-decoration: none; padding: 10px 20px;">
                        Prejsť na hlavnú stránku
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once "parts/footer.php" ?>
