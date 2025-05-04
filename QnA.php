<?php
session_start();
require_once "classes/qna.php";

use qna_class\QnA;

$qna = new QnA();

// Обробка додавання нового питання
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_qna']) && $_SESSION['role'] === 'admin') {
    $question = $_POST['new_question'] ?? '';
    $answer = $_POST['new_answer'] ?? '';
    if (!empty($question) && !empty($answer)) {
        $qna->add($question, $answer);
    }
}

// Обробка редагування
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_qna']) && $_SESSION['role'] === 'admin') {
    $id = $_POST['edit_id'];
    $question = $_POST['edit_question'] ?? '';
    $answer = $_POST['edit_answer'] ?? '';
    $qna->update($id, $question, $answer);
}

// Обробка видалення
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_qna']) && $_SESSION['role'] === 'admin') {
    $id = $_POST['delete_id'];
    $qna->delete($id);
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include_once "parts/header.php" ?>
<body>

<div class="page-heading header-text">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h3>Často kladené otázky a odpovede</h3>
                <span class="breadcrumb"><a href="index.php">Domov</a>  >  QnA</span>
            </div>
        </div>
    </div>
</div>

<?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
    <!-- Форма для додавання -->
    <div class="container my-4">
        <h4>➕ Pridať novú otázku</h4>
        <form method="POST">
            <input type="text" name="new_question" placeholder="Otázka" required class="form-control my-2">
            <textarea name="new_answer" placeholder="Odpoveď" required class="form-control my-2"></textarea>
            <button type="submit" name="add_qna" class="btn btn-success">Pridať</button>
        </form>
    </div>
<?php endif; ?>

<div class="accordion accordion-flush container mb-5" id="accordionFlushExample">
    <?php
    $data = $qna->getAll();
    if (!empty($data)) {
        $index = 0;
        foreach ($data as $item) {
            $index++;
            $id = $item['ID']; // ← ОБОВʼЯЗКОВО додай цей рядок
            $user_id = $_SESSION['ID'] ?? null;
            $question = htmlspecialchars($item['Otazka']);
            $answer = htmlspecialchars($item['Odpoved']);
            ?>

            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-heading<?php echo $index; ?>">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse<?php echo $index; ?>" aria-expanded="false">
                        <?= $question ?>
                    </button>
                </h2>
                <div id="flush-collapse<?php echo $index; ?>" class="accordion-collapse collapse">
                    <div class="accordion-body">
                        <?= nl2br($answer) ?>

                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <!-- Форма редагування -->
                            <form method="POST" class="mt-3">
                                <input type="hidden" name="edit_id" value="<?= $id ?>">
                                <input type="text" name="edit_question" value="<?= $question ?>" class="form-control my-1">
                                <textarea name="edit_answer" class="form-control my-1"><?= $answer ?></textarea>
                                <button type="submit" name="edit_qna" class="btn btn-primary btn-sm">Uložiť</button>
                                <button type="submit" name="delete_qna" value="1" onclick="return confirm('Naozaj zmazať?')" class="btn btn-danger btn-sm">Zmazať</button>
                                <input type="hidden" name="delete_id" value="<?= $id ?>">
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php
        }
    } else {
        echo "<p class='container'>Žiadne otázky a odpovede zatiaľ nie sú dostupné.</p>";
    }
    ?>
</div>

<?php include_once "parts/footer.php" ?>
</body>
</html>
