<?php

  require_once("php/connection.php");

  if (empty($_GET['id']))
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

  $query = "select user_id, permission from images WHERE id=".$_GET['id'];

    $result = mysqli_query($connection, $query);

    if (mysqli_num_rows($result) == 0)
    {
        header('Location: index.php');
        exit();
    }

    $row = mysqli_fetch_row($result);

    $user_id = $row[0];

    if ($row[1] != 1 && $_SESSION['id'] != $user_id)
    {
        header('Location: index.php');
        exit();
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
        <link rel="stylesheet" href="css/image.css">
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
                <?php echo '<input type="text" class="search-field" value="'.$_GET['search'].'" placeholder="Поиск по пользователям и заголовкам">'; ?>
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
        <div class="image-view-block">
            <?php

                $is_album_query = "";
                $is_album_link = "";

                if (isset($_GET['album']))
                {
                    $is_album_query = " and album_id = ".$_GET['album'];
                    $is_album_link = "&album=".$_GET['album'];
                }

                $check_permission = "";

                if ($_SESSION['id'] != $user_id)
                    $check_permission = " and permission = 1";

                $query = "";

                if (!isset($_GET['search']))
                    $query .= "(select id, extension, create_data, header, description, permission, album_id, username from all_images WHERE id<".$_GET['id']." and user_id = ".$user_id.$is_album_query.$check_permission." order by id desc limit 1) union ";

                $query .= "(select id, extension, create_data, header, description, permission, album_id, username from all_images WHERE id=".$_GET['id']." and user_id = ".$user_id.$is_album_query.")";

                if (!isset($_GET['search']))
                    $query .=  "union (select id, extension, create_data, header, description, permission, album_id, username from all_images where id>".$_GET['id']." and user_id = ".$user_id.$is_album_query.$check_permission." order by id limit 1)";

                $result = mysqli_query($connection, $query);

                for ($i = 0; $i < mysqli_num_rows($result); $i++)
                {
                    $row = mysqli_fetch_row($result);
                    for ($j = 0; $j < 8; $j++)
                        $images_array[$i][$j] = $row[$j];
                }

                $main_image_part = "";

                if ($images_array[0][0] != $_GET['id'])
                {
                    $main_image_part .= '<a href="image.php?id='.$images_array[0][0].$is_album_link.'" class="prev-arrow arrow"><img src="img/prev-arrow.png"></a> ';
                    
                    $main_image_index = 1;
                }
                else
                    $main_image_index = 0;

                $main_image_part .= '<img class="page-image" src="uploaded/'.$user_id.'/'.$images_array[$main_image_index][0].'.'.$images_array[$main_image_index][1].'">';
                
                if (count($images_array) == 3 || $main_image_index == 0 && count($images_array) == 2)
                    $main_image_part .= '<a href="image.php?id='.$images_array[$main_image_index + 1][0].$is_album_link.'" class="next-arrow arrow"><img src="img/next-arrow.png"></a>';
                
                $main_image_part .= '<div class="image-nav">
                <img class="share-button" src="img/share.png">
                <img class="download-button" src="img/download-white.png">
                <a href="uploaded/'.$user_id.'/'.$images_array[$main_image_index][0].'.'.$images_array[$main_image_index][1].'" target="_blank"><img class="full-screen-button" src="img/full-screen.png"></a>
                </div>';

                echo $main_image_part;
            ?>
            <div class="back-to-gallery">
                    <?php
                    if (isset($_GET['search']))
                    {
                        $link = 'search.php?value='.$_GET['search'];
                        $return_text = 'Вернуться к поиску';
                    }
                    else if (isset($_GET['album']))
                    {
                        $link = 'album.php?id='.$_GET['album'];
                        $return_text = 'Вернуться к альбому';
                    }
                    else
                    {
                        $link = 'profile.php?id='.$user_id;
                        $return_text = 'Вернуться в галерею';
                    }

                    echo 
                    '<a href="'.$link.'">
                        <img src="img/back-arrow.png">
                        <span>'.$return_text.'</span>
                    </a>';
                    ?>
            </div>
        </div>
        <div class="image-lower-block">
            <div class="image-lower-left">
                <div class="image-info" <?php 
                    if (empty($images_array[$main_image_index][4]))
                        echo 'style="margin-bottom:calc(0.8px + (5 - 0.8) * ((100vw - 320px) / (1920 - 320)));"';
                ?>>
                    <img class="image-owner-avatar" src="img/the-cat.png">
                    <div class="right-info-block">
                        <span class="image-username"><?php echo $images_array[$main_image_index][7]?></span>
                        <span class="image-header"><?php echo $images_array[$main_image_index][3]?></span>
                        <?php
                            if (!empty($images_array[$main_image_index][4]))
                            {
                                echo '<span class="image-description">'.$images_array[$main_image_index][4].'</span>';
                            }
                        ?>
                    </div>
                </div>
                <div class="gray-break"></div>
                <div class="comment-block">
                    <div class="all-comments">
                        <?php

                            $query = "select id, user_id, username, text, create_date from all_comments where image_id=".$_GET['id'];

                            $result = mysqli_query($connection, $query);

                            for ($i = 0; $i < mysqli_num_rows($result); $i++)
                            {
                                $row = mysqli_fetch_row($result);

                                echo '<div data-id="'.$row[0].'" class="comment">
                                <img src="img/the-cat.png" class="comment-avatar">
                                <div class="comment-name-and-text">
                                    <span class="comment-name">'.$row[2].'</span>
                                    <div class="comment-text">'.$row[3].'</div>
                                </div>
                                <span class="comment-date">'.$row[4].'</span>
                            </div>';
                            }

                        ?>
                    </div>
                    <?php

                        if ($_SESSION['auth'])
                        {
                            echo '<div class="new-comment-block">
                            <img src="img/the-cat.png" class="comment-avatar new-comment-avatar">
                            <textarea class="comment-text-input" placeholder="Оставьте комментарий"></textarea>
                            <img class="send-comment-button" src="img/send-gray.png">
                            </div>';
                        }

                    ?>
                </div>
            </div>
            <div class="image-lower-right">
                <span class="image-loaded-date"><?php 
                echo 'Загружено: '.$images_array[$main_image_index][2]?></span>
                <?php 
                    
                    if (!empty($images_array[$main_image_index][6]))
                    {
                        $query = "select name from albums where id=".$images_array[$main_image_index][6];

                        $result = mysqli_query($connection, $query);

                        $row = mysqli_fetch_row($result);

                        echo '<span class="image-album">Альбом: '.$row[0].'</span>';
                    }

                ?>
            </div>
        </div>
        <div id="loading-status" class="loading-status">            
        </div>
        <script type="text/javascript" src="js/jquery-3.5.1.min.js"></script>
        <script type="text/javascript" src="js/header-actions.js"></script>
        <script type="text/javascript" src="js/image.js"></script>
    </body>
</html>