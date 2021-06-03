<?php

  session_start();

  if (!empty($_SESSION['auth']) and $_SESSION['auth']) {
    session_destroy();

    setcookie('username', '', time(), '/');
    setcookie('key', '', time(), '/');

    header("Location: ".$_SERVER['HTTP_REFERER']);
  }

?>
