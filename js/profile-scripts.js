var navElements = document.getElementsByClassName('profile-nav-element')
var profileBlocks = document.getElementsByClassName('profile-block')
var selectedNav = document.getElementsByClassName('selected-nav-element')
var openedBlock = document.getElementsByClassName('opened-block')
var images = document.getElementsByClassName('roll-image')
var selectAll = document.getElementsByClassName('select-all')
var selectedImages = document.getElementsByClassName('selected-image')

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
    images[i].onclick = function()
    {
        if (images[i].classList[1][0] == 'n')
        {
            images[i].classList.remove('not-selected-image')
            images[i].classList.add('selected-image')
        }
        else
        {
            images[i].classList.remove('selected-image')
            images[i].classList.add('not-selected-image')
        }
    }
}

for (let i = 1; i <= selectAll.length; i++)
{
    selectAll[i-1].onclick = function()
    {
        var imagesInside = document.querySelectorAll('.roll-data-block:nth-child(' + i + ') .roll-image')
        for (let j = 0; j < imagesInside.length; j++)
        {
            imagesInside[j].classList.remove('not-selected-image')
            imagesInside[j].classList.add('selected-image')
        }
    }
}

var manageElements = document.getElementsByClassName('manage-images')[0]
var selectedImagesCounter = document.getElementById('selected-images-count')

for (let i = 0; i < selectedImages.length; i++)
{
    selectedImages[i].onclick = function()
    {
        
    }
}


