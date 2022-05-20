<?php
 $isUpdated = false;
 require "config/config.php";
 $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
 if ($mysqli->connect_errno) {
     echo $mysqli->connect_error;
     exit();
 }
 $mysqli->set_charset('utf8');

$sql = "delete from last_upload where users_id = " . $_SESSION["id"];
$results = $mysqli->query($sql);
if(!$results) {
echo $mysqli->error;
}
$sql = "delete from status where users_id = " . $_SESSION["id"];
$results = $mysqli->query($sql);
if(!$results) {
    echo $mysqli->error;
    }
$sql = "delete from users where id = " . $_SESSION["id"];
$results = $mysqli->query($sql);
if(!$results) {
    echo $mysqli->error;
    }

if($mysqli->affected_rows == 1) {
$isUpdated = true;
}

$mysqli->close();

header("Location: ../final-project/logout.php");
?>