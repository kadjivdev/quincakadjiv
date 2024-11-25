@extends('layout.template')
@section('content')
<main id="main" class="main">

    <div class="pagetitle d-flex">
        <div class="col-6">
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Tableau de Bord</a></li>
                    <li class="breadcrumb-item active">Articles</li>
                </ol>
            </nav>
        </div>
        <div class="col-6 justify-content-end">

            {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                        Importer article
                    </button>

                    <a href="{{ route('liste_taux_convert') }}" class="btn btn-primary float-end">Gérer les taux de conversions</a>

            <a href="{{ route('UniteBase') }}" class="btn btn-primary float-end" style="margin-right: 1%"> Unité de base</a>
            <button type="button" class="btn btn-primary float-end mx-2" id="tauxBtn">
                Mettre Taux </button> --}}

            @can('articles.ajouter-article')
            <a href="{{ route('articles.create') }}" style="margin-left: 10px;" class="btn btn-warning float-end petit_bouton" > <i class="bi bi-plus-circle"></i> Ajouter un article</a>
            @endcan

            <a type="button" class="btn btn-dark position-relative petit_bouton" href="{{ route('articles.all_articles') }}">
                <i class="bi bi-card-list"></i> Afficher Tous les articles
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    {{count($articles_all)}}
                </span>
            </a>
        </div>
    </div><!-- End Page +++ -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="alert alert-success d-none" id="tauxMsg">
                </div>
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <form action="{{ url('/article_by_id') }}" method="POST">
                            @csrf
                            <h5 class="card-title text-dark">Articles</h5>
                            <div class="row">
                                <div class="col-10">
                                    <select required class="form-select form-control mb-3" name="id_art_sel" id="id_art_sel">

                                        @foreach ($articles_all as $article)
                                        <option value="{{ $article->id }}" data-donnee="{{ $article->nom }}">
                                            {{ $article->nom }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-2">
                                    <button type="submit" class="btn btn-sm bg_dark text_orange">
                                        <i class="bi bi-list"></i> Afficher
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-dark">Détail de l'article</h5>

                        @if(isset($unites))
                        <table id="example" class=" table table-bordered border-warning  table-hover table-sm table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>N°</th>
                                    <th>Nom article</th>
                                    <th>Stock alerte</th>
                                    <th>Catégorie</th>
                                    <th>Unité de base</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($articles as $article)
                                <tr>
                                    <td>{{ $loop->index +1 }} </td>
                                    <td>{{ $article->nom }}</td>
                                    <td>{{ $article->stock_alert }}</td>
                                    <td>{{ $article->categorie->libelle }}</td>
                                    <td>{{ $article->uniteBase->unite }}</td>

                                    <td class="text-center" style="width: 20%;">
                                        {{-- @can('articles.modifier-article') --}}
                                        <a href="{{ route('articles.edit', $article->id) }}"
                                            class="btn btn-sm bg_dark text_orange"> <i class="bi bi-pencil"></i> </a>
                                        {{-- @endcan --}}
                                        {{-- @can('articles.enregistrer-stock') --}}
                                        <a class="btn btn-sm bg_dark text_orange" data-bs-toggle="modal"
                                            data-bs-target="#staticBackdrop{{ $article->id }}"> <i
                                                class="bi bi-gear"></i> </a>
                                        {{-- @endcan --}}
                                        <a class="btn btn-sm bg_dark text_orange" data-bs-toggle="modal"
                                            data-bs-target="#stockModal{{ $article->id }}"> <i
                                                class="bi bi-box-seam"></i>
                                        </a>
                                    </td>
                                </tr>

                                @endforeach
                                <!-- Modal -->
                                <div class="modal fade" id="staticBackdrop{{ $article->id }}" data-bs-keyboard="false" tabindex="-1"
                                    aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('tauxSupplements.store') }}" method="post">
                                                @csrf
                                                
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Configurer
                                                        le taux</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="p-3 shadow shadow-lg">
                                                        <div class="col-12 mb-1">
                                                            <label for="" class="form-label">Unité de
                                                                base</label>
                                                            <input type="text" readonly
                                                                value="{{ $article->uniteBase->unite }}" required
                                                                class="form-control" name="unite">
                                                            <input type="hidden" value="{{ $article->id }}"
                                                                name="article_id">
                                                        </div>
                                                        <div class="col-12 mb-1">
                                                            <label class="form-label">Taux de conversion</label>
                                                            <input type="text" pattern="[0-9]+([,\.][0-9]+)?"
                                                                class="form-control" name="taux_conversion"
                                                                value="{{ old('taux_conversion') }}"
                                                                placeholder="Ex: 1 ou 2.6 (Nombre entier ou nombre à virgule)">
                                                            @if (old('taux_conversion') && !preg_match('/^[0-9]+(?:\.[0-9]+)?$/', old('taux_conversion')))
                                                            <div class="alert alert-danger">
                                                                Le champ doit contenir des chiffres ou un nombre à
                                                                virgule.
                                                            </div>
                                                            @endif
                                                        </div>
                                                        <div class="col-12 mb-1">
                                                            <label for="">Unité de mesure </label>
                                                            <select name="unite_mesure_id" id="unite_mesure_id"
                                                                class="form-control">
                                                                <option value="">Choisir l'unité à convertir
                                                                </option>
                                                                @foreach ($unites as $unite)
                                                                <option value="{{ $unite->id }}">
                                                                    {{ $unite->unite }}
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <div class="col-lg-12 d-flex flex-row align-items-center justify-content-between">
                                                        <button type="submit" class="btn btn-sm btn-dark text_orange w-100 submitBtn"><i class="bi bi-check-circle"></i> Enregistrer</button>
                                                        <button type="button" class="btn btn-sm btn-dark text_orange w-100 loadingBtn" hidden><span class="spinner-border spinner-border-sm text_orange loading"></span> En cours ...</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                @include('pages.articles.add-stock')
                            </tbody>
                        </table>
                        @endif
                        <!-- End Table with stripped rows -->

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Modal -->
    @if(isset($unites))
    <div class="modal fade" id="staticBackdrop" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="importForm" action="{{ route('article-import') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Formulaire d'import</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="col-12 mb-3">
                            <label for="inputNanme4" class="form-label">Fichier excel</label>
                            <input type="file" class="form-control" id="upload_xls" name="upload_xls">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</main>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
    $(".js-example-basic-multiple").select2();
    var apiUrl = "{{ config('app.url_ajax') }}";

    $(document).ready(function() {
        $('#tauxBtn').click(function() {
            console.log('jai cliqué');
            $.ajax({
                url: apiUrl + '/taux-par-defaut',
                type: 'GET',
                success: function(response) {
                    // window.location.href = response.redirectUrl;
                    console.log('jai cliqué et succès', response);
                    $('#tauxMsg').removeClass('d-none');
                    $('#tauxMsg').html(response.message);
                },
                error: function(error) {
                    // La requête a échoué, vous pouvez gérer l'erreur ici
                    $('#tauxMsg').removeClass('d-none');
                    $('#tauxMsg').html('Erreur lors de la maj des taux de bases');

                }
            });
        });
    });

    $('#id_art_sel').select2({
        width: 'resolve'
    });

</script>
@endsection