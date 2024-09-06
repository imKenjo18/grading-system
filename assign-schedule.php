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

$sectionCheck = "SELECT * FROM `sections`";
$sectionQuery = mysqli_query($connection, $sectionCheck);

$sectionId = array();
$sections = array();

while ($row = mysqli_fetch_assoc($sectionQuery)) {
  $section = $row['id'];
  array_push($sectionId, $section);

  $gradeSection = $row['year_level'] . ' - ' . $row['name'];
  array_push($sections, $gradeSection);
}

function showSections() {
  global $sectionId, $sections;

  echo "<option hidden></option>";
  echo "<option>FREE TIME</option>";

  foreach ($sectionId as $index => $id) {
    echo "<option value='$id'>$sections[$index]</option>";
  } 
}

if (isset($_POST['back'])) {
  header('location: dashboard');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['save']) && isset($_POST['teacher'])) {
    $scheduleCheck = "SELECT * FROM `schedules` WHERE username = ?";
    $scheduleQuery = mysqli_query($connection, $scheduleCheck);
    
    if ($stmt = mysqli_prepare($connection, $scheduleCheck)) {
      mysqli_stmt_bind_param($stmt, "s", $param_teacher);
  
      $param_teacher = trim($_POST['teacher']);
  
      if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_store_result($stmt);
  
        if (mysqli_stmt_num_rows($stmt) == 0) {
          $addSchedule = "INSERT INTO `schedules` (username, monday1, tuesday1, wednesday1, thursday1, friday1, monday2, tuesday2, wednesday2, thursday2, friday2, monday3, tuesday3, wednesday3, thursday3, friday3, monday4, tuesday4, wednesday4, thursday4, friday4, monday5, tuesday5, wednesday5, thursday5, friday5, monday6, tuesday6, wednesday6, thursday6, friday6, monday7, tuesday7, wednesday7, thursday7, friday7, monday8, tuesday8, wednesday8, thursday8, friday8) VALUES ('$param_teacher', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        } else if (mysqli_stmt_num_rows($stmt) > 0) {
          $addSchedule = "UPDATE `schedules` SET monday1 = ?, tuesday1 = ?, wednesday1 = ?, thursday1 = ?, friday1 = ?, monday2 = ?, tuesday2 = ?, wednesday2 = ?, thursday2 = ?, friday2 = ?, monday3 = ?, tuesday3 = ?, wednesday3 = ?, thursday3 = ?, friday3 = ?, monday4 = ?, tuesday4 = ?, wednesday4 = ?, thursday4 = ?, friday4 = ?, monday5 = ?, tuesday5 = ?, wednesday5 = ?, thursday5 = ?, friday5 = ?, monday6 = ?, tuesday6 = ?, wednesday6 = ?, thursday6 = ?, friday6 = ?, monday7 = ?, tuesday7 = ?, wednesday7 = ?, thursday7 = ?, friday7 = ?, monday8 = ?, tuesday8 = ?, wednesday8 = ?, thursday8 = ?, friday8 = ? WHERE username = '$param_teacher'";
        }
      
        if ($stmt2 = mysqli_prepare($connection, $addSchedule)) {
          mysqli_stmt_bind_param($stmt2, "ssssssssssssssssssssssssssssssssssssssss", $param_monday1, $param_tuesday1, $param_wednesday1, $param_thursday1, $param_friday1, $param_monday2, $param_tuesday2, $param_wednesday2, $param_thursday2, $param_friday2, $param_monday3, $param_tuesday3, $param_wednesday3, $param_thursday3, $param_friday3, $param_monday4, $param_tuesday4, $param_wednesday4, $param_thursday4, $param_friday4, $param_monday5, $param_tuesday5, $param_wednesday5, $param_thursday5, $param_friday5, $param_monday6, $param_tuesday6, $param_wednesday6, $param_thursday6, $param_friday6, $param_monday7, $param_tuesday7, $param_wednesday7, $param_thursday7, $param_friday7, $param_monday8, $param_tuesday8, $param_wednesday8, $param_thursday8, $param_friday8);
  
          $param_monday1 = trim($_POST['monday1']);
          $param_tuesday1 = trim($_POST['tuesday1']);
          $param_wednesday1 = trim($_POST['wednesday1']);
          $param_thursday1 = trim($_POST['thursday1']);
          $param_friday1 = trim($_POST['friday1']);
  
          $param_monday2 = trim($_POST['monday2']);
          $param_tuesday2 = trim($_POST['tuesday2']);
          $param_wednesday2 = trim($_POST['wednesday2']);
          $param_thursday2 = trim($_POST['thursday2']);
          $param_friday2 = trim($_POST['friday2']);
  
          $param_monday3 = trim($_POST['monday3']);
          $param_tuesday3 = trim($_POST['tuesday3']);
          $param_wednesday3 = trim($_POST['wednesday3']);
          $param_thursday3 = trim($_POST['thursday3']);
          $param_friday3 = trim($_POST['friday3']);
  
          $param_monday4 = trim($_POST['monday4']);
          $param_tuesday4 = trim($_POST['tuesday4']);
          $param_wednesday4 = trim($_POST['wednesday4']);
          $param_thursday4 = trim($_POST['thursday4']);
          $param_friday4 = trim($_POST['friday4']);
  
          $param_monday5 = trim($_POST['monday5']);
          $param_tuesday5 = trim($_POST['tuesday5']);
          $param_wednesday5 = trim($_POST['wednesday5']);
          $param_thursday5 = trim($_POST['thursday5']);
          $param_friday5 = trim($_POST['friday5']);
  
          $param_monday6 = trim($_POST['monday6']);
          $param_tuesday6 = trim($_POST['tuesday6']);
          $param_wednesday6 = trim($_POST['wednesday6']);
          $param_thursday6 = trim($_POST['thursday6']);
          $param_friday6 = trim($_POST['friday6']);
  
          $param_monday7 = trim($_POST['monday7']);
          $param_tuesday7 = trim($_POST['tuesday7']);
          $param_wednesday7 = trim($_POST['wednesday7']);
          $param_thursday7 = trim($_POST['thursday7']);
          $param_friday7 = trim($_POST['friday7']);
  
          $param_monday8 = trim($_POST['monday8']);
          $param_tuesday8 = trim($_POST['tuesday8']);
          $param_wednesday8 = trim($_POST['wednesday8']);
          $param_thursday8 = trim($_POST['thursday8']);
          $param_friday8 = trim($_POST['friday8']);
  
          if (mysqli_stmt_execute($stmt2)) {
            header("location: dashboard");
          } else {
            echo 'Oops! Something went wrong. Please try again later.';
          }
  
          mysqli_stmt_close($stmt2);
        }
      } else {
        echo 'Oops! Something went wrong. Please try again later.';
      }
  
      mysqli_stmt_close($stmt);
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo WEBSITE_TITLE ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="assets/style.css">

</head>
<body style="
  background: url('assets/bg4.png');
  background-size: cover;
  background-position: center center;
  background-attachment: fixed;">
  
  <!-- <nav class="navbar fixed-top navbarScroll shadow navbar-expand-lg" style="background: #457b9d;">
    <div class="container">
      <a href="dashboard" class="navbar-brand text-dark"><img id="home-svg" src="assets/home2.svg" height="40" alt="Dashboard"></a>
      <a class="nav-link" href="assign-schedule"><button class="btn rounded-pill shadow btn-primary">Assign Schedule</button></a>
      <a class="nav-link" href="add-teacher"><button class="btn rounded-pill shadow btn-primary">Add Teacher</button></a>
      <a class="nav-link" href="add-section"><button class="btn rounded-pill shadow btn-primary">Add Section</button></a>
      <a class="nav-link" href="sections"><button class="btn rounded-pill shadow btn-primary">View Sections</button></a>
      <a class="nav-link" href="add-student"><button class="btn rounded-pill shadow btn-primary">Add Student</button></a>
      <a class="nav-link" href="students"><button class="btn rounded-pill shadow btn-primary">View Students</button></a>
      <span style="color: white">Welcome, Admin!</span>
      <a class="nav-link" href="assets/logout"><button id="logout" class="btn btn-danger rounded-pill shadow" name="logout">Logout</button></a>
    </div>
  </nav> -->

  <section class="intro">
    <div class="mask d-flex align-items-center h-100">
      <div class="container p-3">
        <div class="row justify-content-center">
          <div class="mt-5 col-13">
            <div class="border border-dark table table-responsive shadow-lg bg-white" style="border-radius: 1rem;">
              <div class="card-body text-center" style="border-radius: 1rem;">
                <form autocomplete="off" method="POST">
                  <table class="table table-striped table-xxl table-hover mb-0">
                    <thead>
                      <tr>
                        <td colspan="6">
                          <b>Assign to</b>
                          <select name="teacher" class="p-1 shadow-sm rounded-pill border border-dark">
                            <?php
                            $teacherCheck = "SELECT * FROM `accounts` WHERE privilege = 'teacher'";
                            $teacherQuery = mysqli_query($connection, $teacherCheck);

                            while ($row = mysqli_fetch_assoc($teacherQuery)) {
                              $teacherUsername = $row['username'];
                              $teacherName = $row['given_name'] . ' ' . $row['surname'];

                              echo "<option value='$teacherUsername'>$teacherName</option>";
                            }
                            ?>
                          </select>
                          <br>
                          <button name="back" class="mt-2 shadow btn btn-secondary btn-sm">DASHBOARD</button>
                          <button type="submit" name="save" class="mt-2 shadow btn btn-dark btn-sm">SAVE</button>
                        </td>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <th>Time</th>
                        <th>Monday</th>
                        <th>Tuesday</th>
                        <th>Wednesday</th>
                        <th>Thursday</th>
                        <th>Friday</th>
                      </tr>
                      <tr>
                        <td><b>7:30AM - 8:30AM</b></td>
                        <td>
                          <select name="monday1">
                            <?php showSections(); ?>
                          </select>
                        </td>
                        <td>
                          <select name="tuesday1">
                            <?php showSections(); ?>
                          </select>
                        </td>
                        <td>
                          <select name="wednesday1">
                            <?php showSections(); ?>
                          </select>
                        </td>
                        <td>
                          <select name="thursday1">
                            <?php showSections(); ?>
                          </select>
                        </td>
                        <td>
                          <select name="friday1">
                            <?php showSections(); ?>
                          </select>
                        </td>
                      </tr>
                      <tr>
                        <td><b>8:30AM - 9:30AM</b></td>
                        <td>
                          <select name="monday2">
                            <?php showSections(); ?>
                          </select>
                        </td>
                        <td>
                          <select name="tuesday2">
                            <?php showSections(); ?>
                          </select>
                        </td>
                        <td>
                          <select name="wednesday2">
                            <?php showSections(); ?>
                          </select>
                        </td>
                        <td>
                          <select name="thursday2">
                            <?php showSections(); ?>
                          </select>
                        </td>
                        <td>
                          <select name="friday2">
                            <?php showSections(); ?>
                          </select>
                        </td>
                      </tr>
                      <tr>
                        <td><b>9:30AM - 9:40AM</b></td>
                        <td colspan="5"><b style="letter-spacing:3px;">RECESS TIME</b></td>
                      </tr>
                      <tr>
                        <td><b>9:40AM - 10:40AM</b></td>
                        <td>
                          <select name="monday3">
                            <?php showSections(); ?>
                          </select>
                        </td>
                        <td>
                          <select name="tuesday3">
                            <?php showSections(); ?>
                          </select>
                        </td>
                        <td>
                          <select name="wednesday3">
                            <?php showSections(); ?>
                          </select>
                        </td>
                        <td>
                          <select name="thursday3">
                            <?php showSections(); ?>
                          </select>
                        </td>
                        <td>
                          <select name="friday3">
                            <?php showSections(); ?>
                          </select>
                        </td>
                      </tr>
                      <tr>
                        <td><b>10:40AM - 11:40AM</b></td>
                        <td>
                          <select name="monday4">
                            <?php showSections(); ?>
                          </select>
                        </td>
                        <td>
                          <select name="tuesday4">
                            <?php showSections(); ?>
                          </select>
                        </td>
                        <td>
                          <select name="wednesday4">
                            <?php showSections(); ?>
                          </select>
                        </td>
                        <td>
                          <select name="thursday4">
                            <?php showSections(); ?>
                          </select>
                        </td>
                        <td>
                          <select name="friday4">
                            <?php showSections(); ?>
                          </select>
                        </td>
                      </tr>
                      <tr>
                        <td><b>11:40AM - 1:00PM</b></td>
                        <td colspan="5"><b style="letter-spacing:3px;">LUNCH BREAK</b></td>
                      </tr>
                      <tr>
                        <td><b>1:00PM - 2:00PM</b></td>
                        <td>
                          <select name="monday5">
                            <?php showSections(); ?>
                          </select>
                        </td>
                        <td>
                          <select name="tuesday5">
                            <?php showSections(); ?>
                          </select>
                        </td>
                        <td>
                          <select name="wednesday5">
                            <?php showSections(); ?>
                          </select>
                        </td>
                        <td>
                          <select name="thursday5">
                            <?php showSections(); ?>
                          </select>
                        </td>
                        <td>
                          <select name="friday5">
                            <?php showSections(); ?>
                          </select>
                        </td>
                      </tr>
                      <tr>
                        <td><b>2:00PM - 3:00PM</b></td>
                        <td>
                          <select name="monday6">
                            <?php showSections(); ?>
                          </select>
                        </td>
                        <td>
                          <select name="tuesday6">
                            <?php showSections(); ?>
                          </select>
                        </td>
                        <td>
                          <select name="wednesday6">
                            <?php showSections(); ?>
                          </select>
                        </td>
                        <td>
                          <select name="thursday6">
                            <?php showSections(); ?>
                          </select>
                        </td>
                        <td>
                          <select name="friday6">
                            <?php showSections(); ?>
                          </select>
                        </td>
                      </tr>
                      <tr>
                        <td><b>3:00PM - 4:00PM</b></td>
                        <td>
                          <select name="monday7">
                            <?php showSections(); ?>
                          </select>
                        </td>
                        <td>
                          <select name="tuesday7">
                            <?php showSections(); ?>
                          </select>
                        </td>
                        <td>
                          <select name="wednesday7">
                            <?php showSections(); ?>
                          </select>
                        </td>
                        <td>
                          <select name="thursday7">
                            <?php showSections(); ?>
                          </select>
                        </td>
                        <td>
                          <select name="friday7">
                            <?php showSections(); ?>
                          </select>
                        </td>
                      </tr>
                      <tr>
                        <td><b>4:00PM - 5:00PM</b></td>
                        <td>
                          <select name="monday8">
                            <?php showSections(); ?>
                          </select>
                        </td>
                        <td>
                          <select name="tuesday8">
                            <?php showSections(); ?>
                          </select>
                        </td>
                        <td>
                          <select name="wednesday8">
                            <?php showSections(); ?>
                          </select>
                        </td>
                        <td>
                          <select name="thursday8">
                            <?php showSections(); ?>
                          </select>
                        </td>
                        <td>
                          <select name="friday8">
                            <?php showSections(); ?>
                          </select>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- <script src="assets/main.js"></script> -->
</body>
</html>