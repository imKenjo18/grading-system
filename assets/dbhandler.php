<?php
// error_reporting(0);

define('WEBSITE_TITLE', 'Grading System');

// Login to phpmyadmin
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');

date_default_timezone_set('Asia/Manila');

$loginConn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

//Creates database if it doesn't exist
$createDB = "CREATE DATABASE IF NOT EXISTS `grading_system`";
mysqli_query($loginConn, $createDB);

//Connects to database
define('DB_NAME', 'grading_system');
$connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($connection === false) {
  die('ERROR: Could not connect. ' . mysqli_connect_error());
}

$createAccountsTable = "CREATE TABLE IF NOT EXISTS `grading_system`.`accounts` (`id` INT NOT NULL AUTO_INCREMENT , `username` VARCHAR(255) NOT NULL , `password` VARCHAR(255) NOT NULL , `privilege` VARCHAR(255) NOT NULL , `full_name` VARCHAR(255) NOT NULL , `sex` VARCHAR(255) NOT NULL , `contact_number` VARCHAR(255) NOT NULL , `section_id` INT NOT NULL , `subjects` VARCHAR(255) NOT NULL , `date_added` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `date_edited` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`) , UNIQUE (`username`)) ENGINE = InnoDB;";
mysqli_query($connection, $createAccountsTable);

$createSectionsTable = "CREATE TABLE IF NOT EXISTS `grading_system`.`sections` (`id` INT NOT NULL AUTO_INCREMENT , `name` VARCHAR(255) NOT NULL , `year_level` VARCHAR(255) NOT NULL , `adviser_id` INT NOT NULL , `date_added` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `date_edited` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;";
mysqli_query($connection, $createSectionsTable);

$createSubjectsTable = "CREATE TABLE IF NOT EXISTS `grading_system`.`subjects` (`id` INT NOT NULL AUTO_INCREMENT , `name` VARCHAR(255) NOT NULL , `date_added` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `date_edited` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;";
mysqli_query($connection, $createSubjectsTable);

$createFinalGradesTable = "CREATE TABLE IF NOT EXISTS `grading_system`.`final_grades` (`id` INT NOT NULL AUTO_INCREMENT , `username` VARCHAR(255) NOT NULL , `subject` INT NOT NULL , `quarter1` INT NOT NULL , `quarter2` INT NOT NULL , `quarter3` INT NOT NULL , `quarter4` INT NOT NULL , `date_added` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `date_edited` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`) , CONSTRAINT `fk_final_grades_username` FOREIGN KEY (`username`) REFERENCES `accounts`(`username`) ON DELETE CASCADE ON UPDATE CASCADE , CONSTRAINT `fk_final_grades_subject` FOREIGN KEY (`subject`) REFERENCES `subjects`(`id`) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE = InnoDB;";
mysqli_query($connection, $createFinalGradesTable);

$createGradesTable = "CREATE TABLE IF NOT EXISTS `grading_system`.`grades` (`id` INT NOT NULL AUTO_INCREMENT , `username` VARCHAR(255) NOT NULL , `section_id` INT NOT NULL , `subject_id` INT NOT NULL , `quarter` INT NOT NULL , `type` VARCHAR(255) NOT NULL , `number` VARCHAR(255) NOT NULL , `score` VARCHAR(255) NOT NULL , `date_added` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `date_edited` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`) , CONSTRAINT `fk_grades_username` FOREIGN KEY (`username`) REFERENCES `accounts`(`username`) ON DELETE CASCADE ON UPDATE CASCADE , CONSTRAINT `fk_grades_section` FOREIGN KEY (`section_id`) REFERENCES `sections`(`id`) ON DELETE CASCADE ON UPDATE CASCADE , CONSTRAINT `fk_grades_subject` FOREIGN KEY (`subject_id`) REFERENCES `subjects`(`id`) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE = InnoDB;";
mysqli_query($connection, $createGradesTable);

