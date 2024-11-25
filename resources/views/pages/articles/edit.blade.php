@extends('layout.template')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Articles</h1>
        </div><!-- End Page Title -->
        <section class="section">
            <div class="row">

                <div class="col-lg-12">

                    <div class="card">

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
                            <h5 class="card-title">Modifier un article</h5>

                            <!-- Vertical Form -->
                            <form class="row g-3" action="{{ route('articles.update', $article->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="p-3 shadow shadow-lg">
                                    <div class="col-12">
                                        <label for="">Catégorie</label>
                                        <select name="categorie_id" id="categorie_id"
                                            class="js-example-basic-multiple form-select">
                                            @foreach ($categories as $categorie)
                                                <option value="{{ $categorie->id }}"
                                                    {{ old('categorie', $article->categorie_id) == $categorie->id ? 'selected' : '' }}>
                                                    {{ $categorie->libelle }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label for="inputNanme4" class="form-label">Désignation article</label>
                                        <input type="text" class="form-control" value="{{ $article->nom }}" name="nom">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Stock alert</label>
                                        <input type="number" class="form-control" value="{{ $article->stock_alert }}"
                                            min="1" name="stock_alert">
                                    </div>
    
                                    <div class="col-12">
                                        <label for="">Unité de base</label>
                                        <select name="unite_mesure_id" id="unite_mesure_id"
                                            class="js-example-basic-multiple form-select">
                                            @foreach ($unites as $unite)
                                                <option value="{{ $unite->id }}"
                                                    {{ old('unite', $article->unite_mesure_id) == $unite->id ? 'selected' : '' }}>
                                                    {{ $unite->unite }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="">Choisir Magasins</label>
                                        <select name="magasins[]" id="magasins" class="form-select" multiple>
                                            @foreach ($magasins as $magasin)
                                                <option value="{{ $magasin->id }}"  {{ in_array($magasin->id, $stock_magasins->pluck('magasin_id')->toArray()) ? 'selected' : '' }}>
                                                    {{ $magasin->nom }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-12 d-flex flex-row align-items-center justify-content-between">
                                        <button type="submit" class="btn btn-sm btn-dark text_orange w-100 submitBtn"><i class="bi bi-check-circle"></i> Modifier</button>
                                        <button type="button" class="btn btn-sm btn-dark text_orange w-100 loadingBtn" hidden><span class="spinner-border spinner-border-sm text_orange loading"></span> En cours ...</button>
                                    </div>          
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(".js-example-basic-multiple").select2();
        $('#magasins').select2({});
    </script>
@endsection
