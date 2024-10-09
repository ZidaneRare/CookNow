<?php
include "../config/koneksi.php";
// Fungsi untuk mengambil ikon nutrisi berdasarkan recipe_id
function getNutritionIcons($conn, $recipe_id)
{
    $query = "
        SELECT n.icon 
        FROM nutritions n
        JOIN recipe_nut rn ON n.nutrition_id = rn.nutrition_id
        WHERE rn.recipe_id = $recipe_id LIMIT 5
    ";
    $result = mysqli_query($conn, $query);

    $icons = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $icons[] = $row['icon'];
    }
    return $icons;
}

// Mendapatkan ID dari URL
$id = isset($_GET['id']) ? $_GET['id'] : null;

// Mengambil data recipe berdasarkan ID
$recipe_result = mysqli_query($conn, "SELECT * FROM recipes WHERE recipe_id = $id");
$recipe = mysqli_fetch_assoc($recipe_result);

// Mengambil rating rata-rata dan jumlah review
$rating_result = mysqli_query($conn, "SELECT AVG(rating) as average_rating, COUNT(*) as total_reviews FROM reviews WHERE recipe_id = $id");
$rating_data = mysqli_fetch_assoc($rating_result);
$average_rating = round($rating_data['average_rating'], 1);
$total_reviews = $rating_data['total_reviews'];

// Mengambil komentar dan rating
$reviews_result = mysqli_query($conn, "SELECT u.username, u.photo, r.rating, r.comment, r.review_date FROM reviews r JOIN user u ON r.user_id = u.user_id WHERE r.recipe_id = $id ORDER BY r.review_date DESC");


$query = "SELECT * FROM recipes WHERE recipe_id != $id ORDER BY RAND() LIMIT 20";
$data = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Style -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/detail-recipe.css">
    <link rel="stylesheet" href="../assets/css/footer.css">

    <!-- Font Family -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Title -->
    <link rel="icon" href="../assets/img/logo_title.png" type="image/x-icon">
    <title id="pageTitle">CookNow | Resep Makanan dan Minuman |</title>
</head>

