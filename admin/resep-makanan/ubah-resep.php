<?php
ob_start(); // Mulai output buffering
include "../../config/koneksi.php";

session_start();

if (!isset($_SESSION['admin_name'])) {
    header('location: ../../');
}

if (isset($_SESSION['msg'])) {
    echo "<script>alert('" . $_SESSION['msg'] . "');</script>";
    unset($_SESSION['msg']); // Hapus pesan setelah ditampilkan
}

$id = $_GET['id'];
$data = mysqli_query($conn, "SELECT * FROM recipes WHERE recipe_id='$id'");
$hasil = mysqli_fetch_array($data);
$category = $hasil['category'];

// Ambil data nutrisi yang sudah dipilih
$nutrisi_data = mysqli_query($conn, "SELECT nutrition_id FROM recipe_nut WHERE recipe_id='$id'");
$selected_nutrition = [];
while ($row = mysqli_fetch_assoc($nutrisi_data)) {
    $selected_nutrition[] = $row['nutrition_id'];
}

// memanggil icon nutrisi
$nutritions_result = mysqli_query($conn, "SELECT * FROM nutritions LIMIT 6");

$nutritions_limit = mysqli_query($conn, "SELECT * FROM nutritions LIMIT 6 OFFSET 6");

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/tambah-resep.css">

    <!-- Font Family -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Title -->
    <link rel="icon" href="../../assets/img/logo_title.png" type="image/x-icon">
    <title id="pageTitle">CookNow | Resep Makanan dan Minuman |</title>

    <!-- CKEditor -->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>

</head>

