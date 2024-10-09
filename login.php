<?php
@include 'config/koneksi.php';

session_start();

if (isset($_POST['submit'])) {
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $password = $_POST['password'];

   $select = "SELECT * FROM user WHERE email = '$email'";
   $result = mysqli_query($conn, $select);

   if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_array($result);

      // Verifikasi kata sandi menggunakan password_verify()
      if (password_verify($password, $row['password'])) {

         echo "Role: " . $row['role'] . "<br>";

         if ($row['role'] == 'Admin') {
            $_SESSION['admin_name'] = $row['name'];
            $_SESSION['user_role'] = $row['role'];
            $_SESSION['admin_photo'] = $row['photo'];
            //header('location: admin form');
            header('location: admin/dashboard.php');
         } else if ($row['role'] == 'User') {
            $_SESSION['id_user'] = $row['user_id'];
            var_dump($_SESSION['user_id']);
            $_SESSION['login_user'] = $row['name'];
            $_SESSION['user_email'] = $row['email'];
            $_SESSION['user_name'] = $row['username'];
            $_SESSION['pass'] = $row['password'];
            $_SESSION['user_birth'] = $row['birth'];
            $_SESSION['user_gender'] = $row['gender'];
            $_SESSION['user_photo'] = $row['photo'];
            $_SESSION['user_role'] = $row['role'];
            header('location: user/home.php');
         }
      } else {
         $error[] = 'Incorrect email or password!';
      }
   } else {
      $error[] = 'Incorrect email or password!';
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
   <link rel="stylesheet" href="assets/css/login.css">

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
      <img class="finallogo" style="height: 40px ;" src="assets/img/logo.png" alt="CookNow">
   </a>
   <div class="form-container">
      <form action="" method="post">
         <div class="form">
            <h1 class="fs-h1 text-center">MASUK</h1>
            <hr>
            <?php
            if (isset($error)) {
               foreach ($error as $error) {
                  echo '<span class="error-msg">' . $error . '</span>';
               };
            };
            ?>

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

            <div class="group mb-4 mt-4">
               <input required type="text" name="email" class="input">
               <span class="highlight"></span>
               <span class="bar"></span>
               <label>Email</label>
            </div>

            <div class="group mb-3">
               <input required type="password" name="password" class="input">
               <span class="highlight"></span>
               <span class="bar"></span>
               <label>Password</label>
            </div>

            <div class="d-flex justify-content-center mb-3 mt-4">
               <input style="width:300px;" class="fs-h4 btn btn-hvr rounded-5 border border-3 py-2" type="submit" name="submit" value="Masuk">
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
               <p class="me-1">Belum punya akun?</p>
               <a style="color: var(--teal-3); text-decoration:none;" href="register.php">Ayo Daftar</a>
            </div>
         </div>


         <!--
      <p>
         <input type="email" name="email" required placeholder="Enter your email">
         <input type="password" name="password" required placeholder="Enter your password">
         <input type="submit" name="submit" value="Login Now" class="form-btn">
      </p>

   -->
      </form>
   </div>

   <!-- Bootstrap JS-->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

   <!-- Java Script -->
   <script src="javascript/script.js"></script>

</body>

</html>