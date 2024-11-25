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
                            <h5 class="card-title">Modifier le Fournisseur</h5>

                            <!-- Vertical Form -->
                            <form class="row g-3" action="{{ route('fournisseurs.update', $fournisseur->id) }}"
                                method="POST">
                                @csrf
                                @method('PUT')
                                <div class="col-12">
                                    <label class="form-label">Nom et prénoms</label>
                                    <input type="text" class="form-control" name="name"
                                        value="{{ $fournisseur->name }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email"
                                        value="{{ $fournisseur->email }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Téléphone</label>
                                    <input type="text" class="form-control" name="phone"
                                        value="{{ $fournisseur->phone }}">
                                </div>
                                <div class="col-12">
                                    <label for="inputAddress" class="form-label">Adresse</label>
                                    <input type="text" class="form-control" name="address" id="inputAddress"
                                        placeholder="1234 Main St" value="{{ $fournisseur->address }}">
                                </div>

                                <div class="col-12">
                                    <label for="articles">Choisir les articles vendus</label>
                                    <select name="articles[]" id="articles" class="js-example-basic-multiple form-control"
                                        multiple="multiple">

                                        @foreach ($fournisseur->articles as $article)
                                            <option value="{{ $article->id }}" selected>{{ $article->nom }}</option>
                                        @endforeach

                                    </select>
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
             var apiUrl = "{{ config('app.url_ajax') }}";
            $(document).ready(function() {
                // Initialize Select2 for the articles dropdown
                $('.js-example-basic-multiple').select2({
                    placeholder: 'Select provided articles',
                    allowClear: true,
                    ajax: {
                        url: '{{ route('articles-list') }}',
                        dataType: 'json',
                        data: function(params) {
                            console.log(params);
                            return {
                                term: params.term // search term
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data.articles.map(function(article) {
                                    return {
                                        id: article.id,
                                        text: article.nom
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
