<?php
include "../config/koneksi.php";

session_start();

if (!isset($_SESSION['admin_name'])) {
  header('location: ../');
}

if (isset($_SESSION['msg'])) {
  echo "<script>alert('" . $_SESSION['msg'] . "');</script>";
  unset($_SESSION['msg']); // Hapus pesan setelah ditampilkan
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <!-- Title -->
  <link rel="icon" href="assets/img/logo_title.png" type="image/x-icon">
  <title id="pageTitle">CookNow | Resep Makanan dan Minuman |</title>

  <!-- Custom fonts for this template-->
  <link href="assets/dashboard-sb/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Font Family -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <!-- Custom styles for this template-->
  <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background-color: var(--teal-2);">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-text mx-3"><img src="assets/img/logo.png" alt="" style="height: 30px;"></div>
        <div class="smol"><img src="assets/img/logo_title.png" style="height: 45px; margin-top: 10px;" alt=""></div>
        <style>
          .smol {
            display: none;
          }

          @media (max-width: 768px) {
            .smol {
              display: block !important;
            }
          }
        </style>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item actived">
        <a class="nav-link" href="dashboard.php">
          <i class="activeds fas fa-fw fa-tachometer-alt"></i>
          <span class="text-white">Dashboard</span>
        </a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        Recipe
      </div>

      <!-- Nav Item - Pages Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link" href="resep-makanan/resep.php">
          <i class="activeds fas fa-fw fa-utensils"></i>
          <span class="text-white">Resep Maknanan</span>
        </a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        User
      </div>

      <li class="nav-item">
        <a class="nav-link" href="data-pengguna/pengguna.php">
          <i class="activeds fas fa-fw fa-user"></i>
          <span class="text-white">Data Pengguna</span>
        </a>
      </li>

      <style>
        .actived {
          background-color: var(--teal-1);
        }

        .activeds {
          color: white !important;
        }
      </style>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand topbar mb-4 static-top shadow" style="background-color: var(--teal-1);">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

          <h1 class="h3 mb-0" style="color: #fff; font-weight: bold;">Dashboard</h1>

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto ">
            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline small text-white" style="font-size: 14px;">Admin</span>
                <div class="rounded-circle" style="border:2px solid var(--teal-3); background-image: url(assets/our-team/1.png); background-position: center; background-size: cover; width: 35px; height: 35px;"></div>
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="../logout.php" style="color: red;">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-400" style="color: red;"></i>
                  Logout
                </a>
              </div>
            </li>

          </ul>

        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Pending Requests Card Example -->
          <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
              <div class="card-body">
                <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                      Total User</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                      <?php
                      // Query untuk menghitung jumlah total resep
                      $query = "SELECT COUNT(*) AS total_user FROM user";
                      $result = mysqli_query($conn, $query);

                      // Memeriksa apakah query berhasil dieksekusi
                      if ($result) {
                        // Mengambil hasil dari query
                        $row = mysqli_fetch_assoc($result);
                        $totalUser = $row['total_user'];
                        echo $totalUser;
                      } else {
                        // Jika terjadi error dalam menjalankan query
                        echo "Error";
                      }
                      ?>
                    </div>
                  </div>
                  <div class="col-auto">
                    <i class="fas fa-user fa-2x text-warning"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Pending Requests Card Example -->
          <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
              <div class="card-body">
                <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                      Total Resep</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                      <?php
                      // Query untuk menghitung jumlah total resep
                      $query = "SELECT COUNT(*) AS total_recipes FROM recipes";
                      $result = mysqli_query($conn, $query);

                      // Memeriksa apakah query berhasil dieksekusi
                      if ($result) {
                        // Mengambil hasil dari query
                        $row = mysqli_fetch_assoc($result);
                        $totalResep = $row['total_recipes'];
                        echo $totalResep;
                      } else {
                        // Jika terjadi error dalam menjalankan query
                        echo "Error";
                      }
                      ?>
                    </div>
                  </div>
                  <div class="col-auto">
                    <i class="fas fa-book fa-2x text-primary"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; CookNow 2024</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>


  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="login.html">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="assets/dashboard-sb/vendor/jquery/jquery.min.js"></script>
  <script src="assets/dashboard-sb/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="assets/dashboard-sb/vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="assets/dashboard-sb/js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="assets/dashboard-sb/vendor/chart.js/Chart.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="assets/dashboard-sb/js/demo/chart-area-demo.js"></script>
  <script src="assets/dashboard-sb/js/demo/chart-pie-demo.js"></script>

</body>

</html>