<?php

  require_once("php/connection.php");

  session_start();

  if (empty($_SESSION['auth']) or !($_SESSION['auth'])) {

    if (!empty($_COOKIE['username']) and !empty($_COOKIE['key']) ) {

      $username = $_COOKIE['username'];
      $key = $_COOKIE['key'];

      $query = "select * from users where username='$username' and cookie='$key'";

      $result = mysqli_fetch_assoc(mysqli_query($connection, $query));

      if (!empty($result)) {
        $_SESSION['auth'] = true;
        $_SESSION['id'] = $result['id'];
        $_SESSION['username'] = $result['username'];

        header('Location: index.php');
      }
    }
  }
  else
    header('Location: index.php');

?>
<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Авторизация на photos</title>
        <link rel="stylesheet" href="css/font-file.css">
        <link rel="stylesheet" href="css/reg-auth-header.css">
        <link rel="stylesheet" href="css/auth-reg.css">
    </head>
    <body>
        <header class="reg-auth-header">
            <a href="index.php"><div class="header-logo">
                <span class="logo-text">photos</span>
                <div class="logo-elipses">
                    <div class="logo-circle first-logo-circle"></div>
                    <div class="logo-circle second-logo-circle"></div>
                </div>
            </div></a>
        </header>
        <form class="auth-form">
            <div class="logo-elipses">
                <div class="logo-circle first-logo-circle"></div>
                <div class="logo-circle second-logo-circle"></div>
            </div>
            <span class="reg-label">Авторизация на photos</span>
            <input id="email" class="reg-auth-input" type="text" placeholder="E-mail адрес" name="email">
            <input id="password" class="reg-auth-input" type="password" placeholder="Пароль" name="password">
            <div class="remember-data-block">
                <input id="remember-box" class="remember-box" type="checkbox" name="remember-data">
                <span>Запомнить мои данные</span>
            </div>
            <input id="auth-button" class="reg-auth-button auth-button" type="button" value="Выполнить вход">
            <a class="bottom-form-label bottom-form-link" href="#">Забыли пароль?</a>
            <div class="gray-break"></div>
            <span class="bottom-form-label">
                Ещё нет аккаунта? <a class="bottom-form-link" href="register.php">Регистрация</a>
            </span>
        </form>
        <div id="modal-shadow" class="shadow-screen">
            <div class="modal-message">
                <div class="message-header">
                    <span id="message-header-text"></span>
                    <img id="close-modal-window" src="img/cross.png">
                </div>
                <div class="message-text-block">
                    <span id="message-text"></span>
                </div>
            </div>
        </div>
        <script type="text/javascript" src="js/jquery-3.5.1.min.js"></script>
        <script type="text/javascript" src="js/auth.js"></script>
    </body>
</html>