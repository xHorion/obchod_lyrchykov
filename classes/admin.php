<?php
namespace user_class;

class Admin extends User
{
    public function addQnA($question, $answer)
    {
        $stmt = $this->db->prepare("INSERT INTO otazky (Otazka, Odpoved) VALUES (?, ?)");
        return $stmt->execute([$question, $answer]);
    }

    public function updateQnA($id, $question, $answer)
    {
        $stmt = $this->db->prepare("UPDATE otazky SET Otazka = ?, Odpoved = ? WHERE id = ?");
        return $stmt->execute([$question, $answer, $id]);
    }

    public function deleteQnA($id)
    {
        $stmt = $this->db->prepare("DELETE FROM otazky WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
