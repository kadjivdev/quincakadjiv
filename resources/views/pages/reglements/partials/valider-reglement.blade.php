<section class="section">

    <div class="row" style="text-align: center">

        <div class="col-6">

            <form action="" method="post">
                <label for="">Raisons du rejet</label> <br>
                <textarea name="raison" id="" class="form-control" style="width: 100%" rows="2"></textarea> <br>
                <input type="submit" class="btn btn-warning" value="Rejeter ce Règlement">
            </form>
        </div>

        <div class="col-6">
            <a href="#"
            class="btn btn-danger details-button"
            data-bs-toggle="modal"
            data-reg-id="{{ $reglementFrs->id }}"
            data-bs-target="#confirmModal"
            id="confirmationbtn">
            Valider ce règlement
        </a>
        </div>

    </div>
    <hr>

    <div class="row" style="text-align: center">

        <h5 style="text-decoration: underline">Détails du Règlement</h5>
        <div class="col-4">
            <small>Code Règlement</small>
            <br>
            <h5 class="badge text-bg-warning">{{ $reglementFrs->code }}</h5>
        </div>

        <div class="col-4">
            <small>Date d'enregistrement</small>
            <br>
            <h5 class="badge text-bg-warning">{{ $reglementFrs->created_at->locale('fr_FR')->isoFormat('lll') }}</h5>
        </div>

        <div class="col-4">
            <small>Date Règlement</small>
            <br>
            <h5 class="badge text-bg-warning">{{ $reglementFrs->date_reglement->locale('fr_FR')->isoFormat('ll') }}</h5>
        </div>

    </div>

    <div class="row" style="text-align: center">

        <div class="col-4">
            <small>Fournisseur</small>
            <br>
            <h5 class="badge text-bg-warning">{{ $reglementFrs->facture->fournisseur->name }}</h5>
        </div>

        <div class="col-4">
            <small>Montant Règlement</small>
            <br>
            <h5 class="badge text-bg-warning">{{ number_format($reglementFrs->montant_regle, 2, ',', ' ') }}</h5>
        </div>

        <div class="col-4">
            <small>Mode Règlement</small>
            <br>
            <h5 class="badge text-bg-warning">{{ $reglementFrs->type_reglement }}</h5>
        </div>

    </div>

    <div class="row" style="text-align: center">

        <div class="col-4">
            <small>Référence document</small>
            <br>
            <h5 class="badge text-bg-warning">{{ $reglementFrs->reference }}</h5>
        </div>

        <div class="col-8">
            <small>Nature du comptede paiement</small>
            <br>
            <h5 class="badge text-bg-warning">{{ $reglementFrs->nature_compte_paiement }}</h5>

        </div>



    </div>

    <hr>
    <div class="row" style="text-align: center">
        <h5 style="text-decoration: underline">Détails de la facture et commande</h5>

        <div class="col-6">
            <small>Référence Facture</small>
            <br>
            <h5 class="badge text-bg-success">{{ $factureFrs->ref_facture }}</h5>
        </div>
        <div class="col-6">
            <small>Référence Commande</small>
            <br>
            <h5 class="badge text-bg-success">{{ $cmd->reference }}</h5>
        </div>
    </div>


    <div class="row" style="text-align: center">

        <div class="col-4 flex-row justify-content-center">
            <small class="text-dark" style="text-decoration: underline" >Montants HT</small>
            <h5 class="text-danger" style="font-weight: bolder">{{ number_format($factureFrs->montant_facture, 2, ',', ' ') }}</h5>

        </div>

        <div class="col-4">
            <small class="text-dark" style="text-decoration: underline">Montants TTC</small>
            <h5 class="text-danger" style="font-weight: bolder">{{ number_format($factureFrs->montant_total, 2, ',', ' ') }}</h5>
             </div>

        <div class="col-4">
            <small class="text-dark" style="text-decoration: underline">Montants Régler</small>
            <h5 class="text-success" style="font-weight: bolder">{{ number_format($factureFrs->montant_total, 2, ',', ' ') }} </h5>
         </div>
    </div>



    <div class="row">
        <div class="col-lg-12">
            <small class="card-title">Liste des articles de la facture </small>
            <table id="example" class="table table-bordered border-warning  table-hover table-warning table-sm">

                <thead>
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


