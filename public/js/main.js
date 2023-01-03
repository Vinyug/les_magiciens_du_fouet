"use strict"

// Navbar animée
const btnNav = document.querySelector(`.nav-btn`);
const listeNav = document.querySelector(`nav ul`);
let imgBtn = document.querySelector(`.nav-btn img`);

btnNav.addEventListener(`click`, () => {
    listeNav.classList.toggle(`active-nav`);

    if(imgBtn.src.includes(`menu`)){
        imgBtn.src = `img/cross.png`;

    } else {
        imgBtn.src = `img/menu.png`;
    }
})

// Formulaire contact validation
const inputFirstname = document.getElementById('firstname');
const inputName = document.getElementById('name');
const inputEmail = document.getElementById('email');
const inputPhone = document.getElementById('phone');
const inputMessage = document.getElementById('message');

const errorFirstname = document.getElementById('commentFirstname');
const errorName = document.getElementById('commentName');
const errorEmail = document.getElementById('commentEmail');
const errorPhone = document.getElementById('commentPhone');
const errorMessage = document.getElementById('commentMessage');

const statusMail = document.querySelector('.sendMail');
const submit = document.querySelector('#submit');

let validateFirstname = false;
let validateName = false;
let validateEmail = false;
let validatePhone = true;
let validateMessage = false;

inputFirstname.addEventListener('input', verifyFirstname);
inputName.addEventListener('input', verifyName);
inputEmail.addEventListener('input', verifyEmail);
inputPhone.addEventListener('input', verifyPhone);
inputMessage.addEventListener('input', verifyMessage);
submit.addEventListener('click', validate);

function verifyFirstname() {
    if (inputFirstname.value.length <= 2 || inputFirstname.value.length >= 20) {
        errorFirstname.classList.add('comments-active');
        errorFirstname.innerText = `Le champs doit comporter 3 à 20 caractères`;
        setTimeout(() => {
            errorFirstname.classList.remove('comments-active');
        }, 3000);
    }
    
    if (!inputFirstname.value) {
        errorFirstname.classList.add('comments-active');
        errorFirstname.innerText = `Le champs ne peut pas être vide`;
        setTimeout(() => {
            errorFirstname.classList.remove('comments-active');
        }, 3000);
    }
    
    return validateFirstname = true;
}

function verifyName() {
    if (inputName.value.length <= 2 || inputName.value.length >= 20) {
        errorName.classList.add('comments-active');
        errorName.innerText = `Le champs doit comporter 3 à 20 caractères`;
        setTimeout(() => {
            errorName.classList.remove('comments-active');
        }, 3000);
    }
    
    if (!inputName.value) {
        errorName.classList.add('comments-active');
        errorName.innerText = `Le champs ne peut pas être vide`;
        setTimeout(() => {
            errorName.classList.remove('comments-active');
        }, 3000);
    }
    
    return validateName = true;
}

function verifyEmail() {
    if (!isEmail(inputEmail.value)) {
        errorEmail.classList.add('comments-active');
        errorEmail.innerText = `Le champs doit être un mail`;
        setTimeout(() => {
            errorEmail.classList.remove('comments-active');
        }, 3000);
    }
    
    if (inputEmail.value.length <= 6 || inputEmail.value.length >= 80) {
        errorEmail.classList.add('comments-active');
        errorEmail.innerText = `Le champs doit comporter 6 à 80 caractères`;
        setTimeout(() => {
            errorEmail.classList.remove('comments-active');
        }, 3000);
    }
    
    if (!inputEmail.value) {
        errorEmail.classList.add('comments-active');
        errorEmail.innerText = `Le champs ne peut pas être vide`;
        setTimeout(() => {
            errorEmail.classList.remove('comments-active');
        }, 3000);
    }

    return validateEmail = true;
}

function verifyPhone() {
    if (inputPhone.value.length) {
        if (inputPhone.value.length != 10) {
            errorPhone.classList.add('comments-active');
            errorPhone.innerText = `Le champs doit comporter 10 caractères`;
            setTimeout(() => {
                errorPhone.classList.remove('comments-active');
            }, 3000);
            return validatePhone = false;
        } else if (isNaN(inputPhone.value)) {
            errorPhone.classList.add('comments-active');
            errorPhone.innerText = `Le champs doit comporter des caractères numériques`;
            setTimeout(() => {
                errorPhone.classList.remove('comments-active');
            }, 3000);
            return validatePhone = false;
        } else {
            return validatePhone = true;
        }
    }
}

function verifyMessage() {
    if (inputMessage.value.length >= 600) {
        errorMessage.classList.add('comments-active');
        errorMessage.innerText = `Le champs doit comporter maximum 600 caractères`;
        setTimeout(() => {
            errorMessage.classList.remove('comments-active');
        }, 3000);
    }
    
    if (!inputMessage.value) {
        errorMessage.classList.add('comments-active');
        errorMessage.innerText = `Le champs ne peut pas être vide`;
        setTimeout(() => {
            errorMessage.classList.remove('comments-active');
        }, 3000);
    }
    
    return validateMessage = true;
}

function isEmail(email) {
    let regex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;
    return regex.test(String(email).toLowerCase());
}

function validate(e) {
    e.preventDefault();

    if(validateFirstname && validateName && validateEmail && validatePhone && validateMessage) {
        statusMail.innerText = `Nous vous remercions pour votre message !`;
    } else {
        statusMail.innerText = `Vous devez compléter les champs requis !`;
        setTimeout(() => {
            statusMail.innerText = ``;
        }, 10000);
    }
}