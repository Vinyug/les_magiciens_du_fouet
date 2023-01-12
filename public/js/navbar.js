"use strict"

// Navbar animate
const btnNav = document.querySelector(`.nav-btn`);
const listeNav = document.querySelector(`nav ul`);
let imgBtn = document.querySelector(`.nav-btn img`);


btnNav.addEventListener(`click`, () => {
    listeNav.classList.toggle(`active-nav`);

    if(imgBtn.src.includes(`menu`)){
        imgBtn.src = `/img/cross.png`;

    } else {
        imgBtn.src = `/img/menu.png`;
    }
})