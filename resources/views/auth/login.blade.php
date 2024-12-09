@extends('layout.auth-template')
@section('content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<style>
    :root {
        --kadjiv-orange:#FDC500;
        --kadjiv-dark-orange: #080808;
        --kadjiv-black: #000000;
        --kadjiv-white: #FFFFFF;
        --kadjiv-gray: #F5F5F5;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, var(--kadjiv-orange) 0%, var(--kadjiv-dark-orange) 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .container {
        position: relative;
        z-index: 1;
    }

    .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        background-color: var(--kadjiv-white);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
    }

    .card-body {
        /* padding: 3rem; */
    }

    .logo {
        max-width: 200px;
        margin-bottom: 2rem;
    }

    h2 {
        color: var(--kadjiv-black);
        font-weight: 700;
        margin-bottom: 2rem;
    }

    .form-floating label {
        color: var(--kadjiv-black);
    }

    .form-control {
        border: 2px solid var(--kadjiv-gray);
        border-radius: 8px;
        padding: 1rem 0.75rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: var(--kadjiv-orange);
        box-shadow: 0 0 0 0.2rem rgba(255, 127, 80, 0.25);
    }

    .btn-primary {
        background-color: var(--kadjiv-orange);
        border-color: var(--kadjiv-orange);
        border-radius: 8px;
        padding: 0.75rem 2rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-primary:hover,
    .btn-primary:focus {
        background-color: var(--kadjiv-dark-orange);
        border-color: var(--kadjiv-dark-orange);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .password-toggle {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: var(--kadjiv-black);
        transition: color 0.3s ease;
    }

    .password-toggle:hover {
        color: var(--kadjiv-orange);
    }

    .forgot-password {
        color: var(--kadjiv-black);
        transition: color 0.3s ease;
    }

    .forgot-password:hover {
        color: var(--kadjiv-orange);
    }

    @keyframes logoEntrance {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .logo-animation {
        animation: logoEntrance 1s ease-out;
    }

    .custom-checkbox {
        display: flex;
        align-items: center;
        cursor: pointer;
    }

    .custom-checkbox input {
        display: none;
    }

    .custom-checkbox .checkmark {
        width: 22px;
        height: 22px;
        border: 2px solid var(--kadjiv-gray);
        border-radius: 4px;
        margin-right: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .custom-checkbox input:checked+.checkmark {
        background-color: var(--kadjiv-orange);
        border-color: var(--kadjiv-orange);
    }

    .custom-checkbox .checkmark:after {
        content: '\2714';
        color: white;
        display: none;
    }

    .custom-checkbox input:checked+.checkmark:after {
        display: block;
    }
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <div class="card animate__animated animate__fadeIn">
                <div class="card-body">
                    <form class="needs-validation p-3" novalidate action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="text-center logo-animation">
                                    <img src="{{asset('assets/img/kadjiv.png')}}" alt="Kadjiv Logo" class="logo img-fluid">
                                </div>
                                <h2 class="text-center animate__animated animate__fadeInUp">Connexion</h2>
                            </div>
                            <div class="col-md-8">
                                <div class="">
                                    <div class="form-floating">
                                        <input type="email" class="form-control" name="username" id="username" placeholder="Votre email" required>
                                        <label for="username">Identifiant</label>
                                    </div>
                                    @error('username')
                                    <span class="text-danger small mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <br>
                                <div class="position-relative">
                                    <div class="form-floating">
                                        <input type="password" class="form-control" name="password" id="password" placeholder="Votre mot de passe" required>
                                        <label for="password">Mot de passe</label>
                                    </div>
                                    <i class="bi bi-eye password-toggle" id="togglePassword"></i>
                                    @error('password')
                                    <span class="text-danger small mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <br>
                                <!-- <div class="animate__animated animate__fadeInUp"> -->
                                <label class="custom-checkbox">
                                    <input type="checkbox" id="rememberMe">
                                    <span class="checkmark"></span>
                                    Se souvenir de moi
                                </label>
                                <br>
                                
                                <button id="connectBtn" class="btn btn-dark w-100" type="submit">Se connecter</button>
                                <button class="btn btn-dark d-none w-100" id="myLoader" type="button" disabled>
                                    <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                                    <span>Connexion en cours...</span>
                                </button>
                                <div class="text-center mt-3">
                                    <a href="#" class="forgot-password">Mot de passe oublié ?</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-2"></div>
    </div>
</div>

<script src="{{ asset('assets/js/jquery3.6.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $("#togglePassword").on('click', function() {
            const password = $("#password");
            const type = password.attr('type') === 'password' ? 'text' : 'password';
            password.attr('type', type);
            $(this).toggleClass('bi-eye bi-eye-slash');
        });

        $("#connectBtn").on("click", function() {
            $(this).addClass('animate__animated animate__pulse');
            $("#connectBtn").addClass('d-none');
            $("#myLoader").removeClass('d-none');
            // Permettre la soumission du formulaire après l'animation
            $(this).closest("form").submit();
        });

        $(".form-control").focus(function() {
            $(this).addClass('animate__animated animate__pulse');
        }).blur(function() {
            $(this).removeClass('animate__animated animate__pulse');
        });
    });
</script>
@endsection