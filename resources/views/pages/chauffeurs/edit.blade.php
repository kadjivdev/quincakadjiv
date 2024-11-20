@extends('layout.template')
@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Fournisseurs</h1>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
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
                        <div class="card-body">
                            <h5 class="card-title">Modifier le chauffeur</h5>

                            <!-- Vertical Form -->
                            <form class="row g-3" action="{{ route('chauffeurs.update', $chauffeur->id) }}"
                                method="POST">
                                @csrf
                                @method('PUT')
                                <div class="col-12">
                                    <label class="form-label">Nom et prénoms</label>
                                    <input type="text" class="form-control" name="name"
                                        value="{{ $chauffeur->nom_chauf }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Téléphone</label>
                                    <input type="text" class="form-control" name="phone"
                                        value="{{ $chauffeur->tel_chauf }}">
                                </div>
                                <div class="col-12">
                                    <label for="permis" class="form-label">Permis</label>
                                    <input type="text" class="form-control" name="permis" id="permis" value="{{ $chauffeur->permis }}">
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Modifier</button>
                                    <div class="loader"></div>
                                    <button type="reset" class="btn btn-secondary">Annuler</button>
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
