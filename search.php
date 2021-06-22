<?php

  require_once("php/connection.php");

  if (empty($_GET['value']) || strlen($_GET['value']) < 3)
  {
    header('Location: index.php');
    exit();
  }

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
        <title>Photo</title>
        <link rel="stylesheet" href="css/font-file.css">
        <link rel="stylesheet" href="css/header-style.css">
        <link rel="stylesheet" href="css/search.css">
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
                <?php echo '<input type="text" class="search-field" value="'.$_GET['value'].'" placeholder="Поиск по пользователям и заголовкам">'; ?>
            </div>
            <div class="header-nav">
                <?php
                if (empty($_SESSION['auth']) || !$_SESSION['auth'])
                    echo
                    '<a class="header-nav-element" href="auth.php">Войти</a>
                    <a class="spec-nav-element" href="register.php">
                        <span class="header-nav-element">Регистрация</span>
                    </a>
                    <a class="header-nav-element">О сайте</a>';
                else
                {
                    echo
                    '<label for="header-upload" class="label-upload">
                        <img class="upload-image" src="img/upload.png">
                    </label>
                    <input id="header-upload" type="file" multiple>
                        <a href="profile.php?id='.$_SESSION['id'].'"><div class="spec-nav-element gallery-button">
                        <span class="header-nav-element">Моя галерея</span>
                    </div></a>
                    <span class="header-nav-element">О сайте</span>
                    <a class="header-nav-element" href="php/logout.php">Выйти</a>';
                }
                ?>
            </div>
        </header>
        <div class="nav-block">
            <div class="nav-element selected-nav-element">
                Изображения
            </div>
            <div class="nav-element">
                Пользователи
            </div>
        </div>
        <div class="main-search-block">
            <div class="search-nav-block images-block opened-block">
            <?php 

                $query = "select id, extension, user_id from all_images where header like '%".$_GET['value']."%' and permission=1";

                // if ($_SESSION['id'] != $_GET['id'])
                //     $query .= ' and permission = 1';  

                $result = mysqli_query($connection, $query);
    
                $images_count = mysqli_num_rows($result);

                $images_block = '<ul id="images-unordered-list">';
                    
                for ($i = 0; $i < $images_count; $i++)
                {
                    $row = mysqli_fetch_row($result);
                    
                    $link = 'uploaded/'.$row[2].'/'.$row[0].'.'.$row[1];
                    $images_block .= '<li data-id="'.$row[0].'"><a href="image.php?id='.$row[0].'&search='.$_GET['value'].'"><img src="'.$link.'"></a></li> ';
                }

                $images_block .= '</ul>';

                echo $images_block;
            ?>
            </div>
            <div class="search-nav-block users-block">
                <div class="users-profiles">
                <?php
                    $query = "select id, username from users where username like '%".$_GET['value']."%'";

                    $result = mysqli_query($connection, $query);

                    for ($i = 0; $i < mysqli_num_rows($result); $i++)
                    {
                        $row = mysqli_fetch_row($result);

                        echo '<a href="profile.php?id='.$row[0].'" class="user-profile">
                        <img class="user-avatar" src="img/the-cat.png">
                        <span class="user-name">'.$row[1].'</span>
                        </a>';
                    }
                ?>
                </div>
            </div>
        </div>
        <div id="loading-status" class="loading-status">            
        </div>
        <script type="text/javascript" src="js/jquery-3.5.1.min.js"></script>
        <script type="text/javascript" src="js/header-actions.js"></script>
        <script type="text/javascript" src="js/search.js"></script>
    </body>
</html>