@extends('layout.template')
@section('content')
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Livraisons clients</h1>
    </div><!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body py-2">
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
                        <h5 class="card-title text-dark">Enregistrer une livraison client</h5>
                        <form class="row g-3" action="{{ route('deliveries.store') }}" method="POST">
                            @csrf
                            <div class="col-6">
                                <label class="form-label" for="client_id">Client</label>
                                {{-- <input type="text" name="client_nom" readonly id="clientNom" class="form-control" required> --}}
                                <select class="form-select" name="client_id" id="client_id" required>
                                    <option value="">Choisir le client </option>
                                    @foreach ($clients as $client)
                                    <option value="{{ $client->id }}"> {{ $client->nom_client }} </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-6">
                                <label class="form-label">Choisir le devis</label>
                                <select class="form-select" name="devis_id" id="factureSelect" required>
                                    <option value="">Choisir la facture </option>
                                    {{-- @foreach ($devis as $item)
                                            <option value="{{ $item->id }}"> {{ $item->reference }} </option>
                                    @endforeach --}}
                                </select>
                            </div>

                            <div class="col-6">
                                <label class="form-label">Choisir le chauffeur</label>
                                <select class="js-data-example-ajax form-control" name="chauffeur_id"
                                    id="chauf_select" required></select>
                            </div>

                            <div class="col-6">
                                <label class="form-label">Choisir le véhicule</label>
                                <select class="form-control" name="vehicule_id" id="vehicule_id" required>
                                    <option value=""></option>
                                    @foreach ($vehicules as $vehicule)
                                    <option value="{{$vehicule->id}}">{{$vehicule->num_vehicule}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-6">
                                <label class="form-label">Adresse livraison</label>
                                <input type="text" name="adr_livraison" required class="form-control">
                            </div>

                            <div class="col-6">
                                <label class="form-label">Magasin destockage</label>
                                <select name="magasin_id" id="" class="form-select">
                                    <option value="">Choisir le magasin </option>
                                    @foreach ($magasins as $magasin)
                                    <option value="{{ $magasin->id }}">{{ $magasin->nom }}</option>
                                    @endforeach
                                </select>

                            </div>

                            <div id="dynamic-fields-container">
                                <table id="editableTable" class="table table-responsive">
                                    <thead>
                                        <tr>
                                            <th width="50%">Article</th>
                                            <th width="15%">Quantité</th>
                                            {{-- <th width="15%">Prix unit</th> --}}
                                            <th width="15%">Unité mesure</th>
                                            <th width="5%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
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
</main>

<script src="{{ asset('assets/js/jquery3.6.min.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<script>
    var apiUrl = "{{ config('app.url_ajax') }}";
    $(document).ready(function() {
        // Initialize Select2 for the provided_articles dropdown
        $('.js-data-example-ajax').select2({
            placeholder: 'Selectionner chauffeur',
            ajax: {
                url: apiUrl + '/chaufListAjax',
                dataType: 'json',
                data: function(params) {
                    console.log(params);
                    return {
                        term: params.term // search term
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.chauffeurs.map(function(chauf) {
                            return {
                                id: chauf.id,
                                text: chauf.nom_chauf
                            };
                        })
                    };
                }
            }
        });
    });
</script>
<script>
    var apiUrl = "{{ config('app.url_ajax') }}";

    $('#factureSelect').change(function() {
        var devisId = $(this).val();

        $.ajax({
            url: apiUrl + '/lignesDevis/' + devisId,
            type: 'GET',
            success: function(data) {
                console.log('Réponse de la requête AJAX:', data.articles);

                $('#editableTable tbody').empty();

                if (data.articles.length > 0) {
                    // Mettez à jour le champ #clientNom avec la valeur du premier article
                    // $("#clientNom").val(data.articles[0].nom_client);

                    // Créez la première ligne avec les noms de champs comme attributs data
                    // const firstRow = `
                    //         <tr>
                    //             <td data-name="article">${data.articles[0].nom}
                    //                 <input type="hidden" name="articles[]" readonly value="${data.articles[0].article_id}" class="form-control">

                    //                 </td>
                    //             <td data-name="qte_cmd" contenteditable="true">
                    //                 <input type="number" name="qte_cdes[]" max="${data.articles[0].qte_cmde}" value="${data.articles[0].qte_cmde}" class="form-control">
                    //                 </td>
                    //             // <td data-name="prix_unit" contenteditable="true">
                    //             //     <input type="text" name="prixUnits[]" readonly value="${data.articles[0].prix_unit}" class="form-control">
                    //             //     </td>
                    //             <td data-name="unite" contenteditable="false">${data.articles[0].unite}
                    //                 <input type="hidden" name="unites[]" readonly value="${data.articles[0].unite_mesure_id}" class="form-control">
                    //             </td>
                    //             <td><button class="btn btn-danger btn-sm delete-row"><i class="bi bi-trash"></i></button></td>
                    //         </tr>`;

                    const firstRow = `
                                <tr>
                                    <td data-name="article">${data.articles[0].nom}
                                        <input type="hidden" name="articles[]" readonly value="${data.articles[0].article_id}" class="form-control">

                                        </td>
                                    <td data-name="qte_cmd" contenteditable="true">
                                        <input type="number" name="qte_cdes[]" max="${data.articles[0].qte_cmde}" value="${data.articles[0].qte_cmde}" class="form-control">
                                        </td>
                                    <td data-name="unite" contenteditable="false">${data.articles[0].unite}
                                        <input type="hidden" name="unites[]" readonly value="${data.articles[0].unite_mesure_id}" class="form-control">
                                    </td>
                                    <td><button class="btn bg-dark text_orange btn-sm delete-row"><i class="bi bi-trash"></i></button></td>
                                </tr>`;

                    $('#editableTable tbody').append(firstRow);

                    // Ensuite, ajoutez les lignes pour les autres articles
                    for (let i = 1; i < data.articles.length; i++) {
                        const newRow = `
                        <tr>
                            <td data-name="article">${data.articles[i].nom}
                                <input type="hidden" name="articles[]" readonly value="${data.articles[i].article_id}" class="form-control">
                            </td>
                            <td data-name="qte_cmde" contenteditable="true">
                                <input type="number" name="qte_cdes[]" max="${data.articles[i].qte_cmde}" value="${data.articles[i].qte_cmde}" class="form-control">
                            </td>
                            <td data-name="unite" contenteditable="false"> ${data.articles[i].unite}
                                <input type="hidden" name="unites[]" readonly value="${data.articles[i].unite_mesure_id}" class="form-control">
                            </td>
                            <td>
                                <button class="btn bg-dark text_orange btn-sm delete-row">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>`;

                        $('#editableTable tbody').append(newRow);
                    }

                }
                $('.delete-row').click(function() {
                    $(this).closest('tr').remove();
                });
            },
            error: function(error) {
                console.log('Erreur de la requête AJAX:', error);
            }
        });
    });

    $('#client_id').change(function() {
        var client_id = $(this).val();

        $.ajax({
            url: '/devis_client/' + client_id,
            type: 'GET',
            success: function(data) {
                const select = document.getElementById('factureSelect');
                select.options.length = 1 // Pour ne garder que la première option 

                if (data.length > 0) {
                    data.forEach(devis => {
                        let option = document.createElement('option');
                        option.value = devis.id;
                        option.textContent = devis.reference;

                        select.appendChild(option);
                    })
                }
            },
            error: function(error) {
                console.log('Erreur de la requête AJAX:', error);
            }
        });
    });

    // Déclencher l'événement de changement initial avec la valeur par défaut
    var defaultdevisId = $('#factureSelect').val();
    console.log('ID du devis initial:', defaultdevisId);
    // $('#factureSelect').trigger('change'); // Déclencher l'événement de changement initial
</script>

<script>
    $('#vehicule_id').select2({
        width: 'resolve'
    });

    $('#factureSelect').select2({
        width: 'resolve'
    });

    $('#client_id').select2({
        width: 'resolve'
    });
</script>

@endsection