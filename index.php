<?php
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
  header('location: dashboard');
  exit;
}

require_once 'assets/dbhandler.php';

$username = $password = $username_err = $password_err = $login_err = '';

$accCheck = "SELECT * FROM `accounts`";
$accCheckQuery = mysqli_query($connection, $accCheck);
$accCheckResult = mysqli_num_rows($accCheckQuery);

if ($accCheckResult > 0) {
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty(trim($_POST['username']))) {
      $username_err = 'Please enter username.';
    } else {
      $username = trim($_POST['username']);
    }

    if (empty(trim($_POST['password']))) {
      $password_err = 'Please enter your password.';
    } else {
      $password = trim($_POST['password']);
    }

    if (empty($username_err) && empty($password_err)) {
      $sql = "SELECT id, username, password, privilege, full_name, section_id FROM `accounts` WHERE username = ?";

      if ($stmt = mysqli_prepare($connection, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $param_username);

        $param_username = $username;

        if (mysqli_stmt_execute($stmt)) {
          mysqli_stmt_store_result($stmt);

          if (mysqli_stmt_num_rows($stmt) == 1) {
            mysqli_stmt_bind_result($stmt, $id, $username, $passwordDB, $privilege, $full_name, $section_id);
            if(mysqli_stmt_fetch($stmt)) {
              if ($password == $passwordDB) {
                session_start();

                $_SESSION['loggedin'] = true;
                $_SESSION['id'] = $id;
                $_SESSION['username'] = $username;
                $_SESSION['privilege'] = $privilege;
                $_SESSION['full_name'] = $full_name;
                $_SESSION['section_id'] = $section_id;

                header('location: dashboard');
              } else {
                $login_err = 'Invalid username or password.';
              }
            }
          } else {
            $login_err = 'Invalid username or password.';
          }
        } else {
          echo 'Oops! Something went wrong. Please try again later.';
        }

        mysqli_stmt_close($stmt);
      }
    }

    mysqli_close($connection);
  }
} else {
  header('location: register');
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
      <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card shadow-2-strong border border-dark" style="border-radius: 1rem;">
          <div class="shadow-lg card-body p-5 text-center" style="border-radius: 1rem;">
            <h2>Login</h2>
            <p>Please fill in your credentials to continue.</p>

            <?php
            if (!empty($login_err)) {
              echo '<div class="alert alert-danger">' . $login_err . '</div>';
            }
            ?>

            <form autocomplete="off" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
              <div class="form-group">
                <!-- <label>Username</label> -->
                <input placeholder="Username" name="username" class="form-control rounded-pill shadow-sm border border-dark  <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
              </div>
              <div class="form-group">
                <!-- <label>Password</label> -->
                <input placeholder="Password" type="password" name="password" class="form-control rounded-pill shadow-sm border border-dark <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
              </div><br>
              <div class="form-group">
                <center><input type="submit" class="btn btn-outline-primary shadow btn-block" style="width: 60%;" value="Login"></center>
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