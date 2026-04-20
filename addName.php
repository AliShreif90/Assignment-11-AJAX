<?php
header('Content-Type: application/json');
require_once '../classes/Db_conn.php';

class AddName extends Db_conn
{

    public function getRequestName()
    {
        // Regular form post
        if (isset($_POST['name']) && trim($_POST['name']) !== '') {
            return trim($_POST['name']);
        }

        // Raw body (works if main.js sends JSON)
        $rawData = file_get_contents("php://input");
        if (!empty($rawData)) {
            $jsonData = json_decode($rawData, true);

            if (is_array($jsonData) && isset($jsonData['name']) && trim($jsonData['name']) !== '') {
                return trim($jsonData['name']);
            }

            // Also handle query-string style raw body
            parse_str($rawData, $parsedData);
            if (isset($parsedData['name']) && trim($parsedData['name']) !== '') {
                return trim($parsedData['name']);
            }
        }

        return '';
    }

    public function addAndReturnNames()
    {
        $fullName = $this->getRequestName();

        if ($fullName === '') {
            echo json_encode([
                "names" => $this->getNamesHtml(),
                "debug" => "Name field was empty."
            ]);
            exit();
        }

        $parts = preg_split('/\s+/', $fullName);

        if (count($parts) < 2) {
            echo json_encode([
                "names" => "<p>Please enter a first and last name.</p>",
                "debug" => "Only one name part was provided."
            ]);
            exit();
        }

        $firstName = $parts[0];
        $lastName = $parts[1];

        try {
            $conn = $this->connect();

            $sql = "INSERT INTO names (first_name, last_name) VALUES (:first_name, :last_name)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':first_name', $firstName, PDO::PARAM_STR);
            $stmt->bindParam(':last_name', $lastName, PDO::PARAM_STR);
            $stmt->execute();

            echo json_encode([
                "names" => $this->getNamesHtml(),
                "debug" => "Inserted successfully."
            ]);
            exit();

        } catch (PDOException $e) {
            echo json_encode([
                "names" => $this->getNamesHtml(),
                "debug" => "DB Error: " . $e->getMessage()
            ]);
            exit();
        }
    }

    private function getNamesHtml()
    {
        $sql = "SELECT first_name, last_name FROM names ORDER BY last_name ASC, first_name ASC";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$rows || count($rows) === 0) {
            return "<p>No names to display.</p>";
        }

        $output = "";
        foreach ($rows as $row) {
            $output .= "<p>" . htmlspecialchars($row['last_name']) . ", " . htmlspecialchars($row['first_name']) . "</p>";
        }

        return $output;
    }
}

$obj = new AddName();
$obj->addAndReturnNames();
?>