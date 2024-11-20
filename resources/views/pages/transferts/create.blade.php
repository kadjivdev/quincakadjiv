@extends('layout.template')
@section('content')

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Ventes au comptant</h1>
        </div><!-- End Page Title -->
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
                            <h5 class="card-title">Enregistrer une vente</h5>
                            <form class="row g-3" action="{{ route('ventes.store') }}" method="POST">
                                @csrf

                                <div class="col-4 mb-3">
                                    <label class="form-label">Magasin départ</label>
                                    <select class="form-select" name="magasin_id" id="typeSelect">
                                        <option value="">Choisir le magasin </option>
                                        @foreach ($magasins as $magasin)
                                            <option value="{{ $magasin->id }}"> {{ $magasin->nom }} </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-3 mb-3">
                                    <label class="form-label">Magasin de destination</label>
                                    <select class="form-select" name="type_vente_id" id="typeVenteSelect">
                                        <option value="">Choisir le magasin </option>
                                        @foreach ($magasins as $magasin)
                                            <option value="{{ $magasin->id }}"> {{ $magasin->nom }} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-3">
                                        <label class="form-label">Choisir l'article</label>
                                        <select class="form-select form-control test" name="article_id" id="articleSelect">
                                            <option value="">Choisir l'article </option>

                                        </select>
                                    </div>

                                    <div class="col-2">
                                        <label class="form-label">Quantité</label>
                                        <input type="number" name="qte" id="qte" class="form-control">
                                    </div>

                                    <div class="col-2">
                                        <label class="form-label">Prix unitaire</label>
                                        <input type="number" name="prix" id="prix" class="form-control">
                                    </div>
                                    <div class="col-2">
                                        <label class="form-label">Unité</label>
                                        <select class="form-select" name="unite_id" id="uniteSelect">
                                            <option value="">Choisir l'unité </option>

                                        </select>
                                    </div>

                                    <div class="col-2 py-2">

                                        <button class="btn btn-primary mt-4" type="button" id="ajouterArticle">
                                            Ajouter</button>
                                    </div>
                                </div>

                                <div id="dynamic-fields-container">
                                    <table id="editableTable" class="table table-responsive">
                                        <thead>
                                            <tr>
                                                <th>Article</th>
                                                <th>Quantité</th>
                                                <th>Prix unit</th>
                                                <th>Unité mesure</th>
                                                <th>Montant</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="2">Montant HT</td>
                                                <td colspan="3"><input type="text" id="totalInput"
                                                        class="form-control" name="montant_facture" readonly>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">Taux remise</td>
                                                <td colspan="3"><input type="text" id="tauxRemise"
                                                        class="form-control" name="taux_remise">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">Taux AIB (%)</td>
                                                <td colspan="3"><input type="text" id="aib"
                                                        value="{{ old('aib') }}" class="form-control" name="aib">
                                                    <input type="text" id="montant_aib" class="form-control" readonly>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">TVA(%)</td>
                                                <td colspan="3"><input type="number" id="tva" min="0"
                                                        max="18" value="{{ old('tva') }}" class="form-control"
                                                        name="tva">
                                                    <input type="text" id="montant_tva" class="form-control" readonly>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">Montant total</td>
                                                <td colspan="3"><input type="text" id="totalNet"
                                                        class="form-control" name="montant_total" readonly>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">Montant payer</td>
                                                <td colspan="3"><input type="number" id="montant_regle"
                                                        class="form-control" name="montant_regle" required>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                                    <button type="reset" class="btn btn-secondary">Annuler</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <script src="{{ asset('assets/js/jquery3.6.min.js') }}"></script>
    <script src="{{ asset('assets/js/mindmup-editabletable.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {

            $('#articleSelect').on('change', function() {
                var articleId = $(this).val();
                console.log(articleId, 'id article');
                if (articleId) {
                    $.ajax({
                        url: '/getUnitesByArticle/' + articleId,
                        type: 'GET',
                        success: function(data) {
                            console.log(data);
                            var options = '<option value="">Choisir l\'unité</option>';
                            for (var i = 0; i < data.unites.length; i++) {
                                options += '<option value="' + data.unites[i].id + '">' + data
                                    .unites[i].unite + '</option>';
                            }
                            $('#uniteSelect').html(options);
                        },
                        error: function(error) {
                            console.log('Erreur de la requête Ajax :', error);
                        }
                    });
                } else {
                    $('#uniteSelect').html('<option value="">Choisir l\'unité</option>');
                }
            });

            $('#editableTable').editableTableWidget();

            $('#uniteSelect').on('change', function() {
                convertirQuantite();
            });

            $('#typeVenteSelect').on('change', function() {
                var typeVente = $('#typeVenteSelect option:selected').attr('data-donnee');
                var prixMin;
                var prix = $('#prix').val();

                if (typeVente === 'BTP') {
                    prixMin = $('#articleSelect option:selected').attr('data-prixBtp');
                }
                $('#prix').attr('min', prixMin);

                console.log(prix, prixMin, 'les prix');
            });

            $('#ajouterArticle').click(function() {
                var quantiteConvertiePromise = convertirQuantite();
                quantiteConvertiePromise.done(function(data) {
                    var quantiteConvertie = parseFloat(data.qteConvertie);

                    var articleId = $('#articleSelect').val();
                    var articleNom = $('#articleSelect option:selected').text();
                    var uniteId = $('#uniteSelect option:selected').val();
                    var uniteNom = $('#uniteSelect option:selected').text();
                    var quantite = $('#qte').val();
                    var prix = $('#prix').val();
                    var total = prix * quantite;
                    var qteStock = $('#articleSelect option:selected').attr('data-qteDispo');

                    var typeVente = $('#typeVenteSelect option:selected').attr('data-donnee');
                    var prixMin;

                    if (typeVente === 'BTP') {
                        prixMin = $('#articleSelect option:selected').attr('data-prixBtp');
                    } else if (typeVente === 'Revendeur') {
                        prixMin = $('#articleSelect option:selected').attr('data-prixRevendeur');
                    } else if (typeVente === 'Particulier') {
                        prixMin = $('#articleSelect option:selected').attr('data-prixParticulier');
                    } else {
                        prixMin = $('#articleSelect option:selected').attr('data-prixVente');
                    }

                    $('#prix').attr('min', prixMin);

                    if (parseFloat(prix) < prixMin) {
                        alert('Le prix unitaire est inférieur au prix minimum (' + prixMin + ').');
                        return; // Bloquer l'ajout si le prix est inférieur au prix minimal
                    }

                    console.log(prixMin, 'prix minim', total);

                    if (quantiteConvertie > parseFloat(qteStock)) {
                        alert('La quantité disponible est insuffisante.');
                        return;
                    }

                    var newRow = `
                                    <tr>
                                        <td>${articleNom}<input type="hidden" required name="articles[]" value="${articleId}">
                                            </td>
                                        <td data-name="qte_cmd">${quantite} <input type="hidden" required name="qte_cdes[]" value="${quantite}"</td>
                                        <td data-name="prix_unit">${prix} <input type="hidden" required name="prixUnits[]" value="${prix}"</td>
                                        <td>${uniteNom} <input type="hidden" required name="unites[]" value="${uniteId}"</td>
                                        <td data-name="montant"  contenteditable="false">${total}
                                            <input type="hidden" name="montants[]" value="${total}" readonly class="form-control">
                                        </td>
                                        <td><button type="button" class="btn btn-danger btn-sm delete-row">Supprimer</button></td>
                                    </tr>`;

                    $('#editableTable tbody').append(newRow);
                    calculateTotal();

                    // Effacer les champs après l'ajout
                    $('#articleSelect').val(null).trigger('change');
                    $('#uniteSelect').val('');
                    $('#prix').val('');
                    $('#qte').val('');
                });

                quantiteConvertiePromise.fail(function(error) {
                    console.log('Erreur de la requête Ajax :', error);
                });
                $('#enregistrerVente').click(function() {
                    $('#venteForm').submit();
                });

                $('#editableTable').on('click', '.delete-row', function() {
                    $(this).closest('tr').remove();
                    calculateTotal();

                });

            });

            function convertirQuantite() {
                var articleId = $('#articleSelect').val();
                var uniteId = $('#uniteSelect').val();
                var quantite = $('#qte').val();

                if (articleId && uniteId && quantite) {
                    return $.ajax({
                        url: '/convertirUnite/' + articleId + '/' + uniteId + '/' + quantite,
                        type: 'GET',

                    });
                }
                return $.Deferred().reject().promise();

            }
        });
    </script>

@endsection
