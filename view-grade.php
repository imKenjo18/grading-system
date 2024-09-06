<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header('location: ./');
  exit;
}

if ($_SESSION['privilege'] != 'student') {
  header('location: dashboard');
  exit;
}


if (!isset($_GET['subject']) || $_GET['subject'] < 1) {
  header('location: dashboard');
  exit;
}

$subjectId = $_GET['subject'];

if (!isset($_GET['quarter']) || $_GET['quarter'] < 1 || $_GET['quarter'] > 4) {
  header("location: view-grade?subject=$subjectId&quarter=1");
  exit;
}

$quarter = $_GET['quarter'];

if (isset($_POST['1stqtr'])) {
  header("location: view-grade?subject=$subjectId&quarter=1");
  exit; 
} else if (isset($_POST['2ndqtr'])) {
  header("location: view-grade?subject=$subjectId&quarter=2");
  exit; 
} else if (isset($_POST['3rdqtr'])) {
  header("location: view-grade?subject=$subjectId&quarter=3");
  exit; 
} else if (isset($_POST['4thqtr'])) {
  header("location: view-grade?subject=$subjectId&quarter=4");
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
  <style>
    table, td, th {
      border: 1px solid black;
      vertical-align: middle;
    }

    .bg-color {
      &.lightblue {
        background: lightblue !important;
      }
      
      &.violet {
        background: blueviolet !important;
      }

      &.gray {
        background: lightgray !important;
      }

      &.yellow {
        background: yellow !important;
      }

      &.green {
        background: limegreen !important;
      }

      &.pink {
        background: violet !important;
      }

      &.orange {
        background: lightsalmon !important;
      }
    }

    .text-color {
      &.red {
        color: red !important;
      }
    }

    .mw-50px {
      min-width: 50px;
      width: 50px;
    }

    #q1_grades {
      min-width: 100%;
      border-collapse: collapse;
    
      & th {
        position: sticky;
        /* top: 0; */
        left: 0;
        min-width: 200px;
        height: 40px;
        background: white;
        /* border: 1px solid; */
      }

      & td {
        min-width: 25px;
        text-align: center;
      }
    }
  </style>
