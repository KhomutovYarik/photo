<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Photo</title>
        <link rel="stylesheet" href="css/main-page.css">
    </head>
    <body>
        <header class="default-header">
            <div class="header-logo">
                <span class="logo-text">photos</span>
                <div class="logo-circle first-logo-circle"></div>
                <div class="logo-circle second-logo-circle"></div>
            </div>
            <div class="search-block">
                <img src="img/zoom.png">
                <input type="text" class="search-field" placeholder="Поиск по пользователям и заголовкам">
            </div>
            <div class="header-nav">
                <?php
                if (true)
                    echo
                    '<span class="header-nav-element">Войти</span>
                    <div class="spec-nav-element">
                        <span class="header-nav-element">Регистрация</span>
                    </div>
                    <span class="header-nav-element">О сайте</span>';
                else
                    echo 
                    '<img src="img/upload.png">
                    <div class="spec-nav-element">
                        <span class="header-nav-element">Моя галерея</span>
                    </div>
                    <span class="header-nav-element">О сайте</span>
                    <span class="header-nav-element">Выйти</span>';
                ?>
            </div>
        </header>
    </body>
</html>