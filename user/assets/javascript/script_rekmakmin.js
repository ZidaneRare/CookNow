document.addEventListener('DOMContentLoaded', () => {
    const makanan = [
        { nama: "Udang Asam Manis", gambar: "recipes/udang_asammanis.png", rating: 4, nut: ["icon/nut-Energi.png", "icon/nut-Protein.png"] },
        { nama: "Sayur Asam", gambar: "recipes/sayur_asem.png", rating: 4, nut: ["icon/nut-Energi.png", "icon/nut-Protein.png"] },
        { nama: "Kulit Melinjo", gambar: "recipes/kulit_melinjo.png", rating: 5, nut: ["icon/nut-Energi.png", "icon/nut-Protein.png"] }
    ];
    
    const container_makan = document.getElementById('rekomendasiMakanan');
    makanan.forEach(item => {
        container_makan.innerHTML += `
            <a style="text-decoration:none;" class="card-rekmakmin" href="#">
                <div class="bg-image d-flex justify-content-end pt-3 pe-3" style="background-image: url(${item.gambar});">
                    <i style="position:absolute; background-color: var(--grey-1); padding:5px; box-shadow:0px 5px 10px #002;" class="fs-h3 fa-solid fa-heart text-danger rounded-3"></i>
                </div>
                <div class="desk-rekmakmin pt-2 pb-4">
                    <div class="nama-fav d-flex justify-content-between px-3">
                        <p class="fw-semibold text-black">${item.nama}</p>
                    </div>
                    <div class="rating-star ps-3">
                        ${[...Array(5)].map((_, i) => `<i class="${i < item.rating ? 'fa-solid fa-star text-warning' : 'fa-regular fa-star text-black'}"></i>`).join('')}
                    </div>
                    <div class="nutrition ps-2 pt-2">
                        ${item.nut ? item.nut.map(nutImg => `<img src="${nutImg}" style="height: 100%; margin-right: 5px;">`).join('') : ''}
                    </div>
                </div>
            </a>
        `;
    });


    const minuman = [
        { nama: "Jus Mangga", gambar: "recipes/jus_mangga.png", rating: 4, nut: ["icon/nut-Energi.png", "icon/nut-Protein.png"] },
        { nama: "Jus Stroberi", gambar: "recipes/jus_stroberi.png", rating: 4, nut: ["icon/nut-Energi.png", "icon/nut-Protein.png"] },
        { nama: "Jus Alpukat", gambar: "recipes/jus_alpukat.png", rating: 3, nut: ["icon/nut-Energi.png", "icon/nut-Protein.png"] }
    ];
    
    const container_minum = document.getElementById('rekomendasiMinuman');
    minuman.forEach(item => {
        container_minum.innerHTML += `
            <a style="text-decoration:none;" class="card-rekmakmin" href="#">
                <div class="bg-image d-flex justify-content-end pt-3 pe-3" style="background-image: url(${item.gambar});">
                    <i style="position:absolute; background-color: var(--grey-1); padding:5px; box-shadow:0px 5px 10px #002;" class="fs-h3 fa-solid fa-heart text-danger rounded-3"></i>
                </div>
                <div class="desk-rekmakmin pt-2 pb-4">
                    <div class="nama-fav d-flex justify-content-between px-3">
                        <p class="fw-semibold text-black">${item.nama}</p>
                    </div>
                    <div class="rating-star ps-3">
                        ${[...Array(5)].map((_, i) => `<i class="${i < item.rating ? 'fa-solid fa-star text-warning' : 'fa-regular fa-star text-black'}"></i>`).join('')}
                    </div>
                    <div class="nutrition ps-2 pt-2">
                        ${item.nut ? item.nut.map(nutImg => `<img src="${nutImg}" style="height: 100%; margin-right: 5px;">`).join('') : ''}
                    </div>
                </div>
            </a>
        `;
    });
});