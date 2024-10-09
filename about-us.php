<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Style -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/about-us.css">
    <link rel="stylesheet" href="assets/css/footer.css">

    <!-- Font Family -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Monoton&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Title -->
    <link rel="icon" href="assets/img/logo_title.png" type="image/x-icon">
    <title id="pageTitle">CookNow | Resep Makanan dan Minuman |</title>

</head>

<body>
    <!-- Start Navbar -->
    <nav class="navbar navbar-dark navbar-expand-lg fixed-top" style="background-color: var(--teal-2);">
        <style>
            .navbar {
                max-height: 60px;
            }
        </style>
        <div class="container-fluid" style="padding: 0 100px 0 100px;">
            <a class="navbar-brand" href="../cooknow"><img class="" style="height: 30px ;" src="assets/img/logo.png" alt="CookNow"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="fs-h4 navbar-nav ms-auto gap-xl-5 gap-md-4 gap-sm-2 py-xl-0 py-md-0 py-3">
                    <li class="nav-item">
                        <a class="nav-link nav-home" aria-current="page" href="../cooknow">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-resep" href="recipes.php">Resep</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-about active" href="about-us.php">Tentang Kami</a>
                    </li>
                    <li>
                        <a class="btn btn-hover rounded-5 border border-3 px-4" href="login.php">Masuk</a>
                        <style>
                            .btn-hover {
                                background-color: var(--teal-4);
                                color: var(--teal-2);
                                border-color: var(--teal-2);
                                font-size: 18px;
                                font-weight: 600;
                            }

                            .btn-hover:hover {
                                background-color: var(--teal-3);
                                border-color: var(--teal-3) !important;
                            }

                            .btn-hover:active {
                                background-color: var(--teal-2) !important;
                                border-color: var(--white) !important;
                            }
                        </style>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- End Navbar -->

    <!-- Start Tentang Kami -->
    <div class="container" style="margin-top: 200px; margin-bottom: 100px;">
        <div class="col-12">
            <div class="row">
                <div class="col-7 d-flex flex-column justify-content-center">
                    <h1 class="fw-bolder text-black title-about">TENTANG KAMI</h1>
                    <hr style="color: #1e1e1e !important; border: solid 1.5px;">
                    <p class="fs-body text-black" style="text-align: justify;">Kami adalah sumber terpercaya Anda untuk resep masakan lezat dan informasi nutrisi lengkap. Misi kami adalah membuat memasak lebih mudah dan menyenangkan bagi semua orang, dari pemula hingga koki berpengalaman.</p>
                </div>
                <div class="col-5 d-flex justify-content-end">
                    <img src="assets/img/tentang-kami.png" alt="">
                </div>
            </div>
        </div>

    </div>
    <!-- End Tentang Kami -->

    <!-- Start Our Team -->
    <div class="container-fluid mt-5" style="height: 800px; background: linear-gradient(#F1EEE5 0%, #F8F7F2 63%, #FFFFFF 95%, #F1EEE5 100%);">

        <div id="operator" class="section operator">
            <div class="oprBack">
                <h1>OUR TEAM</h1>
                <div class="list">
                    <div class="yellow"></div>
                    <div class="shading">
                        <div class="line"></div>
                        <div class="line"></div>
                        <div class="line"></div>
                    </div>
                </div>
            </div>

            <div class="charContent">
                <div id="char-1" class="char-1 charContentX">
                    <div class="charImg" id="char-1Img">
                        <img class="img1" src="assets/our-team/1.png">
                    </div>

                    <div class="charDesc" id="char-1Desc">
                        <h2 class="fs-h2" style="color: var(--black); overflow-y: hidden;">Lia</h2>
                        <h4 class="fs-h4" style="background-color: var(--black);">Pricilia Putri Salsabila</h4>
                        <div class="fs-body about-self">
                            Biodata
                        </div>
                    </div>
                </div>
                <div id="char-2" class="char-2 charContentX">
                    <div class="charImg" id="char-2Img">
                        <img class="img1" src="assets/our-team/2.png">
                    </div>

                    <div class="charDesc" id="char-2Desc">
                        <h2 class="fs-h2" style="color: var(--black); overflow-y: hidden;">Saura</h2>
                        <h4 class="fs-h4" style="background-color: var(--black);">Saura Dwikana</h4>
                        <div class="fs-body about-self">
                            Biodata
                        </div>
                    </div>
                </div>
                <div id="char-3" class="char-3 charContentX">
                    <div class="charImg" id="char-3Img">
                        <img class="img1" src="assets/our-team/3.png">
                    </div>

                    <div class="charDesc" id="char-3Desc">
                        <h2 class="fs-h2" style="color: var(--black); overflow-y: hidden;">Iqbal</h2>
                        <h4 class="fs-h4" style="background-color: var(--black);">Maulana Iqbal Yuhansyah</h4>
                        <div class="fs-body about-self">
                            Biodata
                        </div>
                    </div>
                </div>
                <div id="char-4" class="char-4 charContentX">
                    <div class="charImg" id="char-4Img">
                        <img class="img1" src="assets/our-team/4.png">
                    </div>

                    <div class="charDesc" id="char-4Desc">
                        <h2 class="fs-h2" style="color: var(--black); overflow-y: hidden;">Tedy</h2>
                        <h4 class="fs-h4" style="background-color: var(--black);">Tedy Irfan Fatsah</h4>
                        <div class="fs-body about-self">
                            Biodata
                        </div>
                    </div>
                </div>
                <div id="char-5" class="char-5 charContentX">
                    <div class="charImg" id="char-5Img">
                        <img class="img1" src="assets/our-team/5.png">
                    </div>

                    <div class="charDesc" id="char-5Desc">
                        <h2 class="fs-h2" style="color: var(--black); overflow-y: hidden;">Mahdy</h2>
                        <h4 class="fs-h4" style="background-color: var(--black);">Mahdy Saifan Haqq</h4>
                        <div class="fs-body about-self">
                            Biodata
                        </div>
                    </div>
                </div>
                <div id="char-6" class="char-6 charContentX">
                    <div class="charImg" id="char-6Img">
                        <img class="img1" src="assets/our-team/6.png">
                    </div>

                    <div class="charDesc" id="char-6Desc">
                        <h2 class="fs-h2" style="color: var(--black); overflow-y: hidden;">Mulyana</h2>
                        <h4 class="fs-h4" style="background-color: var(--black);">Fahri Ardian Mulyana</h4>
                        <div class="fs-body about-self">
                            Biodata
                        </div>
                    </div>
                </div>
                <div id="char-7" class="char-7 charContentX">
                    <div class="charImg" id="char-7Img">
                        <img class="img1" src="assets/our-team/7.png">
                    </div>

                    <div class="charDesc" id="char-7Desc">
                        <h2 class="fs-h2" style="color: var(--black); overflow-y: hidden;">Rafly</h2>
                        <h4 class="fs-h4" style="background-color: var(--black);">M Rafly Mudzaki</h4>
                        <div class="fs-body about-self">
                            Biodata
                        </div>
                    </div>
                </div>
                <div id="char-8" class="char-8 charContentX">
                    <div class="charImg" id="char-8Img">
                        <img class="img1" src="assets/our-team/8.png">
                    </div>

                    <div class="charDesc" id="char-8Desc">
                        <h2 class="fs-h2" style="color: var(--black); overflow-y: hidden;">Zidane</h2>
                        <h4 class="fs-h4" style="background-color: var(--black);">Muhammad Hanif Zidane</h4>
                        <div class="fs-body about-self">
                            Biodata
                        </div>
                    </div>
                </div>
                <div id="char-9" class="char-9 charContentX">
                    <div class="charImg" id="char-9Img">
                        <img class="img1" src="assets/our-team/9.png">
                    </div>

                    <div class="charDesc" id="char-9Desc">
                        <h2 class="fs-h2" style="color: var(--black); overflow-y: hidden;">Jen</h2>
                        <h4 class="fs-h4" style="background-color: var(--black);">Fatruzei Salman Alfarisi</h4>
                        <div class="fs-body about-self">
                            Biodata
                        </div>
                    </div>
                </div>
                <div id="char-10" class="char-10 charContentX">
                    <div class="charImg" id="char-10Img">
                        <img class="img1" src="assets/our-team/10.png">
                    </div>

                    <div class="charDesc" id="char-10Desc">
                        <h2 class="fs-h2" style="color: var(--black); overflow-y: hidden;">Afkar</h2>
                        <h4 class="fs-h4" style="background-color: var(--black);">Muhammad Nauuri Afkaar </h4>
                        <div class="fs-body about-self">
                            Biodata
                        </div>
                    </div>
                </div>
            </div>

            <div class="parentPreview">
                <div class="preview" id="preview-1">
                    <div class="boxChar" id="char-1x" style="background-image :url(assets/our-team/1.png);">
                        <div class="blendColor"></div>
                        <div class="numChar">
                            <p>01</p>
                        </div>
                        <div class="preName">
                            <h2>//Pricilia Putri Salsabila/</h2>
                            <h3 style="overflow-y: hidden;">Lia</h3>
                        </div>
                    </div>
                    <div class="boxChar" id="char-2x" style="background-image :url(assets/our-team/2.png);">
                        <div class="blendColor"></div>
                        <div class="numChar">
                            <p>02</p>
                        </div>
                        <div class="preName">
                            <h2>//Saura Dwikana</h2>
                            <h3 style="overflow-y: hidden;">Saura</h3>
                        </div>
                    </div>
                    <div class="boxChar" id="char-3x" style="background-image :url(assets/our-team/3.png);">
                        <div class="blendColor"></div>
                        <div class="numChar">
                            <p>03</p>
                        </div>
                        <div class="preName">
                            <h2>//Maula Iqbal Yuhansyah</h2>
                            <h3 style="overflow-y: hidden;">Iqbal</h3>
                        </div>
                    </div>
                    <div class="boxChar" id="char-4x" style="background-image :url(assets/our-team/4.png);">
                        <div class="blendColor"></div>
                        <div class="numChar">
                            <p>04</p>
                        </div>
                        <div class="preName">
                            <h2>//Tedy Irfan Fatsah</h2>
                            <h3 style="overflow-y: hidden;">Tedy</h3>
                        </div>
                    </div>
                    <div class="boxChar" id="char-5x" style="background-image :url(assets/our-team/5.png);">
                        <div class="blendColor"></div>
                        <div class="numChar">
                            <p>05</p>
                        </div>
                        <div class="preName">
                            <h2>//Mahdy Saifan</h2>
                            <h3 style="overflow-y: hidden;">Mahdy</h3>
                        </div>
                    </div>

                </div>
                <div class="preview" id="preview-2">
                    <div class="boxChar" id="char-6x" style="background-image :url(assets/our-team/6.png);">
                        <div class="blendColor"></div>
                        <div class="numChar">
                            <p>06</p>
                        </div>
                        <div class="preName">
                            <h2>//Fahri Ardian Mulyana</h2>
                            <h3 style="overflow-y: hidden;">Mulyana</h3>
                        </div>
                    </div>
                    <div class="boxChar" id="char-7x" style="background-image :url(assets/our-team/7.png);">
                        <div class="blendColor"></div>
                        <div class="numChar">
                            <p>07</p>
                        </div>
                        <div class="preName">
                            <h2>//M Rafly Mudzaki</h2>
                            <h3 style="overflow-y: hidden;">Rafly</h3>
                        </div>
                    </div>
                    <div class="boxChar" id="char-8x" style="background-image :url(assets/our-team/8.png);">
                        <div class="blendColor"></div>
                        <div class="numChar">
                            <p>08</p>
                        </div>
                        <div class="preName">
                            <h2>//Muhammad Hanif Zidane</h2>
                            <h3 style="overflow-y: hidden;">Zidane</h3>
                        </div>
                    </div>
                    <div class="boxChar" id="char-9x" style="background-image :url(assets/our-team/9.png);">
                        <div class="blendColor"></div>
                        <div class="numChar">
                            <p>09</p>
                        </div>
                        <div class="preName">
                            <h2>//Fatruzein Salman Alfarisi</h2>
                            <h3 style="overflow-y: hidden;">Jen</h3>
                        </div>
                    </div>
                    <div class="boxChar" id="char-10x" style="background-image :url(assets/our-team/10.png);">
                        <div class="blendColor"></div>
                        <div class="numChar">
                            <p>10</p>
                        </div>
                        <div class="preName">
                            <h2>//Muhammad Nauri Afkar</h2>
                            <h3 style="overflow-y: hidden;">Afkar</h3>
                        </div>
                    </div>

                </div>
            </div>

            <div class="prevnext">
                <div class="button" id="prev">
                    <div class="a"></div>
                    <div class="b"></div>
                </div>
                <div class="button" id="next">
                    <div class="a"></div>
                    <div class="b"></div>
                </div>
            </div>

        </div>
    </div>
    <!-- End Our Team -->

    <!-- Start Feedback -->
    <div class="feedback-back">
        <div class="mask" style="background-color: hsla(0, 0%, 0%, 0.6);">
            <div class="d-flex justify-content-center align-items-center" style="height: 500px; width: 100vw;">
                <div>
                    <h1 class="fs-h1 text-center" style="overflow-y: hidden;">JANGAN LUPA</h1>
                    <h1 class="fs-h1 text-center" style="overflow-y: hidden;">KIRIM FEEDBACK KAMU YA!</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="form-feedback-parent">
        <div class="form-feedback d-flex flex-column align-items-center">
            <h3 class="text-center mt-5 text-black fw-bold" style="overflow-y: hidden;">Tulis Feedback</h3>
            <br>
            <p class="text-black m-0" style=" width: 800px;" ;>Nama</p>
            <input type="text" style=" width: 800px;">
            <p class="text-black m-0" style=" width: 800px;" ;>Email</p>
            <input type="text" style=" width: 800px;">
            <p class="text-black m-0" style=" width: 800px;" ;>No. Hp</p>
            <input type="text" style=" width: 800px;">
            <p class="text-black m-0" style=" width: 800px;" ;>Pesan</p>
            <input type="text" style=" width: 800px; height :80px">
            <br>
            <div class="fs-h4 btn btn-hvrss rounded-5 border border-3 px-4">Kirim Feedback</div>
            <style>
                .btn-hvrss {
                    background-color: var(--teal-2);
                    color: var(--white);
                    border-color: var(--white);
                    font-size: 18px;
                    font-weight: 600;
                    margin-bottom: 50px;
                }

                .btn-hvrss:hover {
                    background-color: var(--teal-3);
                }

                .btn-hvrss:active {
                    background-color: var(--teal-1) !important;
                }
            </style>
        </div>
    </div>
    </div>
    <!-- End Feedback -->

    <!-- Start footer -->
    <div class="container-fluid p-0">
        <div class="container-fluid footer gap-2">
            <img src="assets/img/logo.png">
            <p style="width: 600px;">Teman Setia Anda dalam Menciptakan Makanan Berkualitas, Mengubah Setiap Masakan menjadi Karya Seni.</p>
            <div class="social-media d-flex gap-3">
                <a href=""><i class="fa-brands fa-x-twitter"></i></a>
                <a href=""><i class="fa-brands fa-whatsapp"></i></a>
                <a href=""><i class="fa-brands fa-facebook"></i></a>
                <a href=""><i class="fa-brands fa-instagram"></i></a>
            </div>
        </div>
        <div class="container-fluid text-center py-2" style="bottom: 0; background-color: var(--teal-1);">Copyright &copy; 2024 CookNow.</div>
    </div>
    <!-- End footer -->


    <!-- Bootstrap JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- Java Script -->
    <script src="assets/javascript/script.js"></script>
    <script src="assets/javascript/script_our_team.js"></script>
</body>

</html>