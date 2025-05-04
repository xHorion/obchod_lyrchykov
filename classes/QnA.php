<?php
namespace qna_class;

require_once "classes/database.php";

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
        $query = "UPDATE otazky SET Otazka = :question, Odpoved = :answer WHERE id = :id";
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':question', $question);
        $stmt->bindParam(':answer', $answer);
        return $stmt->execute();
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
