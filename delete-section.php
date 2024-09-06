<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header('location: ./');
  exit;
}

if ($_SESSION['privilege'] != 'admin') {
  header('location: dashboard');
  exit;
}

require_once 'assets/dbhandler.php';

$sectionId = $_GET['id'];

$deleteSql = "DELETE FROM `sections` WHERE `id` = '$sectionId'";
mysqli_query($connection, $deleteSql);

header('location: sections');
exit;
?>