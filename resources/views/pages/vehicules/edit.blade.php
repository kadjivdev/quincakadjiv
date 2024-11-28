@extends('layout.template')
@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Véhicules</h1>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body py-2">
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
    
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <h5 class="card-title text-dark">Modifier le vehicule "{{$vehicule->num_vehicule}}"</h5>

                            <!-- Vertical Form -->
                            <form class="row g-3" action="{{ route('vehicules.update', $vehicule->id) }}"
                                method="POST">
                                @csrf
                                @method('PUT')
                                <div class="col-12">
                                    <label class="form-label">Immatriculation</label>
                                    <input type="text" class="form-control" name="num"
                                        value="{{ $vehicule->num_vehicule }}">
                                </div>

                                <div class="col-lg-12 d-flex flex-row align-items-center justify-content-between">
                                    <button type="submit" class="btn btn-sm btn-dark text_orange w-100 submitBtn"><i class="bi bi-check-circle"></i> Enregistrer</button>
                                    <button type="button" class="btn btn-sm btn-dark text_orange w-100 loadingBtn" hidden><span class="spinner-border spinner-border-sm text_orange loading"></span> En cours ...</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


        <script>
            $(document).ready(function() {
                $("#connectBtn").on("click", function() {
                    // Afficher le loader
                    $(".myLoader").show();

                    // Effectuer ici vos opérations asynchrones ou autres

                    // Simuler une opération asynchrone (remplacez cela par votre logique réelle)
                    setTimeout(function() {
                        // Cacher le loader une fois l'opération terminée
                        $(".myLoader").hide();
                    }, 2000); // 2000 millisecondes (2 secondes) dans cet exemple
                });
            });
        </script>

    </main>
@endsection
