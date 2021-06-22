var uploadInput = document.getElementById('header-upload')
var loadingStatus = document.getElementById('loading-status')
var cameraRoll = document.getElementsByClassName('roll-block')[0]
var selectedImagesCounter = document.getElementById('selected-images-count')
var selectedImages = document.getElementsByClassName('selected-image')
var manageElements = document.getElementsByClassName('manage-images')[0]
var imagesCount = document.getElementsByClassName('images-count')[0]
var imagesUL = document.getElementById('images-unordered-list')
var searchField = document.getElementsByClassName('search-field')[0]

searchField.addEventListener('keydown', function(e) {
    if (e.keyCode === 13) {
        if (searchField.value.length > 2)
            window.location.href = 'search.php?value=' + searchField.value
        else
            alert('Длина поискового запроса должна составлять не менее трёх символов')
    }
})


var validExtensions = {
    jpg: true,
    jpeg: true,
    png: true
}

function createDateBlock(msg)
{
    var newRollDate = document.createElement('div')
    newRollDate.className = 'roll-data-block'
    var rollInfo = document.createElement('div')
    rollInfo.className = 'roll-info-block'
    var dateLabel = document.createElement('span')
    dateLabel.className = 'uploaded-data'
    dateLabel.textContent = msg
    var selectAllLabel = document.createElement('span')
    selectAllLabel.className = 'select-all'
    selectAllLabel.textContent = 'Выбрать все'
    var imageBlock = document.createElement('div')
    imageBlock.className = 'image-block'

    rollInfo.appendChild(dateLabel)
    rollInfo.appendChild(selectAllLabel)
    newRollDate.appendChild(rollInfo)
    newRollDate.appendChild(imageBlock)

    cameraRoll.prepend(newRollDate)

    selectAllAction(selectAllLabel)

    return newRollDate
}

function selectImage(image)
{
    image.onclick = function()
    {
        if (image.classList[1][0] == 'n')
        {
            image.classList.remove('not-selected-image')
            image.classList.add('selected-image')
        }
        else
        {
            image.classList.remove('selected-image')
            image.classList.add('not-selected-image')
        }
        selectedImagesCounter.textContent = selectedImages.length
        if (selectedImages.length == 0)
            manageElements.style.display = 'none'
        else
            manageElements.style.display = 'block'
    }
}

function selectAllAction(selectAllElement, num)
{
    selectAllElement.onclick = function()
    {
        var imagesInside = document.querySelectorAll('.roll-data-block:nth-child(' + num + ') .roll-image')
        for (let j = 0; j < imagesInside.length; j++)
        {
            imagesInside[j].classList.remove('not-selected-image')
            imagesInside[j].classList.add('selected-image')
        }
        selectedImagesCounter.textContent = selectedImages.length;
        manageElements.style.display = 'block'
    }
}

uploadInput.onchange = function()
{
    if (uploadInput.files.length != 0)
        if (uploadInput.files.length < 11)
        {
            for (let i = 0; i < uploadInput.files.length; i++)
            {
                var filename = uploadInput.files[i].name;
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

                        msg = JSON.parse(data)

                        if (typeof cameraRoll !== 'undefined')
                        {
                            var lastRollDate = document.getElementsByClassName('roll-data-block')[0]

                            var date = msg[1].split('-');
                            var curDate = new Date(date[1] + '-' + date[0] + '-' + date[2])                           

                            if (typeof lastRollDate !== 'undefined')
                            {
                                lastRollDateValue = lastRollDate.getElementsByClassName('uploaded-data')[0]

                                var parsedDate = lastRollDateValue.textContent.split('-')
                                var rollDate = new Date(parsedDate[1] + '-' + parsedDate[0] + '-' + parsedDate[2]);

                                if (curDate > rollDate)
                                {
                                    lastRollDate = createDateBlock(msg[1])
                                }
                            }
                            else
                            {
                                lastRollDate = createDateBlock(msg[1])
                            }

                            imageBlockToInsert = lastRollDate.getElementsByClassName('image-block')[0]

                            for (let i = 0; i < msg[0].length; i++)
                            {
                                var filename = uploadInput.files[i].name

                                var newImage = document.createElement('div')
                                newImage.className = 'roll-image not-selected-image'
                                newImage.dataset.permission = 3
                                newImage.dataset.id = msg[0][i]
                                newImage.dataset.header = filename.split('.').slice(0, -1).join('.')
                                
                                var checkMarkDiv = document.createElement('img')
                                checkMarkDiv.className = 'check-mark'
                                checkMarkDiv.src = 'img/check-mark.png'

                                var url = new URL(window.location.href);
                                var userId = url.searchParams.get('id')

                                var srcLink = 'uploaded/' + userId + '/' + msg[0][i] + '.' + filename.substr(filename.lastIndexOf('.')+1)

                                var addedImage = document.createElement('img')
                                addedImage.className = 'image-item'
                                addedImage.src = srcLink

                                newImage.appendChild(checkMarkDiv)
                                newImage.appendChild(addedImage)
                                imageBlockToInsert.prepend(newImage)
                                selectImage(newImage)

                                var listImageElement = document.createElement('li')
                                listImageElement.dataset.id = msg[0][i]
                                var linkImageElement = document.createElement('a')
                                linkImageElement.href = srcLink
                                linkImageElement.target = '_blank'
                                var galleryImage = document.createElement('img')
                                galleryImage.src = srcLink

                                linkImageElement.appendChild(galleryImage)
                                listImageElement.appendChild(linkImageElement)
                                imagesUL.prepend(listImageElement)
                            }

                            imagesCount.textContent = 'Изображений: ' + document.getElementsByClassName('roll-image').length
                        }
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