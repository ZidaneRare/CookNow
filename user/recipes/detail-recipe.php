<?php
session_start();
include("../../config/koneksi.php");

if (!isset($_SESSION['login_user'])) {
    header('location: ../');
    exit; // pastikan untuk menghentikan eksekusi setelah redirect
}

$id_from_session = $_SESSION['id_user'];
$query = "SELECT * FROM user WHERE user_id = '$id_from_session'";
$result = mysqli_query($conn, $query);

if ($result) {
    $user = mysqli_fetch_assoc($result);
} else {
    echo "Error fetching user data.";
    exit;
}

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

// Memproses form pengiriman komentar dan rating
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $user['user_id']; // Ambil user ID dari sesi
    $rating = isset($_POST['rating']) ? $_POST['rating'] : null;
    $comment = isset($_POST['comment']) ? mysqli_real_escape_string($conn, $_POST['comment']) : null;
    $review_date = date('Y-m-d H:i:s');

    // Periksa apakah sudah ada komentar dari user untuk resep ini
    $check_query = "SELECT * FROM reviews WHERE recipe_id = $id AND user_id = $user_id";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // Jika sudah ada, lakukan UPDATE
        $update_query = "UPDATE reviews SET rating = $rating, comment = '$comment', review_date = '$review_date' WHERE recipe_id = $id AND user_id = $user_id";
        mysqli_query($conn, $update_query);

        echo "Komentar berhasil diperbarui.";
    } else {
        // Jika belum ada, lakukan INSERT
        $insert_query = "INSERT INTO reviews (recipe_id, user_id, rating, comment, review_date) VALUES ($id, $user_id, $rating, '$comment', '$review_date')";
        mysqli_query($conn, $insert_query);

        echo "Komentar berhasil ditambahkan.";
    }

    // Redirect untuk menghindari pengiriman ulang form
    header("Location: detail-recipe.php?id=$id");
    exit;
}

$query = "SELECT * FROM recipes WHERE recipe_id != $id ORDER BY RAND() LIMIT 20";
$data = mysqli_query($conn, $query);

// Mendapatkan ID pengguna dari sesi
$user_id = $_SESSION['id_user'];

// Mendapatkan daftar resep yang difavoritkan oleh pengguna
$favorites_query = "SELECT recipe_id FROM favorites WHERE user_id = $user_id";
$favorites_result = mysqli_query($conn, $favorites_query);
$favorites = [];
while ($row = mysqli_fetch_assoc($favorites_result)) {
    $favorites[] = $row['recipe_id'];
}

