// JavaScript untuk membuat judul bergerak dari kanan ke kiri
document.addEventListener('DOMContentLoaded', (event) => {
    let title = document.title + " "; // Tambahkan spasi di akhir judul
    let speed = 300; // kecepatan pergerakan (ms)
    let moveTitle = () => {
        title = title.substring(1) + title.charAt(0);
        document.title = title;
        setTimeout(moveTitle, speed);
    };
    moveTitle();
});


//Hover Animation pada Kategori Populer
document.querySelectorAll('.card-rekpop').forEach(card => {
    card.addEventListener('mouseenter', () => {
        document.querySelectorAll('.card-rekpop').forEach(otherCard => {
            if (otherCard !== card) {
                otherCard.classList.add('grayscale');
            }
        });
    });

    card.addEventListener('mouseleave', () => {
        document.querySelectorAll('.card-rekpop').forEach(otherCard => {
            otherCard.classList.remove('grayscale');
        });
    });
});



// Fungsi untuk mengatur marquee berdasarkan panjang username
var username = "Asep"; // Ganti dengan username yang diinginkan
function updateMarquee() {
        var marquee = document.getElementById('marquee');

        // Memeriksa panjang username
        if (username.length > 8) {
            // Jika panjangnya lebih dari 8 karakter, atur scrollamount agar bergerak
            marquee.setAttribute('scrollamount', '2'); // Sesuaikan nilainya sesuai kebutuhan
        } else {
            // Jika panjangnya 8 karakter atau kurang, atur scrollamount agar diam
            marquee.setAttribute('scrollamount', '0');
        }

        // Memperbarui teks dalam marquee
        marquee.textContent = username;

        // Menyegarkan marquee untuk memulai ulang
        var marqueeParent = marquee.parentNode;
        var newMarquee = marquee.cloneNode(true);
        marqueeParent.replaceChild(newMarquee, marquee);
    }

// Memanggil fungsi saat halaman dimuat
window.onload = updateMarquee;