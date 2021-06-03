<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Регистрация на photos</title>
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
        <form class="reg-form">
            <div class="logo-elipses">
                <div class="logo-circle first-logo-circle"></div>
                <div class="logo-circle second-logo-circle"></div>
            </div>
            <span class="reg-label">Регистрация на photos</span>
            <input class="reg-auth-input" type="text" placeholder="Имя пользователя" name="username">
            <input class="reg-auth-input" type="text" placeholder="E-mail адрес" name="email">
            <input class="reg-auth-input" type="text" placeholder="Пароль" name="password">
            <input class="reg-auth-input" type="text" placeholder="Повтор пароля" name="repeat-password">
            <button class="reg-auth-button">
                Зарегистрироваться
            </button>
            <span class="bottom-form-label">Зарегистрировавшись, вы соглашаетесь с <br>
                <a class="bottom-form-link" href="#">условиями предоставления услуг</a> и <br>
                <a class="bottom-form-link" href="#">политикой конфиденциальности</a>
            </span>
            <div class="gray-break"></div>
            <span class="bottom-form-label">
                Уже есть аккаунт? <a class="bottom-form-link" href="auth.php">Войти</a>
            </span>
        </form>
    </body>
</html>