@extends('layout.template')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle d-flex">
            <div class="col-6">
                <h1 class="float-left">Magasins</h1>
            </div>
            <div class="col-6 justify-content-end">
                @can('point-ventes.add-magasin')
                    <div class="">
                        <a href="{{ route('magasins.create') }}" class="btn btn-sm bg-dark text_orange float-end"> + Ajouter un magasin</a>
                    </div>
                @endcan
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
                            <h5 class="card-title text-dark">Liste des magasins</h5>

                            <!-- Table with stripped rows -->
                            <table id="example" class="table table-bordered border-warning  table-hover table-striped table-sm">
                                <thead class="table-dark">
                                    <tr>
                                        <th>N°</th>
                                        <th>Nom Magasin</th>
                                        <th>Adresse</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($magasins as $magasin)
                                        <tr>
                                            <td>{{ $i++ }} </td>
                                            <td>{{ $magasin->nom }}</td>
                                            <td>{{ $magasin->adresse }}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm bg-dark text_orange w-100 dropdown-toggle" type="button"
                                                        id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        <i class="bi bi-gear"></i>
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                        @can('point-ventes.voir-magasin')
                                                            <li>
                                                                <a href="{{ route('magasins.show', $magasin->id) }}" class="dropdown-item"
                                                                    data-bs-toggle="tooltip" data-bs-placement="left"
                                                                    data-bs-title="Voir détails"><i class="bi bi-list"></i> Détail </a>
                                                            </li>
                                                        @endcan

                                                        <li>
                                                            <a href="{{route('magasins.edit', $magasin->id )}}"  data-bs-toggle="tooltip" class="dropdown-item" data-bs-placement="left" data-bs-title="Modifier magasin"><i class="bi bi-pencil"></i> Modifier </a>
                                                        </li>

                                                        <li>
                                                            <form action="{{ route('magasins.destroy', $magasin->id) }}"
                                                                method="POST" class="col-3">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item" data-bs-placement="left"
                                                                    data-bs-title="Supprimer Magasin" onclick="return confirm('Voulez vous vraiment valider ce magasin? Cette opération est irréversible')"><i class="bi bi-trash3"></i> Supprimer</button>
                                                            </form>
                                                        </li>      

                                                        <li>
                                                            <a href="{{ route('magasin-inventaires', $magasin->id) }}" class="dropdown-item"
                                                                data-bs-toggle="tooltip" data-bs-placement="left"
                                                                data-bs-title="Voir mes inventaires"><i class="bi bi-list"></i> Inventaires</a>

                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>

                                        </tr>
                                    @empty
                                        <tr>Aucun magasin enregistré.</tr>
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
