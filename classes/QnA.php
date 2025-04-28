<?php
namespace qna_class;

require_once __DIR__ . '/Database.php';

use database_class\Database;
use PDO;

class QnA extends Database
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAll()
    {
        $stmt = $this->getConnection()->query("SELECT Otazka, Odpoved FROM otazky");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}