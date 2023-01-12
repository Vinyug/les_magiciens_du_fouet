"use strict"

// Show post manage li ingredients and steps

function strToLi() {
    const areaUl = document.getElementById('areaUl');
    const areaOl = document.getElementById('areaOl');
    const strUl = document.getElementById('strUl');
    const strOl = document.getElementById('strOl');
    const splitsUl = strUl.innerHTML.split('<br>');
    const splitsOl = strOl.innerHTML.split('<br>');

    for (let i = 0; i < splitsUl.length; i++) {
        let li = document.createElement('li');
        splitsUl[i] = splitsUl[i].replace(/\n|\r/g,'');
        if(splitsUl[i] != "") {
            li.appendChild(document.createTextNode(splitsUl[i]));
            areaUl.appendChild(li);
        }
    }
    
    for (let i = 0; i < splitsOl.length; i++) {
        let li = document.createElement('li');
        splitsOl[i] = splitsOl[i].replace(/\n|\r/g,'');
        if(splitsOl[i] != "") {
            li.appendChild(document.createTextNode(splitsOl[i]));
            areaOl.appendChild(li);
        }
    }
}

strToLi();

