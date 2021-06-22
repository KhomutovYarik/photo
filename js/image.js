var allComments = document.getElementsByClassName('all-comments')[0]
var commentInput = document.getElementsByClassName('comment-text-input')[0]
var sendCommentButton = document.getElementsByClassName('send-comment-button')[0]

sendCommentButton.onclick = function()
{
    if (commentInput.value.length < 3)
        alert('Длина комментария должна быть не менее трёх символов')
    else
    {
        var urlString = new URL(window.location.href)
        var imageId = urlString.searchParams.get('id')
        $.ajax({
            url: '../php/create-comment.php',
            type: 'POST',
            data: {image_id: imageId, comment_text: commentInput.value},
            dataType: 'html',
            success: function(data)
            {   
                msg = JSON.parse(data)

                var newComment = document.createElement('div')
                newComment.className = 'comment'
                newComment.dataset.id = msg[0]

                var profilePic = document.createElement('img')
                profilePic.className = 'comment-avatar'
                profilePic.src = 'img/the-cat.png'

                var commentTextAndName = document.createElement('div')
                commentTextAndName.className = 'comment-name-and-text'

                commentName = document.createElement('span')
                commentName.className = 'comment-name'
                commentName.textContent = msg[1]

                commentText = document.createElement('div')
                commentText.className = 'comment-text'
                commentText.textContent = commentInput.value

                commentDate = document.createElement('span')
                commentDate.className = 'comment-date'
                commentDate.textContent = msg[2]

                commentTextAndName.appendChild(commentName)
                commentTextAndName.appendChild(commentText)
                newComment.appendChild(profilePic)
                newComment.appendChild(commentTextAndName)
                newComment.appendChild(commentDate)
                allComments.appendChild(newComment)

                commentInput.value = ""
            }
        })
    }
}