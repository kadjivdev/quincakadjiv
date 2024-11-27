@extends('layout.template')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Supplément de commande</h1>
        </div><!-- End Page Title -->
        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body mt-1">
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
                            <h5 class="card-title text-dark">Ajouter un supplément de commande</h5>
                            @if ($commande)
                                <!-- Vertical Form -->
                                <form class="row g-3" action="{{ route('supplement-store') }}" method="POST">
                                    @csrf

                                    <div class="col-12">
                                        <label class="form-label">{{ $commande->reference }} {{ Carbon\Carbon::parse($commande->date_cmd)->locale('fr_FR')->isoFormat('ll') }}</label>
                                        <input type="hidden" readonly name="commande_id" value="{{ $commande->id }}" class="form-control" id="commande_id">
                                    </div>

                                    <div id="dynamic-fields-container">
                                        <!-- Dynamic fields will be added here -->
                                    </div>

                                    <span>
                                        <button type="button" id="add-input" class="btn btn-sm btn-dark text_orange">+ Article
                                        </button>
                                    </span>

                                    <div class="col-lg-12 d-flex flex-row align-items-center justify-content-between">
                                        <button type="submit" class="btn btn-sm btn-dark text_orange w-100 submitBtn"><i class="bi bi-check-circle"></i> Enregistrer</button>
                                        <button type="button" class="btn btn-sm btn-dark text_orange w-100 loadingBtn" hidden><span class="spinner-border spinner-border-sm text_orange loading"></span> En cours ...</button>
                                    </div>
                                </form>
                            @else
                                <div class="row">
                                    <h4>Aucune commande valide actuellement.</h4>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="{{ asset('assets/js/jquery3.6.min.js') }}"></script>
    <script>
         var apiUrl = "{{ config('app.url_ajax') }}";

        $(document).ready(function() {
            let dynamicRowCounter = 0;
            // let totalOptions = 0;

            function createDynamicRow(selectValues1) {
                dynamicRowCounter++;
                return `
                    <div class="row dynamic-row" data-row-id="${dynamicRowCounter}">
                        <div class="col-3">
                            <label class="form-label">Articles</label>
                            <select name="articles[]" required class="articles form-select">
                                <option value="">Sélectionner un article</option>
                                ${Array.isArray(selectValues1) ? selectValues1.map(item => `<option value="${item.id}" data-quantity="${item.qte_cmde}" data-prix="${item.prix_unit}" data-unite="${item.unite}">${item.nom}</option>`).join('') : ''}
                            </select>
                        </div>

                        <div class="col-3">
                            <label class="form-label">Quantité</label>
                            <input type="number" min="1"  class="form-control qtecmd" name="qte_cdes[]">
                        </div>

                        <div class="col-3">
                            <label class="form-label">Prix unitaire</label>
                            <input type="number" min="1" readonly required class="form-control prixUnit" name="prixUnits[]">
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

            function fetchDataForFirstSelect(selectedValue1) {
                return $.ajax({
                    url: apiUrl + '/articlesCommande/' + selectedValue1,
                    type: 'GET',
                });
            }

            function updateRowFields(row, selectedOption) {
                var selectedQuantity = selectedOption.data('quantity');
                var selectedUnity = selectedOption.data('unite');
                var selectedPrix = selectedOption.data('prix');
                var rowQte = row;

                rowQte.find('.qtecmd').val(selectedQuantity);
                rowQte.find('.unite').val(selectedUnity);
                rowQte.find('.prixUnit').val(selectedPrix);
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
                            .qte_cmde + '" data-unite="' + option.unite + '" data-prix="' + option.prix_unit + '" >' + option.nom +
                            '</option>');
                        $('#add-input').prop('disabled', false);

                    } else {
                        $('#add-input').prop('disabled', true);

                    }
                });

                updateRowFields(row, dynamicSelect.find(':selected'));

                updateNumberOfRows();
            }

            function fetchDataAndAppendRow() {
                var selectedValue1 = $('#commande_id').val();
                var numberOfRows = updateNumberOfRows();
                $.when(fetchDataForFirstSelect(selectedValue1))
                    .done(function(data1) {
                        console.log(data1.articles);
                        var newRow = $(createDynamicRow(data1.articles));
                        if (data1.articles.length == 0) {
                            $('#add-input').prop('disabled', true);
                        } else {
                            if (data1.articles.length > numberOfRows) {
                                $('#add-input').prop('disabled', false);
                                $('#dynamic-fields-container').append(newRow);
                                updateDynamicSelectOptions(newRow, data1.articles);
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
    </script>
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
