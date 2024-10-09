<?php
session_start();
include("../config/koneksi.php");

if (!isset($_SESSION['login_user'])) {
    header('location: ../');
    exit;
}

// Ambil data pengguna dari database berdasarkan email yang disimpan di session
$email_from_session = $_SESSION['user_email'];
$query = "SELECT * FROM user WHERE email = '$email_from_session'";
$result = mysqli_query($conn, $query);

if ($result) {
    $user = mysqli_fetch_assoc($result);
} else {
    echo "Error fetching user data.";
    exit;
}

if (isset($_POST['simpan'])) {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $birth = mysqli_real_escape_string($conn, $_POST['dob']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email_input = mysqli_real_escape_string($conn, $_POST['email']);

    // Validasi email
    if (!filter_var($email_input, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
    } else {
        $photo = $user['photo']; // Default to current photo

        // Handle image upload
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
                        $imageDestination = 'uploads/' . $imageNameNew;
                        move_uploaded_file($imageTempName, $imageDestination);
                        $photo = $imageDestination; // Update photo path
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

        // Update query
        if ($email_input !== $email_from_session) {
            $update_query = "UPDATE user SET name = '$fullname', gender = '$gender', birth = '$birth', username = '$username', email = '$email_input', photo = '$photo' WHERE email = '$email_from_session'";
        } else {
            $update_query = "UPDATE user SET name = '$fullname', gender = '$gender', birth = '$birth', username = '$username', photo = '$photo' WHERE email = '$email_from_session'";
        }

        $update_result = mysqli_query($conn, $update_query);

        if ($update_result) {
            $_SESSION['login_user'] = $fullname;
            $_SESSION['user_email'] = $email_input;
            $_SESSION['user_photo'] = $photo;
            echo "<script>
            window.location.href = '../profile-user/profile.php';
                alert('Data berhasil diperbarui');
            </script>";
        } else {
            echo "Error updating profile: " . mysqli_error($conn);
        }
    }
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
    <div class="container-fluid d-flex p-0 m-0" style="min-height: 100vh;">

        <!-- Start Sidebar -->
        <div class="sidebar">
            <nav>
                <a class="mt-1 sidebar-1 d-flex justify-content-evenly align-items-center pt-3" href="../user/home.php" style="text-decoration: none;">
                    <i class="fa-solid fa-house m-0" style="font-size: 27px; height: fit-content;"></i>
                    <img class="mt-2" style="height: 35px;" src="assets/img/logo.png" alt="CookNow">
                </a>

                <ul class="nav-option p-0">
                    <a href="profile.php" class="nav-select fs-h4 actived">
                        <i class="ms-5 pe-3 fa-regular fa-address-card"></i>
                        <p class="p-0 m-0">Profil</p>
                    </a>
                    <a href="favorite.php" class="nav-select fs-h4">
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
                <h2 class="fs-h2 my-3">Profil</h2>
            </header>
            
            
            <div class="container-fluid d-flex flex-column align-items-center">
                <form method="POST" action="" enctype="multipart/form-data" class="main-section d-flex justify-content-evenly">
                    <!--Popup-->
                    <div id="popup" class="popup">
                        <div class="popup-content">
                            <div>
                                <div class="scrollable-content">
                                <input type="file" name="image" id = "image" accept=".jpg, .jpeg, .png" value="" style="color:white !important;">
                                <button name="simpan" class="btn btn-primary my-3">Simpan</button>
                                <button id="closePopup" class="btn btn-danger">Kembali</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Popup-->
                    <div class="profile-picture">
                        <img src="<?php echo $_SESSION['user_photo']; ?>" alt="Profile Picture" class="border border-3" style="width: 150px; height: 150px;">
                        <div class="d-flex flex-column justify-content-center mt-3 gap-3">
                            <button type="button" class="fw-medium btn text-white mx-5 rounded-5 px-4 btn-blue" style="background-color: var(--blue-2);" id="ganti">Ganti Foto</button>
                            <button type="button" class="fw-medium btn text-white mx-5 rounded-5 px-4 btn-red" style="background-color: var(--red-2);" id="hapusFoto">Hapus Foto</button>
                        </div>
                    </div>
                    <div class="profile-form">
                        <div class="form-group">
                            <label class="fs-body" for="fullname">Nama Lengkap</label>
                            <input type="text" id="fullname" name="fullname" value="<?php echo $user['name']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="mb-0">Jenis Kelamin</label>
                            <div class="d-flex">
                                <label class="radio-button">
                                    <input type="radio" name="gender" value="Laki-laki" <?php if ($user['gender'] == 'Laki-laki') echo 'checked'; ?>>
                                    <span class="radio"></span>
                                    Laki-laki
                                </label>

                                <label class="radio-button">
                                    <input type="radio" name="gender" value="Perempuan" <?php if ($user['gender'] == 'Perempuan') echo 'checked'; ?>>
                                    <span class="radio"></span>
                                    Perempuan
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="fs-body" for="dob">Tanggal Lahir</label>
                            <input type="date" id="dob" name="dob" value="<?php echo $user['birth']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="fs-body" for="username">Nama Pengguna</label>
                            <input type="text" id="username" name="username" pattern="^\S+$" placeholder="Tidak boleh menggunakan spasi" value="<?php echo $user['username']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="fs-body" for="email">Email</label>
                            <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>
                        </div>
                        <div class="text-end mt-5">
                            <button onclick="return confirm('Apakah anda yakin ingin memperbarui data?')" type="submit" name="simpan" class="fw-medium btn text-white rounded-5 px-4 btn-blue" style="background-color: var(--blue-2);">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>

        </main>
        <!-- End Profile -->

    </div>
</body>


<script src="https://kit.fontawesome.com/0d731e4b11.js" crossorigin="anonymous"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const usernameInput = document.querySelector('input[name="username"]');

        usernameInput.addEventListener('blur', function() {
            if (usernameInput.value.includes(' ')) {
                usernameInput.value = '';
            }
        });
    });

    const popup = document.querySelector('#popup');
    const openPopup = document.querySelector('#ganti');
    const closePopup = document.querySelector('#closePopup');

    openPopup.addEventListener('click', function() {
        popup.style.display = 'block';
    });
    
    closePopup.addEventListener('click', function() {
        popup.style.display = 'none';
    });
        
    window.addEventListener('click', function(event) {
        if (event.target === popup) {
        popup.style.display = 'none';
        }
    });

</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const hapusFotoButton = document.querySelector('#hapusFoto');
        hapusFotoButton.addEventListener('click', function() {
            if (confirm('Apakah Anda yakin ingin menghapus foto profil?')) {
                // Kirim request untuk menghapus foto dari basis data
                const xhr = new XMLHttpRequest();
                xhr.open('GET', 'hapus-photo.php', true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        // Foto berhasil dihapus, update tampilan dengan foto default
                        document.querySelector('.profile-picture img').src = 'assets/img/profile-img.png';
                        alert('Foto profil berhasil dihapus.');
                    }
                };
                xhr.send();
            }
        });
    });
</script>


</html>