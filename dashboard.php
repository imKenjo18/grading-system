<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header('location: ./');
  exit;
}

require_once 'assets/dbhandler.php';
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
  
  <nav class="navbar fixed-top navbarScroll shadow navbar-expand-lg" style="background: #457b9d;">
    <div class="container">
      <a href="dashboard" class="navbar-brand text-dark"><img id="home-svg" src="assets/home2.svg" height="40" alt="Dashboard"></a>
      <?php
        if ($_SESSION['privilege'] == 'admin') {
          echo '<a class="nav-link" href="assign-schedule"><button class="btn rounded-pill shadow btn-primary">Assign Schedule</button></a>
          <a class="nav-link" href="add-teacher"><button class="btn rounded-pill shadow btn-primary">Add Teacher</button></a>
          <a class="nav-link" href="add-section"><button class="btn rounded-pill shadow btn-primary">Add Section</button></a>
          <a class="nav-link" href="sections"><button class="btn rounded-pill shadow btn-primary">View Sections</button></a>
          <a class="nav-link" href="add-subject"><button class="btn rounded-pill shadow btn-primary">Add Subject</button></a>
          <a class="nav-link" href="subjects"><button class="btn rounded-pill shadow btn-primary">View Subjects</button></a>
          <a class="nav-link" href="add-student"><button class="btn rounded-pill shadow btn-primary">Add Student</button></a>
          <a class="nav-link" href="students"><button class="btn rounded-pill shadow btn-primary">View Students</button></a>
          <span style="color: white">Welcome, Admin!</span>
          <a class="nav-link" href="assets/logout"><button id="logout" class="btn btn-danger rounded-pill shadow" name="logout">Logout</button></a>
          </div>
          </nav>';
        } else if ($_SESSION['privilege'] == 'teacher') {
          echo '<span style="color: white">Welcome, Teacher ' . $_SESSION['full_name'] . '!</span>
          <a class="nav-link" href="assets/logout"><button id="logout" class="btn btn-danger shadow rounded-pill" name="logout">Logout</button></a>
          </div>
          </nav>';
        } else if ($_SESSION['privilege'] == 'student') {
          echo '<span style="color: white">Welcome, ' . $_SESSION['full_name'] . '!</span>
          <a class="nav-link" href="assets/logout"><button id="logout" class="btn btn-danger shadow rounded-pill" name="logout">Logout</button></a>
          </div>
          </nav>';
        } 
      ?>

  <?php

  if ($_SESSION['privilege'] == 'admin') {
    $teacherCheck = "SELECT *  FROM `accounts` WHERE `privilege` = 'teacher'";
    $teacherSQL = mysqli_query($connection, $teacherCheck);

    echo '<section class="intro"><div class="mask d-flex align-items-center h-100"><div class="container mt-3"><div class="row justify-content-center"><div class="mt-5 col-10"><div class="mt-5 border border-dark table table-responsive shadow-lg bg-white" style="border-radius: 1rem;"><div class="card-body text-center" style="border-radius: 1rem;"><table class="table table-striped table-xxl table-hover mb-0"><thead><tr><th>Name</th><th>Contact Number</th><th>Actions</th></tr></thead><tbody>';

    while ($row = mysqli_fetch_assoc($teacherSQL)) {
      $teacherId = $row['id'];

      echo '<tr><td>' . $row['full_name'] . '</td><td>' . $row['contact_number'] . '</td><td class="functions"><a href="view-schedule?username=' . $row['username'] .'" title="View Schedule"><img class="icons" id="schedule-svg" src="assets/schedule.svg" height="27" alt="View Schedule"></a><a href="edit-teacher?id=' . $row['id'] .'" title="Edit"><img class="icons" id="edit-svg" src="assets/edit.svg" width="25" height="25" alt="Edit"></a><a href="account-settings?username=' . $row['username'] .'" title="Account Settings"><img class="icons" id="settings-svg" src="assets/settings.svg" width="28" height="28" alt="Account Settings"></a><a onClick="return confirm(\'Proceed to Delete?\');" href="delete?id=' . $teacherId . '" title="Delete"><img class="icons" id="delete-svg" src="assets/delete.svg" width="28" height="28" alt="Delete"></a></td></tr>';
    }
  } else if ($_SESSION['privilege'] == 'teacher') { // FOR THE TEACHER SIDE
    $scheduleCheck = "SELECT * FROM `schedules` WHERE username = '" . $_SESSION['username'] . "'";
    $scheduleQuery = mysqli_query($connection, $scheduleCheck);
    $scheduleCheckResult = mysqli_fetch_assoc($scheduleQuery);

    function showSchedule($column) {
      global $scheduleCheckResult, $connection;
      
      if (!empty($scheduleCheckResult[$column])) {
        $sectionId = $scheduleCheckResult[$column];
    
        if ($sectionId == "FREE TIME") {
          return "FREE TIME";
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
    
      return "<a href=\"view-section?id=$sectionId\" title=\"View Section\">$section</a>";
    }

    echo '<section class="intro"><div class="mask d-flex align-items-center h-100"><div class="container mt-5 p-3"><div class="row justify-content-center"><div class="mt-5 col-13"><div class="border border-dark table table-responsive shadow-lg bg-white" style="border-radius: 1rem;"><div class="card-body text-center" style="border-radius: 1rem;"><table class="table table-striped table-xxl table-hover mb-0"><thead><tr><th>Time</th><th>Monday</th><th>Tuesday</th><th>Wednesday</th><th>Thursday</th><th>Friday</th></tr></thead><tbody>
    <tr>
      <td>7:30AM - 8:30AM</td>
      <td>' . showSchedule('monday1') . '</td>
      <td>' . showSchedule('tuesday1') . '</td>
      <td>' . showSchedule('wednesday1') . '</td>
      <td>' . showSchedule('thursday1') . '</td>
      <td>' . showSchedule('friday1') . '</td>
    </tr>
    <tr>
      <td>8:30AM - 9:30AM</td>
      <td>' . showSchedule('monday2') . '</td>
      <td>' . showSchedule('tuesday2') . '</td>
      <td>' . showSchedule('wednesday2') . '</td>
      <td>' . showSchedule('thursday2') . '</td>
      <td>' . showSchedule('friday2') . '</td>
    </tr>
    <tr>
      <td>9:30AM - 9:40AM</td>
      <td colspan="5"><b>RECESS TIME</b></td>
    </tr>
    <tr>
      <td>9:40AM - 10:40AM</td>
      <td>' . showSchedule('monday3') . '</td>
      <td>' . showSchedule('tuesday3') . '</td>
      <td>' . showSchedule('wednesday3') . '</td>
      <td>' . showSchedule('thursday3') . '</td>
      <td>' . showSchedule('friday3') . '</td>
    </tr>
    <tr>
      <td>10:40AM - 11:40AM</td>
      <td>' . showSchedule('monday4') . '</td>
      <td>' . showSchedule('tuesday4') . '</td>
      <td>' . showSchedule('wednesday4') . '</td>
      <td>' . showSchedule('thursday4') . '</td>
      <td>' . showSchedule('friday4') . '</td>
    <tr>
      <td>11:40AM - 1:00PM</td>
      <td colspan="5"><b>LUNCH BREAK</b></td>
    </tr>
    <tr>
      <td>1:00PM - 2:00PM</td>
      <td>' . showSchedule('monday5') . '</td>
      <td>' . showSchedule('tuesday5') . '</td>
      <td>' . showSchedule('wednesday5') . '</td>
      <td>' . showSchedule('thursday5') . '</td>
      <td>' . showSchedule('friday5') . '</td>
    </tr>
    <tr>
      <td>2:00PM - 3:00PM</td>
      <td>' . showSchedule('monday6') . '</td>
      <td>' . showSchedule('tuesday6') . '</td>
      <td>' . showSchedule('wednesday6') . '</td>
      <td>' . showSchedule('thursday6') . '</td>
      <td>' . showSchedule('friday6') . '</td>
    </tr>
    <tr>
      <td>3:00PM - 4:00PM</td>
      <td>' . showSchedule('monday7') . '</td>
      <td>' . showSchedule('tuesday7') . '</td>
      <td>' . showSchedule('wednesday7') . '</td>
      <td>' . showSchedule('thursday7') . '</td>
      <td>' . showSchedule('friday7') . '</td>
    </tr>
    <tr>
      <td>4:00PM - 5:00PM</td>
      <td>' . showSchedule('monday8') . '</td>
      <td>' . showSchedule('tuesday8') . '</td>
      <td>' . showSchedule('wednesday8') . '</td>
      <td>' . showSchedule('thursday8') . '</td>
      <td>' . showSchedule('friday8') . '</td>
    </tr>';
  } else if ($_SESSION['privilege'] == 'student') { // FOR THE STUDENT SIDE
    $subjectCheck = "SELECT `id`, `name` FROM `subjects`";
    $subjectQuery = mysqli_query($connection, $subjectCheck);

    // $studentCheck = "SELECT * FROM `accounts` WHERE `privilege` = 'student' AND `username` = '" . $_SESSION['username'] . "'";
    // $studentQuery = mysqli_query($connection, $studentCheck);

    echo '<section class="intro"><div class="mask d-flex align-items-center h-100"><div class="container mt-3"><div class="row justify-content-center"><div class="mt-5 col-10"><div class="mt-5 border border-dark table table-responsive shadow-lg bg-white" style="border-radius: 1rem;"><div class="card-body text-center" style="border-radius: 1rem;"><table class="table table-striped table-xxl table-hover mb-0"><thead><tr><th>Subject</th><th>1st Quarter</th><th>2nd Quarter</th><th>3rd Quarter</th><th>4th Quarter</th><th>General Average</th><th>Actions</th></tr></thead><tbody>';

    while ($subject = mysqli_fetch_assoc($subjectQuery)) {
      $subjectID = $subject['id'];
      $subjectName = $subject['name'];

      $gradeCheck = "SELECT * FROM `final_grades` WHERE `username` = '" . $_SESSION['username'] . "' AND `subject` = '$subjectID'";
      $gradeQuery = mysqli_query($connection, $gradeCheck);
      $grade = mysqli_fetch_assoc($gradeQuery);

      $blanks = 0;

      if (!isset($grade['quarter1'])) {
        $quarter1 = '';
        $blanks++;
      } else if ($grade['quarter1'] == 0) {
        $quarter1 = '';
        $blanks++;
      } else {
        $quarter1 = $grade['quarter1'];
      }

      if (!isset($grade['quarter2'])) {
        $quarter2 = '';
        $blanks++;
      } else if ($grade['quarter2'] == 0) {
        $quarter2 = '';
        $blanks++;
      } else {
        $quarter2 = $grade['quarter2'];
      }

      if (!isset($grade['quarter3'])) {
        $quarter3 = '';
        $blanks++;
      } else if ($grade['quarter3'] == 0) {
        $quarter3 = '';
        $blanks++;
      } else {
        $quarter3 = $grade['quarter3'];
      }

      if (!isset($grade['quarter4'])) {
        $quarter4 = '';
        $blanks++;
      } else if ($grade['quarter4'] == 0) {
        $quarter4 = '';
        $blanks++;
      } else {
        $quarter4 = $grade['quarter4'];
      }

      if ($blanks == 0) {
        $general_average = ($quarter1 + $quarter2 + $quarter3 + $quarter4) / 4;
      } else {
        $general_average = '';
      }

      echo "<tr><td>$subjectName</td><td>$quarter1</td><td>$quarter2</td><td>$quarter3</td><td>$quarter4</td><td>$general_average</td><td class='border'><a href=\"view-grade?subject=$subjectID\" title=\"View\"><img class=\"icons\" id=\"view-class-svg\" src=\"assets/eye.svg\" width=\"28\" height=\"28\" alt=\"View\"></a></td></tr>";
    }
  }

  echo '</tbody></table></div></div></div></div></div></div></section>';
  ?>
  <!-- <script src="assets/main.js"></script> -->
</body>
</html>