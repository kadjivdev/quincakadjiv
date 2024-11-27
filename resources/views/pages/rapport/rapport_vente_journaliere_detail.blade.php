@extends('layout.template')
@section('content')
<main id="main" class="main">

    <div class="pagetitle d-flex">
        <div class="col-11">
            <h1 class="float-left">Détails Vente Journalière <span style="color: darkgoldenrod; font-weight: bolder"> {{$facture->num_facture}} </span> du client <span style="color: darkgoldenrod; font-weight: bolder"> {{ $facture->nom_client ? $facture->nom_client : $facture->client_facture }} <span></span></h1>
        </div>

        <div class="col-1" style="display: flex; flex-direction: row; justify-content: flex-end;">
            <a href="{{ URL::previous() }}" class="btn btn-sm bg-dark text_orange">Retour</a>

        </div>

    </div>

    <section class="section">

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
                                    <th>Désignation</th>
                                    <th>Quantité</th>
                                    <th>Prix Unitaire</th>
                                    <th>Montant</th>

                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($detail_facture as $detail_facture)

                                <tr>
                                    <td>{{ $detail_facture->id }} </td>
                                    <td>{{ $detail_facture->nom_article }} </td>
                                    <td>{{ $detail_facture->qte_cmde }} </td>
                                    <td>{{ number_format( $detail_facture->prix_unit , 2, ',', ' ') }} </td>
                                    <td>{{ number_format( $detail_facture->qte_cmde * $detail_facture->prix_unit, 2, ',', ' ') }} </td>

                                </tr>
                                @empty
                                <tr>Aucune vente enregistrée</tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6 text-center" style="display: flex; flex-direction: column; justify-content: center;">
                        <h5> Montant Total HT </h5>
                        <h6> <span class="badge bg-dark text_orange">{{ number_format($facture->montant_facture, 2, ',', ' ') }} <small>FCFA</small></span></h6>
                    </div>

                    <div class="col-6 text-center" style="display: flex; flex-direction: column; justify-content: center;">
                        <h5> Montant Total TTC </h5>
                        <h6> <span class="badge bg-dark text_orange">{{ number_format($facture->montant_total, 2, ',', ' ') }} <small>FCFA</small></span></h6>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection