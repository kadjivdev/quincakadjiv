@extends('layout.template')
@section('content')
<main id="main" class="main">

    <div class="pagetitle d-flex">
        <div class="col-6">
            <h1 class="float-left">Programmation d'achat </h1>
        </div>
        <div class="col-6 justify-content-end">

            <div class="">
                <a href="{{ route('bon-commandes.index') }}" class="btn btn-sm btn-dark text_orange float-end mx-2"> <i
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
                    <div class="card-body pt-1">
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <h5 class="card-title text-dark">Ajouter une programmation</h5>

                        <form class="row g-3" id="programForm" action="{{ route('bon-commandes.store') }}"
                            method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label class="form-label">Date de programmation</label>
                                    <input type="text" class="form-control" required name="date_bon_cmd" id="dateReglement">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Choisir l'article</label>
                                    <select class="form-select form-control test" name="article_id" id="articleSelect">
                                        <option value="">Choisir l'article </option>
                                        @foreach ($articles as $article)
                                        <option value="{{ $article->id }}"> {{ $article->nom }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <label class="form-label">Quantité</label>
                                    <input type="number" min="1" name="qte" id="qte" class="form-control">
                                </div>

                                <div class="col-6">
                                    <label class="form-label">Unité</label>
                                    <select class="form-select" name="unite_id" id="uniteSelect">
                                        <option value="">Choisir l'unité </option>
                                    </select>
                                </div>
                            </div>
                            <div id="dynamic-fields-container">
                                <table id="editableTable" class="table table-responsive table-striped">
                                    <thead class=""> 
                                        <tr>
                                            <th>Article</th>
                                            <th>Quantité</th>
                                            <th>Unité mesure</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-lg-12 d-flex flex-row align-items-center justify-content-between">
                                <button type="submit" class="btn btn-sm btn-dark text_orange w-100 submitBtn" id="ajouterArticle"><i class="bi bi-check-circle"></i> Enregistrer</button>
                                <button type="button" class="btn btn-sm btn-dark text_orange w-100 loadingBtn" hidden><span class="spinner-border spinner-border-sm text_orange loading"></span> En cours ...</button>
                            </div>
                        </form>
                        <br>
                    </div>
                </div>
            </div>
        </div>
</main>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

<script src="{{ asset('assets/js/jquery3.6.min.js') }}"></script>
<script src="{{ asset('assets/js/mindmup-editabletable.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(document).ready(function() {
        // Initialiser le datepicker
        $("#dateReglement").datepicker({
            // minDate: 0, // Empêcher la sélection des dates passées
            beforeShowDay: function(date) {
                // Désactiver les dates supérieures à aujourd'hui
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

    $(document).ready(function() {
        $('#articleSelect').select2({
            width: 'resolve'
        });


        $('#articleSelect').on('change', function() {
            var articleId = $(this).val();
            console.log(articleId, 'id article');
            if (articleId) {
                $.ajax({
                    url: apiUrl + '/getUnitesByArticle/' + articleId,
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

    });
</script>
<script>
    function toggleAddButton() {
        var articleId = $('#articleSelect').val();
        var uniteId = $('#uniteSelect').val();
        var qte = $('#qte').val();
        var isFieldsFilled = articleId && uniteId && qte.trim() !== '';

        $('#ajouterBtn').prop('disabled', !isFieldsFilled);
    }
    $('#qte').on('input', toggleAddButton);


    $('#articleSelect, #uniteSelect').on('change', toggleAddButton);

    $(document).ready(function() {
        $('#articleSelect').select2({
            width: 'resolve'
        });
        $('#editableTable').editableTableWidget();

        $('#ajouterArticle').click(function() {
            var articleId = $('#articleSelect').val();
            var articleNom = $('#articleSelect option:selected').text();
            var uniteId = $('#uniteSelect option:selected').val();
            var uniteNom = $('#uniteSelect option:selected').text();
            var quantite = $('#qte').val();

            if (articleId == "" || articleNom == "" || uniteId == "" || uniteNom == "" || quantite == "") {
                alert('Un ou plusieurs champs sont vides');
            } else {
                var newRow = `
                    <tr>
                        <td>${articleNom}<input type="hidden" required name="articles[]" value="${articleId}"></td>
                        <td>${quantite} <input type="hidden" required name="qte_cdes[]" value="${quantite}"></td>
                        <td>${uniteNom} <input type="hidden" required name="unites[]" value="${uniteId}"></td>
                        <td><button type="button" class="btn btn-danger btn-sm delete-row">Supprimer</button></td>
                    </tr>`;

                $('#editableTable tbody').append(newRow);

                // Effacer les champs après l'ajout
                $('#articleSelect').val(null).trigger('change');
                $('#uniteSelect').val('');
                $('#qte').val('');
            }

        });

        $('#enregistrerVente').click(function() {
            $('#programForm').submit();
        });

        $('#editableTable').on('click', '.delete-row', function() {
            $(this).closest('tr').remove();
        });

        toggleAddButton();

    });
</script>

{{-- <script>
        totalOptions = 0;
        var selectedArticles = [];

        $(document).ready(function() {

            var totalOptions = $('.articles option').length;

            // Fonction pour mettre à jour les options des sélecteurs dynamiques
            function updateDynamicSelectOptions() {
                // Reset des options désactivées pour toutes les lignes
                $('select.articles option').prop('disabled', false);

                // Désactiver les options sélectionnées dans toutes les lignes
                $('select.articles').each(function() {
                    var $select = $(this);
                    var selectedValue = $select.val();

                    if (selectedValue) {
                        // Désactiver l'option sélectionnée dans les autres lignes
                        $('select.articles').not($select).find(`option[value="${selectedValue}"]`).prop(
                            'disabled', true);
                    }
                });
            }

            // Function to create a new dynamic input row
            function createDynamicRow(selectValues1, selectValues2) {
                return `
                            <div class="row">
                                <div class="col-4">
                                    <label class="form-label">Articles</label>
                                    <select name="articles[]" required class="articles form-select">
                                    <option value="">Sélectionner un article</option>
                                        ${Array.isArray(selectValues1)
                                            ? selectValues1
                                            .filter(item => !selectedArticles.includes(item.id))
                                            .map(item => `<option value="${item.id}">${item.nom}</option>`)
                                            .join('')
                                        : ''}
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label class="form-label">Quantité</label>
                                    <input type="number" min="1" required class="form-control" name="qte_cdes[]">
                                </div>
                                <div class="col-3">
                                    <label class="form-label">Unités de mesure</label>
                                    <select name="unites[]" required class="unites form-control">
                                        <option value="">Sélectionner une unité de mesure</option>

                                        ${Array.isArray(selectValues2) ? selectValues2.map(item => `<option value="${item.id}">${item.unite}</option>`).join('') : ''}
                                    </select>
                                </div>
                                <div class="col-2 mt-4 py-2">
                                    <button type="button" class="btn btn-danger remove-field"><i class="bi bi-trash"></i></button>
                                </div>
                            </div>
                        `;
            }

            function fetchDataForFirstSelect() {
                return $.ajax({
                    url: '/articles-list',
                    type: 'GET',
                });
            }

            function fetchDataForSecondSelect() {
                return $.ajax({
                    url: '/unites-list', // Replace with your route for the second select field
                    type: 'GET',
                });
            }

            // Add initial dynamic row
            $.when(fetchDataForFirstSelect(), fetchDataForSecondSelect())
                .done(function(data1, data2) {
                    var newRow = createDynamicRow(data1[0].articles, data2[0].unites);
                    var $newRow = $(newRow).addClass('new-row'); // Ajoutez une classe à la nouvelle ligne
                    $('#dynamic-fields-container').append($newRow);
                    // $('#dynamic-fields-container').append(createDynamicRow(data1[0].articles, data2[0].unites));
                    totalOptions = $('.articles option').length;
                    updateNumberOfRows();
                })
                .fail(function() {
                    console.error('Failed to fetch select values.');
                });
            // Add dynamic input row when "Add Input" button is clicked
            $('#add-input').click(function() {
                var numberOfRows = $('.new-row').length;
                console.log(totalOptions, numberOfRows);
                if ((totalOptions - 1) > numberOfRows) {
                    $.when(fetchDataForFirstSelect(), fetchDataForSecondSelect())
                        .done(function(data1, data2) {
                            var newRow = createDynamicRow(data1[0].articles, data2[0].unites);
                            var $newRow = $(newRow).addClass(
                                'new-row'); // Ajoutez une classe à la nouvelle ligne
                            $('#dynamic-fields-container').append($newRow);

                            console.log(numberOfRows);
                            // Update dynamic select options after adding a new row
                            updateDynamicSelectOptions();
                            updateNumberOfRows();
                        })
                        .fail(function() {
                            console.error('Failed to fetch select values.');
                        });
                }

            });

            $('#dynamic-fields-container').on('click', '.remove-field', function() {
                $(this).closest('.row').remove();

                // Update dynamic select options after removing a row
                updateDynamicSelectOptions();
            });

            // Fonction pour mettre à jour numberOfRows
            function updateNumberOfRows() {
                var numberOfRows = $('.new-row').length;
                console.log(numberOfRows, 'nmbre de row');
            }
        });
    </script> --}}

</section>
<script>
    $(document).ready(function() {
        $('.articles').select2({
            width: 'resolve'
        });

        $("#connectBtn").on("click", function() {
            $(".myLoader").show();

            setTimeout(function() {
                $(".myLoader").hide();
            }, 2000);
        });
    });
</script>
</main>
@endsection