@extends('layout.template')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle d-flex">
            <div class="col-6">
                <h1 class="float-left">Détails de la facture {{ $factureFrs->ref_facture }} </h1>
            </div>
            <div class="col-6 justify-content-end">
                <a href="{{ route('fournisseurs.show', $factureFrs->fournisseur_id) }}"
                    class="btn btn-success float-end mx-2"> <i class="bi bi-arrow-left"></i> Retour</a>

            </div>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body row">
                            <div class="col-4">
                                <h5 class="card-title">{{$factureFrs->montant_facture}}</h5>
                                <p class="card-text">Montant HT</p>
                            </div>

                            <div class="col-4">
                                <h5 class="card-title">{{$factureFrs->montant_total}}</h5>
                                <p class="card-text">Montant TTC</p>
                            </div>
                            <div class="col-4">
                                <h5 class="card-title">{{$factureFrs->montant_regle}}</h5>
                                <p class="card-text">Montant réglé</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Liste des articles de la facture <span
                                    class="badge text-bg-success">{{ $cmd->reference }}</span>
                            </h5>

                            <table id="dataTable" class="table datatable">
                                <thead>
                                    <tr>
                                        <th>N°</th>
                                        <th>
                                            Article
                                        </th>
                                        <th>Quantité</th>
                                        <th>PU</th>
                                        <th>Unité de mesure</th>
                                        <th>Montant</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($lignes as $article)
                                        <tr>
                                            <td>{{ $article->id }} </td>
                                            <td>{{ $article->nom }}</td>
                                            <td>{{ $article->quantity }}</td>
                                            <td>{{ $article->prix_unit }}</td>
                                            <td>{{ $article->unite }}</td>
                                            <td>{{ number_format((float) $article->prix_unit * (float) $article->quantity, 2, ',', ' ') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>

                </div>
            </div>
        </section>
    </main>
@endsection
