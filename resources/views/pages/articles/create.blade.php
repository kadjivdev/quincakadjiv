@extends('layout.template')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle text-dark">
            <h1>Articles</h1>
        </div><!-- End Page Title -->
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card p-4">
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
                            <h5 class="card-title text-dark">Ajouter un article</h5>

                            <!-- Vertical Form -->
                            <form class="row px-3" action="{{ route('articles.store') }}" method="POST">
                                @csrf
                                <div class="p-3 shadow shadow-lg">

                                    <div class="col-12 mb-3">
                                        <label for="">Catégorie</label>
                                        <select name="categorie_id" id="categorie_id"
                                            class="js-example-basic-multiple form-select">
                                            <option value="">Choisir la categorie </option>
                                            @foreach ($categories as $categorie)
                                                <option value="{{ $categorie->id }}">{{ $categorie->libelle }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="inputNanme4" class="form-label">Désignation article</label>
                                        <input type="text" class="form-control" value="{{ old('nom') }}" name="nom">
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label">Stock alert</label>
                                        <input type="number" class="form-control" min="1"
                                            value="{{ old('stock_alert') }}" name="stock_alert">
                                    </div>
    
                                    <div class="col-12 mb-3">
                                        <label for="">Unité de base</label>
                                        <select name="unite_mesure_id" id="unite_mesure_id"
                                            class="js-example-basic-multiple form-select">
                                            <option value="">Choisir l'unité </option>
                                            @foreach ($unites as $unite)
                                                <option value="{{ $unite->id }}">{{ $unite->unite }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="">Choisir Magasins</label>
                                        <select name="magasins[]" id="magasins" class="form-select" multiple>
                                        </select>
                                    </div>
                                    <div class="col-lg-12 d-flex flex-row align-items-center justify-content-between">
                                        <button type="submit" class="btn btn-sm btn-dark text_orange w-100 submitBtn"><i class="bi bi-check-circle"></i> Enregistrer</button>
                                        <button type="button" class="btn btn-sm btn-dark text_orange w-100 loadingBtn" hidden><span class="spinner-border spinner-border-sm text_orange loading"></span> En cours ...</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        {{-- <script src="{{ asset('assets/js/jquery3.6.min.js') }}"></script> --}}
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script>
            $(".js-example-basic-multiple").select2();
        </script>
        <script>
            $(document).ready(function() {
                // Initialize Select2 for the provided_articles dropdown
                $('#magasins').select2({
                    placeholder: 'Selectionner les magasins',
                    ajax: {
                        url: '{{ route('magasins-list') }}',
                        dataType: 'json',
                        processResults: function(data) {
                            return {
                                results: data.magasins.map(function(magasin) {
                                    console.log(magasin);
                                    return {
                                        id: magasin.id,
                                        text: magasin.nom
                                    };
                                })
                            };
                        }
                    }
                });
            });
        </script>

        <script>
            $(document).ready(function() {
                $("#connectBtn").on("click", function() {
                    $(".myLoader").show();
                    setTimeout(function() {
                        $(".myLoader").hide();
                    }, 2000);
                });
            });
        </script>

    </main>
@endsection
