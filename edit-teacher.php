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
// require_once 'assets/functions.php';

$given_name = $given_name_err = $surname = $surname_err = $sex = $sex_err = $address = $address_err = $email = $email_err = $contact_number = $contact_number_err = '';

$accountId = $_GET['id'];
$accCheck = "SELECT * FROM `accounts` WHERE `id` = '$accountId'";
$accCheckQuery = mysqli_query($connection, $accCheck);
$accCheckResult = mysqli_fetch_assoc($accCheckQuery);

$address_db = htmlspecialchars($accCheckResult['address']);

if (isset($_POST['back'])) {
  header('location: dashboard');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (empty(trim($_POST['given_name']))) {
    $given_name_err = 'Please input a name.';
  } else {
    $given_name = trim($_POST['given_name']);
  }

  if (empty(trim($_POST['surname']))) {
    $surname_err = 'Please input a name.';
  } else {
    $surname = trim($_POST['surname']);
  }

  if (empty(trim($_POST['sex']))) {
    $sex_err = 'Please input sex.';
  } else {
    $sex = trim($_POST['sex']);
  }

  if (empty(trim($_POST['address']))) {
    $address_err = 'Please input an address.';
  } else {
    $address = trim($_POST['address']);
  }

  if (empty(trim($_POST['email']))) {
    $email_err = 'Please input an email address.';
  } else {
    $email = trim($_POST['email']);
  }

  if (empty(trim($_POST['contact_number']))) {
    $contact_number_err = 'Please input contact number.';
  } else {
    $contact_number = trim($_POST['contact_number']);
  }

  if (empty($given_name_err) && empty($surname_err) && empty($sex_err) && empty($address_err) && empty($email_err) && empty($contact_number_err)) {
    $sql = "UPDATE `accounts` SET given_name = ?, surname = ?, sex = ?, address = ?, email = ?, contact_number = ? WHERE `id` = '$accountId'";

    if ($stmt = mysqli_prepare($connection, $sql)) {
      mysqli_stmt_bind_param($stmt, "ssssss", $param_given_name, $param_surname, $param_sex, $param_address, $param_email, $param_contact_number);

      $param_given_name = $given_name;
      $param_surname = $surname;
      $param_sex = $sex;
      $param_address = $address;
      $param_email = $email;
      $param_contact_number = $contact_number;

      if (mysqli_stmt_execute($stmt)) {
        header('location: dashboard');
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
                <label><b>Given Name</b></label>
                <input class="form-control shadow-sm rounded-pill border border-dark <?php echo (!empty($given_name_err)) ? 'is-invalid' : ''; ?>" name="given_name" value="<?php echo $accCheckResult['given_name']; ?>">
                <span class="invalid-feedback"><?php echo $given_name_err; ?></span>
              </div>

              <div class="form-group">
                <label><b>Surname</b></label>
                <input class="form-control shadow-sm rounded-pill border border-dark <?php echo (!empty($surname_err)) ? 'is-invalid' : ''; ?>" name="surname" value="<?php echo $accCheckResult['surname']; ?>">
                <span class="invalid-feedback"><?php echo $surname_err; ?></span>
              </div>

              <div class="form-group">
                <label><b>Sex</b></label><br>
                <div class="form-check form-check-inline">
                  <?php 
                  if ($accCheckResult['sex'] == 'Male') {
                    echo '<input type="radio" class="form-check-input" id="sex_male" name="sex" value="Male" checked>';
                  } else {
                    echo '<input type="radio" class="form-check-input" id="sex_male" name="sex" value="Male">';
                  }
                  ?>
                  <label class="form-check-label" for="sex_male">Male</label>
                </div>
                <div class="form-check form-check-inline">
                  <?php 
                  if ($accCheckResult['sex'] == 'Female') {
                    echo '<input type="radio" class="form-check-input" id="sex_female" name="sex" value="Female" checked>';
                  } else {
                    echo '<input type="radio" class="form-check-input" id="sex_female" name="sex" value="Female">';
                  }
                  ?>
                  <label class="form-check-label" for="sex_female">Female</label>
                </div>
              </div>

              <div class="form-group">
                <label><b>Address</b></label>
                <input class="form-control shadow-sm rounded-pill border border-dark <?php echo (!empty($address_err)) ? 'is-invalid' : ''; ?>" name="address" value="<?php echo $address_db; ?>">
                <span class="invalid-feedback"><?php echo $address_err; ?></span>
              </div>

              <div class="form-group">
                <label><b>Email</b></label>
                <input type="email" class="form-control shadow-sm rounded-pill border border-dark <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" name="email" value="<?php echo $accCheckResult['email']; ?>">
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
              </div>

              <div class="form-group">
                <label><b>Contact Number</b></label>
                <input class="form-control shadow-sm rounded-pill border border-dark <?php echo (!empty($contact_number_err)) ? 'is-invalid' : ''; ?>" name="contact_number" value="<?php echo $accCheckResult['contact_number']; ?>">
                <span class="invalid-feedback"><?php echo $contact_number_err; ?></span>
              </div>

              <input class="mt-4 btn btn-primary btn-block" type="submit" value="EDIT">
              <button class="mt-2 btn btn-secondary btn-block" name="back">CANCEL</button>
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