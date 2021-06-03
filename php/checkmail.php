<?php

  require_once("connection.php");

  $email = mysqli_real_escape_string($connection, $_POST['email']);

  $query = "select * from users where email='$email'";

  $count = mysqli_num_rows(mysqli_query($connection, $query));

  if ($count > 0)
      $ans = '0';
  else
      $ans = '1';

  echo $ans;

  mysqli_close($connection);

?>