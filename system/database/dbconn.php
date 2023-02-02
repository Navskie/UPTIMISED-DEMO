<?php
  $host = 'localhost'; #online hostname
  $user = 'root'; #online username
  $pass = ''; #online password
  $dbms = 'uptiph'; #online dbname test

  try
  {
    $connection = new PDO("mysql:host=$host;dbname=$dbms", $user, $pass);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo 'Database Connected Successfully';
  }
  catch (PDOException $x)
  {
    echo 'Database Error Connection: ' . $x->getMessage();
  }
?>