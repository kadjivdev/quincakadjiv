@extends('layout.template')
@section('content')
    <main id="main" class="main">
        <style>
            .ui-datepicker-disabled {
                opacity: 0.5;
            }
        </style>
       <div class="pagetitle d-flex">
        <div class="col-6">
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Tableau de Bord</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('fournisseurs.index') }}">Fournisseur</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('reglements.index') }}">Règlement</a></li>
                    <li class="breadcrumb-item active">Nouveau Règlement</li>
                </ol>
            </nav>
        </div>
        <div class="col-6 d-flex flex-row justify-content-end">
            <div class="">
                <a href="{{ route('reglements.index') }}" class="btn btn-dark float-end petit_bouton"> <i class="bi bi-arrow-return-left"></i> Retour</a>
            </div>
        </div>
    </div><!-- End Page +++ -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        
                        <div class="card-body pt-1">
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
                            <h5 class="card-title text-dark ">Ajouter un règlement</h5>

                            <!-- Vertical Form -->
                            <form class="row g-3 shadow shadow-sm p-2" action="{{ route('reglements.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="col-6 mb-3">
                                    <label class="form-label">Fournisseur</label>
                                    <select class="js-data-example-ajax form-control" name="fournisseur_id"
                                        id="fournisseur_select"></select>
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Factures</label>
                                    <select class="form-control" name="facture_fournisseur_id" id="facture_select"></select>
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Type de règlement</label>
                                    <select name="type_reglement" class="form-control" id="type_reglement">
                                        <option value="Espèce ">En espèce </option>
                                        <option value="Chèque">Chèque</option>
                                        <option value="Virement">Virement</option>
                                        <option value="Décharge">Décharge</option>
                                        <option value="Autres">Autres</option>
                                    </select>
                                </div>

                                <div class="col-6 mb-3">
                                    <label for="dateReglement" class="form-label">Date de règlement</label>
                                    <input type="text" value="{{ old('date_reglement') }}" class="form-control"
                                        name="date_reglement" id="dateReglement">
                                </div>

                                <div class="col-6 mb-3">
                                    <label for="">Montant du règlement</label>
                                    <input type="number" step="0.01" min="1" class="form-control"
                                        value="{{ old('montant_regle') }}" name="montant_regle" id="montant_regle">
                                    <input type="hidden" step="0.01" min="1" class="form-control"
                                        value="{{ old('montant_restant') }}" id="montant_restant" name="montant_restant">
                                </div>

                                <div class="col-6 mb-3">
                                    <label for="">Référence règlement</label>
                                    <input type="text" class="form-control" name="reference"
                                        value="{{ old('reference') }}">
                                </div>

                                <div class="col-12 mb-3 d-none" id="preuveBloc">
                                    <label for="">Preuve de décharge</label>
                                    <input type="file" class="form-control" name="preuve_decharge"
                                        value="{{ old('preuve_decharge') }}">
                                </div>

                                <div class="col-12 mb-3" id="">
                                    <label for="">Nature du compte de paiement</label>
                                    <textarea id="nature_compte_paiement" name="nature_compte_paiement" class="form-control"></textarea>
                                </div>
                                <div class="col-lg-12 d-flex flex-row align-items-center justify-content-between">
                                    <button type="submit" class="btn btn-sm btn-dark text_orange w-100 submitBtn"><i class="bi bi-check-circle"></i> Enregistrer</button>
                                    <button type="button" class="btn btn-sm btn-dark text_orange w-100 loadingBtn" hidden><span class="spinner-border spinner-border-sm text_orange loading"></span> En cours ...</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script>
            $('#type_reglement').change(function() {
                var typeR = $(this).val();
                if (typeR == 'Décharge') {
                    $('#preuveBloc').removeClass('d-none');
                } else {
                    $('#preuveBloc').addClass('d-none');
                }
            });
        </script>
        <script>
            var apiUrl = "{{ config('app.url_ajax') }}";
            $('#fournisseur_select').change(function() {
                var frsId = $(this).val();
                $.ajax({
                    url: apiUrl + '/facturesFrs/' + frsId,
                    type: 'GET',
                    success: function(data) {

                        console.log(data.factures);

                        var options = '<option value="">Choisir la facture</option>';
                        $.each(Object.values(data.factures), function(index, facture) {
                            var reste = parseFloat(facture.montant_total) - parseFloat(facture.montant_regle_valide);
                            options += '<option value="' + facture.id + '">' + facture
                                .ref_facture + ' (' + reste + ' FCFA )' + '</option>';
                        });

                        $('#facture_select').html(options);


                    },
                    error: function(error) {
                        console.log('Erreur de la requête Ajax :', error);
                    }
                });
            });

            $('#facture_select').change(function() {
                var choix = $(this).val();
                $.ajax({
                    url: apiUrl + '/restantFacturesFrs/' + choix,
                    type: 'GET',
                    success: function(data) {

                        console.log(data);

                        // Afficher le montant dans le champ
                        $('#montant_restant').val(data.restant);
                        $('#montant_regle').val(data.restant);

                    },
                    error: function(error) {
                        console.log('Erreur de la requête Ajax :', error);
                    }
                });
            });


            $(document).ready(function() {
                // Initialize Select2 for the provided_articles dropdown
                $('.js-data-example-ajax').select2({
                    placeholder: 'Selectionner fournisseur',
                    ajax: {
                        url: apiUrl + '/frsListAjax',
                        dataType: 'json',
                        data: function(params) {
                            console.log(params);
                            return {
                                term: params.term // search term
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data.fournisseurs.map(function(frs) {
                                    return {
                                        id: frs.id,
                                        text: frs.name
                                    };
                                })
                            };
                        }
                    }
                });
            });
        </script>
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
                        return [date];
                    },
                    dateFormat: 'dd-mm-yy' // Format de la date
                });
            });
        </script>

        <script>
            $(document).ready(function() {
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
