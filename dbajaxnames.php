<?php
require_once("dbconn.php");
if(isset($_GET['q'])) {
  $con = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
  if (!$con) {
      die('Could not connect: ' . mysqli_error($con));
  }
  $emails="SELECT DISTINCT email FROM Comments";
  $result = mysqli_query($con,$emails);
  while($row = mysqli_fetch_array($result)) {
    echo "<option value='".$row['email']."'>";
  }
}
mysqli_close($con);
?>
