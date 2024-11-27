<section class="section">

    <div class="row" style="text-align: center">
        <div class="col-6">
            <small>Référence Facture</small>
            <br>
            <h5 class="badge bg-dark text_orange">{{ $factureFrs->ref_facture }}</h5>
        </div>
        <div class="col-6">
            <small>Référence Commande</small>
            <br>
            <h5 class="badge bg-dark text_orange">{{ $cmd->reference }}</h5>
        </div>
    </div>

    <hr>

    <div class="row" style="text-align: center">

        <div class="col-4 flex-row justify-content-center">
            <small class="text-dark" style="text-decoration: underline" >Montants HT</small>
            <h5 class="text-dark">{{ number_format($factureFrs->montant_facture, 2, ',', ' ') }}</h5>

        </div>

        <div class="col-4">
            <small class="text-dark" style="text-decoration: underline">Montants TTC</small>
            <h5 class="text-dark">{{ number_format($factureFrs->montant_total, 2, ',', ' ') }}</h5>
             </div>

        <div class="col-4">
            <small class="text-dark" style="text-decoration: underline">Montants Régler</small>
            <h5 class="text_orange">{{ number_format($factureFrs->montant_total, 2, ',', ' ') }}</h5>
         </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-lg-12">
            <h5 class="card-title text-dark">Liste des articles de la facture </h5>
            <table id="example" class="table table-bordered border-warning  table-hover table-striped table-sm">

                <thead class="table-dark">
                    <tr>
                        <th>N°</th>
                        <th>Article</th>
                        <th>Quantité</th>
                        <th>PU</th>
                        <th>Unité de mesure</th>
                        <th>Montant</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lignes as $article)
                        <tr>
                            <td>{{ $article->id }}</td>
                            <td>{{ $article->nom }}</td>
                            <td>{{ $article->quantity }}</td>
                            <td>{{ number_format($article->prix_unit, 2, ',', ' ') }}</td>
                            <td>{{ $article->unite }}</td>
                            <td>{{ number_format((float)$article->prix_unit * (float)$article->quantity, 2, ',', ' ') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
