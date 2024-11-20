@extends('layout.template')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle d-flex">
            <div class="col-6">
                <h1 class="float-left">Factures clients sans règlement</h1>
            </div>
            <div class="col-6 justify-content-end">
            </div>
        </div>

        <section class="section">
            <div class="row col-12 mb-3">
                <form method="GET" action="{{ route('facturesCltSansReglemt') }}">
                    <div class="row mb-3">
                        <div class="col-5">
                            <label for="start_date">Date début:</label>
                            <input type="date" class="form-control" name="start_date" id="start_date" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-5">
                            <label for="end_date">Date fin:</label>
                            <input type="date" class="form-control" name="end_date" id="end_date" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-2 mt-4">
                            <button type="submit" class="btn btn-primary">Filtrer</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Liste des factures</h5>

                            <table id="example"
                                class="table table-bordered border-warning  table-hover table-warning table-sm">
                                <thead>
                                    <tr>
                                        <th>N°</th>
                                        <th>Référence</th>
                                        <th>Date facture</th>
                                        <th>Montant</th>
                                        <th>Client</th>
                                        <th>Type facture</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($facturesTous as $facture)
                                        <tr>
                                            <td>{{ $facture->id }} </td>
                                            <td>{{ $facture->ref_facture }} </td>
                                            <td>{{ Carbon\Carbon::parse($facture->date_facture)->locale('fr_FR')->isoFormat('ll') }}
                                            </td>
                                            <td>{{ $facture->montant_total }}</td>
                                            <td>{{ $facture->client->nom_client }}</td>
                                            <td>{{ $facture->typeFacture->libelle }}</td>
                                        </tr>
                                    @empty
                                        <tr>Aucune facture enregistrée.</tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
