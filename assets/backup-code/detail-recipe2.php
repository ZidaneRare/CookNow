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

$query = "SELECT * FROM recipes WHERE recipe_id != $id ORDER BY RAND() LIMIT 4";
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

    <div class="container-fluid mb-5 rounded-top-5" style="background-color: #fff; position: relative;">
        <div class="pt-5">
            <h2 class="fs-h2 text-center" style="color: var(--black);">RESEP LAIN</h2>
            <main class="container-fluid m-0 d-flex justify-content-center" style="padding:0 100px;">
                <div class="container-fluid m-0 my-5" style="min-height: 70vh">
                    <div class="row row-cols-sm-2 row-cols-md-2 row-cols-lg-4 row-cols-xxl-5">
                        <?php
                        while ($hasil = mysqli_fetch_array($data)) {
                            $recipe_id = $hasil['recipe_id'];
                            $is_favorites = in_array($recipe_id, $favorites);
                        ?>
                            <div class="col p-3" style="min-height: 400px; max-height: 450px;">
                                <a href="detail-recipe.php?id=<?= $recipe_id; ?>" id="semua" class="card rounded-4" style=" text-decoration:none; width: 100%; height: 100%; background-color: white; box-shadow: 0 5px 20px #0003;" data-recipe-id="<?= $recipe_id; ?>">
                                    <div class="d-flex justify-content-end pt-3 pe-3 rounded-top-4" style="background-image: url(../../admin/resep-makanan/<?php echo $hasil['image'] ?>); height: 60%; position:relative; background-size:cover;">
                                        <i style="position:absolute; background-color: var(--grey-1); padding:5px; box-shadow:0px 5px 10px #0009; cursor:pointer;" class="love-icon fs-h3 <?= $is_favorites ? 'fa-solid' : 'fa-regular' ?> fa-heart text-danger rounded-3"></i>
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
    <!-- Bootstrap JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

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