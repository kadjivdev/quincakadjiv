@extends('layout.template')
@section('content')
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Inventaires</h1>
    </div><!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body my-1">
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
                        <h5 class="card-title text-dark">Ajouter un inventaire</h5>
                        <!-- Vertical Form -->
                        <form class="row g-3" id="programForm" action="{{ route('inventaires.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-4 mb-3">
                                    <label class="form-label">Date inventaire</label>
                                    <input type="text" class="form-control" name="date_inventaire" id="dateReglement">

                                </div>
                            </div>
                            <div class="col-3">
                                <label class="form-label">Choisir l'article</label>
                                <select class="form-select form-control test" name="article_id" id="articleSelect">
                                    <option value="">Choisir l'article </option>
                                    @foreach ($articles as $article)
                                    <option value="{{ $article->id }}" data-qteStock="{{ $article->qte_stock }}">
                                        {{ $article->nom }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-3">
                                <label class="form-label">Qté Stock</label>
                                <input type="hidden" readonly name="magasin_id" value="{{ $magasin->id}}" class="form-control">
                                <input type="number" readonly name="qte_stock" id="qte_stock" class="form-control">
                            </div>

                            <div class="col-3">
                                <label class="form-label">Qté réelle</label>
                                <input type="number" min="0" name="qte_reel" id="qte" class="form-control">
                            </div>

                            <div class="col-3 py-2">
                                <button class="btn btn-sm bg-dark text_orange mt-4" type="button" id="ajouterArticle">
                                    Ajouter</button>
                            </div>

                            <div id="dynamic-fields-container">
                                <table id="editableTable" class="table table-responsive">
                                    <thead>
                                        <tr>
                                            <th>Article</th>
                                            <th>Qté stock</th>
                                            <th>Qté réelle</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-lg-12 d-flex flex-row align-items-center justify-content-between">
                                <button type="submit" class="btn btn-sm btn-dark text_orange w-100 submitBtn" ><i class="bi bi-check-circle"></i> Enregistrer</button>
                                <button type="button" class="btn btn-sm btn-dark text_orange w-100 loadingBtn" hidden><span class="spinner-border spinner-border-sm text_orange loading"></span> En cours ...</button>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        $("#dateReglement").datepicker({
            beforeShowDay: function(date) {
                var currentDate = new Date();
                currentDate.setHours(0, 0, 0, 0);
                return [date <= currentDate];
            },
            dateFormat: 'dd-mm-yy'
        });
    });
</script>
<script>
    $(document).ready(function(e) {
        e.preventDefault();
        // Function to display SweetAlert confirmation before form submission
        function displayConfirmation() {
            Swal.fire({
                title: "Confirmez-vous ces valeurs de stocks ?",
                text: "Veuillez confirmer la valeur des stocks !",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Oui, enregistrer l'inventaire !"
            }).then((result) => {
                if (result.isConfirmed) {
                    // If user confirms, submit the form
                    $('#programForm').submit();
                }
            });
        }

        // Call displayConfirmation function when the submit button is clicked
        $('#enregistrerVente').click(function() {
            displayConfirmation();
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
            var qteStock = $('#articleSelect option:selected').attr('data-qteStock');

            $('#qte_stock').val(qteStock);
            console.log(qteStock, 'id article');
            // if (articleId) {
            //     $.ajax({
            //         url: apiUrl + '/getUnitesByArticle/' + articleId,
            //         type: 'GET',
            //         success: function(data) {
            //             console.log(data);
            //             var options = '<option value="">Choisir l\'unité</option>';
            //             for (var i = 0; i < data.unites.length; i++) {
            //                 options += '<option value="' + data.unites[i].id + '">' + data
            //                     .unites[i].unite + '</option>';
            //             }
            //             $('#uniteSelect').html(options);
            //         },
            //         error: function(error) {
            //             console.log('Erreur de la requête Ajax :', error);
            //         }
            //     });
            // } else {
            //     $('#uniteSelect').html('<option value="">Choisir l\'unité</option>');
            // }
        });

    });
</script>
<script>
    function toggleAddButton() {
        var articleId = $('#articleSelect').val();
        var qte = $('#qte').val();
        var isFieldsFilled = articleId && qte.trim() !== '';
        $('#ajouterBtn').prop('disabled', !isFieldsFilled);
    }

    $('#qte').on('input', toggleAddButton);
    $('#articleSelect').on('change', toggleAddButton);

    $(document).ready(function() {
        $('#editableTable').editableTableWidget();

        $('#ajouterArticle').click(function() {
            var articleId = $('#articleSelect').val();
            var articleNom = $('#articleSelect option:selected').text();
            // var uniteId = $('#uniteSelect option:selected').val();
            // var uniteNom = $('#uniteSelect option:selected').text();
            var qte_stock = $('#qte_stock').val();
            var qte_reel = $('#qte').val();

            var newRow = `
                    <tr>
                        <td>${articleNom}<input type="hidden" required name="articles[]" value="${articleId}"></td>
                        <td>${qte_stock} <input type="hidden" required name="qte_stock[]" value="${qte_stock}"></td>
                        <td>${qte_reel} <input type="hidden" required name="qte_reel[]" value="${qte_reel}"></td>
                        <td><button type="button" class="btn btn-danger btn-sm delete-row">Supprimer</button></td>
                    </tr>`;

            $('#editableTable tbody').append(newRow);
            $('#articleSelect').val(null).trigger('change');
            $('#qte').val('');
            $('#qte_stock').val('');
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