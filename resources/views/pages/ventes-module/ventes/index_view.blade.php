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
            {{-- <div class="">
                <a href="{{ route('ventes.create') }}" class="btn btn-primary float-end"> + Nouvelle vente</a>
            </div> --}}
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
                        <h5 class="card-title text-dark">Liste des ventes</h5>

                        <!-- Table with stripped rows -->
                        <table id="example" class=" table table-bordered border-warning  table-hover table-striped table-sm">
                            <thead class="table-dark">
                                <tr>
                                    <th>N°</th>
                                    <th>Id Vente</th>
                                    <th>Date vente</th>
                                    <th>Date Création</th>
                                    <th>Ref Facture</th>
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
                                <tr>
                                    <td>{{ $i++ }} </td>
                                    <td class="text-center">{{ $vente->id }} </td>
                                    <td>{{ $formattedDateVente }}</td>
                                    <td>{{ $formattedDate }}</td>
                                    <td>{{ $vente->factureVente?->num_facture }}</td>
                                    <td>{{ $getClientById($vente->client_id) ? $getClientById($vente->client_id)->nom_client : $vente->client }} </td>
                                    <td>{{ number_format($vente->montant, 0, ',', ' ') }} </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm w-100 bg-dark text_orange dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-gear"></i>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                @can('ventes.voir-vente')
                                                <li>
                                                    <a href="{{ route('ventes.show', $vente->id) }}" data-bs-toggle="tooltip" class="dropdown-item" data-bs-placement="left" data-bs-title="Voir détails"><i class="bi bi-list"></i> Détails </a>
                                                    @if ($vente->encaisse == 'non')
                                                        <a onclick="return confirm('Voulez-vius réellement encaisser cette vente ? Cette opération est irréversible')" href="{{ route('ventes-encaisser', $vente->id) }}" data-bs-toggle="tooltip" class="dropdown-item" data-bs-placement="left" data-bs-title="Voir détails"><i class="bi bi-cart3"></i> Encaisser </a>                                                        
                                                    @endif
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                    @endcan

                                </tr>
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
