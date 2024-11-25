@extends('layout.template')
@section('content')

    <main id="main" class="main">

                        <div class="pagetitle d-flex">
        <div class="col-6">
            <h1 class="float-left">Bons de Commandes </h1>
        </div>
        <div class="col-6 justify-content-end">

            <div class="">
            <a href="{{ route('commandes.index') }}" class="btn btn-dark text_orange float-end mx-2"> <i
                        class="bi bi-arrow-left"></i> Retour</a>

            </div>
        </div>
    </div>

        <section class="section">

            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <!-- Afficher des messages de succès -->
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <!-- Afficher des erreurs de validation -->
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="card-body">
                            <h5 class="card-title text-dark">Ajouter un bon commande</h5>

                            @if (count($bons) > 0)
                                <!-- Vertical Form -->
                                <form class="row g-3" action="{{ route('commandes.store') }}" method="POST">
                                    @csrf

                                    <div class="col-4">
                                        <label class="form-label">Choisir une programmation</label>
                                        <select class="form-control" required name="bon_id" id="bonSelect">
                                            <option value="">Sélectionner une program... </option>

                                            @foreach ($bons as $bon)
                                                <option value="{{ $bon->id }}"> {{ $bon->reference }} </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-4">
                                        <label class="form-label" for="frsSelect">Choisir un fournisseur</label>
                                        <select class="js-example-basic-single form-control" required name="fournisseur_id" id="frsSelect" >
                                            @foreach ($fournisseurs as $fournisseur)
                                                <option value="{{ $fournisseur->id }}"> {{ $fournisseur->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-4">
                                        <label class="form-label">Type de facture</label>
                                        <select class="form-control" required name="type_id" id="typeSelect">
                                            @foreach ($types as $type)
                                                <option value="{{ $type->id }}"> {{ $type->libelle }} </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="row mt-4">

                                    <div class="col-4">
                                        <label class="form-label">Coût du Transport</label>
                                        <input type="number" step="0.0001" class="form-control" required name="transport" id="transport">

                                    </div>

                                    <div class="col-4">
                                        <label class="form-label">Coût du Chargement/Déchargement</label>
                                        <input type="number" step="0.0001" class="form-control" required name="charge_decharge" id="charge_decharge">

                                    </div>

                                    <div class="col-4">
                                        <label class="form-label">Autres Coût</label>
                                        <input type="number" step="0.0001" class="form-control" required name="autre" id="autre">
                                    </div>

                                    <div class="col-12 mb-2">
                                        <label class="form-label">Date de bon</label>
                                        <input type="text" class="form-control" required name="date_cmd" id="dateReglement">
                                    </div>

                                    <div id="dynamic-fields-container">
                                        <table id="editableTable" class="table table-responsive">
                                            <thead>
                                                <tr>
                                                    <th>Article</th>
                                                    <th>Quantité</th>
                                                    <th>Prix unitaire</th>
                                                    <th>Montant</th>
                                                    <th>Unité mesure</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="2">Total HT</td>
                                                    <td colspan="3" class="bg-secondary" style="font-weight:bolder;"><input type="text" style="font-weight:bolder;" id="totalInput"
                                                            class="form-control" name="montant_facture" readonly>
                                                    </td>
                                                </tr>
                                                <tr id="rowRem">
                                                    <td colspan="2">Taux remise(%)</td>
                                                    <td colspan="3" class="bg-secondary" style="font-weight:bolder;"><input style="font-weight:bolder;" type="text" id="tauxRemise" value="{{old('taux_remise')}}"
                                                            class="form-control" name="taux_remise">
                                                    </td>
                                                </tr>
                                                <tr id="rowAib">
                                                    <td colspan="2">Taux AIB (%)</td>
                                                    <td colspan="3" class="bg-secondary" style="font-weight:bolder;" ><input style="font-weight:bolder;"  type="text" id="aib" value="{{old('aib')}}"
                                                            class="form-control" name="aib">
                                                            <input type="text" id="montant_aib"
                                                            class="form-control" readonly >
                                                    </td>
                                                </tr>
                                                <tr id="rowTva">
                                                    <td colspan="2">TVA(%)</td>
                                                    <td colspan="3" class="bg-secondary" style="font-weight:bolder;"><input type="text" style="font-weight:bolder;" id="tva" value="{{old('tva')}}"
                                                            class="form-control" name="tva">
                                                            <input type="text" id="montant_tva"
                                                            class="form-control" readonly >
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">Net à payer</td>
                                                    <td colspan="3" class="bg-secondary" style="font-weight:bolder;"><input style="font-weight:bolder;"  type="text"  id="totalNet"
                                                            class="form-control" name="montant_total" readonly>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td colspan="2">Acompte</td>
                                                    <td colspan="3" class="bg-secondary" style="font-weight:bolder;"><input style="font-weight:bolder;"   type="number" min="0"
                                                            id="montant_regle" class="form-control" name="montant_regle">
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>

                                    <div class="col-lg-12 d-flex flex-row align-items-center justify-content-between">
                                        <button type="submit" class="btn btn-sm btn-dark text_orange w-100 submitBtn" id="ajouterArticle"><i class="bi bi-check-circle"></i> Enregistrer</button>
                                        <button type="button" class="btn btn-sm btn-dark text_orange w-100 loadingBtn" hidden><span class="spinner-border spinner-border-sm text_orange loading"></span> En cours ...</button>
                                    </div>
                                </form>
                            @else
                                <div class="row">
                                    <h4>Aucun bon de commande valide actuellement.</h4>
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
        $(".js-example-basic-single").select2();

        $(document).ready(function() {

            $('#typeSelect').change(function() {


        var selectedOption = $(this).val(); // Obtient la valeur sélectionnée

        if(selectedOption == 2) {
            $('#rowRem').hide();
            $('#rowAib').hide();
            $('#rowTva').hide();
            $("#tauxRemise").val(0);
                    $("#aib").val(0);
                    $("#tva").val(0);

        }

        if(selectedOption == 1) {
            $('#rowRem').show();
            $('#rowAib').show();
            $('#rowTva').show();
            $("#tauxRemise").val(0);
                    $("#aib").val(1);
                    $("#tva").val(18);
        }

        calculateTotal();
        });

            $("#btn_valid").hide();
            $("#tauxRemise").val(0);
            $("#aib").val(1);
            $("#tva").val(18);
            $("#montant_regle").val(0);

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
         var apiUrl = "{{ config('app.url_ajax') }}";

        $('#editableTable tbody').on('input', 'input[name^="qte_cdes"], input[name^="prixUnits"]', function() {
            calculateTotal();
        });
        $('#editableTable tfoot').on('input', '#tauxRemise, #aib, #tva', function() {
            calculateTotal();
        });

        function calculateAmount(row) {
            const prixUnit = parseFloat(row.find('[data-name="prix_unit"] input').val()) || 0;
            const qteCmde = parseFloat(row.find('[data-name="qte_cmd"] input').val()) || 0;
            const montant = prixUnit * qteCmde;
            row.find('[data-name="montant"] input').val(montant.toFixed(2));
            calculateTotal();
        }

        function calculateTotal() {
            var total = 0;

            $('#editableTable tbody tr').each(function() {
                const montant = parseFloat($(this).find('[data-name="montant"] input').val()) || 0;
                total += montant;
            });
            var tauxRemise = parseFloat($('#tauxRemise').val()) || 0;
            var tauxAIB = parseFloat($('#aib').val()) || 0;
            var tauxTVA = parseFloat($('#tva').val()) || 0;
            console.log('Taux de remise:', tauxRemise);
            var totalAvecRemise = total * (1 - tauxRemise / 100);
            var totalAIB = totalAvecRemise * (tauxAIB / 100);
            var totalTVA = totalAvecRemise * (tauxTVA / 100);
            var  totalNet = totalAvecRemise * (1+ (tauxAIB / 100) + (tauxTVA / 100));
            $('#totalNet').val(totalNet.toFixed(2));
            $('#montant_tva').val(totalTVA.toFixed(2));
            $('#montant_aib').val(totalAIB.toFixed(2));
            $('#totalInput').val(total.toFixed(2));
        }

        $('#bonSelect').change(function() {
            $("#btn_valid").hide();
            var bon = $(this).val();
            var frsId = $('#frsSelect').val();

            affichage(frsId, bon);
        });

        $('#frsSelect').change(function() {
            $("#btn_valid").hide();
            var frs = $(this).val();
            var bonId = $('#bonSelect').val();

            affichage(frs, bonId);

        });

        function affichage(value1, value2) {
            console.log(value1, value2);
            $.ajax({
                url: apiUrl + '/articles-frs/' + value1 + '/' + value2,
                type: 'GET',
                success: function(data) {

                    $('#editableTable tbody').empty();

                    if (data.articles.length > 0) {
                        $("#btn_valid").show();

                        const firstRow = `
                    <tr>
                        <td data-name="article">${data.articles[0].nom}
                            <input type="hidden" name="articles[]" readonly value="${data.articles[0].id}" class="form-control">

                            </td>
                        <td data-name="qte_cmd" contenteditable="false">
                            <input type="text" name="qte_cdes[]" min="1" max="${data.articles[0].qte_cmde}" readonly value="${data.articles[0].qte_cmde}" class="form-control">
                            </td>
                        <td data-name="prix_unit" contenteditable="true">
                            <input type="text" name="prixUnits[]" class="form-control">
                            </td>
                            <td data-name="montant" contenteditable="false">
                            <input type="text" name="montants[]" readonly class="form-control">
                            </td>
                        <td data-name="unite" contenteditable="false">
                            <input type="text" name="unites[]" readonly value="${data.articles[0].unite}" class="form-control">
                        </td>
                        <td><button class="btn bg-dark text_orange btn-sm delete-row"><i class="bi bi-trash"></i></button></td>
                    </tr>`;

                        $('#editableTable tbody').append(firstRow);

                        // Ensuite, ajoutez les lignes pour les autres articles
                        for (let i = 1; i < data.articles.length; i++) {
                            const newRow = `
                        <tr>
                            <td data-name="article">${data.articles[i].nom}
                                <input type="hidden" name="articles[]" readonly  value="${data.articles[i].id}" class="form-control">
                            </td>
                            <td data-name="qte_cmd" contenteditable="false">
                                <input type="text" name="qte_cdes[]" min="1" max="${data.articles[i].qte_cmde}" readonly reqquired value="${data.articles[i].qte_cmde}" class="form-control"></td>
                            <td data-name="prix_unit" contenteditable="true">
                                <input type="text" name="prixUnits[]" class="form-control">
                            </td>

                            <td data-name="montant" contenteditable="false">
                                <input type="text" name="montants[]" readonly class="form-control">
                            </td>
                            <td data-name="unite" contenteditable="false">
                            <input type="text" name="unites[]" readonly value="${data.articles[i].unite}" class="form-control">
                                </td>
                            <td><button class="btn btn-danger btn-sm delete-row"><i class="bi bi-trash"></i></button></td>
                        </tr>`;

                            $('#editableTable tbody').append(newRow);
                        }
                        calculateTotal();

                    }
                    $('.delete-row').click(function() {
                        $(this).closest('tr').remove();
                        calculateTotal();

                    });
                },
                error: function(error) {
                    console.log('Erreur de la requête AJAX:', error);
                }
            });
        }

        $('#editableTable tbody').on('input', '[data-name="prix_unit"] input, [data-name="qte_cmd"] input', function() {
            const row = $(this).closest('tr');
            calculateAmount(row);
        });

        $('[data-name="prix_unit"] input, [data-name="qte_cmd"] input').on('input', function() {
            const row = $(this).closest('tr');
            calculateAmount(row);
        });
        var defaultbonId = $('#bonSelect').val();
        var defaultfrsId = $('#frsSelect').val();
        console.log('ID du devis initial:', defaultbonId);
        $('#bonSelect').trigger('change');
        $('#frsSelect').trigger('change');
    </script>

    {{-- <script>
        $(document).ready(function() {
            let dynamicRowCounter = 0;
            // let totalOptions = 0;
            function createDynamicRow(selectValues1, selectValues2) {
                dynamicRowCounter++;
                return `
                    <div class="row dynamic-row" data-row-id="${dynamicRowCounter}">
                        <div class="col-3">
                            <label class="form-label">Articles</label>
                            <select name="articles[]" required class="articles form-select" >
                                <option value="">Sélectionner un article</option>
                                ${Array.isArray(selectValues1) ? selectValues1.map(item => `<option value="${item.id}" data-quantity="${item.qte_cmde}" data-unite="${item.unite}">${item.nom}</option>`).join('') : ''}
                            </select>
                        </div>

                        <div class="col-3">
                            <label class="form-label">Quantité</label>
                            <input type="number" min="1" readonly class="form-control qtecmd" name="qte_cdes[]">
                        </div>

                        <div class="col-3">
                            <label class="form-label">Prix unitaire</label>
                            <input type="number" min="1" required class="form-control" name="prixUnits[]">
                        </div>

                        <div class="col-2">
                            <label class="form-label">Unité</label>
                            <input type="text" min="1" readonly class="form-control unite" name="unites[]">
                        </div>

                        <div class="col-1 mt-4 py-2">
                            <button type="button" class="btn btn-danger remove-field" data-row-id="${dynamicRowCounter}">-</button>
                        </div>
                    </div>
                `;
            }

            function updateNumberOfRows() {
                var numberOfRows = $('.dynamic-row').length;
                console.log('Nombre de lignes déjà ajoutées :', numberOfRows);
                return numberOfRows;
            }

            function fetchTotalOptions() {
                // Récupérer le nombre total d'options dans le select "Articles"
                totalOptions = $('.articles option').length;
                console.log('Nombre total d\'options dans le select "Articles" :', totalOptions);
            }

            // Appeler la fonction pour récupérer le nombre total d'options au chargement de la page
            fetchTotalOptions();

            function fetchDataForFirstSelect(selectedValue1, selectedValue2) {
                return $.ajax({
                    url: '/articles-frs/' + selectedValue1 + '/' + selectedValue2,
                    type: 'GET',
                });
            }


            function fetchDataForSecondSelect() {
                return $.ajax({
                    url: '/unites-list',
                    type: 'GET',
                });
            }

            function updateRowFields(row, selectedOption) {
                var selectedQuantity = selectedOption.data('quantity');
                var selectedUnity = selectedOption.data('unite');
                var rowQte = row;
                console.log(row, 'le row actuel');
                rowQte.find('.qtecmd').val(selectedQuantity);
                rowQte.find('.unite').val(selectedUnity);
            }

            function updateDynamicSelectOptions(row, options) {
                var dynamicSelect = row.find('.articles');
                dynamicSelect.empty();
                var allOptionsSelected = true;


                $.each(options, function(index, option) {
                    // Vérifier si l'option est déjà sélectionnée dans une autre ligne
                    var isOptionSelected = $('.dynamic-row select.articles').filter(function() {
                        return $(this).val() == option.id;
                    }).length > 0;

                    // Ajouter l'option seulement si elle n'est pas déjà sélectionnée
                    if (!isOptionSelected) {
                        dynamicSelect.append('<option value="' + option.id + '" data-quantity="' + option
                            .qte_cmde + '" data-unite="' + option.unite + '">' + option.nom +
                            '</option>');
                        $('#add-input').prop('disabled', false);

                    } else {
                        $('#add-input').prop('disabled', true);

                    }
                });

                dynamicSelect.on('change', function() {
                    var selectedQuantity = $(this).find(':selected').data('quantity');
                    var selectedUnity = $(this).find(':selected').data('unite');
                    var rowQte = row; // Utiliser la même référence "row" que dans la fonction

                    updateRowFields(row, $(this).find(':selected'));

                });
                updateRowFields(row, dynamicSelect.find(':selected'));

                updateNumberOfRows();
            }

            function fetchDataAndAppendRow() {
                var selectedValue1 = $('#frsSelect').val();
                var selectedValue2 = $('#bonSelect').val();
                var numberOfRows = updateNumberOfRows();
                $.when(fetchDataForFirstSelect(selectedValue1, selectedValue2), fetchDataForSecondSelect())
                    .done(function(data1, data2) {
                        console.log(data1[0].articles);
                        var newRow = $(createDynamicRow(data1[0].articles, data2[0].unites));
                        if (data1[0].articles.length == 0) {
                            $('#add-input').prop('disabled', true);
                        } else {
                            if (data1[0].articles.length > numberOfRows) {
                                $('#add-input').prop('disabled', false);
                                $('#dynamic-fields-container').append(newRow);
                                updateDynamicSelectOptions(newRow, data1[0].articles);
                                updateRowFields(newRow, newRow.find('.articles :selected'));
                            }
                        }
                    })
                    .fail(function() {
                        console.error('Échec de la récupération des valeurs du sélecteur.');
                    })
            }

            // Ajouter une ligne dynamique lorsqu'on clique sur le bouton "Ajouter champs"
            $(document).on('click', '#add-input', function() {
                fetchDataAndAppendRow();


            });

            // Supprimer une ligne dynamique lorsqu'on clique sur le bouton "Supprimer"
            $(document).on('click', '.remove-field', function() {
                const rowId = $(this).data('row-id');
                console.log(`Remove button clicked for row ${rowId}`);
                $(`.dynamic-row[data-row-id="${rowId}"]`).remove();
            });
        });
    </script> --}}
    <script>
        $(document).ready(function() {
            $("#connectBtn").on("click", function() {
                // Afficher le loader
                $(".myLoader").show();

                // Effectuer ici vos opérations asynchrones ou autres

                // Simuler une opération asynchrone (remplacez cela par votre logique réelle)
                setTimeout(function() {
                    // Cacher le loader une fois l'opération terminée
                    $(".myLoader").hide();
                }, 2000); // 2000 millisecondes (2 secondes) dans cet exemple
            });
        });
    </script>
@endsection
