<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header('location: ./');
  exit;
}

if ($_SESSION['privilege'] != 'teacher') {
  header('location: dashboard');
  exit;
}

require_once 'assets/dbhandler.php';
// require_once 'assets/functions.php';

$name = $q1 = $q2 = $q3 = $q4 = $q1_err = $q2_err = $q3_err = $q4_err = '';

$accountId = $_GET['id'];
$accCheck = "SELECT * FROM `grades` WHERE `id` = '$accountId'";
$accCheckQuery = mysqli_query($connection, $accCheck);
$accCheckResult = mysqli_fetch_assoc($accCheckQuery);

$subject = $accCheckResult['subject'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (empty($username_err) && empty($password_err) && empty($confirm_password_err)) {
    $sql = "UPDATE `grades` SET quarter1 = ?, quarter2 = ?, quarter3 = ?, quarter4 = ? WHERE `id` = '$accountId'";

    $q1 = trim($_POST['q1']);
    $q2 = trim($_POST['q2']);
    $q3 = trim($_POST['q3']);
    $q4 = trim($_POST['q4']);

    if ($stmt = mysqli_prepare($connection, $sql)) {
      mysqli_stmt_bind_param($stmt, "ssss", $param_q1, $param_q2, $param_q3, $param_q4);

      $param_q1 = $q1;
      $param_q2 = $q2;
      $param_q3 = $q3;  
      $param_q4 = $q4;

      if (mysqli_stmt_execute($stmt)) {
        header("location: view-grade?username=$subject");
        exit;
      } else {
        echo 'Oops! Something went wrong. Please try again later.';
      }

      mysqli_stmt_close($stmt);
    }
  }

  mysqli_close($connection);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo WEBSITE_TITLE ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
  <!-- <link rel="stylesheet" href="assets/style.css"> -->
</head>
<body style="
  background: url('assets/bg4.png');
  background-size: cover;
  background-position: center center;
  background-attachment: fixed;">

<section class="vh-100">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card shadow-2-strong border border-dark" style="border-radius: 1rem;">
          <div class="shadow-lg card-body p-5 text-left" style="border-radius: 1rem;">
            <form autocomplete="off" method="POST">

              <div class="form-group">
                <label><b>Student Name</b></label>
                <input disabled class="form-control shadow-sm rounded-pill border border-dark <?php echo (!empty($gname_err)) ? 'is-invalid' : ''; ?>" name="name" value="<?php echo $accCheckResult['surname'] . ', ' . $accCheckResult['given_name']; ?>">
                <span class="invalid-feedback"><?php echo $gname_err; ?></span>
              </div>

              <div class="form-group">
                <label><b>1st Quarter</b></label>
                <input class="form-control shadow-sm rounded-pill border border-dark <?php echo (!empty($q1_err)) ? 'is-invalid' : ''; ?>" name="q1" value="<?php echo $accCheckResult['quarter1']; ?>">
              </div>

              <div class="form-group">
                <label><b>2nd Quarter</b></label>
                <input class="form-control shadow-sm rounded-pill border border-dark <?php echo (!empty($q2_err)) ? 'is-invalid' : ''; ?>" name="q2" value="<?php echo $accCheckResult['quarter2']; ?>">
              </div>

              <div class="form-group">
                <label><b>3rd Quarter</b></label>
                <input class="form-control shadow-sm rounded-pill border border-dark <?php echo (!empty($q3_err)) ? 'is-invalid' : ''; ?>" name="q3" value="<?php echo $accCheckResult['quarter3']; ?>">
              </div>

              <div class="form-group">
                <label><b>4th Quarter</b></label>
                <input class="form-control shadow-sm rounded-pill border border-dark <?php echo (!empty($q4_err)) ? 'is-invalid' : ''; ?>" name="q4" value="<?php echo $accCheckResult['quarter4']; ?>">
              </div>

            <input class="mt-4 btn btn-primary btn-block" type="submit" value="EDIT">
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<script src="assets/main.js"></script>
</body>
</html>