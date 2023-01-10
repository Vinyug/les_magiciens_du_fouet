"use strict"

// modal delete confirmation

const modalDeletePost = document.getElementById('modal-delete-post');
const cancelDeletePost = document.getElementById('cancel-delete-post');
const modalDeleteComment = document.getElementById('modal-delete-comment');
const cancelDeleteComment = document.getElementById('cancel-delete-comment');
const modalDeleteCooker = document.getElementById('modal-delete-cooker');
const cancelDeleteCooker = document.getElementById('cancel-delete-cooker');
const modalDeleteUser = document.getElementById('modal-delete-user');
const cancelDeleteUser = document.getElementById('cancel-delete-user');


window.onclick = function(e) {
    if (e.target == modalDeletePost || e.target == cancelDeletePost) {
        modalDeletePost.style.display = "none";
    } else if (e.target == modalDeleteComment || e.target == cancelDeleteComment) {
        modalDeleteComment.style.display = "none";
    } else if (e.target == modalDeleteCooker || e.target == cancelDeleteCooker) {
        modalDeleteCooker.style.display = "none";
    } else if (e.target == modalDeleteUser || e.target == cancelDeleteUser) {
        modalDeleteUser.style.display = "none";
    }
}