<body>
    <div class="container-fluid d-flex flex-row align-items-center justify-content-center" style="min-height: 100vh; background-color: var(--teal-1);">
        <div class="py-4">
            <h1 class="text-center fs-h1 mb-3 text-white">Ubah Resep</h1>
            <form class="py-5 px-3 rounded-4" method="post" action="" enctype="multipart/form-data" style="background-color:var(--teal-2);">
                <div class="d-flex justify-content-between">
                    <div class="d-flex flex-column left mx-5">
                        <div class="d-flex">
                            <div class="me-4">
                                <label>ID Resep</label>
                                <input type="text" name="recipe_id" value="<?php echo $hasil['recipe_id']; ?>" required><br>
                            </div>
                            <div>
                                <label>Kategori</label>
                                <select id="category" name="category" class="option" required>
                                    <option value="Breakfast" <?php echo ($category == 'Breakfast') ? 'selected' : ''; ?>>Breakfast</option>
                                    <option value="Lunch" <?php echo ($category == 'Lunch') ? 'selected' : ''; ?>>Lunch</option>
                                    <option value="Dinner" <?php echo ($category == 'Dinner') ? 'selected' : ''; ?>>Dinner</option>
                                    <option value="Beverages" <?php echo ($category == 'Beverages') ? 'selected' : ''; ?>>Beverages</option>
                                </select>
                            </div>
                        </div>


                        <div class="d-flex flex-column">
                            <label>Nama</label>
                            <input type="text" name="name" value="<?php echo $hasil['name']; ?>" required><br>
                        </div>
                        <div class="d-flex flex-column">
                            <label>Deskripsi</label>
                            <textarea name="description" required><?php echo $hasil['description']; ?></textarea><br>
                        </div>
                        <div class="d-flex flex-column">
                            <label>Foto</label>
                            <input style="color:white !important;" type="file" name="image"><br>
                            <img style="color:white !important;" src="<?php echo $hasil['image']; ?>" alt="Recipe Image" width="100">
                        </div>
                        <div class="d-flex flex-column">
                            <label>Bahan-bahan</label>
                            <textarea name="ingredients" id="ingredients"><?php echo $hasil['ingredients']; ?></textarea><br>
                        </div>
                        <div class="d-flex flex-column">
                            <label>Instruksi</label>
                            <textarea name="instructions" id="instructions"><?php echo $hasil['instructions']; ?></textarea><br>
                        </div>
                    </div>

                    <div class="d-flex flex-column right mx-5" style="min-width: 400px !important;">
                        <div class="d-flex flex-column">
                            <label>Informasi Nutrisi</label>
                            <textarea name="nutritions" id="nutritions" required><?php echo $hasil['nutritions']; ?></textarea><br>
                        </div>
                        <label class="mt-2">Pilih Nutrisi untuk Icon</label>
                        <div class="d-flex justify-content-evenly">
                            <div class="me-5">
                                <?php while ($nutritions = mysqli_fetch_assoc($nutritions_result)) : ?>
                                    <label class="cyberpunk-checkbox-label">
                                        <div class="mb-2 d-flex align-items-center">
                                            <input type="checkbox" name="nutrisi_icon[]" value="<?= $nutritions["nutrition_id"]; ?>" class="cyberpunk-checkbox" <?php echo in_array($nutritions["nutrition_id"], $selected_nutrition) ? 'checked' : ''; ?>>
                                            <img class="me-1" style="width: 30px; height: 30px;" src="../<?= $nutritions["icon"]; ?>" alt="">
                                            <h1 class="fs-body mb-0" style="height: fit-content; color: #fff !important;"><?= $nutritions["name"]; ?></h1>
                                        </div>
                                    </label>
                                <?php endwhile; ?>
                            </div>
                            <div class="me-5">
                                <?php while ($nlimit = mysqli_fetch_assoc($nutritions_limit)) : ?>
                                    <label class="cyberpunk-checkbox-label">
                                        <div class="mb-2 d-flex align-items-center">
                                            <input type="checkbox" name="nutrisi_icon[]" value="<?= $nlimit["nutrition_id"]; ?>" class="cyberpunk-checkbox" <?php echo in_array($nlimit["nutrition_id"], $selected_nutrition) ? 'checked' : ''; ?>>
                                            <img class="me-1" style="width: 30px; height: 30px;" src="../<?= $nlimit["icon"]; ?>" alt="">
                                            <h1 class="fs-body mb-0" style="height: fit-content; color: #fff !important;"><?= $nlimit["name"]; ?></h1>
                                        </div>
                                    </label>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    </div>

                </div>


                <div class="d-flex justify-content-center">
                    <a class="fw-medium btn text-white mx-5 rounded-5 px-4 btn-red" style="background-color: var(--red-2);" href="resep.php">Kembali</a>
                    <button class="fw-medium btn mx-5 text-white rounded-5 px-4 btn-blue" style="background-color:var(--blue-2);" type="submit" name="Save">Save</button>
                </div>
        </div>
        </form>
    </div>
    </div>

    <!-- CKEditor Initialization -->
    <script>
        function createCKEditor(element, data) {
            ClassicEditor
                .create(element, {
                    toolbar: ['heading', '|', 'undo', 'redo', '|', 'bold', 'italic', 'bulletedList', 'numberedList'],
                    heading: {
                        options: [{
                                model: 'paragraph',
                                title: 'Paragraph',
                                class: 'ck-heading_paragraph'
                            },
                            {
                                model: 'heading1',
                                view: 'h1',
                                title: 'Heading 1',
                                class: 'ck-heading_heading1'
                            },
                            {
                                model: 'heading2',
                                view: 'h2',
                                title: 'Heading 2',
                                class: 'ck-heading_heading2'
                            }
                        ]
                    }
                })
                .then(editor => {
                    editor.setData(data);
                })
                .catch(error => {
                    console.error(error);
                });
        }

        // Ambil data dari PHP
        const instructionsData = `<?php echo addslashes($hasil['instructions']); ?>`;
        const descriptionData = `<?php echo addslashes($hasil['description']); ?>`;
        const ingredientsData = `<?php echo addslashes($hasil['ingredients']); ?>`;

        // Inisialisasi editor dengan data masing-masing
        createCKEditor(document.querySelector('#instructions'), instructionsData);
        createCKEditor(document.querySelector('#description'), descriptionData);
        createCKEditor(document.querySelector('#ingredients'), ingredientsData);

        ClassicEditor
            .create(document.querySelector('#nutritions'), {
                toolbar: ['undo', 'redo', '|', 'bold', 'italic', 'link', 'tableRow'],
                table: {
                    contentToolbar: ['tableRow']
                }
            })
            .then(editor => {
                editor.setData('<?php echo $hasil['nutritions']; ?>');
            })
            .catch(error => {
                console.error(error);
            });

        // Pastikan CKEditor menyimpan nilai kembali ke textarea sebelum form disubmit
        document.getElementById('recipeForm').addEventListener('submit', function (event) {
            document.querySelector('#instructions').value = ClassicEditor.instances['instructions'].getData();
            document.querySelector('#description').value = ClassicEditor.instances['description'].getData();
            document.querySelector('#ingredients').value = ClassicEditor.instances['ingredients'].getData();
            document.querySelector('#nutritions').value = ClassicEditor.instances['nutritions'].getData();
        });
    </script>

