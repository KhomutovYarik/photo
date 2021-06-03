<?php

  require_once("connection.php");

  function getRandString($num)
  {
      //Генерим массив из букв
      $letter = range('a', 'z');
      //Генерим массив из цифр
      $number = range(0, 9);
      //Создаем строку с маленькими и большими буквами и цифрами
      $letter = implode('',$letter);
      $letter = $letter.strtoupper($letter).implode('',$number);
      //Строка с генерированым паролем
      $randStr = '';
      for ($i = 0; $i < $num; $i++){
          //Прогоняем циклом столько, сколько нужно символов в строке
          $randStr .= $letter[rand(0, strlen($letter) - 1)];
      }
      return $randStr;
  }

  if (!empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['repeatPassword']) && $_POST['password'] == $_POST['repeatPassword'])
  {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "select * from users where username='$username' or email='$email'";

    $count = mysqli_num_rows(mysqli_query($connection, $query));

    if ($count == 0)
    {
      $salt = getRandString(10);
      $hashPassword = md5($password.$salt);
      $cookie = getRandString(20);

      $query = "INSERT INTO users (username, email, salt, hash, cookie) values ('$username', '$email', '$salt', '$hashPassword', '$cookie')";

      $status = mysqli_query($connection, $query);

      if ($status)
      {
        session_start();

        $_SESSION['auth'] = true;
        $_SESSION['id'] = $connection->insert_id;
        $_SESSION['username'] = $username;

        setcookie('username', $username, time() + 60*60*24*365, '/');
        setcookie('key', $cookie, time() + 60*60*24*365, '/');
      }

      echo $status;
    }
    else
      echo '0';
  }
  else
    echo '0';

  mysqli_close($connection);

?>