var uploadInput = document.getElementById('header-upload')
var loadingStatus = document.getElementById('loading-status')

var validExtensions = {
    jpg: true,
    jpeg: true,
    png: true
}

uploadInput.onchange = function()
{
    if (uploadInput.files.length != 0)
        if (uploadInput.files.length < 11)
        {
            for (let i = 0; i < uploadInput.files.length; i++)
            {
                var filename = uploadInput.files[0].name;
                if (!validExtensions[filename.substr(filename.lastIndexOf('.')+1)])
                {
                    loadingStatus.textContent = 'Попытка загрузить файл другого типа'
                    loadingStatus.style.backgroundColor = '#FF3636'
                    loadingStatus.style.display = 'block'
                    setTimeout(() => {
                        loadingStatus.style.display = 'none'
                    }, 3000)
                    return
                }
            }
            loadingStatus.textContent = 'Загружается ' + uploadInput.files.length + ' фото'
            loadingStatus.style.backgroundColor = '#FFF'
            loadingStatus.style.display = 'block'
            var formData = new FormData()
            for (let i = 0; i < uploadInput.files.length; i++)
                formData.append("file_" + i, uploadInput.files[i])
                formData.append("files_length", uploadInput.files.length)
            $.ajax({
                    url: "php/upload-images.php",
                    type: "POST",
                    dataType: 'text',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    success: function(data)
                    {
                        loadingStatus.textContent = 'Фото загружены'
                        loadingStatus.style.backgroundColor = '#2B99FF'
                        loadingStatus.style.display = 'block'
                        setTimeout(() => {
                        loadingStatus.style.display = 'none'
                    }, 3000)
                }
            })  
        }
    else
    {
        loadingStatus.textContent = 'Выбрано более десяти файлов'
        loadingStatus.style.backgroundColor = '#FF3636'
        loadingStatus.style.display = 'block'
        setTimeout(() => {
            loadingStatus.style.display = 'none'
        }, 3000)
    }   
}