$is_favorited = in_array($id, $favorites);

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
                background-color: #0009;
                backdrop-filter: blur(20px);
                max-height: 60px;
            }
        </style>
        <div class="container-fluid" style="padding: 0 100px;">
            <a class="navbar-brand" href="../../"><img class="" style="height: 30px ;" src="../../assets/img/logo.png" alt="CookNow"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="fs-h4 navbar-nav ms-auto gap-xl-5 gap-md-4 gap-sm-2 py-xl-0 py-md-0 py-3">
                    <li class="nav-item d-flex align-items-center">
                        <a class="p-0 nav-link nav-home" aria-current="page" href="../">Beranda</a>
                    </li>
                    <li class="nav-item d-flex align-items-center">
                        <a class="p-0 nav-link nav-resep" href="../recipes.php">Resep</a>
                    </li>
                    <li class="nav-item d-flex align-items-center">
                        <a class="p-0 nav-link nav-about" href="../about-us.php">Tentang Kami</a>
                    </li>
                    <li class="d-flex align-items-center">
                        <?php if (isset($_SESSION['login_user'])) : ?>
                            <div class="nav-link d-flex align-items-center">
                                <span class="fs-body"><?php echo $_SESSION['user_name']; ?></span>
                                <a class="ms-3" href="../../profile-user/profile.php">
                                    <div class="rounded-circle" style="border:1px solid var(--teal-3); background-image: url(../../profile-user/<?php echo $_SESSION['user_photo']; ?>); background-position: center; background-size: cover; width: 35px; height: 35px;"></div>
                                </a>
                            </div>
                        <?php endif; ?>
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
                    <i class="love-icon-head fs-h3 <?= $is_favorited ? 'fa-solid' : 'fa-regular' ?> fa-heart text-danger rounded-3" style="cursor:pointer;"></i>
                    <div style="color: var(--black);">Add to Favorite</div>
                </div>
            </div>
        </div>
        <div class="" style="width: 40%;">
            <div style="height: 100%; width: 100%; background-image: url(../../admin/resep-makanan/<?= $recipe['image']; ?>); background-size: cover; background-position: center; background-repeat: no-repeat;"></div>
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
                <form method="post">
                    <div class="rating-star mb-3">
                        <input type="hidden" name="rating" id="rating-input" value="0">
                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                            <i class="fa-regular fa-star rating-star-input" style="color: black;" data-rating="<?= $i; ?>"></i>
                        <?php endfor; ?>
                    </div>
                    <textarea name="comment" class="mb-3" style="color :black; width: 100%; height: 200px;"></textarea>
                    <button type="submit" id="submit-review" class="btnadd btn mb-3 px-5 rounded-5" style="background-color: var(--teal-2);">
                        <span class="text-white" style="text-decoration: none;">Kirim</span>
                    </button>
                </form>
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
                            <div class="avatar rounded-5 me-3" style="background-image: url(../../profile-user/<?php echo $avatar_url; ?>); background-position: center; width: 50px; height: 50px; box-shadow: 0 5px 10px #0002; background-size: cover; background-repeat: no-repeat;"></div>
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
                        while ($hasil = mysqli_fetch_array($data)) {
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

                            $is_favorited = in_array($recipe_id, $favorites);
                        ?>
                            <div class="col p-3" style="min-height: 400px; max-height: 450px;">
                                <a href="detail-recipe.php?id=<?= $recipe_id; ?>" id="semua" class="card rounded-4" style=" text-decoration:none; width: 100%; height: 100%; background-color: white; box-shadow: 0 5px 20px #0003;" data-recipe-id="<?= $recipe_id; ?>">
                                    <div class="bg-image d-flex justify-content-end pt-3 pe-3 rounded-top-4" style="background-image: url(../../admin/resep-makanan/<?php echo $hasil['image'] ?>); height: 60%; position:relative;">
                                        <i style="position:absolute; background-color: var(--grey-1); padding:5px; box-shadow:0px 5px 10px #0009; cursor:pointer;" class="love-icon fs-h3 <?= $is_favorited ? 'fa-solid' : 'fa-regular' ?> fa-heart text-danger rounded-3"></i>
                                    </div>
                                    <div class="desk-rekmakmin pt-2">
                                        <div class="nama-fav d-flex justify-content-between px-3">
                                            <p class="fw-semibold mb-2 fs-body" style="color:var(--black);"><?php echo $hasil['name'] ?></p>
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
                    <div id="not-found-message" class="text-center my-3" style="display: none;">
                        <p class="fs-h2" style="color: var(--teal-2);">Resep tidak tersedia</p>
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
        document.addEventListener('DOMContentLoaded', function() {
            const loveIcon = document.querySelector('.love-icon-head');

            loveIcon.addEventListener('click', function(event) {
                event.stopPropagation(); // Mencegah tautan card diaktifkan
                event.preventDefault(); // Mencegah tautan card diaktifkan jika di dalam link

                const recipeId = <?= $recipe['recipe_id']; ?>;
                const action = this.classList.contains('fa-solid') ? 'remove' : 'add';
                const loveIcon = this;

                fetch('../favorite.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `recipe_id=${recipeId}&action=${action}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            loveIcon.classList.toggle('fa-regular');
                            loveIcon.classList.toggle('fa-solid');
                        } else {
                            console.error(data.message);
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loveIcons = document.querySelectorAll('.love-icon');

            loveIcons.forEach(icon => {
                icon.addEventListener('click', function(event) {
                    event.stopPropagation(); // Mencegah tautan card diaktifkan
                    event.preventDefault(); // Mencegah tautan card diaktifkan jika di dalam link
                    const recipeCard = this.closest('.card');
                    const recipeId = recipeCard.getAttribute('data-recipe-id');
                    const action = this.classList.contains('fa-solid') ? 'remove' : 'add';
                    const loveIcon = this;

                    fetch('../favorite.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: `recipe_id=${recipeId}&action=${action}`
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                loveIcon.classList.toggle('fa-regular');
                                loveIcon.classList.toggle('fa-solid');
                            } else {
                                console.error(data.message);
                            }
                        })
                        .catch(error => console.error('Error:', error));
                });
            });

            const cards = document.querySelectorAll('.card');
            cards.forEach(card => {
                card.addEventListener('click', function(event) {
                    if (event.target.classList.contains('love-icon')) {
                        event.preventDefault(); // Mencegah default action dari link card
                    }
                });
            });
        });
    </script>

</body>

</html>