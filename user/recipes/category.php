<?php
session_start();
include("../../config/koneksi.php");

if (!isset($_SESSION['login_user'])) {
    header('location: ../');
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
$category = isset($_GET['category']) ? $_GET['category'] : null;

// Mendapatkan ID dari URL
$id = isset($_GET['id']) ? $_GET['id'] : null;

$query = "SELECT * FROM recipes WHERE category = '$category' ORDER BY recipe_id DESC";
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

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Style -->
    <link rel="stylesheet" href="../assets/css/recipes.css">
    <link rel="stylesheet" href="../assets/css/style.css">
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
    <nav class="navbar navbar-dark navbar-expand-lg fixed-top">
        <style>
            .navbar {
                background-color: #0009;
                backdrop-filter: blur(20px);
                max-height: 60px;
            }
        </style>
        <div class="container-fluid" style="padding: 0 100px;">
            <a class="navbar-brand" href="../"><img class="" style="height: 30px ;" src="../assets/img/logo.png" alt="CookNow"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="fs-h4 navbar-nav ms-auto gap-xl-5 gap-md-4 gap-sm-2 py-xl-0 py-md-0 py-3">
                    <li class="nav-item d-flex align-items-center">
                        <a class="p-0 nav-link nav-home" aria-current="page" href="../../">Beranda</a>
                    </li>
                    <li class="nav-item d-flex align-items-center">
                        <a class="p-0 nav-link nav-resep" href="../recipes.php">Resep</a>
                    </li>
                    <li class="nav-item d-flex align-items-center">
                        <a class="p-0 nav-link nav-about" href="../../about-us.php">Tentang Kami</a>
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
    <div class="header bg-image d-flex justify-content-center align-items-center" style="background-image: url(../../assets/img/all-recipe.png); height: 50vh;">
        <div class="mask" style="background-color: hsla(0, 0%, 0%, 0.5);">
            <div class="d-flex justify-content-center" style="height: 50vh; width: 100vw;">
                <div class="d-flex flex-column align-items-center" style="padding-top: 120px;">
                    <p class="fs-h1"><?= $category; ?></p>
                    <div class="d-flex" role="search">
                        <div class="mt-2 ms-2" style="position: absolute;">
                            <i class="fa-solid fa-magnifying-glass fs-h3 pe-2" style="color: var(--grey-3);"></i>
                            <i class="fa-regular fa-window-minimize fs-h3" style="rotate: 90deg; color: var(--grey-3);"></i>
                        </div>
                        <input id="search-input" class="form-control rounded-start-3 rounded-end-0 border-white" style="width: 300px; padding-left: 60px;" type="search" placeholder="Cari Resep..." aria-label="Search">
                        <button id="search-button" class="btn rounded-end-3 rounded-start-0 border-white border-2 px-4" type="submit" style="background-color: var(--teal-2); color: var(--white);">Cari</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Header -->

    <!-- Start Content -->
    <main class="container-fluid m-0 d-flex justify-content-center" style="background-color:var(--grey-1); z-index: -2; padding:0 100px;">
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
    <!-- End Content -->

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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- JavaScript -->
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