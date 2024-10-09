<?php
session_start();
include "config/koneksi.php";

if (isset($_SESSION['login_user'])) {
    header('Location: user/home.php');
} else if (isset($_SESSION['admin_name'])) {
    header('Location: admin/dashboard.php');
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

$query = "SELECT * FROM recipes WHERE category = 'Dinner' LIMIT 3";
$data = mysqli_query($conn, $query);

// Mendapatkan ID dari URL
$id = isset($_GET['id']) ? $_GET['id'] : null;

// Mengambil rating rata-rata dan jumlah review
$rating_result = mysqli_query($conn, "SELECT AVG(rating) as average_rating, COUNT(*) as total_reviews FROM reviews WHERE recipe_id = '$id'");
$rating_data = mysqli_fetch_assoc($rating_result);
$average_rating = round($rating_data['average_rating'], 1);
$total_reviews = $rating_data['total_reviews'];

// Mengambil komentar dan rating
$reviews_result = mysqli_query($conn, "SELECT rating FROM reviews WHERE recipe_id = '$id'");

$query2 = "SELECT * FROM recipes WHERE category = 'Beverages' LIMIT 3";
$data2 = mysqli_query($conn, $query2);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Style -->
    <link rel="stylesheet" href="assets/css/home.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/footer.css">

    <!-- Font Family -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Title -->
    <link rel="icon" href="assets/img/logo_title.png" type="image/x-icon">
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
            <a class="navbar-brand" href="../cooknow"><img class="" style="height: 30px ;" src="assets/img/logo.png" alt="CookNow"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="fs-h4 navbar-nav ms-auto gap-xl-5 gap-md-4 gap-sm-2 py-xl-0 py-md-0 py-3">
                    <li class="nav-item">
                        <a class="nav-link nav-home active" aria-current="page" href="../cooknow">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-resep" href="recipes.php">Resep</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-about" href="about-us.php">Tentang Kami</a>
                    </li>
                    <li>
                        <a class="fs-h4 btn btn-hvr rounded-5 border border-3 px-4" href="login.php">Masuk</a>
                        <style>
                            .btn-hvr {
                                background-color: var(--teal-2);
                                color: var(--white);
                                border-color: var(--white);
                                font-size: 18px;
                                font-weight: 600;
                            }

                            .btn-hvr:hover {
                                background-color: var(--teal-3);
                            }

                            .btn-hvr:active {
                                background-color: var(--teal-1) !important;
                            }
                        </style>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- End Navbar -->

    <!-- Start Header -->
    <div class=" header bg-image d-flex justify-content-center align-items-center" style="background-image: url(assets/img/home_background.jpg); height: 50vh;">
        <div class="mask" style="background-color: hsla(0, 0%, 0%, 0.5);">
            <div class="d-flex justify-content-end" style="height: 50vh; width: 100vw;">
                <div class="d-flex flex-column align-items-end" style="padding-top: 80px; padding-right: 120px;">
                    <p class="fs-h1">Temukan Sensasi Baru Dalam</p>
                    <p class="fs-h1">Dunia Kuliner Anda!</p>
                    <form action="recipes.php" method="get" class="d-flex" role="search">
                        <div class="mt-2 ms-2" style="position: absolute;">
                            <i class="fa-solid fa-magnifying-glass fs-h3 pe-2" style="color: var(--grey-3);"></i>
                            <i class="fa-regular fa-window-minimize fs-h3" style="rotate: 90deg; color: var(--grey-3);"></i>
                        </div>
                        <input class="form-control rounded-start-3 rounded-end-0 border-white" style="width: 300px; padding-left: 60px;" type="search" placeholder="Cari Resep..." aria-label="Search">
                        <button class="btn rounded-end-3 rounded-start-0 border-white border-2 px-4" type="submit" style="background-color: var(--teal-2); color: var(--white);">Cari</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Header -->

    <!-- Start Rekomendasi Populer -->
    <div class="container-fluid border-top border-white border-4 home" style="height: 525px;">
        <div class="rekpop border border-bottom-0 border-white border-4 rounded-5 rounded-bottom-0">
            <p class="rekpop-title fs-h2 text-center">KATEGORI POPULER</p>
        </div>

        <div class="container d-flex align-items-center" style="padding-top: 120px;">
            <div class="col-12">
                <div class="row">
                    <a href="recipes/category.php?category=Breakfast" class="col-3 d-flex flex-column card-rekpop" style="text-decoration: none;">
                        <img class="rounded-3" src="assets/img/breakfast.png" alt="">
                        <p class="text-center text-black fw-semibold pt-3 fs-h3">BREAKFAST</p>
                    </a>

                    <a href="recipes/category.php?category=Lunch" class="col-3 d-flex flex-column card-rekpop" style="text-decoration: none;">
                        <img class="rounded-3" src="assets/img/lunch.png" alt="">
                        <p class="text-center text-black fw-semibold pt-3 fs-h3">LUNCH</p>
                    </a>

                    <a href="recipes/category.php?category=Dinner" class="col-3 d-flex flex-column card-rekpop" style="text-decoration: none;">
                        <img class="rounded-3" src="assets/img/dinner.png" alt="">
                        <p class="text-center text-black fw-semibold pt-3 fs-h3">DINNER</p>
                    </a>

                    <a href="recipes/category.php?category=Beverages" class="col-3 d-flex flex-column card-rekpop" style="text-decoration: none;">
                        <img class="rounded-3" src="assets/img/beverage.png" alt="">
                        <p class="text-center text-black fw-semibold pt-3 fs-h3">BEVERAGE</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- End Rekomendasi Populer -->

    <!-- Start Rekomendasi Makanan -->
    <div class="container-fluid rekmakmin-pad" style="background-color: #fff;">
        <div class="" style="margin-left: 20%;">
            <p class="fs-h2 rekpop-title" style="color: var(--black); border-bottom: 1.5px solid var(--black); width: fit-content;">Rekomendasi Makanan Populer</p>
        </div>
        <div class="d-flex ms-4 p-0">
            <img src="assets/img/vektor1.png" style="height: 550px; position: absolute; margin-top: -100px; left:0;">
            <div class="d-flex" style="margin-left:320px; scale:80%;">
                <div class="row">
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
                        <div class="col-4 p-3" style="min-height: 450px; max-height: 450px;">
                            <a href="recipes/detail-recipe.php?id=<?= $recipe_id; ?>" id="semua" class="card rounded-4" style=" text-decoration:none; width: 100%; height: 100%; background-color: white; box-shadow: 0 5px 20px #0003;">
                                <div class="bg-image d-flex justify-content-end pt-3 pe-3 rounded-top-4" style="background-image: url(admin/resep-makanan/<?php echo $hasil['image'] ?>); height: 60%; position:relative;">
                                    <i style="position:absolute; background-color: var(--grey-1); padding:5px; box-shadow:0px 5px 10px #0009; cursor:pointer;" class="love-icon fs-h3 fa-regular fa-heart text-danger rounded-3"></i>
                                </div>
                                <div class="desk-rekmakmin pt-2">
                                    <div class="nama-fav d-flex justify-content-between px-3">
                                        <p class="fw-semibold mb-2 fs-h4" style="color:var(--black);"><?php echo $hasil['name'] ?></p>
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
                                            echo "<img src='$icon' alt='Nutrition Icon' style='width: 30px; height: 30px; margin-right: 5px;'>";
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
        </div>
    </div>
    <!-- End Rekomendasi Makanan -->

    <!-- Start Nutrisi -->
    <div class="container-fluid d-flex justify-content-center align-items-center" style="background-color: var(--teal-2); height: 550px; gap: 100px; padding-top: 60px;">
        <div style=" width: 50%;">
            <p class="fs-h1 nut-title" style="width: 400px;">Mengapa nutrisi penting bagi tubuh?</p>
            <p style="width: 450px;">Nutrisi penting bagi tubuh karena berperan dalam mempertahankan kesehatan dan mendukung fungsi tubuh yang optimal</p>
        </div>
        <div style="position: absolute;">
            <img height="500px" src="assets/img/vektor2.png" alt="">
        </div>
        <div class="d-flex rounded-5 rounded-top-0 rounded-end-5" style="background-color: #fff; height: fit-content; padding: 40px;">
            <div class="left me-4">
                <div class="d-flex">
                    <img class="me-3" style="width: 36px; height: 36px;" src="assets/icon/nut-Kalori.png" alt="">
                    <p class="fs-h4 d-flex align-items-center" style="color: var(--black); height: 36px;">Kalori</p>
                </div>
                <div class="d-flex">
                    <img class="me-3" style="width: 36px; height: 36px;" src="assets/icon/nut-Sodium.png" alt="">
                    <p class="fs-h4 d-flex align-items-center" style="color: var(--black); height: 36px;">Sodium</p>
                </div>
                <div class="d-flex">
                    <img class="me-3" style="width: 36px; height: 36px;" src="assets/icon/nut-ZatBesi.png" alt="">
                    <p class="fs-h4 d-flex align-items-center" style="color: var(--black); height: 36px;">Zat Besi</p>
                </div>
                <div class="d-flex">
                    <img class="me-3" style="width: 36px; height: 36px;" src="assets/icon/nut-Energi.png" alt="">
                    <p class="fs-h4 d-flex align-items-center" style="color: var(--black); height: 36px;">Energi</p>
                </div>
                <div class="d-flex">
                    <img class="me-3" style="width: 36px; height: 36px;" src="assets/icon/nut-Lemak.png" alt="">
                    <p class="fs-h4 d-flex align-items-center" style="color: var(--black); height: 36px;">Lemak</p>
                </div>
                <div class="d-flex">
                    <img class="me-3" style="width: 36px; height: 36px;" src="assets/icon/nut-LemakJenuh.png" alt="">
                    <p class="fs-h4 d-flex align-items-center" style="color: var(--black); height: 36px;">Lemak Jenuh</p>
                </div>
            </div>
            <div class="right">
                <div class="d-flex">
                    <img class="me-3" style="width: 36px; height: 36px;" src="assets/icon/nut-Kalium.png" alt="">
                    <p class="fs-h4 d-flex align-items-center" style="color: var(--black); height: 36px;">Kalium</p>
                </div>
                <div class="d-flex">
                    <img class="me-3" style="width: 36px; height: 36px;" src="assets/icon/nut-Serat.png" alt="">
                    <p class="fs-h4 d-flex align-items-center" style="color: var(--black); height: 36px;">Serat</p>
                </div>
                <div class="d-flex">
                    <img class="me-3" style="width: 36px; height: 36px;" src="assets/icon/nut-Kalsium.png" alt="">
                    <p class="fs-h4 d-flex align-items-center" style="color: var(--black); height: 36px;">Kalsium</p>
                </div>
                <div class="d-flex">
                    <img class="me-3" style="width: 36px; height: 36px;" src="assets/icon/nut-Gula.png" alt="">
                    <p class="fs-h4 d-flex align-items-center" style="color: var(--black); height: 36px;">Gula</p>
                </div>
                <div class="d-flex">
                    <img class="me-3" style="width: 36px; height: 36px;" src="assets/icon/nut-Karbohidrat.png" alt="">
                    <p class="fs-h4 d-flex align-items-center" style="color: var(--black); height: 36px;">Karbohidrat</p>
                </div>
                <div class="d-flex">
                    <img class="me-3" style="width: 36px; height: 36px;" src="assets/icon/nut-Protein.png" alt="">
                    <p class="fs-h4 d-flex align-items-center" style="color: var(--black); height: 36px;">Protein</p>
                </div>
            </div>
        </div>
    </div>
    <!-- End Nutrisi -->

    <!-- Start Rekomendasi Minuman -->
    <div class="container-fluid rekmakmin-pad rekmin-back" style="background-color: #fff;">
        <div class="" style="margin-left: 20%;">
            <p class="fs-h2 rekpop-title" style="color: var(--black); border-bottom: 1.5px solid var(--black); width: fit-content;">Rekomendasi Minuman Populer</p>
        </div>
        <div class="d-flex ms-4 p-0">
            <img src="assets/img/vektor3.png" style="height: 550px; position: absolute; margin-top: -100px; right: 0;">
            <div class="d-flex me-3" style="margin-left:-30px; scale:80%;">
                <!-- <div class="container-fluid d-flex container-makan gap-5 justify-content-start" id="rekomendasiMinuman"></div> -->
                <div class="row">
                    <?php
                    while ($hasil = mysqli_fetch_array($data2)) {
                        $recipe_id = $hasil['recipe_id'];
                        // Query untuk mendapatkan rating dan review
                        $reviews_result = mysqli_query($conn, "SELECT rating FROM reviews WHERE recipe_id = '$recipe_id'");
                        $average_rating = 0;
                        $total_reviews = 0;
                        $total_rating = 0;
                        while ($review = mysqli_fetch_assoc($reviews_result)) {
                            $total_rating += $review['rating'];
                            $total_reviews++;
                        }
                        if ($total_reviews > 0) {
                            $average_rating = round($total_rating / $total_reviews, 1);
                        }
                    ?>
                        <div class="col-4 p-3" style="min-height: 450px; max-height: 450px;">
                            <a href="recipes/detail-recipe.php?id=<?= $recipe_id; ?>" id="semua" class="card rounded-4" style=" text-decoration:none; width: 100%; height: 100%; background-color: white; box-shadow: 0 5px 20px #0003;">
                                <div class="bg-image d-flex justify-content-end pt-3 pe-3 rounded-top-4" style="background-image: url(admin/resep-makanan/<?php echo $hasil['image'] ?>); height: 60%; position:relative;">
                                    <i style="position:absolute; background-color: var(--grey-1); padding:5px; box-shadow:0px 5px 10px #0009; cursor:pointer;" class="love-icon fs-h3 fa-regular fa-heart text-danger rounded-3"></i>
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
                                            echo "<img src='$icon' alt='Nutrition Icon' style='width: 30px; height: 30px; margin-right: 5px;'>";
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
        </div>
    </div>

    <!-- End Rekomendasi Minuman -->

    <!-- Start footer -->
    <div class="container-fluid p-0">
        <div class="container-fluid footer gap-2">
            <img src="assets/img/logo.png">
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
    <script src="assets/javascript/script.js"></script>
    <script src="assets/javascript/script_rekmakmin.js"></script>


    <script>
        // Menggunakan event delegation untuk menangani klik pada ikon love di dalam card
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
            alertDiv.style.transform = 'translate(-50%, 0%)';
            alertDiv.style.padding = '20px 30px';
            alertDiv.style.backgroundColor = 'white';
            alertDiv.style.boxShadow = '0 10px 20px rgba(0, 0, 0, 0.9)';
            alertDiv.style.zIndex = '99999';
            alertDiv.style.borderRadius = '8px';
            alertDiv.innerHTML = `
        <p class="text-black">Harap login terlebih dahulu.</p>
        <div class="d-flex justify-content-end gap-2">
        <button class="btn btn-danger" id="closeAlertButton">Tutup</button>
        <a href="login.php" class="btn btn-primary">Masuk</a>
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