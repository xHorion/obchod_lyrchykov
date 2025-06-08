<?php
function getGreeting($username) {
    $hour = date("H");

    if ($hour >= 5 && $hour < 12) {
        $greeting = "Dobré ráno";
    } elseif ($hour >= 12 && $hour < 18) {
        $greeting = "Dobré popoludnie";
    } elseif ($hour >= 18 && $hour < 22) {
        $greeting = "Dobrý večer";
    } else {
        $greeting = "Dobrú noc";
    }

    return $greeting . ", " . htmlspecialchars($username) . "!";
}


public function setRole($userId, $role) {
$query = "UPDATE users SET role = :role WHERE id = :id";
$stmt = $this->db->getConnection()->prepare($query);
$stmt->bindParam(':role', $role);
$stmt->bindParam(':id', $userId);
return $stmt->execute();
}

public function getAllUsers() {
    $query = "SELECT id, username, email, role FROM users";
    $stmt = $this->db->getConnection()->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>