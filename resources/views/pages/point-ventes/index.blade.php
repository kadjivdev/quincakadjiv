@extends('layout.template')
@section('content')
<main id="main" class="main">

    <div class="pagetitle d-flex">
        <div class="col-6">
            <h1 class="float-left">Points de vente</h1>
        </div>
        <div class="col-6 justify-content-end">
            <div class="">
                <button type="button" class="btn btn-sm bg-dark text_orange" data-bs-toggle="modal"
                    data-bs-target="#staticBackdropImport">
                    <i class="bi bi-file-arrow-down-fill"></i> Importer prix
                </button>
                @can('point-ventes.add-boutique')
                <a href="{{ route('boutiques.create') }}" class="btn btn-sm bg-dark text_orange float-end"> + Ajouter un point</a>
                @endcan
            </div>
        </div>
    </div><!-- End Page +++ -->

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
                        <h5 class="card-title text-dark">Liste des points</h5>

                        <!-- Table with stripped rows -->
                        <table id="example" class="table table-bordered border-warning  table-hover table-striped table-sm">
                            <thead class="table-dark">
                                <tr>
                                    <th>N°</th>
                                    <th>Nom boutique</th>
                                    <th>Adresse</th>
                                    <th>Telephone</th>
                                    {{-- <th>Gérant</th> --}}
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($points as $point)
                                <tr>
                                    <td>{{ $i++ }} </td>
                                    <td>{{ $point->nom }}</td>
                                    <td>{{ $point->adresse }}</td>
                                    <td>{{ $point->phone }}</td>
                                    {{-- <td>{{ $point->gerant->name }}</td> --}}
                                    <td>
                                        <div class="dropdown text-center">
                                            <button class="btn w-100 btn-sm bg-dark text_orange dropdown-toggle" type="button"
                                                id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <i class="bi bi-gear"></i>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                @can('point-ventes.show-boutique')
                                                <li>
                                                    <a href="{{ route('boutiques.show', $point->id) }}" class="dropdown-item" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Voir détails"><i class="bi bi-list"></i> Détail </a>
                                                </li>
                                                @endcan

                                                <li>
                                                    <a href="{{route('boutiques.edit', $point->id )}}" data-bs-toggle="tooltip" class="dropdown-item" data-bs-placement="left" data-bs-title="Modifier point de vente"><i class="bi bi-pencil"></i> Modifier </a>
                                                </li>

                                                <li>
                                                    <form action="{{ route('boutiques.destroy', $point->id) }}"
                                                        method="POST" class="col-3">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item" data-bs-placement="left"
                                                            data-bs-title="Supprimer Point de vente" onclick="return confirm('Voulez vous vraiment valider ce point de vente ? Cette opération est irréversible')"><i class="bi bi-trash3"></i> Supprimer</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>Aucun point enregistré.</tr>
                                @endforelse
                            </tbody>
                        </table>
                        <!-- End Table with stripped rows -->

                    </div>
                </div>


            </div>
        </div>

        <div class="modal fade" id="staticBackdropImport" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" aria-labelledby="staticBackdropLabel3" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="importForm" action="{{ route('prix-import') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel3">Formulaire d'import</h1>
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
    </section>
</main>
@endsection