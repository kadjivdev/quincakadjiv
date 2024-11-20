@extends('layout.template')
@section('content')
<main id="main" class="main">
    <div class="pagetitle d-flex">
        <div class="col-3">
            <h1 class="float-left"> Magasin {{ $magasin->nom }}</h1>
        </div>
        <div class="col-9 justify-content-end">
            <div style="width:100%;display:flex; flex-direction:row;align-items:center;justify-content:space-between" class="">
                @can('rapports.ajouter-inventaire')
                <a href="{{ route('inventaire-multiple', $magasin->id) }}" class="btn btn-primary float-end"> + Ajouter des inventaire</a>
                @endcan


                @can('rapports.ajouter-inventaire')
                <a href="{{ route('inventaire-create', $magasin->id) }}" class="btn btn-primary float-end"> + Ajouter un inventaires</a>
                @endcan

                @can('rapports.ajouter-inventaire')
                <a href="{{ route('inventaire-bulk', $magasin->id) }}" class="btn btn-primary float-end"> + Inventaire en lot</a>
                @endcan

                <a href="{{ route('magasins.index') }}" class="btn mx-2 btn-success float-end"> <i class="bi bi-arrow-left"></i> Retour</a>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
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
                        <h5 class="card-title">Liste des articles disponibles</h5>

                        <!-- Table with stripped rows -->
                        <table id="example" class=" table table-bordered border-warning  table-hover table-warning table-sm">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Désignation</th>
                                    <th>Catégorie</th>
                                    <th>Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($articles as $article)
                                <tr>
                                    <td>{{ $i++ }} </td>
                                    <td>{{ $article->nom }}</td>
                                    <td>{{ $article->categorie->libelle }}</td>
                                    <td>{{ $article->qte_stock }}</td>
                                </tr>
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
</main>
@endsection