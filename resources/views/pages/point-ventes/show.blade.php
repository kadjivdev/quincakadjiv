@extends('layout.template')
@section('content')
<main id="main" class="main">

    <div class="pagetitle d-flex">
        <div class="col-6">
            <h1 class="float-left">Points de vente {{ $point->nom }}</h1>
        </div>
        <div class="col-6 justify-content-end">
            <div class="">
                <button type="button" class="btn btn-sm bg-dark text_orange" data-bs-toggle="modal" data-bs-target="#staticBackdropStock">
                    <i class="bi bi-file-arrow-down-fill"></i> Importer stock
                </button>
                <a href="{{ route('boutiques.index')}}" class="btn btn-sm bg-dark text_orange float-end"> <i class="bi bi-arrow-left"></i> Retour</a>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body py-1">
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
                        <h5 class="card-title text-dark">Liste des articles disponibles</h5>

                        <!-- Table with stripped rows -->
                        <table id="example" class=" table table-bordered border-warning  table-hover table-striped table-sm">
                            <thead class="table-dark">
                                <tr>
                                    <th>N°</th>
                                    <th>Désignation</th>
                                    <th>Catégorie</th>
                                    <th>Prix particulier</th>
                                    <th>Prix spécial</th>
                                    <th>Stock</th>
                                    <th>Prix gros</th>
                                    <th>Prix btp</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($detail as $article)
                                <tr>
                                    <td>{{ $i++ }} </td>
                                    <td>{{ $article->nom }}</td>
                                    <td>{{ $article->categorie }}</td>
                                    <td>{{ $article->prix_particulier }}</td>
                                    <td>{{ $article->prix_special }}</td>
                                    <td>{{ $article->qte_stock }}</td>
                                    <td>{{ $article->prix_revendeur }}</td>
                                    <td>{{ $article->prix_btp }}</td>
                                    <td>
                                        <a class="btn btn-sm bg-dark text_orange w-100" data-bs-toggle="modal"
                                            data-bs-target="#staticBackdrop{{$article->id}}"> <i class="bi bi-eye"></i> </a>
                                    </td>
                                </tr>

                                <!-- Modal -->
                                <div class="modal fade" id="staticBackdrop{{$article->id}}"
                                    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('prix-store') }}" method="post">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Ajouter les prix de l'article</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    @csrf
                                                    <input type="hidden" value="{{$article->articleId}}" name="article_id">
                                                    <input type="hidden" value="{{$article->pointId}}" name="point_id">
                                                    <div class="col-12">
                                                        <label for="" class="form-label">Prix spécial</label>
                                                        <input type="number" min="1" required class="form-control"
                                                            name="prix_special">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label">Prix revendeur</label>
                                                        <input type="number" min="1" class="form-control"
                                                            name="prix_revendeur">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label">Prix particulier</label>
                                                        <input type="number" min="1" class="form-control"
                                                            name="prix_particulier">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label">Prix BTP</label>
                                                        <input type="number" min="1" class="form-control" name="prix_btp">
                                                    </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <div class="col-lg-12 d-flex flex-row align-items-center justify-content-between">
                                                        <button type="submit" class="btn btn-sm btn-dark text_orange w-100 submitBtn" id="ajouterArticle"><i class="bi bi-check-circle"></i> Enregistrer</button>
                                                        <button type="button" class="btn btn-sm btn-dark text_orange w-100 loadingBtn" hidden><span class="spinner-border spinner-border-sm text_orange loading"></span> En cours ...</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>Aucun article disponible ici.</tr>
                                @endforelse
                            </tbody>
                        </table>
                        <!-- End Table with stripped rows -->
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="staticBackdropStock" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropStockLabel3" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="importForm" action="{{ route('stock-import') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropStockLabel3">Formulaire d'import</h1>
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
                        <div class="col-lg-12 d-flex flex-row align-items-center justify-content-between">
                            <button type="submit" class="btn btn-sm btn-dark text_orange w-100 submitBtn" id="ajouterArticle"><i class="bi bi-check-circle"></i> Enregistrer</button>
                            <button type="button" class="btn btn-sm btn-dark text_orange w-100 loadingBtn" hidden><span class="spinner-border spinner-border-sm text_orange loading"></span> En cours ...</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection