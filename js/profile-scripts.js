var navElements = document.getElementsByClassName('profile-nav-element')
var profileBlocks = document.getElementsByClassName('profile-block')
var selectedNav = document.getElementsByClassName('selected-nav-element')
var openedBlock = document.getElementsByClassName('opened-block')
var images = document.getElementsByClassName('roll-image')
var selectAll = document.getElementsByClassName('select-all')
var selectedImages = document.getElementsByClassName('selected-image')
var imagesList = imagesUL.getElementsByTagName('li')

var manageElements = document.getElementsByClassName('manage-images')[0]
var imageCount = document.getElementsByClassName('images-count')[0]
var selectedImagesCounter = document.getElementById('selected-images-count')
var clearSelection = document.getElementById('clear-selected-images')

for (let i = 0; i < navElements.length; i++)
{
    navElements[i].onclick = function()
    {
        selectedNav[0].classList.remove('selected-nav-element')
        openedBlock[0].classList.remove('opened-block')
        navElements[i].classList.add('selected-nav-element')
        profileBlocks[i].classList.add('opened-block')
    }
}

for (let i = 0; i < images.length; i++)
{
    selectImage(images[i])
}

for (let i = 0; i < selectAll.length; i++)
{
    selectAllAction(selectAll[i], i + 1)
}

function clear()
{
    for (let i = selectedImages.length - 1; i >= 0; i--)
    {
        selectedImages[i].classList.add('not-selected-image')
        selectedImages[i].classList.remove('selected-image')
    }
    manageElements.style.display = 'none'
}

clearSelection.onclick = clear

var editButton = document.getElementById('edit-button')
var addToAlbumButton = document.getElementById('add-to-album-button')
var downloadButton = document.getElementById('download-button')
var deleteButton = document.getElementById('delete-button')

var closeModalButtons = document.getElementsByClassName('close-modal-window')
var shadowObject = document.getElementById('modal-shadow')
var cancelButtons = document.getElementsByClassName('cancel-button')
var acceptButtons = document.getElementsByClassName('accept-button')

var modalMainHeader = document.getElementById('modal-main-header')
var modalNameInput = document.getElementsByClassName('modal-name-input')[0]
var modalDescInput = document.getElementsByClassName('modal-desc-input')[0]
var modalPermissionInput = document.getElementsByClassName('modal-access-combobox')[0]
var modalWindowTypes =  document.getElementsByClassName('modal-window-type')

var albumElements = document.getElementsByClassName('album-element')
var selectedAlbumElements = document.getElementsByClassName('selected-album-element')
var checkMarksAlbums = document.getElementsByClassName('check-mark-album')
var activeCheckMarkAlbums = document.getElementsByClassName('visible-check-mark')
var createAlbumButton = document.getElementsByClassName('create-new-album')[0]
var albumsListBlock = document.getElementsByClassName('albums-modal-block')[0]

function closeModal()
{
    shadowObject.style.display = 'none'
}

function openModal()
{
    shadowObject.style.display = 'block'
}

function chooseImageModal(i)
{
    modalMainHeader.textContent = "Редактирование изображения"
    if (i == 0)
    {
        modalNameInput.placeholder = "Заголовок изображения"
        modalDescInput.placeholder = "Описание (необязательно)"
    }
    else
    {
        modalNameInput.placeholder = "Изменить все заголовки"
        modalDescInput.placeholder = "Изменить все описания"
        modalNameInput.value = ""
        modalDescInput.value = ""
        modalPermissionInput.value = 1
    }
    openModal()
}

function chooseAlbumModal(i)
{
    if (i == 0)
        modalMainHeader.textContent = "Создание альбома"
    else
        modalMainHeader.textContent = "Редактирование альбома"
    modalNameInput.placeholder = "Название альбома"
    modalDescInput.placeholder = "Описание (необязательно)"
    modalNameInput.value = ""
    modalDescInput.value = ""
    openModal()
}

window.onclick = function(event) 
{
    if (event.target == shadowObject)
        closeModal()
}

for (let i = 0; i < closeModalButtons.length; i++)
{
    closeModalButtons[i].onclick = closeModal
}

for (let i = 0; i < cancelButtons.length; i++)
{
    cancelButtons[i].onclick = closeModal
}

editButton.onclick = function()
{
    shadowObject.dataset.type = 1
    modalWindowTypes[1].style.display = 'none'
    modalWindowTypes[0].style.display = 'flex'
    if (selectedImages.length == 1)
    {
        modalNameInput.value = selectedImages[0].dataset.header
        modalPermissionInput.value = selectedImages[0].dataset.permission
        if (typeof selectedImages[0].dataset.description !== 'undefined')
            modalDescInput.value = selectedImages[0].dataset.description
        else
            modalDescInput.value = ""
        chooseImageModal(0)
    }
    else
        chooseImageModal(1)
}

addToAlbumButton.onclick = function()
{
    shadowObject.dataset.type = 2
    modalWindowTypes[0].style.display = 'none'
    modalWindowTypes[1].style.display = 'flex'
    openModal()
}

