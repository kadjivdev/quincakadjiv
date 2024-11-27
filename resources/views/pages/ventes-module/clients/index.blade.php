@extends('layout.template')
@section('content')
<main id="main" class="main">

    <div class="pagetitle d-flex">
        <div class="col-3">
            <h1 class="float-left">Clients</h1>
        </div>
        <div class="col-4">
            {{-- <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#staticBackdrop1">
                Importer Report à nouveau
            </button> --}}
        </div>
        <div class="col-5 justify-content-end">
            <div class="">
                {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                    Importer
                </button> --}}
                <a href="{{ route('clients.create')}}" class="btn btn-sm bg-dark text_orange float-end"> + Ajouter un client</a>
            </div>
        </div>
    </div><!-- End Page +++ -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body mt-1">
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
                        <h5 class="card-title text-dark">Liste des clients</h5>

                        <table id="example" class="table table-bordered border-warning  table-hover table-striped table-sm">
                            <thead class="table-dark">
                                <tr>
                                    <th width="2%">N°</th>
                                    <th width="30%">
                                        Nom et Prénom(s)
                                    </th>
                                    <th width="10%">Département</th>
                                    <th width="15%">Agent</th>
                                    <th width="15%">Adresse</th>
                                    <th width="10%">Contact</th>
                                    <th width="15%">Solde</th>
                                    <th width="3%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($clients as $client)
                                <tr>
                                    <td>{{ $client->id }} <input type="hidden" class="form-control" value="{{ $client->id}}" name="client_id"></td>
                                    <td>{{ $client->nom_client }}</td>
                                    <td>{{ $client->departement?->libelle }}</td>
                                    <td>{{ $client->agent?->nom }}</td>
                                    <td>{{ $client->address }}</td>

                                    <td>{{ $client->phone }}</td>
                                    <td>{{ number_format($client->solde, '2', ',', ' ') }}</td>

                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm bg-dark text_orange w-100 dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-gear"></i>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">

                                            <li>
                                                    <a href="{{route('reglements-clt' , $client->id)}}" data-bs-toggle="tooltip" class="dropdown-item" data-bs-placement="left" data-bs-title="Voir l'historique du compte"><i class="bi bi-list"></i> Historique du Compte </a>

                                                </li>

                                                <li>
                                                    <a href="{{route('clients.show', $client->id )}}" data-bs-toggle="tooltip" class="dropdown-item" data-bs-placement="left" data-bs-title="Voir compte client"><i class="bi bi-list"></i> Détail du Compte </a>

                                                </li>
                                                <li>
                                                    <a href="{{route('clients.edit', $client->id )}}" data-bs-toggle="tooltip" class="dropdown-item" data-bs-placement="left" data-bs-title="Modifier client"><i class="bi bi-pencil"></i> Modifier le Client </a>

                                                </li>

                                                <li>
                                                    <a href="{{route('reglements-clt.create', $client->id )}}" class="dropdown-item" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Enregistrer un règlement"><i class="bi bi-plus"></i> Enregistrer un Règlement </a>

                                                </li>

                                                <li>
                                                    <a href="{{route('real-reglements-clt', $client->id )}}" class="dropdown-item" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Liste des règlements"><i class="bi bi-list"></i> Liste des Règlements </a>

                                                </li>

                                                <li>
                                                    <a href="{{route('acompte-create', $client->id )}}" class="dropdown-item" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Enregistrer un accompte "><i class="bi bi-plus"></i> Enregistrer un Accompte </a>

                                                </li>

                                                <li>
                                                    <a href="{{route('acomptes-clt', $client->id )}}" class="dropdown-item" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Liste des accomptes"><i class="bi bi-list"></i> Liste des Accomptes </a>

                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>Aucun client enregistré</tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('client-import') }} " method="post" enctype="multipart/form-data">
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

     <!-- Modal -->
     <div class="modal fade" id="staticBackdrop1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('compte-client-import') }} " method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Formulaire d'import Compte client</h1>
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
