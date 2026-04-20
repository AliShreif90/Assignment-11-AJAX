<?php
header('Content-Type: application/json');
require_once '../classes/Db_conn.php';

class ClearNames extends Db_conn
{
    public function clearAll()
    {
        $sql = "DELETE FROM names";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();

        echo json_encode([
            "names" => "<p>No names to display.</p>"
        ]);
        exit();
    }
}

$obj = new ClearNames();
$obj->clearAll();
?>