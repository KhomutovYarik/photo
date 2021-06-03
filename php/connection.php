<?php
	$connection = mysqli_connect("127.0.0.1:3306", "root", "", "photos");
    if ($connection == false)
    {
        echo 'Не удалось подключиться!';
        exit();
    }
?>