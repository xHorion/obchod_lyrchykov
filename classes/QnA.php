<?php
namespace qna_class;

require_once "classes/database.php";

use \PDO;
use database_class\Database;

class QnA {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAll() {
        $query = "SELECT * FROM otazky";
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function add($question, $answer) {
        $query = "INSERT INTO otazky (Otazka, Odpoved) VALUES (:question, :answer)";
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->bindParam(':question', $question);
        $stmt->bindParam(':answer', $answer);
        return $stmt->execute();
    }

    public function update($id, $question, $answer) {
        // Отримуємо поточний запис
        $querySelect = "SELECT Otazka, Odpoved FROM otazky WHERE id = :id";
        $stmtSelect = $this->db->getConnection()->prepare($querySelect);
        $stmtSelect->bindParam(':id', $id);
        $stmtSelect->execute();
        $current = $stmtSelect->fetch(PDO::FETCH_ASSOC);

        if ($current) {
            // Вставляємо в таблицю зміни обидва — старі і нові значення
            $queryInsertLog = "INSERT INTO otazky_change_log 
            (otazka_id, old_question, old_answer, new_question, new_answer) 
            VALUES (:id, :oldQuestion, :oldAnswer, :newQuestion, :newAnswer)";
            $stmtInsert = $this->db->getConnection()->prepare($queryInsertLog);
            $stmtInsert->bindParam(':id', $id);
            $stmtInsert->bindParam(':oldQuestion', $current['Otazka']);
            $stmtInsert->bindParam(':oldAnswer', $current['Odpoved']);
            $stmtInsert->bindParam(':newQuestion', $question);
            $stmtInsert->bindParam(':newAnswer', $answer);
            $stmtInsert->execute();
        }

        // Оновлюємо основний запис
        $queryUpdate = "UPDATE otazky SET Otazka = :question, Odpoved = :answer WHERE id = :id";
        $stmtUpdate = $this->db->getConnection()->prepare($queryUpdate);
        $stmtUpdate->bindParam(':id', $id);
        $stmtUpdate->bindParam(':question', $question);
        $stmtUpdate->bindParam(':answer', $answer);

        return $stmtUpdate->execute();
    }

    public function getChangeLog($id) {
        $query = "SELECT * FROM otazky_change_log WHERE otazka_id = :id ORDER BY changed_at DESC";
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($id) {
        $query = "DELETE FROM otazky WHERE id = :id";
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}


// Перевірка, чи авторизований адмін
$isAdmin = isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $isAdmin) {
    if (isset($_POST['add'])) {
        $question = $_POST['question'] ?? '';
        $answer = $_POST['answer'] ?? '';
        $qna = new QnA();
        $qna->add($question, $answer);
    } elseif (isset($_POST['edit'])) {
        $id = $_POST['id'];
        $question = $_POST['question'];
        $answer = $_POST['answer'];
        $qna = new QnA();
        $qna->update($id, $question, $answer);
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $qna = new QnA();
        $qna->delete($id);
    }
}
