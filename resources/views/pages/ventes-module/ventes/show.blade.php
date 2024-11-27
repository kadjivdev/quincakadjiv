@extends('layout.template')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle d-flex">
            <div class="col-9">
                {{-- <h1 class="float-left">Détails vente n° {{Carbon\Carbon::parse($item->created_at)->locale('fr_FR')->isoFormat('ll')}} </h1> --}}
                <h1 style="font-size:150%" class="float-left">Détails vente n° {{$item->factureVente?->num_facture}} | Client : {{$item->acheteur?->nom_client}} </h1>
            </div>
            <div class="col-3 justify-content-end" >
                <a href="{{ route('ventes.index')}}" class="btn btn-sm bg-dark text_orange float-end mx-2"> <i class="bi bi-arrow-left"></i> Retour</a>

            </div>
        </div>
        
        <div class="pagetitle d-flex">
            <div class="col-6">
                {{-- <h1 class="float-left">Détails vente n° {{Carbon\Carbon::parse($item->created_at)->locale('fr_FR')->isoFormat('ll')}} </h1> --}}
                <b style="font-size:30px; font-weight:bolder;" class="float-left">Total TTC: {{number_format($item->montant, 2, ',', ' ')}}</b>
            </div>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title text-dark">Liste des articles  <span class="badge bg-dark text_orange">{{ $item->reference }}</span>
                                </h5>

                            <table id="example" class=" table table-bordered border-warning  table-hover table-striped table-sm">
                                <thead class="bg-dark">
                                    <tr>
                                        <th>N°</th>
                                        <th>
                                           Article
                                        </th>
                                        <th>Quantité</th>
                                        <th>Prix unitaire</th>
                                        <th>Montant</th>
                                        <th>Unité de mesure</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($lignes as $article)
                                        <tr>
                                            <td>{{ $i++ }} </td>
                                            <td>{{ $article->nom }}</td>
                                            <td>{{ $article->qte_cmde }}</td>
                                            <td>{{ number_format($article->prix_unit, 0, ',', ' ') }}</td>
                                            <td>{{ number_format($article->prix_unit * $article->qte_cmde, 0, ',', ' ') }}</td>
                                            <td>{{ $article->unite }}</td>
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
