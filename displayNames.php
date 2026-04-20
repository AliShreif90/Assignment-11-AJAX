<?php
header('Content-Type: application/json');
require_once '../classes/Db_conn.php';

class DisplayNames extends Db_conn
{
    public function getAllNames()
    {
        $sql = "SELECT first_name, last_name FROM names ORDER BY last_name ASC, first_name ASC";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$rows || count($rows) === 0) {
            echo json_encode([
                "names" => "<p>No names to display.</p>"
            ]);
            exit();
        }

        $output = "";
        foreach ($rows as $row) {
            $output .= "<p>" . htmlspecialchars($row['last_name']) . ", " . htmlspecialchars($row['first_name']) . "</p>";
        }

        echo json_encode([
            "names" => $output
        ]);
        exit();
    }
}

$obj = new DisplayNames();
$obj->getAllNames();
?>