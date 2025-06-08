<?php
session_start();

require_once __DIR__ . '/classes/User.php';
use App\User;

// Перевірка, чи користувач авторизований і чи він адмін
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo 'Prístup zamietnutý';
    exit;
}

// Підключення до бази
$mysqli = new mysqli('localhost', 'root', '', 'obchod');
if ($mysqli->connect_error) {
    die("Chyba pri pripájaní k databáze: " . $mysqli->connect_error);
}

// Обробка POST-запиту (змінити роль користувача)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['new_role'])) {
    $userId = (int)$_POST['user_id'];
    $newRole = ($_POST['new_role'] === 'admin') ? 'admin' : 'user';

    $stmt = $mysqli->prepare("UPDATE users SET role = ? WHERE id = ?");
    $stmt->bind_param("si", $newRole, $userId);
    $stmt->execute();
    $stmt->close();

    if ($_SESSION['user_id'] === $userId) {
        $_SESSION['role'] = $newRole;
    }

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Отримання всіх користувачів
function getAllUsers(mysqli $mysqli): array {
    $result = $mysqli->query("SELECT id, username, role FROM users");
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

$users = getAllUsers($mysqli);
?>

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
                    <h3>Admin Panel</h3>
                </div>
            </div>
        </div>
    </div>

    <main style="max-width: 900px; margin: 40px auto; padding: 0 20px;">

        <h1 style="text-align: center; margin-bottom: 30px;">Zoznam používateľov</h1>

        <div style="overflow-x: auto; display: flex; justify-content: center;">
            <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse; width: 100%; max-width: 700px;">
                <thead style="background: #f0f0f0;">
                <tr>
                    <th>ID</th>
                    <th>Používateľ</th>
                    <th>Role</th>
                    <th>Činnosť</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['role']) ?></td>
                        <td>
                            <?php if ($user['id'] !== $_SESSION['user_id']): ?>
                                <form method="post" style="display: inline;">
                                    <input type="hidden" name="user_id" value="<?= (int)$user['id'] ?>">
                                    <?php if ($user['role'] === 'admin'): ?>
                                        <input type="hidden" name="new_role" value="user">
                                        <button type="submit" style="background-color: orange; padding: 6px 12px;">
                                            Urobiť používateľom
                                        </button>
                                    <?php else: ?>
                                        <input type="hidden" name="new_role" value="admin">
                                        <button type="submit" style="padding: 6px 12px;">
                                            Urobiť adminom
                                        </button>
                                    <?php endif; ?>
                                </form>
                            <?php else: ?>
                                Vy (<?= htmlspecialchars($user['role']) ?>)
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </main>

<?php include 'parts/footer.php'; ?>