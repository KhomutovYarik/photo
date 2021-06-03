var email = document.getElementById('email')
var password = document.getElementById('password')
var rememberBox = document.getElementById('remember-box')
var authButton = document.getElementById('auth-button')
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

authButton.onclick = function()
{
    if (email.value.match(/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/) && password.value.length >= 8 && password.value.length <= 20) {
        var rememberValue
        if (rememberBox.checked)
            rememberValue = 1
        else
            rememberValue = 0
        $.ajax({
            url: '../php/auth-script.php',
            type: 'POST',
            data: ({email: email.value, password: password.value, remember: rememberValue}),
            dataType: 'html',
            success: function(data)
            {   
                if (data == 1)
                {
                    window.location.replace("index.php")
                }
                else
                {
                    messageHeader.textContent = "Неверные данные"
                    messageText.textContent = 'Пользователь с такой парой e-mail и пароль не существует в системе'
                    shadowObject.style.display = 'block'
                }
            }
        })
    }
    else {
        messageHeader.textContent = "Заполните форму"
        messageText.innerHTML = 'Введите Ваш e-mail адрес <br> <br> Длина пароля должна составлять от 8 до 20 символов'
        shadowObject.style.display = 'block'
    }
}