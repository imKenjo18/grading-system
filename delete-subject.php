<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header('location: login');
  exit;
}

if ($_SESSION['privilege'] != 'admin') {
  header('location: dashboard');
  exit;
}

require_once 'assets/dbhandler.php';

$subjectId = $_GET['id'];

$deleteSql = "DELETE FROM `subjects` WHERE `id` = '$subjectId'";
mysqli_query($connection, $deleteSql);

header('location: subjects');
exit;
?>