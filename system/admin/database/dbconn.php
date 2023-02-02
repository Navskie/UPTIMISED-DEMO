<?php

  // $connect = mysqli_connect('localhost', 'u708090748_uptimised', '@User2022', 'u708090748_uptimisedph'); 
  $connect = mysqli_connect('localhost', 'root', '', 'uptimisedph');

  date_default_timezone_set('Asia/Manila');
  $now = date('m-d-Y');
  $timenow = date('h:m:s');
  $timedate = $now.' - '.$timenow;

  session_start();
  
?>