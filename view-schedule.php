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

$teacherUsername = $_GET['username'];
$scheduleCheck = "SELECT * FROM `schedules` WHERE username = '$teacherUsername'";
$scheduleQuery = mysqli_query($connection, $scheduleCheck);
$scheduleCheckResult = mysqli_fetch_assoc($scheduleQuery);

function showSchedule($column) {
  global $scheduleCheckResult, $connection;
  
  if (!empty($scheduleCheckResult[$column])) {
    $sectionId = $scheduleCheckResult[$column];

    if ($sectionId == "FREE TIME") {
      echo "FREE TIME";
      return;
    }
  } else {
    return;
  }

  $sectionCheck = "SELECT * FROM `sections` WHERE id = '$sectionId'";
  $sectionQuery = mysqli_query($connection, $sectionCheck);
  $sectionCheckResult = mysqli_fetch_assoc($sectionQuery);

  if (!empty($sectionCheckResult['year_level']) && !empty($sectionCheckResult['name'])) {
    $section = $sectionCheckResult['year_level'] . " - " . $sectionCheckResult['name'];
  } else {
    $section = '';
  }

  echo "<a href=\"view-section?id=$sectionId\" title=\"View Section\">$section</a>";
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
                <table class="table table-striped table-xxl table-hover mb-0">
                  <thead>
                    <th colspan="6">
                      Schedule of <?php $teacherCheck = "SELECT * FROM `accounts` WHERE username = '$teacherUsername'";
                      $teacherQuery = mysqli_query($connection, $teacherCheck);
                      $teacherResult = mysqli_fetch_assoc($teacherQuery);

                      echo $teacherResult['given_name'] . ' ' . $teacherResult['surname'];
                      ?>
                      <br>
                      <a href="dashboard"><button class="mt-2 shadow btn btn-secondary btn-sm">DASHBOARD</button></a>
                      <a href="assign-schedule"><button class="mt-2 shadow btn btn-dark btn-sm">EDIT</button></a>
                    </th>
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
                      <td><?php showSchedule('monday1'); ?></td>
                      <td><?php showSchedule('tuesday1'); ?></td>
                      <td><?php showSchedule('wednesday1'); ?></td>
                      <td><?php showSchedule('thursday1'); ?></td>
                      <td><?php showSchedule('friday1'); ?></td>
                    </tr>
                    <tr>
                      <td><b>8:30AM - 9:30AM</b></td>
                      <td><?php showSchedule('monday2'); ?></td>
                      <td><?php showSchedule('tuesday2'); ?></td>
                      <td><?php showSchedule('wednesday2'); ?></td>
                      <td><?php showSchedule('thursday2'); ?></td>
                      <td><?php showSchedule('friday2'); ?></td>
                    </tr>
                    <tr>
                      <td><b>9:30AM - 9:40AM</b></td>
                      <td colspan="5"><b style="letter-spacing:3px;">RECESS TIME</b></td>
                    </tr>
                    <tr>
                      <td><b>9:40AM - 10:40AM</b></td>
                      <td><?php showSchedule('monday3'); ?></td>
                      <td><?php showSchedule('tuesday3'); ?></td>
                      <td><?php showSchedule('wednesday3'); ?></td>
                      <td><?php showSchedule('thursday3'); ?></td>
                      <td><?php showSchedule('friday3'); ?></td>
                    </tr>
                    <tr>
                      <td><b>10:40AM - 11:40AM</b></td>
                      <td><?php showSchedule('monday4'); ?></td>
                      <td><?php showSchedule('tuesday4'); ?></td>
                      <td><?php showSchedule('wednesday4'); ?></td>
                      <td><?php showSchedule('thursday4'); ?></td>
                      <td><?php showSchedule('friday4'); ?></tr>
                    <tr>
                      <td><b>11:40AM - 1:00PM</b></td>
                      <td colspan="5"><b style="letter-spacing:3px;">LUNCH BREAK</b></td>
                    </tr>
                    <tr>
                      <td><b>1:00PM - 2:00PM</b></td>
                      <td><?php showSchedule('monday5'); ?></td>
                      <td><?php showSchedule('tuesday5'); ?></td>
                      <td><?php showSchedule('wednesday5'); ?></td>
                      <td><?php showSchedule('thursday5'); ?></td>
                      <td><?php showSchedule('friday5'); ?></td>
                    </tr>
                    <tr>
                      <td><b>2:00PM - 3:00PM</b></td>
                      <td><?php showSchedule('monday6'); ?></td>
                      <td><?php showSchedule('tuesday6'); ?></td>
                      <td><?php showSchedule('wednesday6'); ?></td>
                      <td><?php showSchedule('thursday6'); ?></td>
                      <td><?php showSchedule('friday6'); ?></td>
                    </tr>
                    <tr>
                      <td><b>3:00PM - 4:00PM</b></td>
                      <td><?php showSchedule('monday7'); ?></td>
                      <td><?php showSchedule('tuesday7'); ?></td>
                      <td><?php showSchedule('wednesday7'); ?></td>
                      <td><?php showSchedule('thursday7'); ?></td>
                      <td><?php showSchedule('friday7'); ?></td>
                    </tr>
                    <tr>
                      <td><b>4:00PM - 5:00PM</b></td>
                      <td><?php showSchedule('monday8'); ?></td>
                      <td><?php showSchedule('tuesday8'); ?></td>
                      <td><?php showSchedule('wednesday8'); ?></td>
                      <td><?php showSchedule('thursday8'); ?></td>
                      <td><?php showSchedule('friday8'); ?></td>
                    </tr>
                  </tbody>
                </table>
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