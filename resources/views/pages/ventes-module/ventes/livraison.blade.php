@extends('layout.template')
@section('content')
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Livraisons ventes au comptant</h1>
    </div><!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body py-3">
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
                        @if (count($bons) > 0)
                        <h5 class="card-title text-dark">Livraison de vente au comptant </h5>
                        <form class="row g-3" action="{{ route('bons-ventes.store') }}" method="POST">
                            @csrf

                            <div class="col-12">
                                <label class="form-label">Choisir le client</label>
                                <select class="form-select" name="client_id" id="client-select">
                                    <option value="">Choisir le client </option>

                                    @foreach ($clientsAvecBons as $item)
                                    <option value="{{ $item->id }}"> {{ $item->nom_client }} </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-4">
                                <label class="form-label">Choisir le bon de vente</label>
                                <select class="form-select" name="bon_vente_id" id="bonSelect">
                                    <option value="">Choisir le bon </option>

                                    @foreach ($bons as $item)
                                    <option value="{{ $item->id }}"> {{ $item->code_bon }} </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-4">
                                <label class="form-label">Client</label>
                                {{-- <input type="hidden" name="magasin_id" readonly value="{{ $magasin->id }}"
                                class="form-control"> --}}
                                <input type="text" name="client_nom" readonly id="clientNom"
                                    class="form-control">
                            </div>

                            <div class="col-4">
                                <label class="form-label">Choisir le magasin</label>
                                <select class="form-select" name="magasin_id" id="magasinSelect">
                                    <option value="">Choisir le magasin </option>

                                    @foreach ($magasins as $magasin)
                                    <option value="{{ $magasin->id }}"> {{ $magasin->nom }} </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-4">
                                <label class="form-label">Choisir le chauffeur</label>
                                <select class="form-control" name="chauffeur_id"
                                    id="chauf_select" required>
                                    @foreach ($chauffeurs as $chauffeur)
                                    <option value="{{$chauffeur->id}}">{{$chauffeur->nom_chauf}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-4">
                                <label class="form-label">Choisir le véhicule</label>
                                <select class="form-control" name="vehicule_id" id="vehicule_id" required>
                                    <option value=""></option>
                                    @foreach ($vehicules as $vehicule)
                                    <option value="{{$vehicule->id}}">{{$vehicule->num_vehicule}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-4">
                                <label class="form-label">Adresse livraison</label>
                                <input type="text" name="adr_livraison" required class="form-control">
                            </div>

                            <div id="dynamic-fields-container">
                                <table id="editableTable" class="table table-responsive">
                                    <thead>
                                        <tr>
                                            <th>Article</th>
                                            <th>Qté vendue</th>
                                            <th>Qté restante</th>
                                            <th>Qté livrée</th>
                                            <th>Unité mesure</th>
                                            <th>Action</th>
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
                        @else
                        <div class="alert alert-success py-3">
                            Aucun bon de vente non livré.
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

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<script>
    var apiUrl = "{{ config('app.url_ajax') }}";

    $('#bonSelect').change(function() {
        var bonVenteId = $(this).val();
        console.log(bonVenteId);
        $.ajax({
            url: apiUrl + '/lignesBonventes/' + bonVenteId,
            type: 'GET',
            success: function(data) {
                $('#editableTable tbody').empty();
                console.log(data.articles[0]);

                if (data.articles.length > 0) {
                    console.log(data.articles[0]);
                    $("#clientNom").val(data.articles[0].nom_client);
                    // $("#seuil").val(data.articles[0].seuil);

                    const firstRow = `
                                    <tr>
                                        <td data-name="article"> ${data.articles[0].nom}
                                            <input type="hidden" name="article[]" readonly value="${data.articles[0].article_id}" class="form-control">
                                            <input type="hidden" name="vente_lignes[]" readonly value="${data.articles[0].id}" class="form-control">
                                        </td>
                                        <td data-name="qte_cmd" contenteditable="true">
                                            <input type="text" name="qte_cmde[]" value="${data.articles[0].qte_cmde}" readonly class="form-control">
                                        </td>
                                        <td data-name="qte_cmd" contenteditable="true">
                                            <input type="text" name="qte_cmde[]" value="${data.articles[0].qte_livre}" readonly class="form-control">
                                        </td>
                                        <td data-name="prix_unit" contenteditable="true">
                                            <input type="number" name="qte_livre[]" max="${data.articles[0].qte_livre}" min="0" class="form-control">
                                            </td>
                                        <td data-name="unite" contenteditable="false"> ${data.articles[0].unite}
                                            <input type="hidden" name="unite[]" readonly value="${data.articles[0].unite_mesure_id}" class="form-control">
                                        </td>
                                        <td><button class="btn bg-dark text_orange btn-sm delete-row"><i class="bi bi-trash"></i></button></td>
                                    </tr>`;

                    $('#editableTable tbody').append(firstRow);

                    for (let i = 1; i < data.articles.length; i++) {
                        const newRow = `
                                        <tr>
                                            <td data-name="article">${data.articles[i].nom}
                                                <input type="hidden" name="article[]" readonly value="${data.articles[i].article_id}" class="form-control">
                                                <input type="hidden" name="vente_lignes[]" readonly value="${data.articles[i].id}" class="form-control">
                                            </td>
                                            <td data-name="qte_cmd" contenteditable="true">
                                                <input type="text" name="qte_cmde[]" value="${data.articles[i].qte_cmde}" readonly class="form-control">
                                            </td>
                                            <td data-name="qte_cmd" contenteditable="true">
                                                <input type="text" name="qte_cmde[]" value="${data.articles[i].qte_livre}" readonly class="form-control">
                                            </td>
                                            <td data-name="prix_unit" contenteditable="true">
                                                <input type="number" name="qte_livre[]" max="${data.articles[i].qte_livre}" min="0" class="form-control">
                                            </td>
                                            <td data-name="unite" contenteditable="false"> ${data.articles[i].unite}
                                                <input type="hidden" name="unite[]" readonly value="${data.articles[i].unite_mesure_id}" class="form-control">
                                            </td>
                                            <td><button class="btn bg-dark text_orange btn-sm delete-row"><i class="bi bi-trash"></i></button></td>
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

    var defaultDevisId = $('#bonSelect').val();
    // console.log('ID du devis initial:', defaultDevisId);
    $('#bonSelect').trigger('change');

    $('#bonSelect').select2({
        width: 'resolve'
    });

    $('#magasinSelect').select2({
        width: 'resolve'
    });

    $('#chauf_select').select2({
        width: 'resolve'
    });

    $('#client-select').select2({
        width: 'resolve'
    });
</script>

<script>
    $(document).on('change', '#client-select', function() {
        let clientId = $(this).val();
        let bonSelect = $('#bonSelect');

        bonSelect.empty(); // Vide le select
        bonSelect.append('<option value="">Choisir le bon</option>'); // Ajoute l'option par défaut

        if (clientId) {
            $.ajax({
                url: apiUrl + `/bons-par-client/${clientId}`,
                method: 'GET',
                success: function(data) {
                    if (data.length > 0) {
                        data.forEach(bon => {
                            bonSelect.append(`<option value="${bon.id}">${bon.code_bon}</option>`);
                        });
                    } else {
                        bonSelect.append('<option value="">Aucun bon disponible</option>');
                    }
                },
                error: function(err) {
                    console.error('Erreur lors du chargement des bons:', err);
                    bonSelect.append('<option value="">Erreur de chargement</option>');
                }
            });
        }
    });
</script>

@endsection