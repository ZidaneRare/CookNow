<?php
session_start();
include("../config/koneksi.php");

if (!isset($_SESSION['login_user'])) {
    header('location: ../');
    exit;
}

// Mendapatkan ID pengguna dari sesi
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

// Mendapatkan daftar resep yang difavoritkan oleh pengguna
$favorites_query = "SELECT recipe_id FROM favorites WHERE user_id = $user_id";
$favorites_result = mysqli_query($conn, $favorites_query);
$favorites = [];
while ($row = mysqli_fetch_assoc($favorites_result)) {
    $favorites[] = $row['recipe_id'];
}


$query = "SELECT * FROM recipes WHERE category = 'Dinner' LIMIT 3";
$data = mysqli_query($conn, $query);

$query2 = "SELECT * FROM recipes WHERE category = 'Beverages' LIMIT 3";
$data2 = mysqli_query($conn, $query2);

$search_query = "";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_query = mysqli_real_escape_string($conn, $_GET['search']);
    $query3 = "SELECT * FROM recipes WHERE name LIKE '%$search_query%' ORDER BY recipe_id DESC";
} else {
    $query3 = "SELECT * FROM recipes ORDER BY recipe_id DESC";
}

$data3 = mysqli_query($conn, $query3);
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
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Font Family -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Title -->
    <link rel="icon" href="assets/img/logo_title.png" type="image/x-icon">
    <title id="pageTitle">CookNow | Resep Makanan dan Minuman |</title>
</head>

<body>
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
                        $is_favorited = in_array($recipe_id, $favorites);
                    ?>
                        <div class="col-4 p-3" style="min-height: 450px; max-height: 450px;">
                            <a href="recipes/detail-recipe.php?id=<?= $recipe_id; ?>" id="semua" class="card rounded-4" style=" text-decoration:none; width: 100%; height: 100%; background-color: white; box-shadow: 0 5px 20px #0003;" data-recipe-id="<?= $recipe_id; ?>">
                                <div class="bg-image d-flex justify-content-end pt-3 pe-3 rounded-top-4" style="background-image: url(../admin/resep-makanan/<?php echo $hasil['image'] ?>); height: 60%; position:relative;">
                                <i style="position:absolute; background-color: var(--grey-1); padding:5px; box-shadow:0px 5px 10px #0009; cursor:pointer;" class="love-icon fs-h3 <?= $is_favorited ? 'fa-solid' : 'fa-regular' ?> fa-heart text-danger rounded-3"></i>
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

    <!-- Bootstrap JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- Java Script -->
    <script src="assets/javascript/script.js"></script>

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

                    fetch('favorite.php', {
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