@extends('layout.template')
@section('content')
<main id="main" class="main">

    <div class="pagetitle d-flex">
        <div class="col-6">
            <h1 class="float-left">Ventes au comptant</h1>
        </div>
        <div class="col-6 justify-content-end">
            <!--   @can('ajouter-vente')
                    <div class="">
                        <a href="{{ route('ventes.create') }}" class="btn btn-primary float-end"> + Nouvelle vente</a>
                    </div>
                @endcan -->
            <div class="">
                <a href="{{ route('ventes.create') }}" class="btn btn-sm bg-dark text_orange float-end"> + Nouvelle vente</a>
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
                        <h5 class="card-title text-dark">Liste des ventes</h5>

                        <!-- Table with stripped rows -->
                        <table id="example" class="table table-bordered border-warning  table-hover table-striped table-sm">
                            <thead class="table-dark">
                                <tr>
                                    <th>N°</th>
                                    <th>Id Vente</th>
                                    <th>Date vente</th>
                                    <th>Date Création</th>
                                    <th>Client</th>
                                    <th>Montant </th>
                                    <th>Action </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($ventes as $vente)
                                @php
                                $datevente = Carbon\Carbon::parse($vente->created_at);
                                $datevente_vte = Carbon\Carbon::parse($vente->date_vente ?? $vente->factureVente?->date_facture);
                                $formattedDate = $datevente->locale('fr_FR')->isoFormat('ll');
                                $formattedDateVente = $datevente_vte->locale('fr_FR')->isoFormat('ll');
                                @endphp
                                @if (is_null($vente->validated_at))
                                <tr>
                                    <td>{{ $i++ }} </td>
                                    <td class="text-center">{{ $vente->id }} </td>
                                    <td>{{ $formattedDateVente }}</td>
                                    <td>{{ $formattedDate }}</td>
                                    <td>{{ $getClientById($vente->client_id) ? $getClientById($vente->client_id)->nom_client : $vente->client }} </td>
                                    <td>{{ number_format($vente->montant, 0, ',', ' ') }} </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm bg-dark text_orange w-100 dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-gear"></i>
                                            </button>
                                            <!-- <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                @can('ventes.voir-vente')
                                                <li>
                                                    <a href="{{ route('ventes.show', $vente->id) }}" data-bs-toggle="tooltip" class="dropdown-item" data-bs-placement="left" data-bs-title="Voir détails"><i class="bi bi-list"></i> Détails </a>
                                                </li>
                                                @endcan

                                                @if (!$vente->validated_at)
                                                @can('ventes.voir-vente')
                                                <li>
                                                    <a href="{{route('vente-validate', $vente->id)}}" onclick="return confirm('Êtes-vous sûr de vouloir valider la vente ?')" data-bs-toggle="tooltip" class="dropdown-item" data-bs-placement="left" data-bs-title="Valider la vente"><i class="bi bi-check-circle"></i> Valider </a>
                                                </li>
                                                <li>
                                                    <a href="{{route('ventes.edit', $vente->id )}}" data-bs-toggle="tooltip" class="dropdown-item" data-bs-placement="left" data-bs-title="Modifier vente"><i class="bi bi-pencil"></i>Modifier </a>
                                                </li>
                                                <li>
                                                    <form action="{{ route('vente-del', $vente->id) }}" method="POST" class="col-3">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item" data-bs-placement="left" data-bs-toggle="tooltip" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette ? Cette opération est irréversible')" data-bs-title="Supprimer la vente"><i class="bi bi-trash3"></i> Supprimer</button>
                                                    </form>
                                                </li>
                                                @endcan
                                                @endif
                                            </ul> -->
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                @can('ventes.voir-vente')
                                                @endcan
                                                @if (!$vente->validated_at)
                                                @can('ventes.voir-vente')
                                                <li>
                                                    <a href="{{route('vente-validation', $vente->id)}}" data-bs-toggle="tooltip" class="dropdown-item" data-bs-placement="left" data-bs-title="Valider la vente"><i class="bi bi-check-circle"></i> Validation </a>
                                                </li>
                                                <li>
                                                    <a href="{{route('ventes.edit', $vente->id )}}" data-bs-toggle="tooltip" class="dropdown-item" data-bs-placement="left" data-bs-title="Modifier vente"><i class="bi bi-pencil"></i> Modifier </a>
                                                </li>
                                                <li>
                                                    <form action="{{ route('vente-del', $vente->id) }}" method="POST" class="col-3">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item" data-bs-placement="left" data-bs-toggle="tooltip" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette ? Cette opération est irréversible')" data-bs-title="Supprimer la vente"><i class="bi bi-trash3"></i> Supprimer</button>
                                                    </form>
                                                </li>
                                                @endcan
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                                @empty
                                <tr>Aucune vente enregistrée</tr>
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