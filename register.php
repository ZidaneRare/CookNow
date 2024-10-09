<?php
@include 'config/koneksi.php';

session_start();

if (isset($_POST['submit'])) {
    $name =  $_POST['name'];
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $gender = $_POST['gender'];
    $birth = $_POST['birth'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = 'User';
    $photo = 'assets/img/profile-img.png';

    $select = "SELECT * FROM user WHERE email = '$email'";
    $result = mysqli_query($conn, $select);

    if (mysqli_num_rows($result) > 0) {
        $error_message = 'User already exists!';
        echo "<script>alert('$error_message');</script>";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user data into the database
        $insert = "INSERT INTO user (name, gender, birth, username, email, password, role, photo) VALUES ('$name', '$gender', '$birth', '$username', '$email', '$hashed_password', '$role', '$photo')";
        mysqli_query($conn, $insert);
        header('location:login.php');
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title -->
    <link rel="icon" href="media/logo_title.png" type="image/x-icon">
    <title id="pageTitle">CookNow | Resep Makanan dan Minuman |</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/register.css">

    <!-- Font Family -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>

<body>
    <a href="../cooknow">
        <img class="finallogo" style="height: 40px;" src="assets/img/logo.png" alt="CookNow">
    </a>
    <form class="" action="" method="post">
        <?php
        if (isset($error)) {
            foreach ($error as $error) {
                echo '<span class="error-msg">' . $error . '</span>';
            };
        };
        ?>

        <div class="form">
            <h1 class="fs-h1 text-center">DAFTAR</h1>
            <hr>

            <style>
                input:-webkit-autofill,
                input:-webkit-autofill:hover,
                input:-webkit-autofill:focus,
                input:-webkit-autofill:active {
                    color: white !important;
                    -webkit-text-fill-color: white !important;
                    -webkit-box-shadow: 0 0 0 1000px transparent inset !important;
                    -webkit-background-clip: text !important;
                    background-clip: text !important;
                }
            </style>

            <div class="inputbox group mb-4 mt-4">
                <input required type="text" name="name" class="input">
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Nama Lengkap</label>
            </div>

            <div>
                <p class="mb-0">Jenis Kelamin</p>
                <div class="d-flex mt-0">
                    <label class="radio-button">
                        <input type="radio" name="gender" value="Laki-Laki">
                        <span class="radio"></span>
                        Laki-laki
                    </label>

                    <label class="radio-button">
                        <input type="radio" name="gender" value="Perempuan">
                        <span class="radio"></span>
                        Perempuan
                    </label>
                </div>
            </div>
            <div class="inputbox group mb-4 mt-4">
                <p class="mb-0">Tanggal Lahir</p>
                <input required type="date" name="birth" class="input">
                <span class="highlight"></span>
                <span class="bar"></span>
            </div>


            <div class="inputbox group mb-4 mt-4">
                <input required type="text" name="username" class="input" pattern="^\S+$" placeholder="Tidak boleh menggunakan spasi">
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Nama Pengguna</label>
            </div>

            <div class="inputbox group mb-4 mt-4">
                <input required type="text" name="email" class="input">
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Email</label>
            </div>

            <div class="inputbox group mb-3">
                <input required type="password" name="password" class="input">
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Password</label>
            </div>

            <div class="d-flex justify-content-center mb-3 mt-4">
                <input style="width:300px;" class="fs-h4 btn btn-hvr rounded-5 border border-3 py-2" type="submit" name="submit" value="Daftar">
                <style>
                    .btn-hvr {
                        background-color: var(--teal-4);
                        color: var(--teal-2);
                        border-color: var(--teal-4);
                        font-size: 18px;
                        font-weight: 600;
                    }

                    .btn-hvr:hover {
                        background-color: var(--teal-3);
                        color: var(--white);
                    }

                    .btn-hvr:active {
                        border-color: var(--white);
                        background-color: var(--teal-2) !important;
                    }
                </style>
            </div>

            <div class="bottom d-flex flex-row justify-content-center m-0 p-0">
                <p class="me-1">Sudah punya akun?</p>
                <a style="color: var(--teal-3); text-decoration:none;" href="login.php">Ayo Masuk</a>
            </div>
        </div>
    </form>



    <!-- Java Script -->
    <script src="javascript/script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const usernameInput = document.querySelector('input[name="username"]');

            usernameInput.addEventListener('blur', function() {
                if (usernameInput.value.includes(' ')) {
                    usernameInput.value = '';
                }
            });
        });
    </script>
</body>

</html>