<body>
    <!-- Start Navbar -->
    <nav class="navbar navbar-dark navbar-expand-lg fixed-top" style="background-color: var(--teal-2);">
        <style>
            .navbar {
                max-height: 60px;
            }
        </style>
        <div class="container-fluid" style="padding: 0 100px 0 100px;">
            <a class="navbar-brand" href="../"><img class="" style="height: 30px ;" src="../assets/img/logo.png" alt="CookNow"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="fs-h4 navbar-nav ms-auto gap-xl-5 gap-md-4 gap-sm-2 py-xl-0 py-md-0 py-3">
                    <li class="nav-item">
                        <a class="nav-link nav-home" aria-current="page" href="../">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-resep" href="../recipes.php">Resep</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-about" href="../about-us.php">Tentang Kami</a>
                    </li>
                    <li>
                        <a class="btn btn-hover rounded-5 border border-3 px-4" href="../login.php">Masuk</a>
                        <style>
                            .btn-hover {
                                background-color: var(--teal-4);
                                color: var(--teal-2);
                                border-color: var(--teal-2);
                                font-size: 18px;
                                font-weight: 600;
                            }

                            .btn-hover:hover {
                                background-color: var(--teal-3);
                                border-color: var(--teal-3) !important;
                            }

                            .btn-hover:active {
                                background-color: var(--teal-2) !important;
                                border-color: var(--white) !important;
                            }
                        </style>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- End Navbar -->

    <!-- Start Header -->
    <header class="container-fluid d-flex p-0" style="width: 90%; margin-top: 120px; background-color: #fff; height: 400px; box-shadow: 0 10px 15px #0002;">
        <div class="p-5" style="width: 60%; height: 100%; color: var(--black); position: relative;">
            <h1 class="fs-h2"><?= $recipe['name']; ?></h1>
            <div class="fs-body" style="color: #1e1e1e;"><?= $recipe['description']; ?></div>
            <div class="d-flex gap-4 align-items-center mb-5" style="height: fit-content; width: fit-content; position: absolute; bottom: 0;">
                <div class="rating-star">
                    <?php
                    for ($i = 1; $i <= 5; $i++) {
                        echo $i <= $average_rating ? '<i class="fa-solid fa-star text-warning"></i>' : '<i class="fa-regular fa-star text-black"></i>';
                    }
                    ?>
                </div>
                <div style="color: var(--black);"><?= $average_rating; ?>/5</div>
                <div style="width: 2px; height: 20px; background-color: var(--black);"></div>
                <div class="d-flex gap-3">
                    <i class="love-icon fs-h3 fa-regular fa-heart text-danger" style="cursor:pointer;"></i>
                    <div style="color: var(--black);">Add to Favorite</div>
                </div>
            </div>
        </div>
        <div class="" style="width: 40%;">
            <div style="height: 100%; width: 100%; background-image: url(../admin/resep-makanan/<?= $recipe['image']; ?>); background-size: cover; background-position: center; background-repeat: no-repeat;"></div>
        </div>
    </header>
    <!-- End Header -->

    <!-- Start Content -->
    <main class="container-fluid d-flex justify-content-evenly" style="margin: 100px 0; height: 100%;">
        <div class="left">
            <div class="accordion mb-3" id="accordionBahan"> <!--Start Accordion-->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button style="color: var(--black); font-size: 20px; font-weight: 600;" class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBahan" aria-expanded="true" aria-controls="collapseBahan">
                            Bahan Bahan
                        </button>
                </div>
                <div id="collapseBahan" class="accordion-collapse collapse show" data-bs-parent="#accordionBahan">
                    <div class="accordion-body">
                        <p class="fs-body" style="color: var(--black);"><?= $recipe['ingredients']; ?></p>
                    </div>
                </div>
            </div> <!--End Accordion-->
            <div class="accordion mb-3" id="accordionResep"> <!--Start Accordion-->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button style="color: var(--black); font-size: 20px; font-weight: 600;" class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseResep" aria-expanded="true" aria-controls="collapseResep">
                            Cara Memasak
                        </button>
                </div>
                <div id="collapseResep" class="accordion-collapse collapse show" data-bs-parent="#accordionResep">
                    <div class="accordion-body">
                        <p class="fs-body" style="color: var(--black);"><?= $recipe['instructions']; ?></p>
                    </div>
                </div>
            </div> <!--End Accordion-->

            <div class="comment p-4 mb-3" style="height: 400px; background-color: #fff;">
                <p style="color: var(--black);font-size: 20px; font-weight: 600;">Berikan Komentar dan Rating Anda</p>
                <div class="rating-star mb-3">
                    <input type="hidden" name="rating" id="rating-input" value="0">
                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                        <i class="fa-regular fa-star rating-star-input" style="color: black;" data-rating="<?= $i; ?>"></i>
                    <?php endfor; ?>
                </div>
                <textarea name="comment" class="mb-3" style="color :black; width: 100%; height: 200px;" placeholder="Harap untuk masuk terlebih dahulu sebelum berkomentar" readonly></textarea>
                <button id="submit-comment" class="sumbits btn mb-3 px-5 rounded-5" style="background-color: var(--teal-2);">
                    <span class="text-white" style="text-decoration: none;">Kirim</span>
                </button>
            </div>

            <!-- Menampilkan Komentar -->
            <div class="comment p-4" style="height: 400px; background-color: #fff; overflow: hidden;">
                <p style="color: var(--black);font-size: 20px; font-weight: 600;">Komentar</p>
                <div class="rounded-2 p-3" style="width: 100%; height: calc(100% - 40px); margin-top: 10px; overflow-y: auto; background-color: var(--grey-2);">
                    <?php while ($reviews = mysqli_fetch_assoc($reviews_result)) : ?>
                        <div class="comments d-flex mb-3">
                            <?php
                            // Ambil avatar dari kolom 'photo' jika ada
                            $avatar_url = isset($reviews['photo']) ? $reviews['photo'] : '../assets/img/profile-img.png';
                            ?>
                            <div class="avatar rounded-5 me-3" style="background-image: url(../profile-user/<?php echo $avatar_url; ?>); background-position: center; width: 50px; height: 50px; box-shadow: 0 5px 10px #0002; background-size: cover; background-repeat: no-repeat;"></div>
                            <div class="text p-2" style="width: 80%; height: auto; background-color: white; border-radius: 0 20px 20px 20px; box-shadow: 0 5px 10px #0002;">
                                <p class="comment-name fs-h4 p-0 m-0" style="color: var(--black);"><?= $reviews['username']; ?></p>
                                <div class="rating-star p-0">
                                    <?php
                                    // Menampilkan rating dalam bentuk bintang
                                    for ($i = 1; $i <= 5; $i++) {
                                        echo $i <= $reviews['rating'] ? '<i class="fa-solid fa-star text-warning"></i>' : '<i class="fa-regular fa-star text-black"></i>';
                                    }
                                    ?>
                                </div>
                                <p class="comment-text fs-body m-0 mb-1" style="color: var(--black); word-wrap: break-word; white-space: normal;"><?= $reviews['comment']; ?></p>
                                <p class="comment-date fs-note m-0" style="color: var(--grey-2);"><?= $reviews['review_date']; ?></p>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>

        </div>
        <div class="right">
            <div class="accordion" id="accordionExample"> <!--Start Accordion-->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button style="color: var(--black); font-size: 20px; font-weight: 600;" class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNutrisi" aria-expanded="true" aria-controls="collapseNutrisi">
                            Informasi Nutrisi
                        </button>
                </div>
                <div id="collapseNutrisi" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <p class="fs-body" style="color: var(--black); background-color: red;"><?= $recipe['nutritions']; ?></p>
                    </div>
                </div>
            </div> <!--End Accordion-->

        </div>
    </main>
    <!-- End Content -->

    <div class="container-fluid mb-5 rounded-top-5" style="background-color: #fff; position: relative;">
        <div class="pt-5">
            <h2 class="fs-h2 text-center" style="color: var(--black);">RESEP LAIN</h2>
            <main class="container-fluid m-0 d-flex justify-content-center" style="padding:0 100px;">
                <div class="container-fluid m-0 my-5" style="min-height: 70vh">
                    <div class="row row-cols-sm-2 row-cols-md-2 row-cols-lg-4 row-cols-xxl-5">
                    <?php
                    while ($hasil = $data->fetch_assoc()) {
                        $recipe_id = $hasil['recipe_id'];

                        // Query untuk mendapatkan rating dan review
                        $stmt = $conn->prepare("SELECT rating FROM reviews WHERE recipe_id = ?");
                        $stmt->bind_param("i", $recipe_id);
                        $stmt->execute();
                        $reviews_result = $stmt->get_result();

                        $average_rating = 0;
                        $total_reviews = 0;
                        $total_rating = 0;
                        while ($review = $reviews_result->fetch_assoc()) {
                            $total_rating += $review['rating'];
                            $total_reviews++;
                        }
                        if ($total_reviews > 0) {
                            $average_rating = round($total_rating / $total_reviews, 1);
                        }
                        $stmt->close();
                    ?>
                        <div class="col-4 p-3" style="min-height: 400px; max-height: 450px;">
                            <a href="detail-recipe.php?id=<?= $recipe_id; ?>" id="semua" class="card rounded-4" style=" text-decoration:none; width: 100%; height: 100%; background-color: white; box-shadow: 0 5px 20px #0003;">
                                <div class="bg-image d-flex justify-content-end pt-3 pe-3 rounded-top-4" style="background-image: url(../admin/resep-makanan/<?php echo $hasil['image'] ?>); height: 60%; position:relative; background-size:cover;">
                                    <i style="position:absolute; background-color: var(--grey-1); padding:5px; box-shadow:0px 5px 10px #0009; cursor:pointer;" class="love-icon fs-h3 fa-regular fa-heart text-danger rounded-3"></i>
                                </div>
                                <div class="desk-rekmakmin pt-2">
                                    <div class="nama-fav d-flex justify-content-between px-3">
                                        <p class="fw-semibold mb-2 fs-fs-body" style="color:var(--black);"><?php echo $hasil['name'] ?></p>
                                    </div>
                                    <div class="rating-star ps-3">
                                        <?php
                                        // Menampilkan rating dalam bentuk bintang
                                        for ($i = 1; $i <= 5; $i++) {
                                            echo $i <= $average_rating ? '<i class="fa-solid fa-star text-warning"></i>' : '<i class="fa-regular fa-star text-black"></i>';
                                        }
                                        ?>
                                    </div>
                                    <div class="nutrition ps-3 pt-2">
                                        <?php
                                        $icons = getNutritionIcons($conn, $recipe_id);
                                        foreach ($icons as $icon) {
                                            echo "<img src='../$icon' alt='Nutrition Icon' style='width: 30px; height: 30px; margin-right: 5px;'>";
                                        }
                                        ?>
                                    </div>
                                </div>
                            </a>
                        </div>

                    <?php
                    }
                    ?>
                    </div>
                </div>
            </main>
        </div>
    </div>






    <!-- Start footer -->
    <div class="container-fluid p-0">
        <div class="container-fluid footer gap-2">
            <img src="../assets/img/logo.png">
            <p style="width: 600px;">Teman Setia Anda dalam Menciptakan Makanan Berkualitas, Mengubah Setiap Masakan menjadi Karya Seni.</p>
            <div class="social-media d-flex gap-3">
                <a href=""><i class="fa-brands fa-x-twitter"></i></a>
                <a href=""><i class="fa-brands fa-whatsapp"></i></a>
                <a href=""><i class="fa-brands fa-facebook"></i></a>
                <a href=""><i class="fa-brands fa-instagram"></i></a>
            </div>
        </div>
        <div class="container-fluid text-center py-2" style="bottom: 0; background-color: var(--teal-1);">Copyright &copy; 2024 CookNow.</div>
    </div>
    <!-- End footer -->

    <!-- Bootstrap JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- Java Script -->
    <script src="../assets/javascript/script.js"></script>

    <script>
        // SEARCH

        // Fungsi untuk mencari produk berdasarkan inputan teks
        const searchInput = document.getElementById("search-input");
        searchInput.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                searchProducts();
            }
        });

        const searchButton = document.getElementById("search-button");
        searchButton.addEventListener("click", searchProducts);

        function searchProducts() {
            const searchInput = document.getElementById("search-input");
            const searchText = searchInput.value.toUpperCase();

            const cards = document.querySelectorAll(".col");
            let cardFound = false; // Menandakan apakah ada card yang ditemukan

            cards.forEach((card) => {
                const title = card.querySelector("p").textContent.toUpperCase();
                if (title.includes(searchText)) {
                    card.style.display = "block";
                    cardFound = true; // Setel cardFound menjadi true jika ada card yang ditemukan
                } else {
                    card.style.display = "none";
                }
            });

            const notFoundMessage = document.getElementById("not-found-message");
            if (cardFound) {
                notFoundMessage.style.display = "none"; // Sembunyikan pesan jika ada card yang ditemukan
            } else {
                notFoundMessage.style.display = "block"; // Tampilkan pesan jika tidak ada card yang ditemukan
            }
        }
    </script>

    <script>
        document.querySelectorAll('.rating-star-input').forEach(star => {
            star.addEventListener('click', function() {
                const rating = this.getAttribute('data-rating');
                document.getElementById('rating-input').value = rating;

                document.querySelectorAll('.rating-star-input').forEach(s => {
                    if (s.getAttribute('data-rating') <= rating) {
                        s.classList.remove('fa-regular');
                        s.classList.add('fa-solid');
                        s.classList.add('text-warning');
                    } else {
                        s.classList.add('fa-regular');
                        s.classList.remove('fa-solid');
                        s.classList.remove('text-warning');
                    }
                });
            });
        });


        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('submit-review').addEventListener('click', function(event) {
                const rating = document.getElementById('rating-input').value;
                if (rating == 0) {
                    event.preventDefault(); // Mencegah pengiriman form
                    alert('Harap berikan rating sebelum mengirimkan komentar.'); // Menampilkan pesan peringatan
                }
            });
        });
    </script>

    <script>
        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('love-icon')) {
                event.preventDefault(); // Mencegah perilaku default dari tautan <a>
                event.stopPropagation(); // Menghentikan propagasi event agar tidak mengarahkan ke link <a>

                // Toggle kelas 'fa-solid' dan 'fa-regular' pada ikon yang diklik
                event.target.classList.toggle('fa-solid');
                event.target.classList.toggle('fa-regular');

                // Menampilkan form login atau peringatan
                showLoginAlert();
            }
        });

        function showLoginAlert() {
            // Cek apakah sudah ada elemen alert sebelumnya
            if (document.getElementById('loginAlert')) {
                return;
            }

            // Membuat elemen peringatan
            var alertDiv = document.createElement('div');
            alertDiv.id = 'loginAlert';
            alertDiv.style.position = 'fixed';
            alertDiv.style.top = '0%';
            alertDiv.style.left = '50%';
            alertDiv.style.transform = 'translate(-50%, 10%)';
            alertDiv.style.padding = '20px';
            alertDiv.style.backgroundColor = 'white';
            alertDiv.style.boxShadow = '0 10px 20px rgba(0, 0, 0, 0.9)';
            alertDiv.style.zIndex = '99999';
            alertDiv.style.borderRadius = '8px';
            alertDiv.innerHTML = `
                <p class="text-black">Harap login terlebih dahulu.</p>
                <div class="d-flex justify-content-end gap-2">
                <button class="btn btn-danger" id="closeAlertButton">Tutup</button>
                <a href="../login.php" class="btn btn-primary">Masuk</a>
                </div>
            `;

            // Menambahkan event listener untuk tombol tutup
            alertDiv.querySelector('#closeAlertButton').addEventListener('click', function() {
                document.body.removeChild(alertDiv);
            });

            // Menambahkan elemen peringatan ke body
            document.body.appendChild(alertDiv);
        }
    </script>
</body>

</html>