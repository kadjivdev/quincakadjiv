@extends('layout.template')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Magasins</h1>
        </div><!-- End Page Title -->
        <section class="section">
            <div class="row">

                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body py-1">
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
                            <h5 class="card-title text-dark">Modifier un Magasin</h5>

                            <!-- Vertical Form -->
                            <form class="row g-3" action="{{ route('magasins.update', $magasin->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="col-12">
                                    <label for="">Point de vente</label>
                                    <select name="point_vente_id" id="pointSelect" class="form-select js-data-example-ajax">
                                        <option value="">Choisir un point de vente</option>
                                        @foreach ($points as $point)
                                            <option value="{{ $point->id }}" {{ $magasin->point_vente_id == $point->id ? 'selected' : '' }}>{{ $point->nom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="" class="form-label">Nom du magasin</label>
                                    <input type="text" class="form-control" name="nom" value="{{$magasin->nom}}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Adresse lieu du magasin</label>
                                    <input type="text" class="form-control" name="adresse" value="{{$magasin->adresse}}">
                                </div>

                                <div class="col-12">
                                    <label for="">Responsable</label>
                                    <select name="user_id" id="users_select" class="form-control">
                                        {{-- @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach --}}
                                    </select>
                                </div>
                                <div class="col-lg-12 d-flex flex-row align-items-center justify-content-between">
                                    <button type="submit" class="btn btn-sm btn-dark text_orange w-100 submitBtn" id="ajouterArticle"><i class="bi bi-check-circle"></i> Enregistrer</button>
                                    <button type="button" class="btn btn-sm btn-dark text_orange w-100 loadingBtn" hidden><span class="spinner-border spinner-border-sm text_orange loading"></span> En cours ...</button>
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

            var apiUrl = "{{ config('app.url_ajax') }}";
            $('#pointSelect').change(function() {
                var pointId = $(this).val();
                $.ajax({
                    url: apiUrl + '/pointUsers/' + pointId,
                    type: 'GET',
                    success: function(data) {

                        console.log(data.users);
                        var options = '<option value="">Choisir l\'utilisateur </option>';
                        $.each(Object.values(data.users), function(index, user) {

                            options += '<option value="' + user.id + '">' + user
                                .name + '</option>';
                        });

                        $('#users_select').html(options);


                    },
                    error: function(error) {
                        console.log('Erreur de la requête Ajax :', error);
                    }
                });
            });
        </script>
    </main>
@endsection
