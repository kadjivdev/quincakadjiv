<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>QuinkaKadjiv - Connexion</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="{{ asset('assets/img/kadjiv.png') }}" rel="icon">
    <link href="{{ asset('assets/img/kadjiv.png') }}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        /* Style pour le loader */
        .loader {
            display: none;
            border: 6px solid #f3f3f3;
            border-top: 6px solid #3498db;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>

</head>

<body>

    <div class="container-fluid">
        <!-- HEADER -->
        <!-- <div class="row">
            <div class="col-md-12 px-0 mx-0 fixed-top">
                <nav class="navbar navbar-expand-lg bg-body-tertiary">
                    <div class="container-fluid">
                        <a class="navbar-brand bg-dark text-white px-3 rounded shadow shadow-lg" href="#">KADJIV</a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav mr-auto">
                                <li class="nav-item active">
                                    <a class="nav-link" href="#"> -- <span class="sr-only">Quinquaillérie</span></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
        </div> -->
        <div class="row">
            <div class="col py-5">
                @yield('content')
                <p class="text-center text-white">© Copyright <em class="text_orange"> {{date("Y")}}</em> | Powered By -- <strong class="text_orange">Kadjiv </strong> </p>
            </div>
        </div>
        <!-- FOOTER -->
        <!-- <div class="row bg-white fixed-bottom">
            <div class="col-md-12 px-0 mx-0 py-3">
                <p class="text-center">© Copyright <em class="text_orange"> {{date("Y")}}</em> | Powered By -- <strong class="text_orange">Kadjiv </strong> </p>
            </div>
        </div> -->
    </div>


    <!-- Vendor JS Files -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Template Main JS File -->
    <script src="assets/js/main.js"></script>

</body>

</html>