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
?>