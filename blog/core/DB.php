<?php

class DB {

  function connect($db)
  {
    try {
      $conn = new PDO("pgsql:host={$db['host']};dbname=blog", $db['username'], $db['password']);

      // set the PDO error mode to Exception
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      return $conn;
    } catch (PDOException $exception) {
      exit($exception->getMessage());
    }
  }
}
