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
      }
    }
  }
  
?>
<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>photos</title>
        <link rel="stylesheet" href="css/font-file.css">
        <link rel="stylesheet" href="css/header-style.css">
        <link rel="stylesheet" href="css/main-page.css">
    </head>
    <body>
        <header class="default-header">
            <a class="header-logo" href="index.php">
                <span class="logo-text">photos</span>
                <div class="logo-circle first-logo-circle"></div>
                <div class="logo-circle second-logo-circle"></div>
            </a>
            <div class="search-block">
                <img class="search-image" src="img/zoom.png">
                <input type="text" class="search-field" placeholder="Поиск по пользователям и заголовкам">
            </div>
            <div class="header-nav">
                <?php
                if (!$_SESSION['auth'])
                    echo
                    '<a class="header-nav-element" href="auth.php">Войти</a>
                    <a class="spec-nav-element" href="register.php">
                        <span class="header-nav-element">Регистрация</span>
                    </a>
                    <a class="header-nav-element">О сайте</a>';
                else
                    echo
                    '<label for="header-upload" class="label-upload">
                        <img class="upload-image" src="img/upload.png">
                    </label>
                    <input id="header-upload" name="files[]" type="file" multiple>
                        <a href="profile.php?id='.$_SESSION['id'].'"><div class="spec-nav-element gallery-button">
                        <span class="header-nav-element">Моя галерея</span>
                    </div></a>
                    <span class="header-nav-element">О сайте</span>
                    <a class="header-nav-element" href="php/logout.php">Выйти</a>';
                ?>
            </div>
        </header>
        <div class="middle-page-part">
            <span class="main-top-label">Делись изображениями</span>
            <?php
            if (!$_SESSION['auth'])
                echo
                '<span class="main-bottom-label">Регистрируйся прямо сейчас и начни <br> бесплатно загружать всё, что пожелаешь </span>
                <a href="register.php">
                    <div class="start-now-button button">
                        Начни сейчас
                    </div>
                </a>';
            else
                echo
                '<span class="main-bottom-label">Для загрузки изображений просто перетащите их <br>
                сюда либо выберите соответствующий элемент ниже</span>
                <label for="header-upload">
                    <div class="start-load-button button">
                        Начать загрузку
                    </div>
                </label>';
            ?>
        </div>
        <div id="loading-status" class="loading-status">
            Загружается 5 файлов...
        </div>
        <script type="text/javascript" src="js/jquery-3.5.1.min.js"></script>
        <script type="text/javascript" src="js/header-actions.js"></script>
    </body>
</html>