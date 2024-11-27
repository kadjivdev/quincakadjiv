@extends('layout.template')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Factures clients</h1>
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
                                <h5 class="card-title text-dark">Détail facture client</h5>
                                <form class="row g-3" action="{{ route('factures.update', $facture->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="col-3">
                                        <label class="form-label">Choisir le devis</label>
                                        <input type="text" value="{{$facture->reference}}" readonly class="form-control">
                                        <input type="hidden" value="{{$facture->devis_id}}" name="devis_id" id="devisSelect">
                                    </div>

                                    <div class="col-4">
                                        <label class="form-label">Client</label>
                                        <input type="hidden" name="client_id" required id="client_id" class="form-control">
                                        <input type="hidden" name="seuil" readonly id="seuil" class="form-control">
                                        <input type="text" name="client_nom" required readonly id="clientNom"
                                            class="form-control">
                                    </div>

                                    <div class="col-3">
                                            <label class="form-label">Date</label>
                                            <input type="date" required name="date_fact" value="{{ \Carbon\Carbon::parse($facture->date_facture)->format('Y-m-d') }}" id="data_fact" class="form-control">
                                        </div>

                                    <div class="col-2">
                                        <label class="form-label">Type de facture</label>
                                        <select class="form-select" required name="type_id" id="typeSelect">
                                            <option value="">Choisir le type </option>

                                            @foreach ($types as $type)
                                                <option value="{{ $type->id }}" {{ $facture->facture_type_id == $type->id ? 'selected' : '' }}> {{ $type->libelle }} </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div id="dynamic-fields-container">
                                        <table id="editableTable" class="table table-responsive">
                                            <thead>
                                                <tr>
                                                    <th>Article</th>
                                                    <th>Quantité</th>
                                                    <th>Prix unit</th>
                                                    <th>Unité mesure</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="2">Montant HT</td>
                                                    <td colspan="3" class="bg-secondary"><input type="text" id="totalInput"
                                                            class="form-control" name="montant_facture" readonly>
                                                    </td>
                                                </tr>
                                                <tr id="rowRem">
                                                    <td colspan="2">Taux remise</td>
                                                    <td colspan="3" class="bg-secondary" ><input required type="text" id="tauxRemise"
                                                            class="form-control" name="taux_remise" readonly>
                                                    </td>
                                                </tr>
                                                <tr id="rowAib">
                                                    <td colspan="2">Taux AIB (%)</td>
                                                    <td colspan="3" class="bg-secondary" ><input type="text" id="aib"
                                                            value="{{ old('aib') }}" required class="form-control" name="aib">
                                                        <input type="text" id="montant_aib" class="form-control"
                                                            readonly>
                                                    </td>
                                                </tr>
                                                <tr id="rowTva">
                                                    <td colspan="2">TVA(%)</td>
                                                    <td colspan="3" class="bg-secondary" style="background-color: rgba(150, 150, 150, 0.89);"><input type="number" id="tva" min="0"
                                                            max="18" value="{{ old('tva') }}" required class="form-control"
                                                            name="tva">
                                                        <input type="text" id="montant_tva" class="form-control"
                                                            readonly>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">Montant total</td>
                                                    <td colspan="3" class="bg-secondary" style="background-color: rgba(233, 138, 10, 0.89);"><input type="text" id="totalNet"
                                                            class="form-control" name="montant_total" readonly>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">Acompte</td>
                                                    <td colspan="3" class="bg-secondary" style="background-color: rgba(32, 214, 4, 0.89);"><input type="number" id="montant_regle"
                                                            class="form-control" name="montant_regle" value="{{$facture->montant_regle}}" readonly>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </form>
                                <div>
                                    @if (is_null($facture->validate_at))
                                        <form action="{{ route('validate_facture', $facture->id) }}"
                                            method="POST" class="col-12">
                                            @csrf
                                            @method('POST')
                                            <div class="col-lg-12 d-flex flex-row align-items-center justify-content-between">
                                                <button type="submit" class="btn btn-sm btn-dark text_orange w-100 submitBtn" onclick="return confirm('Voulez vous vraiment valider cette facture? Cette opération est irréversible')"><i class="bi bi-check-circle"></i> Valider la facture</button>
                                                <button type="button" class="btn btn-sm btn-dark text_orange w-100 loadingBtn" hidden><span class="spinner-border spinner-border-sm text_orange loading"></span> En cours ...</button>
                                            </div>
                                            <!-- <button type="submit" class="btn btn-sm bg-dark text_orange" onclick="return confirm('Voulez vous vraiment valider cette facture? Cette opération est irréversible')">Valider la Facture</button> -->
                                        </form>
                                    @endif
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>


    <script>
        var devisId = $("#devisSelect");
            $.ajax({
                url: apiUrl + '/lignesDevis/' + devisId,
                type: 'GET',
                success: function(data) {
                    $('#editableTable tbody').empty();

                    if (data.articles.length > 0) {
                        console.log(data.articles[0]);
                        $("#clientNom").val(data.articles[0].nom_client);
                        $("#client_id").val(data.articles[0].id);
                        $("#seuil").val(data.articles[0].seuil);

                        const firstRow = `
                                    <tr>
                                        <td data-name="article"> ${data.articles[0].nom}
                                            <input type="hidden" name="article[]" readonly value="${data.articles[0].article_id}" class="form-control">

                                            </td>
                                        <td data-name="qte_cmd" contenteditable="true">
                                            <input type="text" name="qte_cmde[]" value="${data.articles[0].qte_cmde}" class="form-control">
                                            </td>
                                        <td data-name="prix_unit" contenteditable="true">
                                            <input type="text" name="prix_unit[]" value="${data.articles[0].prix_unit}" class="form-control">
                                            </td>
                                        <td data-name="unite" contenteditable="false"> ${data.articles[0].unite}
                                            <input type="hidden" name="unite[]" readonly value="${data.articles[0].unite_mesure_id}" class="form-control">
                                        </td>
                                    </tr>`;

                        $('#editableTable tbody').append(firstRow);

                        for (let i = 1; i < data.articles.length; i++) {
                            const newRow = `
                                        <tr>
                                            <td data-name="article">${data.articles[i].nom}
                                            <input type="hidden" name="article[]" readonly value="${data.articles[i].article_id}" class="form-control">

                                                </td>
                                            <td data-name="qte_cmde" contenteditable="true">
                                                <input type="text" name="qte_cmde[]" value="${data.articles[i].qte_cmde}" class="form-control"></td>
                                            <td data-name="prix_unit" contenteditable="true">
                                            <input type="text" name="prix_unit[]" value="${data.articles[i].prix_unit}" class="form-control">
                                                </td>
                                            <td data-name="unite" contenteditable="false"> ${data.articles[i].unite}
                                            <input type="hidden" name="unite[]" readonly value="${data.articles[i].unite_mesure_id}" class="form-control">
                                                </td>
                                        </tr>`;

                            $('#editableTable tbody').append(newRow);
                        }

                        $("#tva").val(18);
                        $("#aib").val(1);

                        calculateTotal();
                    }
                    $('.delete-row').click(function() {
                        $(this).closest('tr').remove();
                        calculateTotal();
                    });
                },
                error: function(error) {
                    console.log('Erreur de la requête AJAX:', error);
                }
            });
    </script>

    <script src="{{ asset('assets/js/jquery3.6.min.js') }}"></script>
    <script>
        var apiUrl = "{{ config('app.url_ajax') }}";

        $('#editableTable tbody').on('input', 'input[name^="qte_cmde"],  input[name^="prix_unit"]', function() {
            calculateTotal();
        });
        $('#editableTable tfoot').on('input', '#tauxRemise, #aib, #tva', function() {
            calculateTotal();
        });

        $('#tauxRemise').val(0);
        $('#aib').val(0);
        $('#tva').val(0);
        calculateTotal();

        $('#typeSelect').change(function() {


            var selectedOption = $(this).val(); // Obtient la valeur sélectionnée

            if(selectedOption == 2) {
                $('#rowRem').hide();
                $('#rowAib').hide();
                $('#rowTva').hide();
            }

            if(selectedOption == 1) {
                $('#rowRem').show();
                $('#rowAib').show();
                $('#rowTva').show();
            }


        });

        function calculateTotal() {
            var total = 0;
            $('#editableTable tbody tr').each(function() {
                var qte_cmde = parseFloat($(this).find('input[name^="qte_cmde"]').val()) || 0;
                var prix_unit = parseFloat($(this).find('input[name^="prix_unit"]').val()) || 0;

                total += qte_cmde * prix_unit;
            });
            var tauxRemise = parseFloat($('#tauxRemise').val()) || 0;
            var tauxAIB = parseFloat($('#aib').val()) || 0;
            var tauxTVA = parseFloat($('#tva').val()) || 0;
            var seuilPercent = parseFloat($('#seuil').val()) || 0;

            var totalAvecRemise = total * (1 - tauxRemise / 100);
            var totalHt = total /1.19;
            var totalAIB = totalHt * (tauxAIB / 100);
            var totalTVA = totalHt * (tauxTVA / 100);
            var totalNew = totalHt + totalTVA + totalAIB;
            var totalNet = totalNew * (1 - tauxRemise / 100);
            $('#totalNet').val(totalNet.toFixed(2));
            $('#montant_tva').val(totalTVA.toFixed(2));
            $('#montant_aib').val(totalAIB.toFixed(2));
            var avance = totalAvecRemise * (seuilPercent / 100);

            $('#totalInput').val(totalHt.toFixed(2));
            // $('#montant_regle').val(avance.toFixed(2));
            // $('#montant_regle').attr('min', avance.toFixed(2));
            $('#montant_regle').attr('max', totalNet.toFixed(2));
        }

        $('#devisSelect').change(function() {
            var devisId = $(this).val();
            $.ajax({
                url: apiUrl + '/lignesDevis/' + devisId,
                type: 'GET',
                success: function(data) {
                    $('#editableTable tbody').empty();

                    if (data.articles.length > 0) {
                        console.log(data.articles[0]);
                        $("#clientNom").val(data.articles[0].nom_client);
                        $("#client_id").val(data.articles[0].id);
                        $("#seuil").val(data.articles[0].seuil);

                        const firstRow = `
                                    <tr>
                                        <td data-name="article"> ${data.articles[0].nom}
                                            <input type="hidden" name="article[]" readonly value="${data.articles[0].article_id}" class="form-control">

                                            </td>
                                        <td data-name="qte_cmd" contenteditable="true">
                                            <input type="text" name="qte_cmde[]" value="${data.articles[0].qte_cmde}" class="form-control" readonly>
                                            </td>
                                        <td data-name="prix_unit" contenteditable="true">
                                            <input type="text" name="prix_unit[]" value="${data.articles[0].prix_unit}" class="form-control" readonly>
                                            </td>
                                        <td data-name="unite" contenteditable="false"> ${data.articles[0].unite}
                                            <input type="hidden" name="unite[]" readonly value="${data.articles[0].unite_mesure_id}" class="form-control">
                                        </td>
                                    </tr>`;

                        $('#editableTable tbody').append(firstRow);

                        for (let i = 1; i < data.articles.length; i++) {
                            const newRow = `
                                        <tr>
                                            <td data-name="article">${data.articles[i].nom}
                                            <input type="hidden" name="article[]" readonly value="${data.articles[i].article_id}" class="form-control">

                                                </td>
                                            <td data-name="qte_cmde" contenteditable="true">
                                                <input type="text" name="qte_cmde[]" value="${data.articles[i].qte_cmde}" class="form-control" readonly></td>
                                            <td data-name="prix_unit" contenteditable="true">
                                            <input type="text" name="prix_unit[]" value="${data.articles[i].prix_unit}" class="form-control" readonly>
                                                </td>
                                            <td data-name="unite" contenteditable="false"> ${data.articles[i].unite}
                                            <input type="hidden" name="unite[]" readonly value="${data.articles[i].unite_mesure_id}" class="form-control">
                                                </td>
                                        </tr>`;

                            $('#editableTable tbody').append(newRow);
                        }

                        $("#tva").val(18);
                        $("#aib").val(1);

                        calculateTotal();
                    }
                    $('.delete-row').click(function() {
                        $(this).closest('tr').remove();
                        calculateTotal();
                    });
                },
                error: function(error) {
                    console.log('Erreur de la requête AJAX:', error);
                }
            });
        });

        // Déclencher l'événement de changement initial avec la valeur par défaut
        var defaultDevisId = $('#devisSelect').val();
        console.log('ID du devis initial:', defaultDevisId);
        $('#devisSelect').trigger('change'); // Déclencher l'événement de changement initial
    </script>
@endsection
