{{-- {{ <?php dd($la_categorie); exit(); ?>}} --}}

@extends('layout.template')
@section('content')
    <main id="main" class="main">
        <div class="pagetitle d-flex">
            <div class="col-6">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Tableau de Bord</a></li>
                        <li class="breadcrumb-item active">Catégorie</li>
                    </ol>
                </nav>
            </div>
            <div class="col-6 d-flex flex-row justify-content-end">
                <div class="">
                    {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                        Importer
                    </button> --}}
                    <span class="badge rounded-pill bg-dark">
                        {{ count($categories) }} Catégories au total
                    </span>
                </div>
            </div>
        </div><!-- End Page +++ -->

        <section class="section">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Liste des categories</h5>

                            <table id="example"
                                class=" table table-bordered border-warning  table-hover table-warning table-sm"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th width="3%">N°</th>
                                        <th width="94%">Nom categorie</th>
                                        <th width="3%">...</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($categories as $categorie)
                                        <tr>
                                            <td>{{ $categorie->id }} </td>
                                            <td>{{ $categorie->libelle }}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-secondary dropdown-toggle btn-small" type="button"
                                                        id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        <i class="bi bi-gear"></i>
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                        @can('articles.categorie-show')
                                                            <li>
                                                                <a href="{{ route('categories.show', $categorie->id) }}"
                                                                   data-bs-toggle="tooltip" data-bs-placement="left"
                                                                   data-bs-title="Voir les détails de la catégorie"
                                                                   class="dropdown-item">Voir détail</a>
                                                            </li>
                                                        @endcan

                                                        @can('articles.edit-category')
                                                            <li>
                                                                <a href="{{ route('categories.edit', $categorie->id) }}"
                                                                   data-bs-toggle="tooltip" data-bs-placement="left"
                                                                   data-bs-title="Modifier la catégorie"
                                                                   class="dropdown-item">Modifier</a>
                                                            </li>
                                                        @endcan

                                                        {{-- @if (is_null($commande->validated_at))
                                                                <li>
                                                                    <form action="{{ route('commandes.destroy', $commande->id) }}"
                                                                        class="form-inline" method="POST"
                                                                        onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette commande?');">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="dropdown-item text-danger"
                                                                            data-bs-toggle="tooltip" data-bs-placement="left"
                                                                            data-bs-title="Supprimer la commande">Supprimer</button>
                                                                    </form>
                                                                </li>
                                                            @endif --}}
                                                    </ul>
                                                </div>


                                            </td>
                                        </tr>
                                    @empty
                                        <tr>Aucune categorie enregistré.</tr>
                                    @endforelse

                                </tbody>
                            </table>

                        </div>
                    </div>

                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Ajouter une Catégorie</h5>

                            <!-- Afficher des messages de succès -->
                            @if (session('success'))
                                <div class="alert alert-success  d-flex flex-row align-items-center" role="alert">
                                    <i class="bi bi-check-circle"></i>&nbsp; &nbsp;
                                    <div>
                                        {{ session('success') }}
                                    </div>
                                </div>
                            @endif

                            <!-- Afficher des erreurs de validation -->
                            @if ($errors->any())
                                <div class="alert alert-danger  d-flex flex-row align-items-center" role="alert">
                                    <i class="bi bi-exclamation-triangle"></i>&nbsp; &nbsp;
                                    <div>
                                        @foreach ($errors->all() as $error)
                                            {{ $error }}
                                        @endforeach
                                    </div>
                                </div>
                                <!-- <div class="alert alert-danger">
                                                        <ul>
                                                            @foreach ($errors->all() as $error)
    <li>{{ $error }}</li>
    @endforeach
                                                        </ul>
                                                    </div> -->
                            @endif

                            <!-- Vertical Form -->
                            <form class="row g-3" action="{{ route('categories.update', $la_categorie->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="col-12">
                                    <label for="inputNanme4" class="form-label">Nom de catégorie</label>
                                    <input type="text" class="form-control" value="{{$la_categorie->libelle}}" name="libelle">
                                    {{-- <input type="hidden" value="{{$la_categorie->id}}" name="id_cat"> --}}
                                </div>

                                <div class="col-lg-12 d-flex flex-row align-items-center justify-content-between">
                                    <button type="submit" class="btn btn-success">Enregistrer</button>
                                    <div class="loader"></div>
                                    <button class="btn btn-success button_loader" id="myLoader" type="button" disabled>
                                        <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                                        <span role="status">En cours...</span>
                                    </button>
                                    <button type="reset" class="btn btn-dark">Annuler</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('categorie-import') }} " method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Formulaire d'import</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="col-12 mb-3">
                                <label for="inputNanme4" class="form-label">Fichier excel</label>
                                <input type="file" class="form-control" name="upload">
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
    </main>
@endsection
