<?php
@include '../../config/koneksi.php';

session_start();

if (!isset($_SESSION['admin_name'])) {
    header('location: ../../');
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

    <!-- Custom styles for this page -->
    <link href="../assets/dashboard-sb/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link rel="stylesheet" href="../assets/css/resep.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/sb-admin-2.min.css">

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

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background-color: var(--teal-2);">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <div class="sidebar-brand-text mx-3"><img src="../../assets/img/logo.png" alt="" style="height: 30px;"></div>
                <div class="smol"><img src="../../assets/img/logo_title.png" style="height: 45px; margin-top: 10px;" alt=""></div>
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
            <li class="nav-item">
                <a class="nav-link" href="../dashboard.php">
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
            <li class="nav-item actived">
                <a class="nav-link" href="resep.php">
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
                <a class="nav-link" href="../data-pengguna/pengguna.php">
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

                    <h1 class="h3 mb-0 ms-3" style="color: #fff; font-weight: bold;">Resep Makanan</h1>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto me-3">
                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline small text-white">Admin</span>
                                <div class="rounded-circle" style="border:2px solid var(--teal-3); background-image: url(media/about-us/our-team/1.png); background-position: center; background-size: cover; width: 35px; height: 35px;"></div>
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="../../logout.php" style="color: red;">
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

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Tabel Resep Makanan</h1>

                    <button class="btnadd btn mb-3" style="background-color: var(--teal-2);" id="addData">
                        <a class="text-white" style="text-decoration: none;" href="tambah-resep.php">Tambah Resep</a>
                    </button>

                    <style>
                        .btnadd:hover {
                            background-color: var(--teal-3) !important;
                        }
                    </style>

                    <div class="fixed-table-container" style="position: relative;">
                        <!-- DataTales Example -->
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <div class="table-responsive" style="overflow-y: auto; height: calc(75vh - 70px);">
                                    <table data-ordering="false" class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th class="text-center" style="background-color: var(--teal-2); color: white;">recipe_id</th>
                                                <th class="text-center" style="background-color: var(--teal-2); color: white;">category</th>
                                                <th class="text-center" style="background-color: var(--teal-2); color: white;">name</th>
                                                <th class="text-center" style="background-color: var(--teal-2); color: white;">description</th>
                                                <th class="text-center" style="background-color: var(--teal-2); color: white;">image</th>
                                                <th class="text-center" style="background-color: var(--teal-2); color: white;">ingredients</th>
                                                <th class="text-center" style="background-color: var(--teal-2); color: white;">instructions</th>
                                                <th class="text-center" style="background-color: var(--teal-2); color: white;">nutritions</th>
                                                <th class="text-center" style="background-color: var(--teal-2); color: white;">Edit Data</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th class="text-center" style="background-color: var(--teal-1); color: white;">recipe_id</th>
                                                <th class="text-center" style="background-color: var(--teal-1); color: white;">category</th>
                                                <th class="text-center" style="background-color: var(--teal-1); color: white;">name</th>
                                                <th class="text-center" style="background-color: var(--teal-1); color: white;">description</th>
                                                <th class="text-center" style="background-color: var(--teal-1); color: white;">image</th>
                                                <th class="text-center" style="background-color: var(--teal-1); color: white;">ingredients</th>
                                                <th class="text-center" style="background-color: var(--teal-1); color: white;">instructions</th>
                                                <th class="text-center" style="background-color: var(--teal-1); color: white;">nutritions</th>
                                                <th class="text-center" style="background-color: var(--teal-1); color: white;">Edit Data</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php
                                            include "../../config/koneksi.php";

                                            $query = "SELECT * FROM recipes";

                                            $data = mysqli_query($conn, $query);
                                            while ($hasil = mysqli_fetch_array($data)) {
                                            ?>
                                                <tr>
                                                    <td><?php echo $hasil['recipe_id']; ?></td>
                                                    <td><?php echo $hasil['category']; ?></td>
                                                    <td><?php echo $hasil['name']; ?></td>
                                                    <td><?php echo $hasil['description']; ?></td>
                                                    <td><img src="<?php echo $hasil['image']; ?>" width=200 title="<?php echo $hasil['image']; ?>"> </td>
                                                    <td><?php echo $hasil['ingredients']; ?></td>
                                                    <td><?php echo $hasil['instructions']; ?></td>
                                                    <td><?php echo $hasil['nutritions']; ?></td>
                                                    <td class="aksi d-flex gap-2">
                                                        <a href="ubah-resep.php?id=<?php echo $hasil['recipe_id'] ?>" class="btn btn-warning btn-circle">
                                                            <i class="fas fa-pen text-light"></i>
                                                        </a>
                                                        <a onclick="return confirm('Apakah Anda Yakin Ingin Menghapus Data Ini?')" href="hapus-resep.php?id=<?php echo $hasil['recipe_id'] ?>" class="btn btn-danger btn-circle">
                                                            <i class="fas fa-trash text-light"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->

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
    <script src="../assets/dashboard-sb/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/dashboard-sb/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../assets/dashboard-sb/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../assets/dashboard-sb/js/sb-admin-2.min.js"></script>
    <script src="../assets/dashboard-sb/js/add_data.js"></script>

    <!-- Page level plugins -->
    <script src="../assets/dashboard-sb/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../assets/dashboard-sb/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="../assets/dashboard-sb/js/demo/datatables-demo.js"></script>

    <script src="../assets/js/script.js"></script>

</body>

</html>