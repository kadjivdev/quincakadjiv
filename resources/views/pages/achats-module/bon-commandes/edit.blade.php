@extends('layout.template')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle d-flex">
        <div class="col-6">
            <h1 class="float-left">Programmation d'achat </h1>
        </div>
        <div class="col-6 justify-content-end">

            <div class="">
            <a href="{{ route('bon-commandes.index') }}" class="btn btn-success float-end mx-2"> <i
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
                            <h5 class="card-title">Modifier une programmation</h5>
                            <!-- Vertical Form -->
                            <form class="row g-3" id="programForm" action="{{ route('bon-commandes.update', $bon->id) }}"
                                method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-3 mb-3">
                                        <label class="form-label">Date de programmation</label>
                                        <input type="text" class="form-control" name="date_bon_cmd" value="{{ $bon->date_bon_cmd }}" id="dateReglement">

                                    </div>
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

                                <div class="col-2">
                                    <label class="form-label">Quantité</label>
                                    <input type="text" name="qte" id="qte" class="form-control">
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

                                <div id="dynamic-fields-container">
                                    <table id="editableTable" class="table table-responsive">
                                        <thead>
                                            <tr>
                                                <th>Article</th>
                                                <th>Quantité</th>
                                                <th>Unité mesure</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ $ligne->nom }}<input type="hidden" required name="articles[]" value="{{ $ligne->article_id }}"></td>
                                                <td> <input type="number" required name="qte_cdes[]" class="form-control" value="{{ $ligne->qte_cmde }}"> </td>
                                                <td>{{ $ligne->unite }} <input type="hidden" required name="unites[]" value="{{ $ligne->unite_mesure_id }}"> </td>
                                                <td><button type="button" class="btn btn-danger btn-sm delete-row">Supprimer</button></td>
                                            </tr>`
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Modifier </button>
                                    <div class="loader"></div>

                                    <button type="reset" class="btn btn-secondary">Annuler</button>
                                </div>
                            </form>
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
            });

            $('#enregistrerVente').click(function() {
                $('#programForm').submit();
            });

            $('#editableTable').on('click', '.delete-row', function() {
                $(this).closest('tr').remove();
            });
        });
    </script>

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
