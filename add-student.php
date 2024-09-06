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

$username = $password = $confirm_password = $username_err = $password_err = $confirm_password_err = $full_name = $full_name_err = $contact_number = $contact_number_err = $section = $section_err = '';

$sectionCheck = "SELECT * FROM `sections`";
$sectionQuery = mysqli_query($connection, $sectionCheck);

if (isset($_POST['back'])) {
  header('location: students');
  exit;
}

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

  if (empty(trim($_POST['full_name']))) {
    $full_name_err = 'Please input a name.';
  } else {
    $full_name = trim($_POST['full_name']);
  }

  if (empty(trim($_POST['contact_number']))) {
    $contact_number_err = 'Please input contact number.';
  } else {
    $contact_number = trim($_POST['contact_number']);
  }

  if (!isset($_POST['section'])) {
    $section = '';
  } else {
    $section = trim($_POST['section']);
  }
  
  if (empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($given_name_err) && empty($surname_err) && empty($address_err) && empty($email_err) && empty($contact_number_err)) {
    $sql = "INSERT INTO accounts (username, password, privilege, full_name, sex, contact_number, section_id) VALUES (?, ?, 'student', ?, ?, ?, ?)";

    $sex = trim($_POST['sex']);
    
    if ($stmt = mysqli_prepare($connection, $sql)) {
      mysqli_stmt_bind_param($stmt, "sssssi", $param_username, $param_password, $param_full_name, $param_sex, $param_contact_number, $param_section);
      
      $param_username = $username;
      $param_password = $password;
      $param_full_name = $full_name;
      $param_sex = $sex;
      $param_contact_number = $contact_number;
      $param_section = $section;
      if (mysqli_stmt_execute($stmt)) {
        $add_success = 'Successfully added.';
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
  <div class=" container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card shadow-2-strong border border-dark" style="border-radius: 1rem;">
          <div class="shadow-lg card-body p-5 text-left" style="border-radius: 1rem;">
            <form autocomplete="off" method="POST">
              <div class="form-group">
                <label><b>Username</b></label>
                <input autofocus id="barcode" class="form-control shadow-sm rounded-pill border border-dark <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" name="username" value="<?php
                if (empty($add_success)) {
                  if (!empty($username)) {
                    echo $username;
                  } 
                }
                ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
              </div>

              <div class="form-group">
                <label><b>Password</b></label>
                <input class="form-control shadow-sm rounded-pill border border-dark <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" type="password" name="password" value="<?php
                if (empty($add_success)) {
                  if (!empty($password)) {
                    echo $password;
                  } 
                }
                ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
              </div>

              <div class="form-group">
                <label><b>Confirm Password</b></label>
                <input class="form-control shadow-sm rounded-pill border border-dark <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" type="password"  name="confirm_password"  value="<?php
                if (empty($add_success)) {
                  if (!empty($confirm_password)) {
                    echo $confirm_password;
                  } 
                }
                ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
              </div>

              <div class="form-group">
                <label><b>Full Name</b></label>
                <input class="form-control shadow-sm rounded-pill border border-dark <?php echo (!empty($full_name_err)) ? 'is-invalid' : ''; ?>" name="full_name" value="<?php
                if (empty($add_success)) {
                  if (!empty($full_name)) {
                    echo $full_name;
                  } 
                }
                ?>">
                <span class="invalid-feedback"><?php echo $full_name_err; ?></span>
              </div>

              <div class="form-group">
                <label><b>Sex</b></label><br>
                <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="sex_male" name="sex" value="Male" checked>
                  <label class="form-check-label" for="sex_male">Male</label>
                </div>
                <div class="form-check form-check-inline">
                <input type="radio" class="form-check-input" id="sex_female" name="sex" value="Female">
                <label class="form-check-label" for="sex_female">Female</label>
                </div>
              </div>

              <div class="form-group">
                <label><b>Contact Number (Parent/Guardian)</b></label>
                <input class="form-control shadow-sm rounded-pill border border-dark <?php echo (!empty($contact_number_err)) ? 'is-invalid' : ''; ?>" name="contact_number" value="<?php
                if (empty($add_success)) {
                  if (!empty($contact_number)) {
                    echo $contact_number;
                  } 
                }
                ?>">
                <span class="invalid-feedback"><?php echo $contact_number_err; ?></span>
              </div>

              <div class="form-group">
                <label><b>Section</b></label>
                <select class="form-control shadow-sm rounded-pill border border-dark" name="section">
                <?php
                while ($row = mysqli_fetch_assoc($sectionQuery)) {
                  $sectionId = $row['id'];
                  $sectionName = $row['year_level'] . ' - ' . $row['name'];
                  echo "<option value='$sectionId'>$sectionName</option>";
                }
                ?>
                </select>
              </div>

              <?php
              if (!empty($add_success)) {
                echo '<span class="mt-4 alert alert-success btn-block text-center">' . $add_success . '</span>';
              }
              ?>

              <input class="mt-4 btn btn-primary btn-block" type="submit" value="ADD STUDENT">
              <button class="mt-2 btn btn-secondary btn-block" name="back">VIEW STUDENTS</button>
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