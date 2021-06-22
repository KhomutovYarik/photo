var navElements = document.getElementsByClassName('nav-element')
var searchBlocks = document.getElementsByClassName('search-nav-block')
var selectedNav = document.getElementsByClassName('selected-nav-element')
var openedBlock = document.getElementsByClassName('opened-block')

for (let i = 0; i < navElements.length; i++)
{
    navElements[i].onclick = function()
    {
        selectedNav[0].classList.remove('selected-nav-element')
        openedBlock[0].classList.remove('opened-block')
        navElements[i].classList.add('selected-nav-element')
        searchBlocks[i].classList.add('opened-block')
    }
}