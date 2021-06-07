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

                $query = "select id, extension, create_data, header, description, permission from all_images WHERE user_id=".$_SESSION['id'];

                $result = mysqli_query($connection, $query);

                $images_count = mysqli_num_rows($result);

                for ($i = 0; $i < $images_count; $i++)
                {
                    $row = mysqli_fetch_row($result);
                    for ($j = 0; $j < 6; $j++)
                        $images_array[$i][$j] = $row[$j];
                }

                $images_block = "";

                for ($i = 0; $i < $images_count; $i++)
                {
                    $link = 'uploaded/'.$_GET['id'].'/'.$images_array[$i][0].'.'.$images_array[$i][1];
                    if (($i + 1) % 2 == 1)
                        $images_block .= '<div class="images-row-block"> ';
                    $images_block .= '<a href="'.$link.'" target="_blank"><img src="'.$link.'"></a> ';
                    if (($i + 1) % 2 == 0 || $i == $images_count - 1)
                        $images_block .= '</div>';
                }

                $profile_blocks = '<div class="profile-block images-block'.check_block(1).'">'.$images_block.'</div>
                <div class="profile-block albums-block'.check_block(2).'">
                        
                </div>';

                echo $profile_blocks;

                if (!empty($_SESSION['id']) && $_GET['id'] == $_SESSION['id'])
                {
                    $settings_block.='<div class="profile-block settings-block'.check_block(3).'">        
                    </div>';
                    echo $settings_block;

                    $roll_block = '<div class="profile-block roll-block'.check_block(4).'">';

                    $last_data = "";

                    function addToRollBlock($row1, $row2, $row4, $row5, $row6)
                    {
                        $string = '<div data-id="'.$row1.'" data-header="'.$row4.'" ';
                        if (!empty($row5))
                            $string .= 'data-description="'.$row5.'" '; 
                        $string .= 'data-permission="'.$row6.'" class="roll-image not-selected-image">
                        <img class="check-mark" src="img/check-mark.png">
                        <img class="image-item" src="uploaded/'.$_SESSION['id'].'/'.$row1.'.'.$row2.'">
                        </div>';
                        
                        return $string;
                    }

                    for ($i = 0; $i < $images_count; $i++)
                    {

                        if ($images_array[$i][2] != $last_data)
                        {
                            if ($last_data != "")
                                $roll_block .= '
                                    </div>
                                </div>';
                            $roll_block .= '<div class="roll-data-block">
                            <div class="roll-info-block">
                                <span class="uploaded-data">'.$images_array[$i][2].'</span>
                                <span class="select-all">Выбрать все</span>
                            </div><div class="image-block">';
                            $roll_block .= addToRollBlock($images_array[$i][0], $images_array[$i][1], $images_array[$i][3], $images_array[$i][4], $images_array[$i][5]);
                            $last_data = $images_array[$i][2];
                        }
                        else
                        {
                            $roll_block .= addToRollBlock($images_array[$i][0], $images_array[$i][1], $images_array[$i][3], $images_array[$i][4], $images_array[$i][5]);
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
        <div id="modal-shadow" class="shadow-screen">
            <div class="modal-message">
                <div class="modal-window-type">
                    <div class="modal-header-block">
                        <span id="modal-main-header">Редактирование изображения</span>
                        <img class="close-modal-window" src="img/cross.png">
                    </div>
                    <div class="gray-break"></div>
                    <input class="modal-name-input" type="text" placeholder="Заголовок изображения">
                    <textarea class="modal-desc-input" placeholder="Описание (необязательно)"></textarea>
                    <div class="access-modal-block">
                        <span class="access-modal-label">Доступность: </span>
                        <select class="modal-access-combobox">
                            <option value="1">Всем</option>
                            <option value="2">По ссылке</option>
                            <option value="3">Закрытый</option>
                        </select>
                    </div>
                    <div class="modal-button-block">
                        <input class="cancel-button" type="button" value="Отмена">
                        <input class="accept-button" type="button" value="Принять">
                    </div>
                </div>
                <div class="modal-window-type album-modal">
                    <div class="modal-header-block">
                        <span id="modal-main-header">Выберите альбом</span>
                        <img class="close-modal-window" src="img/cross.png">
                    </div>
                    <div class="gray-break"></div>
                    <div class="albums-modal-block">
                        <?php

                            $query = "select id, name, description, create_date, permission, image_id, extension_name, count from all_albums where user_id=".$_SESSION['id'];

                            $result = mysqli_query($connection, $query);

                            $count_rows = mysqli_num_rows($result);

                            for ($i = 0; $i < $count_rows; $i++)
                            {
                                $row = mysqli_fetch_row($result);
                                
                                echo '<div data-id="'.$row[0].'" class="album-element">
                                <img class="album-img" src="uploaded/'.$_SESSION['id'].'/'.$row[5].'.'.$row[6].'">
                                <div class="album-element-info">
                                    <span class="album-element-name">'.$row[1].'</span>
                                    <span class="album-element-count">Изображений: '.$row[7].'</span>
                                </div>
                                <img class="check-mark-album" src="img/selected-album.png">
                                </div>';
                            }
                        ?>
                    </div>
                    <div class="gray-break"></div>
                    <div class="modal-button-block">
                        <div class="create-new-album">
                            <img src="img/create-album.png">
                            <span class="create-new-album-label">
                                Создать новый альбом
                            </span>
                        </div>
                        <input class="cancel-button" type="button" value="Отмена">
                        <input class="accept-button" type="button" value="Принять">
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript" src="js/jquery-3.5.1.min.js"></script>
        <script type="text/javascript" src="js/header-actions.js"></script>
        <script type="text/javascript" src="js/profile-scripts.js"></script>
    </body>
</html>