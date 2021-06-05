<?php

  require_once("php/connection.php");

  if (empty($_GET['id']))
    header('Location: index.php');

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

  function check_tab($num)
  {
        if (!empty($_SESSION['id']) && !empty($_GET['tab']) && ($num == $_GET['tab'] || $num == 1 && ($_GET['tab'] < 1 || $_GET['tab'] > 4)) || (empty($_GET['tab']) || empty($_SESSION['id'])) && $num == 1)
            return " selected-nav-element";
        else
            return "";
  }

  function check_block($num)
  {
        if (!empty($_SESSION['id']) && !empty($_GET['tab']) && ($num == $_GET['tab'] || $num == 1 && ($_GET['tab'] < 1 || $_GET['tab'] > 4)) || (empty($_GET['tab']) || empty($_SESSION['id'])) && $num == 1)
            return " opened-block";
        else
            return "";
  }

  $user_id = $_GET['id'];

  if (!empty($_SESSION['username']))
        $username = $_SESSION['username'];
  else
  {
        $query = "select username from users where id=$user_id";
        $result = mysqli_query($connection, $query);

        if (mysqli_num_rows($result) > 0)
            $username = mysqli_fetch_row($result)[0];
        else
            header('Location: index.php');
  }

  $query = "select count(*) from images where user_id=$user_id";

  $result = mysqli_query($connection, $query);

  $images_count = mysqli_fetch_row($result)[0];

?>
<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>photos</title>
        <link rel="stylesheet" href="css/font-file.css">
        <link rel="stylesheet" href="css/header-style.css">
        <link rel="stylesheet" href="css/profile.css">
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
                if (empty($_SESSION['auth']) || !$_SESSION['auth'])
                    echo
                    '<a class="header-nav-element" href="auth.php">Войти</a>
                    <a class="spec-nav-element" href="register.php">
                        <span class="header-nav-element">Регистрация</span>
                    </a>
                    <a class="header-nav-element">О сайте</a>';
                else
                {
                    if (!empty($_SESSION['id']) && $_GET['id'] == $_SESSION['id'])
                        $is_our_profile = "selected-gallery";
                    else
                        $is_our_profile = "";
                    echo
                    '<label for="header-upload" class="label-upload">
                        <img class="upload-image" src="img/upload.png">
                    </label>
                    <input id="header-upload" type="file" multiple>
                        <a href="profile.php?id='.$_SESSION['id'].'"><div class="spec-nav-element gallery-button '.$is_our_profile.'">
                        <span class="header-nav-element">Моя галерея</span>
                    </div></a>
                    <span class="header-nav-element">О сайте</span>
                    <a class="header-nav-element" href="php/logout.php">Выйти</a>';
                }
                ?>
            </div>
        </header>
        <div class="profile-info-block">
            <img class="profile-image" src="img/the-cat.png">
            <div class="profile-info">
                <span class="profile-username"><?php echo $username?></span>
                <span class="images-count">Изображений: <?php echo $images_count?></span>
            </div>
        </div>
        <div class="profile-nav-block">
            <?php
                $profile_navs = '<div class="profile-nav-element'.check_tab(1).'">
                    Изображения
                </div>
                <div class="profile-nav-element'.check_tab(2).'">
                    Альбомы
                </div>';
                if (!empty($_SESSION['id']) && $_GET['id'] == $_SESSION['id'])
                    $profile_navs.='<div class="profile-nav-element'.check_tab(3).'">
                    Настройки
                    </div>
                    <div class="profile-nav-element'.check_tab(4).'">
                        Фотоплёнка
                    </div>';

                echo $profile_navs;
            ?>
        </div>
        <div class="main-profile-block">
            <?php 
                $profile_blocks = '<div class="profile-block images-block'.check_block(1).'">

                </div>
                <div class="profile-block albums-block'.check_block(2).'">
                        
                </div>';

                echo $profile_blocks;

                if (!empty($_SESSION['id']) && $_GET['id'] == $_SESSION['id'])
                {
                    $settings_block.='<div class="profile-block settings-block'.check_block(3).'">        
                    </div>';
                    echo $settings_block;

                    $roll_block = '<div class="profile-block roll-block'.check_block(4).'">';

                    $query = "select id, extension, create_data from all_images WHERE user_id=".$_SESSION['id'];

                    $result = mysqli_query($connection, $query);

                    $count_rows = mysqli_num_rows($result);

                    $last_data = "";

                    for ($i = 0; $i < $count_rows; $i++)
                    {
                        $row = mysqli_fetch_row($result);

                        if ($row[2] != $last_data)
                        {
                            if ($last_data != "")
                                $roll_block .= '
                                    </div>
                                </div>';
                            $roll_block .= '<div class="roll-data-block">
                            <div class="roll-info-block">
                                <span class="uploaded-data">'.$row[2].'</span>
                                <span class="select-all">Выбрать все</span>
                            </div><div class="image-block">
                            <div data-id="'.$row[0].'" class="roll-image not-selected-image">
                                <img class="check-mark" src="img/check-mark.png">
                                <img class="image-item" src="uploaded/'.$_SESSION['id'].'/'.$row[0].'.'.$row[1].'">
                            </div>';
                            $last_data = $row[2];
                        }
                        else
                        {
                            $roll_block .= '
                            <div data-id="'.$row[0].'" class="roll-image not-selected-image">
                                <img class="check-mark" src="img/check-mark.png">
                                <img class="image-item" src="uploaded/'.$_SESSION['id'].'/'.$row[0].'.'.$row[1].'">
                            </div>';
                        }
                    }
                    $roll_block .= '</div>
                        </div>
                    </div>';

                    echo $roll_block;
                }
            ?>
        </div>
        <div class="manage-images">
            <div class="manage-info">
                <div class="selected-images-count-block">
                    Выбрано: <span id="selected-images-count"></span>
                </div>
                <div id="clear-selected-images">
                    Снять выделение
                </div>
            </div>
            <div class="gray-break"></div>
            <div class="manage-elements">
                <div id="edit-button">
                    <img src="img/edit.png"><span>Изменить</span>
                </div>
                <div id="add-to-album-button">
                    <img src="img/add-to-album.png"><span>Добавить в альбом</span>
                </div>
                <div id="download-button">
                    <img src="img/download.png"><span>Скачать</span>
                </div>
                <div id="delete-button">
                    <img src="img/delete.png"><span>Удалить</span>
                </div>
            </div>
        </div>
        <div id="loading-status" class="loading-status">
            Загружается 5 файлов...
        </div>
        <script type="text/javascript" src="js/jquery-3.5.1.min.js"></script>
        <script type="text/javascript" src="js/header-actions.js"></script>
        <script type="text/javascript" src="js/profile-scripts.js"></script>
    </body>
</html>