<?php
class Db_conn
{
  protected function connect()
  {
    $server = 'mysql:host=localhost;dbname=ashreif;charset=utf8mb4';
    $user = 'ashreif';
    $password = 'uxMJ7r6ZKjo8WLw';

    try {
      $conn = new PDO($server, $user, $password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return $conn;
    } catch (PDOException $e) {
      die('Connection failed: ' . $e->getMessage());
    }
  }
}
?>