</body>

</html>


<?php
if (isset($_POST['Save'])) {
    $recipe_id = $_POST['recipe_id'];
    $category = $_POST['category'];
    $name = $_POST['name'];
    $instructions = $_POST['instructions'];
    $description = $_POST['description'];
    $ingredients = $_POST['ingredients'];
    $nutritions = $_POST['nutritions'];

    $imageDestination = $hasil['image']; // Default to current image

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $image = $_FILES['image'];
        $imageName = $_FILES['image']['name'];
        $imageTempName = $_FILES['image']['tmp_name'];
        $imageSize = $_FILES['image']['size'];
        $imageError = $_FILES['image']['error'];
        $imageType = $_FILES['image']['type'];

        $imageExt = explode('.', $imageName);
        $imageActualExt = strtolower(end($imageExt));

        $allowed = array('jpg', 'jpeg', 'png');

        if (in_array($imageActualExt, $allowed)) {
            if ($imageError === 0) {
                if ($imageSize < 1000000) {
                    $imageNameNew = uniqid('', true) . "." . $imageActualExt;
                    $imageDestination = '../uploads/' . $imageNameNew;
                    move_uploaded_file($imageTempName, $imageDestination);
                } else {
                    echo "Ukuran foto kamu terlalu besar";
                }
            } else {
                echo "Ada yang error!";
            }
        } else {
            echo "Type foto yang anda masukan salah";
        }
    }

    // Query untuk update data resep
    $sql = "UPDATE recipes SET category='$category', name='$name', instructions='$instructions', description='$description', ingredients='$ingredients', nutritions='$nutritions', image='$imageDestination' WHERE recipe_id='$recipe_id'";

    // Jalankan query update
    if (mysqli_query($conn, $sql)) {
        // Hapus semua nutrisi yang terhubung dengan resep ini
        $delete_existing_nutrisi_sql = "DELETE FROM recipe_nut WHERE recipe_id='$recipe_id'";
        mysqli_query($conn, $delete_existing_nutrisi_sql);

        // Tambahkan nutrisi yang baru dipilih
        if (isset($_POST['nutrisi_icon']) && is_array($_POST['nutrisi_icon'])) {
            foreach ($_POST['nutrisi_icon'] as $nut_id) {
                $nut_id = mysqli_real_escape_string($conn, $nut_id);
                $nut_sql = "INSERT INTO recipe_nut (recipe_id, nutrition_id) VALUES ('$recipe_id', '$nut_id')";
                mysqli_query($conn, $nut_sql);
            }
        }

        // Redirect ke halaman setelah berhasil menyimpan
        header('location: resep.php');
    } else {
        // Jika query gagal dijalankan
        header('location: resep.php?error');
    }
}

ob_end_flush(); // Akhiri output buffering
?>