</head>
<body style="
  background: url('assets/bg4.png');
  background-size: cover;
  background-position: center center;
  background-attachment: fixed;">
  
  <nav class="navbar fixed-top navbarScroll shadow navbar-expand-lg" style="background: #457b9d;">
    <div class="container">
      <a href="dashboard" class="navbar-brand text-dark"><img id="home-svg" src="assets/home2.svg" height="40" alt="Dashboard"></a>
      <div class="tab">
        <form method="POST">
          <button name="1stqtr">1st Quarter</button>
          <button name="2ndqtr">2nd Quarter</button>
          <button name="3rdqtr">3rd Quarter</button>
          <button name="4thqtr">4th Quarter</button>
        </form>
      </div>

  <?php
    echo '<span style="color: white">Welcome, ' . $_SESSION['given_name'] . '!</span>';

    echo '<a class="nav-link" href="assets/logout"><button id="logout" class="btn btn-danger shadow rounded-pill" name="logout">Logout</button></a></div></nav>';

    echo '<section class="intro"><div class="mt-5 p-3"><div class="row justify-content-center"><div class="mt-5"><div class="table-responsive shadow-lg bg-white" style="border-radius: 1rem;"><div class="card-body text-center" style="border-radius: 1rem;">';

    $studentSectionCheckSql = "SELECT * FROM `accounts` WHERE `username` = '" . $_SESSION['username'] . "'";
    $studentSectionCheck = mysqli_query($connection, $studentSectionCheckSql);

    $sqlWW = "SELECT number FROM `grades` WHERE `section_id` = '" . $_SESSION['section_id'] . "' AND `subject_id` = '$subjectId' AND `quarter` = '$quarter' AND `type` = 'WW' ORDER BY `number` DESC LIMIT 1";
    $sqlWW_q = mysqli_query($connection, $sqlWW);
    $sqlWW_r = mysqli_fetch_assoc($sqlWW_q);

    if (empty($sqlWW_r['number'])) {
      $WW_rows = 0;
    } else {
      $WW_rows = $sqlWW_r['number'];
    }

    $sqlPT = "SELECT number FROM `grades` WHERE `section_id` = '" . $_SESSION['section_id'] . "' AND `subject_id` = '$subjectId' AND `quarter` = '$quarter' AND `type` = 'PT' ORDER BY `number` DESC LIMIT 1";
    $sqlPT_q = mysqli_query($connection, $sqlPT);
    $sqlPT_r = mysqli_fetch_assoc($sqlPT_q);

    if (empty($sqlPT_r['number'])) {
      $PT_rows = 0;
    } else {
      $PT_rows = $sqlPT_r['number'];
    }

    $sql0 = "SELECT * FROM `grades` WHERE `username` = 'admin' AND `section_id` = '" . $_SESSION['section_id'] . "' AND `subject_id` = '$subjectId' AND `quarter` = '$quarter' AND `type` = 'QE'";
    $sql0_q = mysqli_query($connection, $sql0);
    $sql0_n_r = mysqli_num_rows($sql0_q);
    $sql0_r = mysqli_fetch_assoc($sql0_q);

    $QE_HPS = '';

    if ($sql0_n_r != 0) {
      $QE_HPS = $sql0_r['score'];
    }
    
    echo '<table id="q1_grades" class="table table-striped table-xxl table-hover mb-0">
      <thead>
        <tr class="bg-color lightblue">
          <th class="bg-color lightblue">Name</th>
          <td id="q1_ww" colspan="1" style="min-width: 100px;"><b>Written Works</b></td>
          <td><b>Total</b></td>
          <td><b>PS</b></td>
          <td><b>WS</b></td>
          <td id="q1_pt" colspan="1" style="min-width: 120px;"><b>Performance Task</b></td>
          <td><b>Total</b></td>
          <td><b>PS</b></td>
          <td><b>WS</b></td>
          <td id="q1_qe" style="min-width: 155px;"><b>Quarterly Examination</b></td>
          <td><b>PS</b></td>
          <td><b>WS</b></td>
          <td><b>Initial</b></td>
          <td style="min-width: 120px;"><b>Quarterly Grade</b></td>
        </tr>
      </thead>
      <tbody>
      <tr>
        <th></th>
        <td id="q1_ww_blank"></td>
        <td></td>
        <td>100.00</td>
        <td class="bg-color violet pointer" id="q1_ww_ws">20%</td>
        <td id="q1_pt_blank"></td>
        <td></td>
        <td>100.00</td>
        <td class="bg-color violet pointer" id="q1_pt_ws">60%</td>
        <td>PT</td>
        <td>100.00</td>
        <td class="bg-color violet pointer" id="q1_qe_ws">20%</td>
        <td>100.00</td>
        <td>100</td>
      </tr>
      <tr class="bg-color gray text-color red">
        <th class="bg-color gray">Highest Possible Score</th>
        <td></td>
        <td id="q1_ww_total"></td>
        <td>100.00</td>
        <td id="q1_ww_ws2">20.00</td>
        <td></td>
        <td id="q1_pt_total"></td>
        <td>100.00</td>
        <td id="q1_pt_ws2">60.00</td>
        <td id="q1_qe_total"></td>
        <td>100.00</td>
        <td id="q1_qe_ws2">20.00</td>
        <td>100.00</td>
        <td>100</td>
      </tr>';

    $WW_HPS = array();
    $PT_HPS = array();

    $sqlWW_HPS = "SELECT * FROM `grades` WHERE `username` = 'admin' AND `section_id` = '" . $_SESSION['section_id'] . "' AND `subject_id` = '$subjectId' AND `quarter` = '$quarter' AND `type` = 'WW' ORDER BY `number` ASC";
    $sqlWW_HPS_q = mysqli_query($connection, $sqlWW_HPS);

    $x = 1;

    while ($row = mysqli_fetch_assoc($sqlWW_HPS_q)) {
      if ($row['number'] == $x) {
        $score = $row['score'];
        array_push($WW_HPS, $score);
      } else {
        for ($i = $x; $i < $WW_rows + 1; $i++) {
          if ($row['number'] == $i) {
            while (count($WW_HPS) < ($row['number'] - 1)) {
              array_push($WW_HPS, 'undefined');
            }

            $score = $row['score'];
            array_push($WW_HPS, $score);
          }
        }
      }
      $x++;
    }

    for ($z = count($WW_HPS); $z < $WW_rows; $z++) {
      array_push($WW_HPS, 'undefined');
    }

    $sqlPT_HPS = "SELECT * FROM `grades` WHERE `username` = 'admin' AND `section_id` = '" . $_SESSION['section_id'] . "' AND `subject_id` = '$subjectId' AND `quarter` = '$quarter' AND `type` = 'PT' ORDER BY `number` ASC";
    $sqlPT_HPS_q = mysqli_query($connection, $sqlPT_HPS);

    $x = 1;

    while ($row = mysqli_fetch_assoc($sqlPT_HPS_q)) {
      if ($row['number'] == $x) {
        $score = $row['score'];
        array_push($PT_HPS, $score);
      } else {
        for ($i = $x; $i < $PT_rows + 1; $i++) {
          if ($row['number'] == $i) {
            while (count($PT_HPS) < ($row['number'] - 1)) {
              array_push($PT_HPS, 'undefined');
            }

            $score = $row['score'];
            array_push($PT_HPS, $score);
          }
        }
      }
      $x++;
    }

    for ($z = count($PT_HPS); $z < $PT_rows; $z++) {
      array_push($PT_HPS, 'undefined');
    }

    $usernames = array();
    $qe_scores = array();

    while ($student = mysqli_fetch_assoc($studentSectionCheck)) {
      $studentUsername = $student['username'];
      $studentName = $student['surname'] . ', ' . $student['given_name'];

      echo '<tr><th>' . $studentName . '</th>';

      array_push($usernames, $studentUsername);

      $sql1 = "SELECT score FROM `grades` WHERE `username` = '$studentUsername' AND `subject_id` = '$subjectId' AND `quarter` = '$quarter' AND `type` = 'QE'";
      $sql1_q = mysqli_query($connection, $sql1);
      $sql1_r = mysqli_fetch_assoc($sql1_q);
      
      if (!empty($sql1_r['score'])) {
        $qe = $sql1_r['score'];
        
        array_push($qe_scores, $qe);
      } else {
        array_push($qe_scores, 'undefined');
      }

      $sql2 = "SELECT number , score FROM `grades` WHERE `username` = '$studentUsername' AND `subject_id` = '$subjectId' AND `quarter` = '$quarter' AND `type` = 'WW' ORDER BY `number` ASC";
      $sql2_q = mysqli_query($connection, $sql2);
      $sql2_n_r = mysqli_num_rows($sql2_q);

      $arrayNameWW = "ww_arr_" . $studentUsername;
      $$arrayNameWW = array();

      $x = 1;

      while ($sql2_r = mysqli_fetch_assoc($sql2_q)) {
        if ($sql2_r['number'] == $x) {
          $score = $sql2_r['score'];
          array_push($$arrayNameWW, $score);
        } else {
          for ($i = $x; $i < $WW_rows + 1; $i++) {
            if ($sql2_r['number'] == $i) {
              while (count($$arrayNameWW) < ($sql2_r['number'] - 1)) {
                array_push($$arrayNameWW, 'undefined');
              }

              $score = $sql2_r['score'];
              array_push($$arrayNameWW, $score);
            }
          }
        }
        $x++;
      }

      for ($z = count($$arrayNameWW); $z < $WW_rows; $z++) {
        array_push($$arrayNameWW, 'undefined');
      }

      $sql3 = "SELECT number , score FROM `grades` WHERE `username` = '$studentUsername' AND `subject_id` = '$subjectId' AND `quarter` = '$quarter' AND `type` = 'PT' ORDER BY `number` ASC";
      $sql3_q = mysqli_query($connection, $sql3);
      $sql3_n_r = mysqli_num_rows($sql3_q);

      $arrayNamePT = "pt_arr_" . $studentUsername;
      $$arrayNamePT = array();

      $x = 1;

      while ($sql3_r = mysqli_fetch_assoc($sql3_q)) {
        if ($sql3_r['number'] == $x) {
          $score = $sql3_r['score'];
          array_push($$arrayNamePT, $score);
        } else {
          for ($i = $x; $i < $PT_rows + 1; $i++) {
            if ($sql3_r['number'] == $i) {
              while (count($$arrayNamePT) < ($sql3_r['number'] - 1)) {
                array_push($$arrayNamePT, 'undefined');
              }

              $score = $sql3_r['score'];
              array_push($$arrayNamePT, $score);
            }
          }
        }
        $x++;
      }

      for ($z = count($$arrayNamePT); $z < $PT_rows; $z++) {
        array_push($$arrayNamePT, 'undefined');
      }

      echo '<td></td><td class="bg-color yellow"></td><td class="bg-color yellow"></td><td class="bg-color yellow"></td><td></td><td class="bg-color green"></td><td class="bg-color green"></td><td class="bg-color green"></td><td class="bg-color pink"></td><td class="bg-color pink"></td><td class="bg-color pink"></td><td class="bg-color orange"></td><td class="bg-color orange"></td></tr>';
    }

  echo '</tbody></table></div></div></div></div></div></section>';
  ?>
  <script>
    const usernames = ['<?php echo implode("', '", $usernames) ?>'];
    const WW_HPS = [<?php echo implode(", ", $WW_HPS) ?>];
    const PT_HPS = [<?php echo implode(", ", $PT_HPS) ?>];
    const QE_HPS = <?php if (!empty($QE_HPS)) {
      echo $QE_HPS;
      } else {
        echo 'undefined';
      } ?>;
    const qe_arr = [<?php echo implode(", ", $qe_scores) ?>];
    <?php
    foreach ($usernames as $username) {
      $ww_array_name = "ww_arr_" . $username;
      echo "const ww_arr_$username = [" . implode(', ', $$ww_array_name) . "];\n";

      $pt_array_name = "pt_arr_" . $username;
      echo "const pt_arr_$username = [" . implode(', ', $$pt_array_name) . "];\n";
    }
    ?>
    const WW_COLUMNS = <?php echo $WW_rows ?>;
    const PT_COLUMNS = <?php echo $PT_rows ?>;

    const table = document.getElementById('q1_grades');
    const tableRows = table.rows.length;

    // Written Works Section
    for (let i = 0; i < WW_COLUMNS; i++) {
      addWrittenWorkColumn();
    }

    let wwCell = [];
    let wwIndex = (document.getElementById('q1_ww_blank').cellIndex) - (WW_COLUMNS);
    console.log(WW_COLUMNS);

    let newWWIndex = wwIndex - 1;

    let wwTotalIndex = document.getElementById('q1_ww_total').cellIndex;
    
    (function () {
      let arrayNames = [];

      arrayNames.push(<?php foreach ($usernames as $username) {
        echo "ww_arr_$username,"; } ?>);

      console.log(arrayNames);
      console.log(WW_HPS);

      let z = 0;
      
      for (let y = 2; y < tableRows; y++) {
        let i = 0;
        for (let x = wwIndex; x < WW_COLUMNS + wwIndex; x++) {
          if (y == 2) {
            if (WW_HPS[x-1] == undefined) {
              table.rows[y].cells[x].innerText = '';
            } else {
              table.rows[y].cells[x].innerText = WW_HPS[x-1];
              i += parseFloat(table.rows[y].cells[x].innerText);

              const round = (num, precision) => Number(Math.round(num + "e+" + precision) + "e-" + precision);

              const roundedTotal = round(i, 2);

              table.rows[y].cells[wwTotalIndex].innerText = roundedTotal;
            }
            wwCell.push(x);
            newWWIndex = x;
          } else {
            if (arrayNames[z][x-1] == undefined) {
              table.rows[y].cells[x].innerText = '';
            } else {
              table.rows[y].cells[x].innerText = arrayNames[z][x-1];
              i += parseFloat(table.rows[y].cells[x].innerText);

              const round = (num, precision) => Number(Math.round(num + "e+" + precision) + "e-" + precision);

              const roundedTotal = round(i, 2);

              table.rows[y].cells[wwTotalIndex].innerText = roundedTotal;

              const hpsTotal = parseFloat(table.rows[2].cells[wwTotalIndex].innerText);
              const studentTotal = parseFloat(table.rows[y].cells[wwTotalIndex].innerText);
              const ps = (studentTotal / hpsTotal) * 100;

              const roundedPS = round(ps, 2).toFixed(2);

              table.rows[y].cells[wwTotalIndex + 1].innerText = roundedPS;

              const wsPercentage = parseFloat(table.rows[2].cells[wwTotalIndex + 2].innerText) / 100;
              const ws = ps * wsPercentage;

              const roundedWS = round(ws, 2).toFixed(2);

              
              table.rows[y].cells[wwTotalIndex + 2].innerText = roundedWS;
            }
          }


        }

        if (y != 2) {
          z++;
        }
      }
    })();

    //Performance Tasks Section
    for (let i = 0; i < PT_COLUMNS; i++) {
      addPerformanceTaskColumn();
    }

    let ptCell = [];
    let ptIndex = (document.getElementById('q1_pt_blank').cellIndex) - (PT_COLUMNS);
    console.log(PT_COLUMNS);

    let newPTIndex = ptIndex - 1;

    let ptTotalIndex = document.getElementById('q1_pt_total').cellIndex;
    
    (function () {
      let arrayNames = [];

      arrayNames.push(<?php foreach ($usernames as $username) {
        echo "pt_arr_$username,"; } ?>);

      console.log(arrayNames);
      console.log(PT_HPS);

      let z = 0;
      
      for (let y = 2; y < tableRows; y++) {
        let i = 0;
        for (let x = ptIndex; x < PT_COLUMNS + ptIndex; x++) {
          if (y == 2) {
            if (PT_HPS[x-ptIndex] == undefined) {
              table.rows[y].cells[x].innerText = '';
            } else {
              table.rows[y].cells[x].innerText = PT_HPS[x-ptIndex];
              i += parseFloat(table.rows[y].cells[x].innerText);

              const round = (num, precision) => Number(Math.round(num + "e+" + precision) + "e-" + precision);

              const roundedTotal = round(i, 2);

              table.rows[y].cells[ptTotalIndex].innerText = roundedTotal;
            }
            ptCell.push(x);
            newPTIndex = x;
          } else {
            if (arrayNames[z][x-ptIndex] == undefined) {
              table.rows[y].cells[x].innerText = '';
            } else {
              table.rows[y].cells[x].innerText = arrayNames[z][x-ptIndex];
              i += parseFloat(table.rows[y].cells[x].innerText);

              const round = (num, precision) => Number(Math.round(num + "e+" + precision) + "e-" + precision);

              const roundedTotal = round(i, 2);

              table.rows[y].cells[ptTotalIndex].innerText = roundedTotal;

              const hpsTotal = parseFloat(table.rows[2].cells[ptTotalIndex].innerText);
              const studentTotal = parseFloat(table.rows[y].cells[ptTotalIndex].innerText);
              const ps = (studentTotal / hpsTotal) * 100;

              const roundedPS = round(ps, 2).toFixed(2);

              table.rows[y].cells[ptTotalIndex + 1].innerText = roundedPS;

              const wsPercentage = parseFloat(table.rows[2].cells[ptTotalIndex + 2].innerText) / 100;
              const ws = ps * wsPercentage;

              const roundedWS = round(ws, 2).toFixed(2);

              
              table.rows[y].cells[ptTotalIndex + 2].innerText = roundedWS;
            }
          }


        }

        if (y != 2) {
          z++;
        }
      }
    })();
    

    // Quarterly Examination Section
    (function () {
      const qeTotalIndex = document.getElementById('q1_qe_total').cellIndex;

      for (let i = 2, x = -1; i < tableRows; i++, x++) {
        if (i == 2) {
          if (QE_HPS == undefined) {
            table.rows[i].cells[qeTotalIndex].innerText = '';
          } else  {
            table.rows[i].cells[qeTotalIndex].innerText = QE_HPS;
          }
        } else {
          if (qe_arr[x] == undefined) {
            table.rows[i].cells[qeTotalIndex].innerText = '';
          } else  {
            table.rows[i].cells[qeTotalIndex].innerText = qe_arr[x];

            const dividend = parseFloat(table.rows[i].cells[qeTotalIndex].innerText);
            const divisor = parseFloat(table.rows[2].cells[qeTotalIndex].innerText);
            const percentage = (dividend / divisor) * 100;

            const round = (num, precision) => Number(Math.round(num + "e+" + precision) + "e-" + precision);

            const roundedPercentage = round(percentage, 2).toFixed(2);
            // const roundedPercentage = roundToTwo(percentage);
            // console.log(roundedPercentage);

            if (isNaN(parseFloat(roundedPercentage))) {
              table.rows[i].cells[qeTotalIndex + 1].innerText = '';
            } else {
              table.rows[i].cells[qeTotalIndex + 1].innerText = roundedPercentage;
              // console.log(round);

              const wsPercentage = parseFloat(table.rows[2].cells[qeTotalIndex + 2].innerText) / 100;
              const ws = percentage * wsPercentage;

              const roundedWS = round(ws, 2).toFixed(2);

              table.rows[i].cells[qeTotalIndex + 2].innerText = roundedWS;
            }
          }
        }
      }
    })();

    // Initial and Quarterly Grade
    (function () {
      const WW_WS_INDEX = document.getElementById('q1_ww_ws2').cellIndex;
      const PT_WS_INDEX = document.getElementById('q1_pt_ws2').cellIndex;
      const QE_WS_INDEX = document.getElementById('q1_qe_ws2').cellIndex;
      
      for (let i = 3; i < tableRows; i++) {
        let ww_ws, pt_ws, qe_ws;

        if (isNaN(parseFloat(table.rows[i].cells[WW_WS_INDEX].innerText))) {
          ww_ws = 0;
        } else {
          ww_ws = parseFloat(table.rows[i].cells[WW_WS_INDEX].innerText);
        }

        if (isNaN(parseFloat(table.rows[i].cells[PT_WS_INDEX].innerText))) {
          pt_ws = 0;
        } else {
          pt_ws = parseFloat(table.rows[i].cells[PT_WS_INDEX].innerText);
        }

        if (isNaN(parseFloat(table.rows[i].cells[QE_WS_INDEX].innerText))) {
          qe_ws = 0;
        } else {
          qe_ws = parseFloat(table.rows[i].cells[QE_WS_INDEX].innerText);
        }

        const round = (num, precision) => Number(Math.round(num + "e+" + precision) + "e-" + precision);

        const INITIAL_GRADE = round(ww_ws + pt_ws + qe_ws, 2).toFixed(2);

        if (INITIAL_GRADE == 0) {
          table.rows[i].cells[QE_WS_INDEX + 1].innerText = '';
        } else {
          table.rows[i].cells[QE_WS_INDEX + 1].innerText = INITIAL_GRADE;
        }

        let quarterlyGrade = '';

        if (INITIAL_GRADE == 0) {
          quarterlyGrade = '';
        } else if (INITIAL_GRADE < 4) {
          quarterlyGrade = 60;
        } else if (INITIAL_GRADE < 8) {
          quarterlyGrade = 61;
        } else if (INITIAL_GRADE < 12) {
          quarterlyGrade = 62;
        } else if (INITIAL_GRADE < 16) {
          quarterlyGrade = 63;
        } else if (INITIAL_GRADE < 20) {
          quarterlyGrade = 64;
        } else if (INITIAL_GRADE < 24) {
          quarterlyGrade = 65;
        } else if (INITIAL_GRADE < 28) {
          quarterlyGrade = 66;
        } else if (INITIAL_GRADE < 32) {
          quarterlyGrade = 67;
        } else if (INITIAL_GRADE < 36) {
          quarterlyGrade = 68;
        } else if (INITIAL_GRADE < 40) {
          quarterlyGrade = 69;
        } else if (INITIAL_GRADE < 44) {
          quarterlyGrade = 70;
        } else if (INITIAL_GRADE < 48) {
          quarterlyGrade = 71;
        } else if (INITIAL_GRADE < 52) {
          quarterlyGrade = 72;
        } else if (INITIAL_GRADE < 56) {
          quarterlyGrade = 73;
        } else if (INITIAL_GRADE < 60) {
          quarterlyGrade = 74;
        } else if (INITIAL_GRADE < 62) {
          quarterlyGrade = 75;
        } else if (INITIAL_GRADE < 63.2) {
          quarterlyGrade = 76;
        } else if (INITIAL_GRADE < 64.8) {
          quarterlyGrade = 77;
        } else if (INITIAL_GRADE < 66.4) {
          quarterlyGrade = 78;
        } else if (INITIAL_GRADE < 68) {
          quarterlyGrade = 79;
        } else if (INITIAL_GRADE < 69.6) {
          quarterlyGrade = 80;
        } else if (INITIAL_GRADE < 71.2) {
          quarterlyGrade = 81;
        } else if (INITIAL_GRADE < 72.8) {
          quarterlyGrade = 82;
        } else if (INITIAL_GRADE < 74.4) {
          quarterlyGrade = 83;
        } else if (INITIAL_GRADE < 76) {
          quarterlyGrade = 84;
        } else if (INITIAL_GRADE < 77.6) {
          quarterlyGrade = 85;
        } else if (INITIAL_GRADE < 79.2) {
          quarterlyGrade = 86;
        } else if (INITIAL_GRADE < 80.8) {
          quarterlyGrade = 87;
        } else if (INITIAL_GRADE < 82.4) {
          quarterlyGrade = 88;
        } else if (INITIAL_GRADE < 84) {
          quarterlyGrade = 89;
        } else if (INITIAL_GRADE < 85.6) {
          quarterlyGrade = 90;
        } else if (INITIAL_GRADE < 87.2) {
          quarterlyGrade = 91;
        } else if (INITIAL_GRADE < 88.8) {
          quarterlyGrade = 92;
        } else if (INITIAL_GRADE < 90.4) {
          quarterlyGrade = 93;
        } else if (INITIAL_GRADE < 92) {
          quarterlyGrade = 94;
        } else if (INITIAL_GRADE < 93.6) {
          quarterlyGrade = 95;
        } else if (INITIAL_GRADE < 95.2) {
          quarterlyGrade = 96;
        } else if (INITIAL_GRADE < 96.8) {
          quarterlyGrade = 97;
        } else if (INITIAL_GRADE < 98.4) {
          quarterlyGrade = 98;
        } else if (INITIAL_GRADE < 100) {
          quarterlyGrade = 99;
        } else if (INITIAL_GRADE >= 100) {
          quarterlyGrade = 100;
        }

        table.rows[i].cells[QE_WS_INDEX + 2].innerText = quarterlyGrade;
      }
    })();

    function roundToTwo(num) {
      return +(Math.round(num + "e+2")  + "e-2");
    }

    function addWrittenWorkColumn(index, score) {
      const colspan = parseInt(document.getElementById('q1_ww').attributes.colspan.value);
      const wwText = 'Q' + colspan;
      document.getElementById('q1_ww').setAttribute('colspan', colspan + 1);

      const wwBlankIndex = document.getElementById('q1_ww_blank').cellIndex;

      for (let i = 1; i < tableRows; i++) {
        if (i == 1) {
          table.rows[i].insertCell(wwBlankIndex).setAttribute('id', wwText);
          table.rows[i].cells[wwBlankIndex].innerText = wwText;
        } else {
          table.rows[i].insertCell(wwBlankIndex);
        }
      }
    }

    function addPerformanceTaskColumn(index, score) {
      const colspan = parseInt(document.getElementById('q1_pt').attributes.colspan.value);
      const ptText = 'P' + colspan;
      document.getElementById('q1_pt').setAttribute('colspan', colspan + 1);

      const ptBlankIndex = document.getElementById('q1_pt_blank').cellIndex;

      for (let i = 1; i < tableRows; i++) {
        if (i == 1) {
          table.rows[i].insertCell(ptBlankIndex).setAttribute('id', ptText);
          table.rows[i].cells[ptBlankIndex].innerText = ptText;
        } else {
          table.rows[i].insertCell(ptBlankIndex);
        }
      }
    }

    const Q1_WW_WS = document.getElementById('q1_ww_ws');
    const Q1_PT_WS = document.getElementById('q1_pt_ws');
    const Q1_QE_WS = document.getElementById('q1_qe_ws');

    const Q1_WW_WS2 = document.getElementById('q1_ww_ws2');
    const Q1_PT_WS2 = document.getElementById('q1_pt_ws2');
    const Q1_QE_WS2 = document.getElementById('q1_qe_ws2');
    let ws = [Q1_WW_WS, Q1_PT_WS, Q1_QE_WS];
    let ws2 = [Q1_WW_WS2, Q1_PT_WS2, Q1_QE_WS2];

    ws.forEach((element, i) => {
      element.onclick = () => {
        setWSInnerText(element, i);
      }
    })

    function setWSInnerText(element, i) {
      switch (element.innerText) {
        case '10%':
          element.innerText = '20%'
          ws2[i].innerText = ws[i].innerText.substr(0, 2) + '.00';
          break;
        case '20%':
          element.innerText = '30%'
          ws2[i].innerText = ws[i].innerText.substr(0, 2) + '.00';
          break;
        case '30%':
          element.innerText = '40%'
          ws2[i].innerText = ws[i].innerText.substr(0, 2) + '.00';
          break;
        case '40%':
          element.innerText = '50%'
          ws2[i].innerText = ws[i].innerText.substr(0, 2) + '.00';
          break;
        case '50%':
          element.innerText = '60%'
          ws2[i].innerText = ws[i].innerText.substr(0, 2) + '.00';
          break;
        case '60%':
          element.innerText = '70%'
          ws2[i].innerText = ws[i].innerText.substr(0, 2) + '.00';
          break;
        case '70%':
          element.innerText = '10%'
          ws2[i].innerText = ws[i].innerText.substr(0, 2) + '.00';
          break;
      }
    }

    if (WW_COLUMNS != 0) {
      const colspan = parseInt(document.getElementById('q1_ww').attributes.colspan.value);
      document.getElementById('q1_ww').setAttribute('colspan', colspan - 1);

      const wwBlankIndex = document.getElementById('q1_ww_blank').cellIndex;

      for (let i = 1; i < tableRows; i++) {
          table.rows[i].deleteCell(wwBlankIndex);
      }
    }

    if (PT_COLUMNS != 0) {
      const colspan = parseInt(document.getElementById('q1_pt').attributes.colspan.value);
      document.getElementById('q1_pt').setAttribute('colspan', colspan - 1);

      const ptBlankIndex = document.getElementById('q1_pt_blank').cellIndex;

      for (let i = 1; i < tableRows; i++) {
          table.rows[i].deleteCell(ptBlankIndex);
      }
    }
  </script>
</body>
</html>