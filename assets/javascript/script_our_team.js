//NAVBAR CHANGE COLOR LOGIC
document.addEventListener('DOMContentLoaded', function () {
    const sections = document.querySelectorAll('.section');
    const navLinks = document.querySelectorAll('.navListItem');

    window.addEventListener('scroll', function () {
        let current = '';

        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;

            if (pageYOffset >= sectionTop - sectionHeight / 3) {
                current = section.getAttribute('id');
            }
        });

        navLinks.forEach(link => {
            link.classList.remove('active');
        });

        const activeLink = document.getElementById(current + '-link');
        if (activeLink) {
            activeLink.classList.add('active');
        }
    });
});

//CHARBOX CHANGE BOX & DIV LOGIC
var char1 = document.getElementById("char-1x");
const charActvList = document.querySelectorAll('.boxChar');

//-logika ketika di klik
char1.classList.add('boxCharActive');
charActvList.forEach(charActv => {
  charActv.addEventListener('click', () => {
    document.querySelector('.boxCharActive')?.classList.remove('boxCharActive');
    char1.classList.remove('onlyChar1');
    charActv.classList.add('boxCharActive');
  });
});

//-logika ketika diklik kemudian tampilan backgroundnya mengikuti ketika di hover
// Fungsi untuk mengatur posisi background image
function setBackgroundPosition(element, position) {
    element.style.setProperty('--img', 'url(' + element.style.getPropertyValue('--img') + ')');
    element.style.backgroundPosition = position;
}

charActvList.forEach(charActv => {
    charActv.addEventListener('click', () => {
        // Menghapus kelas boxCharActive dari semua boxChar
        charActvList.forEach(char => {
            char.classList.remove('boxCharActive');
            setBackgroundPosition(char, ''); // Menghapus posisi background-image
        });

        // Menambah kelas boxCharActive pada boxChar yang sedang diklik
        charActv.classList.add('boxCharActive');
        // Mendapatkan posisi background-image dari boxChar yang sedang diklik
        const backgroundImagePosition = window.getComputedStyle(charActv).getPropertyValue('background-position');
        // Mengatur posisi background-image pada boxChar yang sedang diklik
        setBackgroundPosition(charActv, backgroundImagePosition);
    });
});

//PREV NEXT CODE!!!
var pre1 = document.getElementById("preview-1");
var pre2 = document.getElementById("preview-2");
pre1.classList.add('flexPre');
pre2.classList.add('nonePre');

var buttons = document.querySelectorAll('.button');
buttons.forEach(function(button) {
    button.addEventListener('click', function() {
        if (pre1.classList.contains('flexPre')) {
            pre1.classList.add('slideToLeft');
            setTimeout(function() {
                pre1.classList.remove('slideToLeft');
                pre1.classList.remove('flexPre');
                pre1.classList.add('nonePre');
                pre2.classList.remove('nonePre');
                pre2.classList.add('flexPre');
            }, 180);
        }
        else if (pre2.classList.contains('flexPre')) {
            pre2.classList.add('slideToLeft');
            setTimeout(function() {
                pre2.classList.remove('slideToLeft');
                pre2.classList.remove('flexPre');
                pre2.classList.add('nonePre');
                pre1.classList.remove('nonePre');
                pre1.classList.add('flexPre');
            }, 180);
        }
    });
});

//CHANGE CharIMG and CharDesc
var boxChars = document.querySelectorAll('.boxChar');
var charLists = document.querySelectorAll('.charContentX');
var charImg = document.querySelectorAll('.charImg');
var charDesc = document.querySelectorAll('.charDesc');

boxChars.forEach((boxChar, index) => {
    boxChar.addEventListener('click', () => toggleDiv(index));
});

function toggleDiv(index) {
    charLists.forEach((charList, i) => {
        charImg[i].classList.remove('slideInLeft');
        charDesc[i].classList.remove('slideInRight');
        charImg[i].classList.add('slideToRight');
        charDesc[i].classList.add('slideToLeft');
        setTimeout(function(){
            charImg[i].classList.remove('slideToRight');
            charDesc[i].classList.remove('slideToLeft');
            charImg[i].classList.add('slideInLeft');
            charDesc[i].classList.add('slideInRight');
            charList.style.display = i === index ? "flex" : "none";
        }, 380)
    });
}



