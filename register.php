<?php
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
  header('location: dashboard');
  exit;
}

require_once 'assets/dbhandler.php';

$username = $password = $confirm_password = $username_err = $password_err = $confirm_password_err = '';

$accCheck = "SELECT * FROM `accounts`";
$accCheckQuery = mysqli_query($connection, $accCheck);
$accCheckResult = mysqli_num_rows($accCheckQuery);

if ($accCheckResult == 0) {
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty(trim($_POST['username']))) {
      $username_err = 'Please enter a username.';
    } else if (!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST['username']))) {
      $username_err = 'Username can only contain letters, numbers, and underscores.';
    } else {
      $sql = "SELECT id FROM `accounts` WHERE  username = ?";
  
      if ($stmt = mysqli_prepare($connection, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $param_username);
  
        $param_username = trim($_POST['username']);
  
        if (mysqli_stmt_execute($stmt)) {
          mysqli_stmt_store_result($stmt);
  
          if (mysqli_stmt_num_rows($stmt) == 1) {
            $username_err = 'This username is already taken.';
          } else {
            $username = trim($_POST['username']);
          }
        } else {
          echo 'Oops! Something went wrong. Please try again later.';
        }
  
        mysqli_stmt_close($stmt);
      }
    }
  
    if (empty(trim($_POST['password']))) {
      $password_err = 'Please enter a password.';
    } else if (strlen(trim($_POST['password'])) < 3) {
      $password_err = 'Password must have at least 3 characters.';
    } else {
      $password = trim($_POST['password']);
    }
  
    if (empty(trim($_POST['confirm_password']))) {
      $confirm_password_err = 'Please confirm password.';
    } else {
      $confirm_password = trim($_POST['confirm_password']);
      
      if (empty($password_err) && ($password != $confirm_password)) {
        $confirm_password_err = 'Password did not match.';
      }
    }
  
    if (empty($username_err) && empty($password_err) && empty($confirm_password_err)) {
      $sql = "INSERT INTO accounts (username, password, privilege) VALUES (?, ?, 'admin')";
  
      if ($stmt = mysqli_prepare($connection, $sql)) {
        mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
  
        $param_username = $username;
        $param_password = $password;
  
        if (mysqli_stmt_execute($stmt)) {
          header('location: ./');
        } else {
          echo 'Oops! Something went wrong. Please try again later.';
        }
  
        mysqli_stmt_close($stmt);
      }
    }
  
    mysqli_close($connection);
  }
} else {
  header('location: ./');
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo WEBSITE_TITLE ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
  <link rel="stylesheet" href="assets/style.css">
</head>
<body style="
  background: url('assets/bg4.png');
  background-size: cover;
  background-position: center center;
  background-attachment: fixed;">

<section class="vh-100">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
    <!-- <img src="assets/logo1.png" height="120" class="position-absolute mt-3" style="top: 9%; left: 43%; z-index: 1;"> -->
      <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card shadow-2-strong border border-dark" style="border-radius: 1rem;">
          <div class="shadow-lg card-body p-5 text-center" style="border-radius: 1rem;">
            <h2>Register</h2>
            <p>Please fill this form to create an ADMIN account.</p>
            <form autocomplete="off" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">

              <div class="form-group">
                <!-- <label>Username</label> -->
                <input placeholder="Username" name="username" class="form-control border-dark shadow-sm rounded-pill  <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
              </div>

              <div class="form-group">
                <!-- <label>Password</label> -->
                <input placeholder="Password" type="password" name="password" class="form-control border-dark shadow-sm rounded-pill  <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
              </div>

              <div class="form-group">
                <!-- <label>Confirm Password</label> -->
                <input placeholder="Confirm Password" type="password" name="confirm_password" class="form-control border-dark shadow-sm rounded-pill  <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
              </div>

              <div class="form-group">
                <br><center><input type="submit" class="btn btn-outline-primary shadow btn-block" style="width: 60%;" value="Register"></center>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
</body>
</html>