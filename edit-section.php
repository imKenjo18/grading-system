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

$name = $name_err = $year_level = $year_level_err = '';

$sectionId = $_GET['id'];
$sectionCheck = "SELECT * FROM `sections` WHERE `id` = '$sectionId'";
$sectionCheckQuery = mysqli_query($connection, $sectionCheck);
$sectionCheckResult = mysqli_fetch_assoc($sectionCheckQuery);

$teacherCheck = "SELECT *  FROM `accounts` WHERE `privilege` = 'teacher'";
$teacherSQL = mysqli_query($connection, $teacherCheck);

$name_db = htmlspecialchars($sectionCheckResult['name']);
$year_level_db = htmlspecialchars($sectionCheckResult['year_level']);

if (isset($_POST['back'])) {
  header('location: sections');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (empty(trim($_POST['name']))) {
    $name_err = 'Please input a name.';
  } else {
    $name = trim($_POST['name']);
  }

  if (empty($name_err) && empty($year_level_err)) {
    $sql = "UPDATE `sections` SET name = ?, year_level = ?, adviser_id = ? WHERE `id` = '$sectionId'";
    
    $year_level = trim($_POST['year_level']);
    $adviser = trim($_POST['adviser']);

    if ($stmt = mysqli_prepare($connection, $sql)) {
      mysqli_stmt_bind_param($stmt, "ssi", $param_name, $param_year_level, $param_adviser);
      
      $param_name = $name;
      $param_year_level = $year_level;
      $param_adviser = $adviser;
      
      if (mysqli_stmt_execute($stmt)) {
        header('location: sections');
        exit;
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
                <input class="form-control shadow-sm rounded-pill border border-dark <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" name="name" value="<?php echo $name_db; ?>">
                <span class="invalid-feedback"><?php echo $name_err; ?></span>
              </div>

              <div class="form-group">
                <label><b>Year Level</b></label>
                <select class="form-control shadow-sm rounded-pill border border-dark" name="year_level">
                  <?php
                  for ($x = 1; $x <= 10; $x++) {
                    if ($year_level_db == "Grade $x") {
                      echo "<option selected>Grade $x</option>";
                    } else {
                      echo "<option>Grade $x</option>";
                    }
                  }
                  ?>
                </select>
              </div>

              <div class="form-group">
                <label><b>Adviser</b></label>
                <select class="form-control shadow-sm rounded-pill border border-dark" name="adviser">
                <?php
                while (($teacher = mysqli_fetch_assoc($teacherSQL)) && ($section = $sectionCheckResult)) {
                  $teacherId = $teacher['id'];
                  $teacherName = $teacher['given_name'] . ' ' . $teacher['surname'];

                  if ($teacherId == $section['adviser_id']) {
                    echo "<option selected value='$teacherId'>$teacherName</option>";
                  } else {
                    echo "<option value='$teacherId'>$teacherName</option>";
                  }
                }
                ?>
                </select>
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