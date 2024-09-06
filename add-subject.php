<?php
session_start();

// error_reporting(0);

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

$name = $name_err = $add_success = '';

if (isset($_POST['back'])) {
    header('location: subjects');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (empty(trim($_POST['name']))) {
    $name_err = 'Please input a name.';
  } else {
    $name = trim($_POST['name']);
  }

  if (empty($name_err)) {
    $sql = "INSERT INTO subjects (name) VALUES (?)";

    if ($stmt = mysqli_prepare($connection, $sql)) {
      mysqli_stmt_bind_param($stmt, "s", $param_name);

      $param_name = $name;
      
      if (mysqli_stmt_execute($stmt)) {
        $add_success = 'Successfully added.';
      } else {
        echo 'Oops! Something went wrong. Please try again later.';
      }

      mysqli_stmt_close($stmt);
    }
    mysqli_close($connection);
  }
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
  <div class=" container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card shadow-2-strong border border-dark" style="border-radius: 1rem;">
          <div class="shadow-lg card-body p-5 text-left" style="border-radius: 1rem;">
            <form autocomplete="off" method="POST">

              <div class="form-group">
                <label><b>Name</b></label>
                <input class="form-control shadow-sm rounded-pill border border-dark <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" name="name" value="<?php
                if (empty($add_success)) {
                  if (!empty($name)) {
                    echo $name;
                  } 
                }
                ?>">
                <span class="invalid-feedback"><?php echo $name_err; ?></span>
              </div>

              <?php
              if (!empty($add_success)) {
                echo '<span class="mt-4 alert alert-success btn-block text-center">' . $add_success . '</span>';
              }
              ?>
              <input class="mt-4 btn btn-primary btn-block" type="submit" value="ADD SUBJECT">
              <button class="mt-2 btn btn-secondary btn-block" name="back">VIEW SUBJECTS</button>
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