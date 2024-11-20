@extends('layout.template')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Livraisons clients</h1>
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
                            <h5 class="card-title">Enregistrer une Livraison client</h5>
                            <form class="row g-3" action="{{ route('deliveries.store') }}" method="POST">
                                @csrf

                                <div class="col-6">
                                    <label class="form-label">Choisir le devis</label>
                                    <select class="form-select" name="devis_id" id="commandeSelect">
                                        <option value="">Choisir le devis </option>

                                        @foreach ($devis as $item)
                                            <option value="{{ $item->id }}"> {{ $item->reference }} </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-6">
                                    <label class="form-label">Client</label>
                                    <input type="text" name="client_nom" readonly id="" class="form-control">
                                </div>

                                <div class="col-6">
                                    <label class="form-label">Date de livraison</label>
                                    <input type="date" name="date_livraison" id="date_livraison" onchange="updateDate()"
                                        class="form-control">
                                </div>

                                <div id="dynamic-fields-container">
                                    <!-- Dynamic fields will be added here -->
                                </div>
                                <div class="row col-2">
                                    <button type="button" id="add-input" class="btn btn-xs btn-primary">+ Article
                                    </button>
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

    <script src="{{ asset('assets/js/jquery3.6.min.js') }}"></script>

    <script>
        // Fonction pour mettre à jour la date par défaut au chargement de la page
        function updateDate() {
            var currentDate = new Date();
            var day = currentDate.getDate();
            var month = currentDate.getMonth() + 1; // Les mois commencent à 0, donc ajout de 1
            var year = currentDate.getFullYear();

            // Formatage de la date au format YYYY-MM-DD (format attendu par le champ de type date)
            var formattedDate = year + '-' + (month < 10 ? '0' : '') + month + '-' + (day < 10 ? '0' : '') + day;

            // Mettre à jour la valeur du champ de date
            document.getElementById('date_livraison').value = formattedDate;
        }

        // Appeler la fonction au chargement de la page
        window.onload = updateDate;
    </script>
    <script>
        $(document).ready(function() {
            let dynamicRowCounter = 0;

            function createDynamicRow(selectValues1) {
                dynamicRowCounter++;
                return `
                <div class="row dynamic-row" data-row-id="${dynamicRowCounter}">
                    <div class="col-3">
                        <label class="form-label">Articles</label>
                        <select name="articles[]" required class="articles form-select">
                            <option value="">Choisir l'article livré </option>
                            ${Array.isArray(selectValues1) ? selectValues1.map(item => {
                                return `<option value="${item.id}">${item.nom}</option>`;
                            }).join('') : ''}
                        </select>
                    </div>

                    <div class="col-3">
                        <label class="form-label">Quantité</label>
                        <input type="number" min="1" required class="form-control qtecmd" name="qte_cdes[]">
                    </div>

                    <div class="col-3">
                        <label class="form-label">Prix unitaire</label>
                        <input type="number" min="1" readonly required class="form-control prixUnits" name="prixUnits[]">
                    </div>

                    <div class="col-2">
                        <label class="form-label">Unités</label>
                        <input type="text" required readonly class="form-control unites" name="unites[]">
                    </div>

                    <div class="col-1 mt-4 py-2">
                        <button type="button" class="btn btn-danger remove-field" data-row-id="${dynamicRowCounter}">-</button>
                    </div>
                </div>
            `;
            }

            function fetchDataForFirstSelect(selectedValue1) {
                return $.ajax({
                    url: '/lignesDevis/' + selectedValue1,
                    type: 'GET',
                });
            }

            function updateDynamicSelectOptions(row, options) {
                var dynamicSelect = row.find('.articles');
                dynamicSelect.empty();
                $.each(options, function(index, option) {
                    dynamicSelect.append('<option value="' + option.id + '">' + option.nom + '</option>');
                });

                dynamicSelect.change(function() {
                    var selectedCommande = $('#commandeSelect').val();

                    var selectedArticle = $(this).val();
                    // Effectuer une requête AJAX pour obtenir les informations associées à l'article
                    $.ajax({
                        url: '/lignesDevis/' + selectedCommande,
                        type: 'GET',
                        success: function(response) {
                            console.log(response, 'la reponse');
                            // Filtrer la réponse pour obtenir les détails de l'article sélectionné
                            var selectedArticleDetails = response.articles.find(function(
                                article) {
                                return article.id == selectedArticle;
                            });
                            console.log(selectedArticleDetails);

                            // Vérifier si l'article a été trouvé
                            if (selectedArticleDetails) {
                                // Mettre à jour les champs de prix unitaire et quantité dans la même ligne
                                row.find('.qtecmd').val(selectedArticleDetails.qte_cmde);
                                row.find('.prixUnits').val(selectedArticleDetails.prix_unit);
                                row.find('.unites').val(selectedArticleDetails.unite);

                                var maxQuantity = selectedArticleDetails.qte_cmde;
                                row.find('.qtecmd').attr('max', maxQuantity);
                                console.log(maxQuantity, 'max qte');
                            } else {
                                console.error('Article non trouvé dans la réponse.');
                            }
                        },
                        error: function() {
                            console.error(
                                'Erreur lors de la récupération des informations de l\'article.'
                            );
                        }
                    });
                });
            }

            function fetchDataAndAppendRow() {
                var selectedValue1 = $('#commandeSelect').val();

                $.when(fetchDataForFirstSelect(selectedValue1))
                    .done(function(data1) {
                        var newRow = $(createDynamicRow(data1.articles));
                        // Vérifier si le nombre actuel de lignes est inférieur au nombre total d'options
                        var currentRows = $('.dynamic-row').length;
                        var totalOptions = data1.articles.length;
                        console.log(data1.articles);
                        if (currentRows < totalOptions) {
                            $('#dynamic-fields-container').append(newRow);
                            updateDynamicSelectOptions(newRow, data1.articles);
                            // Attribuer automatiquement les valeurs des autres champs
                            var selectedArticleId = newRow.find('.articles').val();
                            var selectedArticle = data1.articles.find(article => article.id ==
                                selectedArticleId);

                            if (selectedArticle) {
                                newRow.find('.qtecmd').val(selectedArticle.qte_cmde);
                                newRow.find('.prixUnits').val(selectedArticle.prix_unit);
                                newRow.find('.unites').val(selectedArticle.unite);

                                var maxQuantity = selectedArticle.qte_cmde;
                                console.log(maxQuantity, 'max qte');

                                newRow.find('.qtecmd').attr('max', selectedArticle.qte_cmde);
                            }
                        } else {
                            console.log('Limite atteinte, impossible d\'ajouter plus de lignes.');
                        }
                    })
                    .fail(function() {
                        console.error('Échec de la récupération des valeurs du sélecteur.');
                    });
            }

            // Ajouter une ligne dynamique lorsqu'on clique sur le bouton "Ajouter champs"
            $(document).on('click', '#add-input', function() {
                console.log("Add button clicked!");
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
@endsection
