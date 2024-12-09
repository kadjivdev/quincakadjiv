@extends('layout.template')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle d-flex">
            <div class="col-6">
                <h1 class="float-left text-dark">Rapport des ventes</h1>
            </div>

        </div>

        <section class="section">
            <div class="row col-12 mb-3">
                <form method="GET" action="{{ route('rap_fact_vte_all') }}">
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="start_date">Date début:</label>
                            <input type="date" class="form-control" name="start_date" id="start_date" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-4">
                            <label for="end_date">Date fin:</label>
                            <input type="date" class="form-control" name="end_date" id="end_date" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-1 mt-4">
                            <button type="submit" class="btn btn-sm bg-dark text_orange">Filtrer</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title text-dark">Liste des ventes</h5>

                            <!-- Table with stripped rows -->
                            <table id="example"
                                class=" table table-bordered border-warning  table-hover table-striped table-sm">
                                <thead class="table-dark">
                                    <tr>
                                        <th>N°</th>
                                        <th>Date Ecriture</th>
                                        <th>Date vente</th>
                                        <th>Catégorie vente</th>
                                        <th>Catégorie vente</th>
                                        <th>Client</th>
                                        <th>Montant </th>
                                        <th>Action </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($ventesAllSorted as $vente)
                                        @php
                                            $date_ecriture = Carbon\Carbon::parse($vente->created_at);
                                            $date_vente = $vente->date_vente ? Carbon\Carbon::parse($vente->date_vente) : Carbon\Carbon::parse($vente->date_devis);

                                            $formatted_date_ecriture = $date_ecriture->locale('fr_FR')->isoFormat('ll');
                                            $formatted_date_vente = $date_vente->locale('fr_FR')->isoFormat('ll');
                                        @endphp
                                        <tr>
                                            <td>{{ $i++ }} </td>
                                            <td>{{ $formatted_date_ecriture }}</td>
                                            <td>{{ $formatted_date_vente }}</td>
                                            <td>{{ $vente->typeVente?->libelle ?? '-' }} </td>
                                            <td>{{ $vente->encaisse ? 'Vente Comptant' : 'Vente à crédit'}} </td>
                                            <td>{{ $vente->acheteur->nom_client ?? $vente->client->nom_client  }} </td>
                                            <td>{{ $vente->montant ?? $vente->montant_total }} </td>
                                            <td>
                                                @can('ventes.voir-vente')
                                                    <a href="{{ route('ventes.show', $vente->id) }}" class="btn btn-sm bg-dark text_orange"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        data-bs-title="Voir détails"> <i class="bi bi-eye"></i> </a>
                                                </td>
                                            @endcan

                                        </tr>
                                    @empty
                                        <tr>Aucune vente enregistrée</tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-4" style="display: flex; flex-direction: column; justify-content: center; align-items: center">
                            <h5 > Montant Vente Comptant </h5>
                            <span class="badge bg-dark text_orange" >{{number_format( $total_comptant, 2, ',', ' ')}} <small>FCFA</small></span>
                        </div>

                        <div class="col-4" style="display: flex; flex-direction: column; justify-content: center; align-items: center">
                            <h5 > Montant Vente Proforma </h5>
                            <span class="badge bg-dark text_orange" >{{number_format( $total_proforma, 2, ',', ' ')}} <small>FCFA</small></span>
                        </div>

                        <div class="col-4" style="display: flex; flex-direction: column; justify-content: center; align-items: center">
                            <h5 > Montant Total </h5>
                            <span class="badge bg-dark text_orange" >{{number_format( $total_proforma + $total_comptant, 2, ',', ' ')}} <small>FCFA</small></span>
                        </div>

                    </div>

                </div>
            </div>
        </section>
    </main>
@endsection
