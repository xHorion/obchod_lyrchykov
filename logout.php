<?php
session_start();
session_unset();
session_destroy();

header("Location: index.php"); // Перенаправлення на головну після виходу
exit;
