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
            <input class="reg-auth-input" type="text" placeholder="E-mail адрес" name="email">
            <input class="reg-auth-input" type="text" placeholder="Пароль" name="password">
            <div class="remember-data-block">
                <input class="remember-box" type="checkbox" name="remember-data">
                <span>Запомнить мои данные</span>
            </div>
            <button class="reg-auth-button auth-button">
                Выполнить вход
            </button>
            <a class="bottom-form-label bottom-form-link" href="#">Забыли пароль?</a>
            <div class="gray-break"></div>
            <span class="bottom-form-label">
                Ещё нет аккаунта? <a class="bottom-form-link" href="register.php">Регистрация</a>
            </span>
        </form>
    </body>
</html>