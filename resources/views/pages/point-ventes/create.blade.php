@extends('layout.template')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Points de vente</h1>
        </div><!-- End Page Title -->
        <section class="section">
            <div class="row">

                <div class="col-lg-12">

                    <div class="card">
                        <!-- Afficher des messages de succès -->
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <!-- Afficher des erreurs de validation -->
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">Ajouter un point de vente</h5>

                            <!-- Vertical Form -->
                            <form class="row g-3" action="{{ route('boutiques.store') }}" method="POST">
                                @csrf
                                <div class="col-12">
                                    <label for="" class="form-label">Nom du point</label>
                                    <input type="text" class="form-control" name="nom">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Adresse</label>
                                    <input type="text" class="form-control" name="adresse">
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Téléphone</label>
                                    <input type="text" class="form-control" name="phone">
                                </div>

                                {{-- <div class="col-12">
                                    <label for="">Responsable</label>
                                    <select name="user_id" id="user_id" class="form-select">
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div> --}}
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                                    <div class="loader"></div>
                                    <button type="reset" class="btn btn-secondary">Annuler</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </section>
        <script src="{{ asset('assets/js/jquery3.6.min.js') }}"></script>

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
