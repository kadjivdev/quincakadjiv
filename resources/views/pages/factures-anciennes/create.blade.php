@extends('layout.template')
@section('content')
    <main id="main" class="main">
        <style>
            .ui-datepicker-disabled {
                opacity: 0.5;
            }
        </style>
        <div class="pagetitle">
            <h1>Report à nouveau </h1>
        </div><!-- End Page Title -->
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body py-1">
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
                            <h5 class="card-title text-dark">Enregistrer une ancienne facture client</h5>

                            <!-- Vertical Form -->
                            <form class="row g-3" action="{{ route('factures-anciennes.store') }}" method="POST">
                                @csrf
                                <div class="col-6 mb-3">
                                    <label class="form-label">Client</label>
                                    <select class="js-data-example-ajax form-control" name="client_id"
                                        id="client_select"></select>
                                </div>

                                <div class="col-6 mb-3">
                                    <label class="form-label">Type de facture</label>
                                    <select class="form-select js-data-example-ajax" name="type_id" id="typeSelect">
                                        <option value="">Choisir le type </option>
                                        @foreach ($types as $type)
                                            <option value="{{ $type->id }}"> {{ $type->libelle }} </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-6 mb-3">
                                    <label for="dateReglement" class="form-label">Date de facture</label>
                                    <input type="text" value="{{ old('date_facture') }}" class="form-control"
                                        name="date_facture" id="dateReglement">
                                </div>

                                <div class="col-6 mb-3">
                                    <label for="">Montant non remboursé</label>
                                    <input type="text" class="form-control" value="{{ old('montant_total') }}"
                                        name="montant_total">
                                </div>

                                <div class="col-lg-12 d-flex flex-row align-items-center justify-content-between">
                                    <button type="submit" class="btn btn-sm btn-dark text_orange w-100 submitBtn" id="ajouterArticle"><i class="bi bi-check-circle"></i> Enregistrer</button>
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
            var apiUrl = "{{ config('app.url_ajax') }}";
            $(document).ready(function() {
                $('.js-data-example-ajax').select2({
                    placeholder: 'Selectionner client',
                    ajax: {
                        url: apiUrl + '/allClients',
                        dataType: 'json',
                        data: function(params) {
                            console.log(params);
                            return {
                                term: params.term
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data.clients.map(function(frs) {
                                    return {
                                        id: frs.id,
                                        text: frs.nom_client
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
