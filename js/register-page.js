var username = document.getElementById('username')
var email = document.getElementById('email')
var password = document.getElementById('password')
var repeatPassword = document.getElementById('repeat-password')
var regButton = document.getElementById('reg-button')
var shadowObject = document.getElementById('modal-shadow')
var messageHeader = document.getElementById('message-header-text')
var closeModal = document.getElementById('close-modal-window')
var messageText = document.getElementById('message-text')

window.onclick = function(event) 
{
    if (event.target == shadowObject) {
        shadowObject.style.display = 'none'
    }
}

closeModal.onclick = function()
{
    shadowObject.style.display = 'none'
}

regButton.onclick = function()
{
    if (username.value.match(/^(\w+){4,}$/) && username.value.length <= 20)
    {
        $.ajax({
            url: '../php/checklog.php',
            type: 'POST',
            data: ({username: username.value}),
            dataType: 'html',
            success: function(data)
            {
                if (data == 0)
                {
                    messageHeader.textContent = "Пользователь существует"
                    messageText.textContent = "Пользователь с таким именем уже существует в системе"
                    shadowObject.style.display = 'block'
                }
                else
                {
                    if (email.value.match(/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/))
                    {
                            $.ajax({
                                url: '../php/checkmail.php',
                                type: 'POST',
                                data: ({email: email.value}),
                                dataType: 'html',
                                success: function(data)
                                {
                                    if (data == 0)
                                    {
                                        messageHeader.textContent = "E-mail занят"
                                        messageText.textContent = "Пользователь с таким адресом электронной почты уже зарегистрирован в системе"
                                        shadowObject.style.display = 'block'
                                    }
                                    else
                                    {
                                        if (password.value.match(/(?=.*\d)(?=.*[A-Z]).{8,}/) && password.value.length <= 20)
                                        {
                                            if (password.value == repeatPassword.value)
                                                $.ajax({
                                                    url: '../php/register-script.php',
                                                    type: 'POST',
                                                    data: ({username: username.value, email: email.value, password: password.value, repeatPassword:repeatPassword.value}),
                                                    dataType: 'html',
                                                    success: function(data)
                                                    {
                                                        if (data == 1)
                                                        {
                                                            window.location.replace("index.php")
                                                        }
                                                        else
                                                        {
                                                            messageHeader.textContent = "Регистрация не удалась"
                                                            messageText.textContent = "Не удалось зарегистрироваться"
                                                            shadowObject.style.display = 'block'
                                                        }
                                                    }
                                                })
                                            else
                                            {
                                                messageHeader.textContent = "Пароли не совпадают"
                                                messageText.textContent = "Введённые вами пароли не совпадают"
                                                shadowObject.style.display = 'block'
                                            }
                                        }
                                        else
                                        {
                                            messageHeader.textContent = "Некорректный пароль"
                                            messageText.textContent = "Пароль должен содержать хотя бы одну букву в верхнем регистре и цифру\nДлина пароля должна составлять от 8 до 20 символов"
                                            shadowObject.style.display = 'block'
                                        }
                                    }
                                }
                        })
                    }
                    else
                    {
                        messageHeader.textContent = "Некорректный e-mail"
                        messageText.textContent = "Введён некорректный адрес электронной почты"
                        shadowObject.style.display = 'block'
                    }
                }
            }
        })
    }
    else
    {
        messageHeader.textContent = "Некорректное имя"
        messageText.innerHTML = 'Имя пользователя должно состоять только из латинских букв и цифр <br> <br> Длина имени должна составлять от 4 до 20 символов'
        shadowObject.style.display = 'block'
    }
}