function albumModalSelect(i)
{
    for (let j = activeCheckMarkAlbums.length - 1; j >= 0; j--)
        {
            activeCheckMarkAlbums[j].classList.remove('visible-check-mark')
            selectedAlbumElements[j].classList.remove('selected-album-element')
        }
        checkMarksAlbums[i].classList.add('visible-check-mark')
        albumElements[i].classList.add('selected-album-element')
}

for (let i = 0; i < acceptButtons.length; i++)
    acceptButtons[i].onclick = function()
    {
        let imagesArray = []
        for (let i = 0; i < selectedImages.length; i++)
        {
            imagesArray.push(selectedImages[i].dataset.id)
        }

        var dataFromForm = {images: imagesArray}

        if (shadowObject.dataset.type == 1)
        {
            dataFromForm['permission'] = modalPermissionInput.value

            if (modalNameInput.value != "")
                dataFromForm['header'] = modalNameInput.value
            if (modalDescInput.value != "")
                dataFromForm['description'] = modalDescInput.value

            $.ajax({
                url: '../php/edit-images.php',
                type: 'POST',
                data: dataFromForm,
                dataType: 'html',
                success: function(data)
                {   
                    for (let i = 0; i < selectedImages.length; i++)
                    {
                        selectedImages[i].dataset.header = modalNameInput.value
                        selectedImages[i].dataset.permission = modalPermissionInput.value
                        selectedImages[i].dataset.description = modalDescInput.value
                    }
                    clear()
                    closeModal()
                }
            })
        }
        else if (shadowObject.dataset.type == 2)
        {
            dataFromForm['album_id'] = selectedAlbumElements[0].dataset.id

            $.ajax({
                url: '../php/add-to-album.php',
                type: 'POST',
                data: dataFromForm,
                dataType: 'html',
                success: function(data)
                {   

                    clear()
                    closeModal()
                }
            })
        }
        else if (shadowObject.dataset.type == 3)
        {
            dataFromForm['name'] = modalNameInput.value
            dataFromForm['permission'] = modalPermissionInput.value

            if (modalDescInput.value != "")
                dataFromForm['description'] = modalDescInput.value

                $.ajax({
                    url: '../php/create-album.php',
                    type: 'POST',
                    data: dataFromForm,
                    dataType: 'html',
                    success: function(data)
                    {   
                        var newAlbum = document.createElement('div')
                        newAlbum.className = 'album-element'
                        newAlbum.dataset.id = data

                        var albumImage = document.createElement('img')
                        albumImage.className = 'album-img'
                        albumImage.src = selectedImages[0].getElementsByClassName('image-item')[0].src
                        newAlbum.appendChild(albumImage)

                        var infoBlock = document.createElement('div')
                        infoBlock.className = 'album-element-info'
                        var albumName = document.createElement('span')
                        albumName.className = 'album-element-name'
                        var albumImageCount = document.createElement('span')
                        albumImageCount.className = 'album-element-count'
                        albumName.textContent = modalNameInput.value
                        albumImageCount.textContent = 'Изображений: ' + selectedImages.length
                        infoBlock.appendChild(albumName)
                        infoBlock.appendChild(albumImageCount)
                        newAlbum.appendChild(infoBlock)

                        var checkMark = document.createElement('img')
                        checkMark.className = 'check-mark-album'
                        checkMark.src = 'img/selected-album.png'

                        newAlbum.appendChild(checkMark)
                        albumsListBlock.appendChild(newAlbum)
                        albumElements[albumElements.length - 1].onclick = function()
                        {
                            albumModalSelect(albumElements.length - 1)
                        }
                        clear()
                        closeModal()
                    }
                })
        }
    }

deleteButton.onclick = function()
{
    let imagesArray = []
    for (let i = 0; i < selectedImages.length; i++)
    {
        imagesArray.push(selectedImages[i].dataset.id)
    }

    $.ajax({
        url: '../php/delete-images.php',
        type: 'POST',
        data: {images: imagesArray},
        dataType: 'html',
        success: function(data)
        {   
            for (let i = selectedImages.length - 1; i >= 0; i--)
            {
                for (let j = 0; j < imagesList.length; j++)
                    if (imagesList[j].dataset.id == selectedImages[i].dataset.id)
                    {
                        imagesList[j].remove()
                        break
                    }
                if (selectedImages[i].parentNode.parentNode.getElementsByClassName('roll-image').length == 1)
                    selectedImages[i].parentNode.parentNode.remove()
                else
                    selectedImages[i].remove();
            }
            imageCount.textContent = 'Изображений: ' + document.getElementsByClassName('roll-image').length
            clear()
            closeModal()
        }
    })
}

for (let i = 0; i < albumElements.length; i++)
{
    albumElements[i].onclick = function()
    {
        albumModalSelect(i)
    }
}

createAlbumButton.onclick = function()
{
    shadowObject.dataset.type = 3
    modalWindowTypes[1].style.display = 'none'
    modalWindowTypes[0].style.display = 'flex'
    chooseAlbumModal(0)
}