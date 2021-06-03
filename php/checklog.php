<?php

  require_once("connection.php");

  $username = mysqli_real_escape_string($connection, $_POST['username']);

  $query = "select * from users where username='$username'";

  $count = mysqli_num_rows(mysqli_query($connection, $query));

  if ($count > 0)
      $ans = '0';
  else
      $ans = '1';

  echo $ans;

  mysqli_close($connection);

?>