@extends('layout.template')
@section('content')
    <main id="main" class="main">

    <div class="pagetitle d-flex">
        <div class="col-6">
            <h1 class="float-left">Bons de Commandes </h1>
        </div>
        <div class="col-6 justify-content-end">

            <div class="">
            <a href="{{ route('commandes.index') }}" class="btn btn-sm bg-dark text_orange float-end mx-2"> <i
                        class="bi bi-arrow-left"></i> Retour</a>

            </div>

        </div>
    </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body py-3">
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
                            @if (count($lignes) > 0)
                                <h5 class="card-title text-dark">Modifier un bon de commande</h5>
                                <form class="row g-3" action="{{ route('commandes.update', $item->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="col-4">
                                        <label class="form-label">Commande</label>
                                        <input type="text" name="commande_id" readonly value="{{ $item->reference }}"
                                            id="clientNom" class="form-control">

                                    </div>

                                    <div class="col-3">
                                        <label class="form-label">Fournisseur</label>
                                        <input type="text" name="fournisseur_id" readonly
                                            value="{{ $item->fournisseur->name }} "  class="form-control">
                                    </div>

                                    <div class="col-3">
                                        <label class="form-label">Type de facture</label>
                                        <select class="form-select js-example-basic-single" name="type_id" id="typeSelect">
                                            <option value="">Choisir le type </option>

                                            @foreach ($types as $type)
                                                <option value="{{ $type->id }}"> {{ $type->libelle }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-2">
                                        <label class="form-label">Date de bon</label>
                                        <input type="text" class="form-control" value="{{ $item->date_cmd }} "
                                            name="date_cmd" id="dateReglement">

                                    </div>

                                    <div id="dynamic-fields-container">
                                        <table id="editableTable" class="table table-responsive">
                                            <thead>
                                                <tr>
                                                    <th>Article</th>
                                                    <th>Quantité</th>
                                                    <th>Prix unit</th>
                                                    <th>Unité mesure</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($lignes as $ligne)
                                                    <tr>
                                                        <td data-name="article"> {{ $ligne->nom }}
                                                            <input type="hidden" name="article[]" readonly
                                                                value="{{ $ligne->article_id }}" class="form-control">

                                                        </td>
                                                        <td data-name="qte_cmd" contenteditable="true">
                                                            <input type="text" name="qte_cmde[]"
                                                                value="{{ $ligne->qte_cmde }}" class="form-control">
                                                        </td>
                                                        <td data-name="prix_unit" contenteditable="true">
                                                            <input type="text" name="prix_unit[]"
                                                                value="{{ $ligne->prix_unit }}" class="form-control">
                                                        </td>
                                                        <td data-name="unite" contenteditable="false"> {{ $ligne->unite }}
                                                            <input type="hidden" name="unite[]" readonly
                                                                value="{{ $ligne->unite_mesure_id }}" class="form-control">
                                                        </td>
                                                        <td><button class="btn btn-dark text_orange btn-sm delete-row"><i
                                                                    class="bi bi-trash"></i></button></td>

                                                    </tr>
                                                @endforeach

                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="2">Total HT</td>
                                                    <td colspan="3" class="bg-secondary"><input type="text" id="totalInput" value="{{old('montant_facture')}}"
                                                            class="form-control " name="montant_facture" readonly>
                                                    </td>
                                                </tr>
                                                <tr id="rowRem">
                                                    <td colspan="2">Taux remise(%)</td>
                                                    <td colspan="3" class="bg-secondary"><input type="text" id="tauxRemise" value="{{ $item->facture->taux_remise }}"
                                                            class="form-control " name="taux_remise">
                                                    </td>
                                                </tr>
                                                <tr id="rowAib">
                                                    <td colspan="2">Taux AIB (%)</td>
                                                    <td colspan="3" class="bg-secondary"><input type="text" id="aib" value="{{ $item->facture->aib }}"
                                                            class="form-control" name="aib">
                                                        <input type="text" id="montant_aib" class="form-control"
                                                            readonly>
                                                    </td>
                                                </tr>
                                                <tr id="rowTva">
                                                    <td colspan="2">TVA(%)</td>
                                                    <td colspan="3" class="bg-secondary"><input type="text" id="tva" value="{{ $item->facture->tva }}"
                                                            class="form-control" name="tva">
                                                        <input type="text" id="montant_tva" class="form-control"
                                                            readonly>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">Net à payer</td>
                                                    <td colspan="3" class="bg-secondary"><input type="text" id="totalNet" value="{{old('montant_total')}}"
                                                            class="form-control" name="montant_total" readonly>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td colspan="2">Acompte</td>
                                                    <td colspan="3" class="bg-secondary"><input type="number" min="0" value="{{old('montant_regle')}}"
                                                            id="montant_regle" class="form-control" name="montant_regle">
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>

                                    <div class="col-lg-12 d-flex flex-row align-items-center justify-content-between">
                                        <button type="submit" class="btn btn-sm btn-dark text_orange w-100 submitBtn"><i class="bi bi-check-circle"></i> Enregistrer</button>
                                        <button type="button" class="btn btn-sm btn-dark text_orange w-100 loadingBtn" hidden><span class="spinner-border spinner-border-sm text_orange loading"></span> En cours ...</button>
                                    </div>
                                </form>
                            @else
                                <div class="alert alert-success py-3">
                                    Aucun devis non facturé disponible.
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <script>
        $(".js-example-basic-multiple").select2();

        $(document).ready(function() {
            // Initialiser le datepicker
            $("#dateReglement").datepicker({
                beforeShowDay: function(date) {
                    var currentDate = new Date();
                    currentDate.setHours(0, 0, 0, 0);
                    return [date <= currentDate];
                },
                dateFormat: 'dd-mm-yy' // Format de la date
            });
        });
    </script>
    <script>
        $(document).ready(function() {

        $('#tauxRemise').val(0);
            $('#aib').val(0);
            $('#tva').val(0);
            calculateAndRefreshTotals();

            $('#typeSelect').change(function() {


var selectedOption = $(this).val(); // Obtient la valeur sélectionnée

if(selectedOption == 2) {
    $('#rowRem').hide();
    $('#rowAib').hide();
    $('#rowTva').hide();
}

if(selectedOption == 1) {
    $('#rowRem').show();
    $('#rowAib').show();
    $('#rowTva').show();
}


});

            // Fonction pour calculer le montant et mettre à jour les totaux
            function calculateAndRefreshTotals() {
                var totalMontant = 0;

                // Parcours de chaque ligne du tableau
                $('#editableTable tbody tr').each(function() {
                    const prixUnit = parseFloat($(this).find('input[name^="prix_unit"]').val()) || 0;
                    const qteCmde = parseFloat($(this).find('input[name^="qte_cmde"]').val()) || 0;
                    const montant = prixUnit * qteCmde;

                    $(this).find('input[name^="montant_facture"]').val(montant.toFixed(2));

                    // Ajout du montant au total
                    totalMontant += montant;
                });

                // Mettre à jour les totaux dans le pied de page
                var tauxRemise = parseFloat($('#tauxRemise').val()) || 0;
                var tauxAIB = parseFloat($('#aib').val()) || 0;
                var tauxTVA = parseFloat($('#tva').val()) || 0;

                var totalAvecRemise = totalMontant * (1 - tauxRemise / 100);
                var totalAIB = totalAvecRemise * (tauxAIB / 100);
                var totalTVA = totalAvecRemise * (tauxTVA / 100);
                var totalNet = totalAvecRemise * (1 + (tauxAIB / 100) + (tauxTVA / 100));

                // Mettre à jour les champs dans le pied de page
                $('#totalNet').val(totalNet.toFixed(2));
                $('#montant_tva').val(totalTVA.toFixed(2));
                $('#montant_aib').val(totalAIB.toFixed(2));
                $('#totalInput').val(totalMontant.toFixed(2));
            }

            $('#editableTable tbody').on('input', 'input[name^="qte_cmde"], input[name^="prix_unit"]', function() {
                calculateAndRefreshTotals();
            });

            $('#editableTable tfoot').on('input', '#tauxRemise, #aib, #tva', function() {
                calculateAndRefreshTotals();

            });

            // Au chargement de la page, effectuez le calcul initial
            calculateAndRefreshTotals();
        });
    </script>


@endsection