$createSchedulesTable = "CREATE TABLE IF NOT EXISTS `grading_system`.`schedules` (`id` INT NOT NULL AUTO_INCREMENT , `username` VARCHAR(255) NOT NULL , `monday1` VARCHAR(255) NOT NULL , `tuesday1` VARCHAR(255) NOT NULL , `wednesday1` VARCHAR(255) NOT NULL , `thursday1` VARCHAR(255) NOT NULL , `friday1` VARCHAR(255) NOT NULL , `monday2` VARCHAR(255) NOT NULL , `tuesday2` VARCHAR(255) NOT NULL , `wednesday2` VARCHAR(255) NOT NULL , `thursday2` VARCHAR(255) NOT NULL , `friday2` VARCHAR(255) NOT NULL , `monday3` VARCHAR(255) NOT NULL , `tuesday3` VARCHAR(255) NOT NULL , `wednesday3` VARCHAR(255) NOT NULL , `thursday3` VARCHAR(255) NOT NULL , `friday3` VARCHAR(255) NOT NULL , `monday4` VARCHAR(255) NOT NULL , `tuesday4` VARCHAR(255) NOT NULL , `wednesday4` VARCHAR(255) NOT NULL , `thursday4` VARCHAR(255) NOT NULL , `friday4` VARCHAR(255) NOT NULL , `monday5` VARCHAR(255) NOT NULL , `tuesday5` VARCHAR(255) NOT NULL , `wednesday5` VARCHAR(255) NOT NULL , `thursday5` VARCHAR(255) NOT NULL , `friday5` VARCHAR(255) NOT NULL , `monday6` VARCHAR(255) NOT NULL , `tuesday6` VARCHAR(255) NOT NULL , `wednesday6` VARCHAR(255) NOT NULL , `thursday6` VARCHAR(255) NOT NULL , `friday6` VARCHAR(255) NOT NULL , `monday7` VARCHAR(255) NOT NULL , `tuesday7` VARCHAR(255) NOT NULL , `wednesday7` VARCHAR(255) NOT NULL , `thursday7` VARCHAR(255) NOT NULL , `friday7` VARCHAR(255) NOT NULL , `monday8` VARCHAR(255) NOT NULL , `tuesday8` VARCHAR(255) NOT NULL , `wednesday8` VARCHAR(255) NOT NULL , `thursday8` VARCHAR(255) NOT NULL , `friday8` VARCHAR(255) NOT NULL , `date_added` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `date_edited` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`) , UNIQUE (`username`) , CONSTRAINT `fk_schedules_username` FOREIGN KEY (`username`) REFERENCES `accounts`(`username`) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE = InnoDB;";
mysqli_query($connection, $createSchedulesTable);

$createGradeTableSettingsTable = "CREATE TABLE IF NOT EXISTS `grading_system`.`grade_table_settings` (`id` INT NOT NULL AUTO_INCREMENT , `section_id` INT NOT NULL , `quarter` VARCHAR(255) NOT NULL , `subject_id` INT NOT NULL , `ww_ws` INT NOT NULL , `pt_ws` INT NOT NULL , `qe_ws` INT NOT NULL , PRIMARY KEY (`id`) , CONSTRAINT `fk_grade_settings_section` FOREIGN KEY (`section_id`) REFERENCES `sections`(`id`) ON DELETE CASCADE ON UPDATE CASCADE , CONSTRAINT `fk_grade_settings_subject` FOREIGN KEY (`subject_id`) REFERENCES `subjects`(`id`) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE = InnoDB;";
mysqli_query($connection, $createGradeTableSettingsTable);

$accountsSql = "SELECT * FROM `accounts`";
$accountsResult = mysqli_query($connection, $accountsSql);

if (mysqli_num_rows($accountsResult) == 0) {
  $addAdminSql = "INSERT INTO `accounts` (`username`, `password`, `privilege`) VALUES ('admin', 'admin', 'admin')";
  mysqli_query($connection, $addAdminSql);
}
?>