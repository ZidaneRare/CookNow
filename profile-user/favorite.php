<?php
session_start();
include("../config/koneksi.php");

if (!isset($_SESSION['login_user'])) {
    header('location: ../');
}

$user_id = $_SESSION['id_user'];

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

$query = "SELECT * FROM recipes WHERE category = '$category'";
$data = mysqli_query($conn, $query);

// Mengambil rating rata-rata dan jumlah review
$rating_result = mysqli_query($conn, "SELECT AVG(rating) as average_rating, COUNT(*) as total_reviews FROM reviews WHERE recipe_id = '$id'");
$rating_data = mysqli_fetch_assoc($rating_result);
$average_rating = round($rating_data['average_rating'], 1);
$total_reviews = $rating_data['total_reviews'];

// Mengambil komentar dan rating
$reviews_result = mysqli_query($conn, "SELECT rating FROM reviews WHERE recipe_id = '$id'");
// Mendapatkan daftar resep yang difavoritkan oleh pengguna
$favorites_query = "SELECT r.* FROM recipes r JOIN favorites f ON r.recipe_id = f.recipe_id WHERE f.user_id = $user_id";
$favorites_result = mysqli_query($conn, $favorites_query);
$favorites = [];
while ($row = mysqli_fetch_assoc($favorites_result)) {
    $favorites[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Style -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/sidebar.css">
    <link rel="stylesheet" href="assets/css/profil.css">

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
    <div class="container-fluid d-flex p-0 m-0" style="min-height: 100vh;">

        <!-- Start Sidebar -->
        <div class="sidebar">
            <nav>
                <a class="mt-1 sidebar-1 d-flex justify-content-evenly align-items-center pt-3" href="../user/home.php" style="text-decoration: none;">
                    <i class="fa-solid fa-house m-0" style="font-size: 27px; height: fit-content;"></i>
                    <img class="mt-2" style="height: 35px;" src="assets/img/logo.png" alt="CookNow">
                </a>

                <ul class="nav-option p-0">
                    <a href="profile.php" class="nav-select fs-h4">
                        <i class="ms-5 pe-3 fa-regular fa-address-card"></i>
                        <p class="p-0 m-0">Profil</p>
                    </a>
                    <a href="favorite.php" class="nav-select fs-h4 actived">
                        <i class="ms-5 pe-3 fa-regular fa-heart"></i>
                        <p class="p-0 m-0">Favorit</p>
                    </a>
                    <a href="../logout.php" class="nav-select fs-h4">
                        <i class="ms-5 pe-3 fa-solid fa-arrow-right-from-bracket"></i>
                        <p class="p-0 m-0">Keluar</p>
                    </a>
                </ul>
            </nav>
        </div>
        <!-- End Sidebar -->

        <!-- Start Profile -->
        <main class="main-content">
            <header class="d-flex align-items-center p-5">
                <h1 class="fs-h2 my-3">Favorit</h1>
            </header>

            <div class="d-flex flex-column align-items-center">
                <!-- Start Isi Konten (Favorite Resep) -->
                <div class="row container-fluid">
                    <?php
                    if (empty($favorites)) {
                        echo '<p class="fs-h2 text-center mt-5" style="color: var(--teal-2);">Belum Ada Favorite</p>';
                    } else {
                        foreach ($favorites as $favorite) {
                            $recipe_id = $favorite['recipe_id'];

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
                                <a href="../user/recipes/detail-recipe.php?id=<?= $recipe_id; ?>" id="semua" class="card rounded-4" style=" text-decoration:none; width: 100%; height: 100%; background-color: white; box-shadow: 0 5px 20px #0003;" data-recipe-id="<?= $recipe_id; ?>">
                                    <div class="bg-image d-flex justify-content-end pt-3 pe-3 rounded-top-4" style="background-image: url(../admin/resep-makanan/<?php echo $favorite['image'] ?>); height: 60%; position:relative;">
                                        <i style="position:absolute; background-color: var(--grey-1); padding:5px; box-shadow:0px 5px 10px #0009; cursor:pointer;" class="love-icon fs-h3 fa-solid fa-heart text-danger rounded-3"></i>
                                    </div>
                                    <div class="desk-rekmakmin pt-2">
                                        <div class="nama-fav d-flex justify-content-between px-3">
                                            <p class="fw-semibold mb-2 fs-h4" style="color:var(--black);"><?php echo $favorite['name'] ?></p>
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
                    }
                    ?>
                </div>
                <!-- End Isi Konten (Favorite Resep) -->
            </div>

        </main>
        <!-- End Profile -->

    </div>
</body>
<script src="https://kit.fontawesome.com/0d731e4b11.js" crossorigin="anonymous"></script>

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

                // Toggle kelas fa-solid dan text-danger untuk mengubah warna
                if (this.classList.contains('fa-solid')) {
                    this.classList.remove('fa-solid');
                    this.classList.add('fa-regular');
                } else {
                    this.classList.remove('fa-regular');
                    this.classList.add('fa-solid');
                }

                fetch('favorites.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `recipe_id=${recipeId}&action=${action}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status !== 'success') {
                            console.error(data.message);
                            // Revert the icon state if there was an error
                            if (action === 'add') {
                                this.classList.remove('fa-regular');
                                this.classList.add('fa-solid');
                            } else {
                                this.classList.remove('fa-solid');
                                this.classList.add('fa-regular');
                            }
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

</html>