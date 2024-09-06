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
      <a class="nav-link" href="assign-schedule"><button class="btn rounded-pill shadow btn-primary">Assign Schedule</button></a>
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
  </nav>

  <section class="intro">
    <div class="mask d-flex align-items-center h-100">
      <div class="container mt-5 p-3">
        <div class="row justify-content-center">
          <div class="mt-5 col-10">
            <div class="border border-dark table table-responsive shadow-lg bg-white" style="border-radius: 1rem;">
              <div class="card-body text-center" style="border-radius: 1rem;">
                <table class="table table-striped table-xxl table-hover mb-0">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Section</th>
                      <th>Contact Number</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $studentCheck = "SELECT *  FROM `accounts` WHERE `privilege` = 'student'";
                    $studentSQL = mysqli_query($connection, $studentCheck);
                    
                    while ($student = mysqli_fetch_assoc($studentSQL)) {
                      $id = $student['id'];
                      $sectionId = $student['section_id'];

                      $sectionCheck = "SELECT *  FROM `sections` WHERE `id` = '$sectionId'";
                      $sectionSQL = mysqli_query($connection, $sectionCheck);
                      $section = mysqli_fetch_assoc($sectionSQL);

                      if (empty($section)) {
                        $class = '';
                      } else {
                        $class = $section['year_level'] . ' - ' . $section['name'];
                      }

                      echo '<tr><td>' . $student['full_name'] . '</td><td>' . $class . '</td><td>' . $student['contact_number'] . '</td><td>';
                      
                      if ($sectionId == 0) {
                        echo '<a href="javascript:message();" title="View Section"><img class="icons" id="view-class-svg" src="assets/view-class.svg" width="25" height="25" alt="View Section"></a>';
                      } else {
                        echo '<a href="view-section?id=' . $sectionId .'" title="View Section"><img class="icons" id="view-class-svg" src="assets/view-class.svg" width="25" height="25" alt="View Section"></a>';
                      }
                      
                      echo '<a href="edit-student?id=' . $id .'" title="Edit"><img class="icons" id="edit-svg" src="assets/edit.svg" width="25" height="25" alt="Edit"></a><a href="account-settings?username=' . $student['username'] .'" title="Account Settings"><img class="icons" id="settings-svg" src="assets/settings.svg" width="28" height="28" alt="Account Settings"></a><a onClick="return confirm(\'Proceed to Delete?\');" href="delete?id=' . $id . '" title="Delete"><img class="icons" id="delete-svg" src="assets/delete.svg" width="28" height="28" alt="Delete"></a></td></tr>';
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <script src="assets/main.js"></script>
</body>
</html>