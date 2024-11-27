@extends('layout.template')
@section('content')
    <main id="main" class="main">
        <style>
            .ui-datepicker-disabled {
                opacity: 0.5;
            }
        </style>
        <div class="pagetitle">
            <h1 class="text-dark">Reglements</h1>
        </div><!-- End Page Title -->
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
                            <h5 class="card-title text-dark">Détail règlement</h5>

                            <!-- Vertical Form -->
                            <form class="row g-3" action="{{ route('reglements-clt.update', $reglement->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="col-6 mb-3">
                                    <label class="form-label">Client</label>
                                    <input type="hidden" id="clientId" value="{{ $client->id }}">
                                    <input type="text" class="form-control" readonly name="clientId" value="{{ $client->nom_client }}" />
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Facture</label>
                                    <input type="hidden" id="factId" value="{{ $reglement->facture_id }}">
                                    <input type="text" class="form-control" readonly name="facture_ref" value="{{ $facture->num_facture }}" />
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Type de règlement</label>
                                    <select name="type_reglement" class="form-control js-example-basic-multiple" id="type_reglement">
                                        <option value="Espèce"
                                            {{ $reglement->type_reglement == 'Espèce' ? 'selected' : '' }}>En espèce
                                        </option>
                                        <option value="Chèque"
                                            {{ $reglement->type_reglement == 'Chèque' ? 'selected' : '' }}>Chèque</option>
                                        <option value="Virement"
                                            {{ $reglement->type_reglement == 'Virement' ? 'selected' : '' }}>Virement
                                        </option>
                                        <option value="Décharge"
                                            {{ $reglement->type_reglement == 'Décharge' ? 'selected' : '' }}>Décharge
                                        </option>
                                        <option value="Autres"
                                            {{ $reglement->type_reglement == 'Autres' ? 'selected' : '' }}>Autres</option>
                                    </select>
                                </div>

                                <div class="col-6 mb-3">
                                    <label for="dateReglement" class="form-label">Date de règlement</label>
                                    <input type="text" class="form-control" name="date_reglement"
                                        value="{{ $reglement->date_reglement }}" id="datseReglement" readonly>
                                </div>

                                <div class="col-6 mb-3">
                                    <label for="">Montant du règlement</label>
                                    <input type="number" min="1" class="form-control" value="{{ $reglement->montant_regle }}"
                                        name="montant_regle" readonly>
                                </div>

                                <div class="col-6 mb-3">
                                    <label for="">Référence règlement</label>
                                    <input type="text" class="form-control" value="{{ $reglement->reference }}"
                                        name="reference" readonly>
                                </div>

                                <div class="col-6 mb-3 d-none" id="preuveBloc">
                                    <label for="">Preuve de décharge</label>
                                    <input type="file" class="form-control" value="{{ $reglement->preuve_decharge }}"
                                        name="preuve_decharge">
                                </div>
                                {{-- 
                                    <div class="col-lg-12 d-flex flex-row align-items-center justify-content-between">
                                        <button type="submit" class="btn btn-sm btn-dark text_orange w-100 submitBtn"><i class="bi bi-check-circle"></i> Enregistrer</button>
                                        <button type="button" class="btn btn-sm btn-dark text_orange w-100 loadingBtn" hidden><span class="spinner-border spinner-border-sm text_orange loading"></span> En cours ...</button>
                                    </div>
                                </div> --}}
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
