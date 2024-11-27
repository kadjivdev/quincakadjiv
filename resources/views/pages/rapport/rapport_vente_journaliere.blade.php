@extends('layout.template')
@section('content')
    <main id="main" class="main">
        <div class="pagetitle d-flex">
            <div class="col-6">
                <h1 class="float-left">Ventes Journalière</h1>
            </div>
        </div>

        <section class="section">
            <div class="row col-12 mb-3">
                <form method="GET" action="{{ route('rapport_vente_journaliere') }}">
                    <div class="row mb-3">
                        <div class="col-8">
                            <label for="start_date">Date de vente:</label>
                            <input type="date" class="form-control" name="date_facture" id="date_facture" value="{{ request('date_facture') }}">
                        </div>

                        <div class="col-4 mt-4">
                            <button type="submit" class="btn btn-sm bg-dark text_orange w-100"><i class="bi bi-check-circle"></i> Valider</button>
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
                                        <th>Référence</th>
                                        <th>Type vente</th>
                                        <th>Catégorie vente</th>
                                        <th>Client</th>
                                        <th>Montant TTC</th>
                                        <th>Action </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($factures as $facture)
                                        @php
                                            $date_ecriture = Carbon\Carbon::parse($facture->created_at);
                                            $date_vente = $facture->date_facture != null ? Carbon\Carbon::parse($facture->date_facture) : '';

                                            $formatted_date_ecriture = $date_ecriture->locale('fr_FR')->isoFormat('ll');
                                            $formatted_date_vente = $facture->date_facture != null ? $date_vente->locale('fr_FR')->isoFormat('ll') : '';

                                            $vente_id = $facture->vente_id ? $facture->vente_id : 'aaa';
                                        @endphp
                                        <tr>
                                            <td>{{ $facture->id }} </td>
                                            <td>{{ $formatted_date_ecriture }}</td>
                                            <td>{{ $formatted_date_vente }}</td>
                                            <td>{{ $facture->num_facture }} </td>
                                            <td>{{ $facture->type_vente }} </td>
                                            <td>{{ $facture->nom_client ? "Vente Comptant" : "Vente à Crédit" }} </td>
                                            <td>{{ $facture->nom_client ? $facture->nom_client : $facture->client_facture }} </td>
                                            <td>{{ number_format($facture->montant_total , 2, ',', ' ')}} </td>
                                            <td>
                                                @can('ventes.voir-vente')
                                                    <a href="{{ route('rapport_vente_journaliere_detail', ['facture' => $facture->id, 'vente_id' => $vente_id]) }}" class="btn btn-sm bg-dark text_orange"
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

                    <div class="row" style="display: flex; flex-direction: row; justify-content: space-between">
                        <div class="col-4 text-center" style="display: flex; flex-direction: column; justify-content: center;">
                            <h5> Total Vente Comptant </h5>
                            <h6> <span class="badge bg-dark text_orange">{{ number_format($total_comptant, 2, ',', ' ') }} <small>FCFA</small></span></h6>
                        </div>

                        <div class="col-4 text-center" style="display: flex; flex-direction: column; justify-content: center;">
                            <h5> Total Globale </h5>
                            <h6> <span class="badge bg-dark text_orange">{{ number_format($total_comptant + $total_proforma, 2, ',', ' ') }} <small>FCFA</small></span></h6>
                        </div>

                        <div class="col-4 text-center" style="display: flex; flex-direction: column; justify-content: center;">
                            <h5> Total Vente à Crédit </h5>
                            <h6> <span class="badge bg-dark text_orange">{{ number_format($total_proforma, 2, ',', ' ') }} <small>FCFA</small></span></h6>
                        </div>
                </div>
            </div>
        </section>
    </main>
@endsection
