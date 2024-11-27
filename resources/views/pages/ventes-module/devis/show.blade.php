@extends('layout.template')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle d-flex">
            <div class="col-6">
                <h1 class="float-left">Détails Proforma du {{Carbon\Carbon::parse($item->date_devis)->locale('fr_FR')->isoFormat('ll')}} </h1>
            </div>
            <div class="col-6 justify-content-end">
                <a href="{{ route('devis.index')}}" class="btn btn-sm bg-dark text_orange float-end mx-2"> <i class="bi bi-arrow-left"></i> Retour</a>

            </div>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title text-dark">Liste des articles du devis <span class="badge bg-dark text_orange">{{ $item->reference }}</span></h5>

                            <table id="example" class="table table-sm table-hover table-bordered border-warning table-sm">
                                <thead class="table-dark">
                                    <tr>
                                        <th>N°</th>
                                        <th>
                                           Article
                                        </th>
                                        <th>Quantité</th>
                                        <th>Prix unitaire</th>
                                        <th>Unité de mesure</th>
                                        <th>Montant</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; $total = 0; ?>

                                    @foreach ($lignes as $article)
                                        <tr>
                                            <td>{{ $i}} </td>
                                            <td>{{ $article->nom }}</td>
                                            <td>{{ $article->qte_cmde }}</td>
                                            <td>{{ $article->prix_unit }}</td>
                                            <td>{{ $article->unite }}</td>
                                            <td>{{ $article->qte_cmde * $article->prix_unit }}</td>
                                        </tr>
                                        <?php $i++; $total = $total + ($article->qte_cmde * $article->prix_unit); ?>

                                    @endforeach
                                </tbody>
                            </table>
                            <h5 class="card-title">Total du Proforma : <b class="bg-dark text_orange" style="font-size:30px; text-align:center; font-weight:bolder;"> <?php echo number_format($total, 2, ',', ' ') ; ?>  </b> FCFA</h5>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </main>
@endsection
