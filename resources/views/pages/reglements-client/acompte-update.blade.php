@extends('layout.template')
@section('content')
<main id="main" class="main">
    <style>
        .ui-datepicker-disabled {
            opacity: 0.5;
        }
    </style>
    <div class="pagetitle">
        <h1>Accomptes clients</h1>
    </div><!-- End Page Title -->
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
                        <h5 class="card-title">Modifier un accompte client</h5>

                        <!-- Vertical Form -->
                        <form class="row g-3" action="{{ route('acompte-save-update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="col-6 mb-3">
                                <label class="form-label">Client</label>

                                <input type="text" readonly value="{{ $client->nom_client }}" class="form-control" name="client_name" id="client_name"> 
                                <input type="hidden" value="{{ $client->id }}" class="form-control" name="client_id" id="client_select">
                                <input type="hidden" value="{{ $accompte->id }}" class="form-control" name="accompte_id" id="accompte_id">
                            </div>

                            <div class="col-3">
                                <label class="form-label">Date</label>
                                <input type="date" value="{{$accompte->date_op}}" required name="date_acc" id="date_acc" class="form-control" max="<?php echo date('Y-m-d'); ?>">
                            </div>

                            <div class="col-3 mb-3">
                                <label class="form-label">Type de règlement</label>
                                <select name="type_reglement" class="form-control" id="type_reglement">
                                    <option value="Espèce" {{ $accompte->type_reglement == 'Espèce' ? 'selected' : '' }}>En espèce</option>
                                    <option value="Chèque" {{ $accompte->type_reglement == 'Chèque' ? 'selected' : '' }}>Chèque</option>
                                    <option value="Virement" {{ $accompte->type_reglement == 'Virement' ? 'selected' : '' }}>Virement</option>
                                </select>                                
                            </div>



                            <div class="col-6 mb-3">
                                <label for="">Montant accompte</label>
                                <input type="number" value="{{$accompte->montant_acompte}}" min="1" class="form-control" value="{{ old('montant_regle') }}" name="montant_acompte">
                            </div>

                            <div class="col-6 mb-3">
                                <label for="">Référence règlement</label>
                                <input type="text" value="{{$accompte->reference}}" class="form-control" name="reference" value="{{ old('reference') }}">
                            </div>


                            <div class="col-6 mb-3" id="">
                                    <label for="">Observation accompte client</label>
                                    <textarea class='form-control' id="observation_acompte_client" name="observation_acompte_client" rows="5" >{{$accompte->observation_acompte_client}}</textarea>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                                <div class="loader"></div>

                                <button type="reset" class="btn btn-secondary">Annuler</button>
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
        $(document).ready(function() {
            var apiUrl = "{{ config('app.url_ajax') }}";

            // Initialize Select2 for the provided_articles dropdown
            $('.js-data-example-ajax').select2({
                placeholder: 'Selectionner client',
                ajax: {
                    url: apiUrl + '/allClients',
                    dataType: 'json',
                    data: function(params) {
                        console.log(params);
                        return {
                            term: params.term // search term
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
</main>
@endsection
