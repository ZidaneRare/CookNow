<?php
session_start();

if (!isset($_SESSION['admin_name'])) {
    header('location: ../../');
}


if (isset($_SESSION['msg'])) {
    echo "<script>alert('" . $_SESSION['msg'] . "');</script>";
    unset($_SESSION['msg']); // Hapus pesan setelah ditampilkan
}

include "../../config/koneksi.php";

if (isset($_POST['submit'])) {
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

    $recipe_id =  $_POST['recipe_id'];
    $category = $_POST['category'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $instructions = $_POST['instructions'];
    $ingredients = $_POST['ingredients'];
    $nutritions = $_POST['nutritions'];

    $select = "SELECT * FROM recipes WHERE recipe_id = '$recipe_id'";
    $result = mysqli_query($conn, $select);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['msg'] = "ID Resep sudah ada, mohon masukan ulang";
        header('location: table-resep.php');
    } else {
        $sql = "INSERT INTO recipes(recipe_id,category,name,image,instructions,description, ingredients, nutritions) VALUES ('$recipe_id','$category','$name','$imageDestination','$instructions','$description', '$ingredients', '$nutritions')";
        //cek apakah simpan berhasil
        if (mysqli_query($conn, $sql)) {
            // Jika berhasil menyimpan resep, simpan juga nutrisi yang dipilih
            if (isset($_POST['nutrisi_icon']) && is_array($_POST['nutrisi_icon'])) {
                foreach ($_POST['nutrisi_icon'] as $nut_id) {
                    $nut_id = mysqli_real_escape_string($conn, $nut_id);
                    $nut_sql = "INSERT INTO recipe_nut (recipe_id, nutrition_id) VALUES ('$recipe_id', '$nut_id')";
                    mysqli_query($conn, $nut_sql);
                }
            }
            // Redirect ke table-resep.php setelah berhasil menyimpan
            header('location:resep.php');
        } else {
            // Jika tidak berhasil, redirect dengan error
            header('location:resep.php?error');
        }
    }
}

// memanggil icon nutrisi
$nutritions_result = mysqli_query($conn, "SELECT * FROM nutritions LIMIT 6");
$nutritions = mysqli_fetch_assoc($nutritions_result);

$nutritions_limit = mysqli_query($conn, "SELECT * FROM nutritions LIMIT 6 OFFSET 6");
$nlimit = mysqli_fetch_assoc($nutritions_limit);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Style -->
    <link rel="stylesheet" href="../assets/css/tambah-resep.css">
    <link rel="stylesheet" href="../assets/css/style.css">

    <!-- Font Family -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Title -->
    <link rel="icon" href="../assets/img/logo_title.png" type="image/x-icon">
    <title id="pageTitle">CookNow | Resep Makanan dan Minuman |</title>

    <!--CkEditor-->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>

</head>

<body>
    <div class="container-fluid d-flex flex-row align-items-center justify-content-center" style="background-color: var(--teal-1); min-height: 100vh;">
        <div class="pb-5">
            <h2 class="text-center p-2 text-white fs-h1">Tambah Resep</h2>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
                <?php
                if (isset($error)) {
                    foreach ($error as $error) {
                        echo '<span class="error-msg">' . $error . '</span>';
                    };
                };
                ?>
                <div class="container d-flex flex-column py-5 px-3 rounded-4" style="width: fit-content; background-color: var(--teal-2);">
                    <div class="d-flex justify-content-between">

                        <div class="d-flex flex-column left mx-5">
                            <div class="d-flex">
                                <div class="me-4">
                                    <label class="me-2">ID Resep</label>
                                    <input style="width: 50px;" type="int" name="recipe_id" value="<?= $auto_increment_resep = $row['Auto_increment']; ?>" readonly>
                                </div>

                                <div>
                                    <label class="me-2">Kategori</label>
                                    <select id="kategori" name="category" class="option .selectpopup" required>
                                        <option value="">--Pilih Kategori--</option>
                                        <option value="Breakfast">Breakfast</option>
                                        <option value="Lunch">Lunch</option>
                                        <option value="Dinner">Dinner</option>
                                        <option value="Beverages">Beverages</option>
                                    </select>
                                </div>
                            </div>


                            <label>Nama</label>
                            <input type="text" name="name" id="nama" required value="">

                            <label>Deskripsi</label>
                            <textarea type="text" name="description" id="deskripsi" value=""></textarea>

                            <label>Foto</label>
                            <input style="color: white !important;" type="file" name="image" id="image" required value="">

                            <label>Bahan-bahan</label>
                            <textarea id="ingredients" name="ingredients"></textarea>

                            <label>Cara Memasak</label>
                            <textarea id="instructions" name="instructions"></textarea>
                        </div>

                        <div class="d-flex flex-column right mx-5" style="min-width: 400px;">
                            <label>Informasi Nutrisi</label>
                            <textarea id="nutritions" name="nutritions"></textarea>

                            <div>
                                <label class="mt-2">Pilih Nutrisi untuk Icon</label>

                                <div class="d-flex justify-content-evenly">
                                    <div class="me-5">
                                        <?php foreach ($nutritions_result as $nutritions) : ?>
                                            <label class="cyberpunk-checkbox-label">
                                                <div class="mb-2 d-flex align-items-center">
                                                    <input type="checkbox" name="nutrisi_icon[]" value="<?= $nutritions["nutrition_id"]; ?>" class="cyberpunk-checkbox">
                                                    <img class="me-1" style="width: 30px; height: 30px;" src="../<?= $nutritions["icon"]; ?>" alt="">
                                                    <h1 class="fs-body mb-0" style=" height: fit-content; color: #fff !important;"><?= $nutritions["name"]; ?></h1>
                                                </div>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>

                                    <div class="me-5">
                                        <?php foreach ($nutritions_limit as $nlimit) : ?>
                                            <label class="cyberpunk-checkbox-label">
                                                <div class="mb-2 d-flex align-items-center">
                                                    <input type="checkbox" name="nutrisi_icon[]" value="<?= $nlimit["nutrition_id"]; ?>" class="cyberpunk-checkbox">
                                                    <img class="me-1" style="width: 30px; height: 30px;" src="../<?= $nlimit["icon"]; ?>" alt="">
                                                    <h1 class="fs-body mb-0" style=" height: fit-content; color: #fff !important;"><?= $nlimit["name"]; ?></h1>
                                                </div>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>

                                </div>


                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        <a class="fw-medium btn text-white mx-5 rounded-5 px-4 btn-red" style="background-color: var(--red-2);" href="resep.php">Kembali</a>
                        <button class="fw-medium btn mx-5 text-white rounded-5 px-4 btn-blue" style="background-color:var(--blue-2);" type="submit" name="submit">Simpan</button>
                    </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- Java Script -->
    <script>
        function createCKEditor(element) {
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
                .catch(error => {
                    console.error(error);
                });
        }

        createCKEditor(document.querySelector('#ingredients'))
        createCKEditor(document.querySelector('#instructions'))
    </script>

    <script>
        ClassicEditor
            .create(document.querySelector('#nutritions'), {
                toolbar: ['undo', 'redo', '|', 'bold', 'italic', 'link', 'tableRow'],
                table: {
                    contentToolbar: ['tableRow']
                },
                initialData: `
                <table>
                    <tbody>
                        <tr>
                            <td>Nama Nutrisi</td>
                            <td>Jumlah Nutrisi</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="text-align: right !important;"></td>
                        </tr>
                    </tbody>
                </table>
                <p>Informasi Tambahan</p>
            `
            })
            .catch(error => {
                console.error(error);
            });
    </script>


    <script src="javascript/script.js"></script>
</body>

</html>