@extends('layout.template')
@section('content')
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Appros / Livraisons</h1>
    </div><!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body mt-1">
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
                        <div class="row mt-4">
                                <div class="col-4">
                                <label class="form-label">Coût du Transport</label>
                                <input type="text" name="transport" readonly id="transport" style="font-weight: bolder; font-size: medium;" class="form-control bg bg-light">
                            </div>

                            <div class="col-4">
                                <label class="form-label">Coût du Chargement déchargement</label>
                                <input type="text" name="charge_decharge" readonly id="charge_decharge" style="font-weight: bolder; font-size: medium;" class="form-control bg bg-light">
                            </div>

                            <div class="col-4">
                                <label class="form-label">Autre Coût</label>
                                <input type="text" name="autre" readonly id="autre" style="font-weight: bolder; font-size: medium;" class="form-control bg bg-light">
                            </div>

                            <!-- </div> -->
                            @can('approvisionnements.ajouter-livraison-directe')
                            <div class="col-3 float-end">
                                <button type="button" class="btn btn-sm bg-dark text_orange mt-3" id="btn-formDisplay">Livraison
                                    non physique</button>
                            </div>
                            @endcan

                        </div>

                        <form class="row g-3 mt-4" action="{{ route('livraisons.store') }}" id="approForm" method="POST">
                            @csrf

                            <div class="col-4">
                                <label class="form-label">Choisir la commande</label>
                                <select class="js-example-basic-multiple form-select" name="commande_id" id="commandeSelect">
                                    <option value="">Choisir le bon </option>

                                    @foreach ($commandes as $commande)
                                    <option value="{{ $commande->id }}"> {{ $commande->reference }} </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-4">
                                <label class="form-label">Fournisseur</label>
                                <input type="text" name="fournisseur" readonly id="fournisseur" class="form-control">
                            </div>

                            <div class="col-4">
                                <label class="form-label">Magasin de livraison</label>
                                <select class="form-select" name="magasin_id" id="magasinSelect">
                                    <option value="">Choisir le magasin </option>
                                    @foreach ($magasins as $magasin)
                                    <option value="{{ $magasin->id }}"> {{ $magasin->nom }} </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-3">
                                <label class="form-label">Date de livraison</label>
                                <input type="date" name="date_livraison" id="date_livraison" class="form-control">
                            </div>

                            <div class="col-3">
                                <label class="form-label">Choisir le chauffeur</label>
                                <select class="form-control" name="chauffeur_id" id="chauffeur_id" required>
                                    <option value="">Choisir un chauffeur</option>
                                    @foreach ($chauffeurs as $chauffeur)
                                    <option value="{{$chauffeur->id}}">{{$chauffeur->nom_chauf}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-3">
                                <label class="form-label">Choisir le véhicule</label>
                                <select class="form-control" name="vehicule_id" id="vehicule_id" required>
                                    <option value="">Choisir un véhicule</option>
                                    @foreach ($vehicules as $vehicule)
                                    <option value="{{$vehicule->id}}">{{$vehicule->num_vehicule}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-3">
                                <label class="form-label">Coût de Revient</label>
                                <input type="number" step="0.0001" name="cout_revient" required id="cout_revient" class="form-control">

                            </div>

                            <div id="dynamic-fields-container">
                                <table id="editableTableAppro" class="table table-responsive">
                                    <thead>
                                        <tr>
                                            <th>Article</th>
                                            <th>Quantité</th>
                                            <th>Prix unit</th>
                                            <th>Montant</th>

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


                        <form class="row g-3" action="{{ route('livraisonsDirectes.store') }}" style="display: none;" method="POST" id="livarisonDirecte">
                            @csrf

                            <div class="col-4 mb-3">
                                <label for="" class="form-label">Choisir la commande</label>
                                <select class="js-example-basic-multiple form-control" name="commande_id" id="cmdSelect">
                                    <option value="">Choisir le commande </option>
                                    @foreach ($commandes as $commande)
                                    <option value="{{ $commande->id }}"> {{ $commande->reference }} </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-4 mb-3">
                                <label for="" class="form-label">Clients à livrer</label>
                                <select class=" js-example-basic-multiple form-control " name="client_id" id="cmdSelect">
                                    <option value="">Choisir le client </option>
                                    @foreach ($clients as $client)
                                    <option value="{{ $client->id }}"> {{ $client->nom_client }} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4 mb-3">
                                <label class="form-label">Type de facture</label>
                                <select class="form-select" name="type_id" id="typeSelect">
                                    <option value="">Choisir le type </option>
                                    @foreach ($types as $type)
                                    <option value="{{ $type->id }}"> {{ $type->libelle }} </option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="dynamic-fields-container">
                                <table id="editableTable" class="table table-responsive">
                                    <thead>
                                        <tr>
                                            <th>Article</th>
                                            <th>Quantité</th>
                                            <th>Prix achat</th>
                                            <th>Prix vente</th>
                                            <th>Montant</th>
                                            <th>Unité mesure</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                                <button type="reset" class="btn btn-secondary">Annuler</button>
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
    $(".js-example-basic-multiple").select2();
    $(document).ready(function() {

        $('#vehicule_id').select2({
            width: 'resolve'
        });


        $('#chauffeur_id').select2({
            width: 'resolve'
        });

        $('#btn-formDisplay').on('click', function() {
            $('#approForm').hide();
            $('#livarisonDirecte').show();
            $(this).hide();
        });
    });
    // Fonction pour mettre à jour la date par défaut au chargement de la page
    function updateDate() {
        var currentDate = new Date();
        var day = currentDate.getDate();
        var month = currentDate.getMonth() + 1; // Les mois commencent à 0, donc ajout de 1
        var year = currentDate.getFullYear();

        // Formatage de la date au format YYYY-MM-DD (format attendu par le champ de type date)
        var formattedDate = year + '-' + (month < 10 ? '0' : '') + month + '-' + (day < 10 ? '0' : '') + day;

        // Mettre à jour la valeur du champ de date
        document.getElementById('date_livraison').value = formattedDate;
    }

    // Appeler la fonction au chargement de la page
    window.onload = updateDate;
</script>

<script>
    var apiUrl = "{{ config('app.url_ajax') }}";

    $('#commandeSelect').change(function() {
        var commandeId = $(this).val();

        $.ajax({
            url: apiUrl + '/lignesCommande/' + commandeId,
            type: 'GET',
            success: function(data) {
                console.log('Réponse de la requête AJAX:', data.articles);

                if (data.articles.length > 0) {
                    // Mettre à jour le champ #clientNom avec la valeur du premier article
                    $("#fournisseur").val(data.articles[0].fournisseur);
                    $("#transport").val(data.articles[0].transport);
                    $("#charge_decharge").val(data.articles[0].charge_decharge);
                    $("#autre").val(data.articles[0].autre);

                    // Parcourir les articles récupérés
                    for (let i = 0; i < data.articles.length; i++) {
                        // Vérifier si une ligne avec le même identifiant d'article existe déjà
                        if (!$(`input[name='lignes[]'][value='${data.articles[i].id}']`).length) {
                            // Si la ligne n'existe pas, ajouter une nouvelle ligne pour l'article
                            const montant = data.articles[i].qte_cmde * data.articles[i].prix_unit;
                            const newRow = `
                            <tr>
                                <td data-name="article">
                                    <input type="hidden" class="lignes" name="commandes[]" value="${data.articles[i].commande_id}">
                                    <input type="hidden" class="lignes" name="lignes[]" value="${data.articles[i].id}">
                                    <input type="text" name="articles[]" required readonly value="${data.articles[i].nom}" class="form-control articles">
                                </td>
                                <td data-name="qte_cmde" contenteditable="true">
                                    <input type="number" step="0.0001"  max="${data.articles[i].qte_cmde}" required class="form-control qtecmd" name="qte_cdes[]" value="${data.articles[i].qte_cmde}">
                                </td>
                                <td data-name="prix_unit" contenteditable="true">
                                    <input type="number"  step="0.0001" readonly required class="form-control prixUnits" name="prixUnits[]" value="${data.articles[i].prix_unit}">
                                </td>
                                <td data-name="montant" class="montant">${montant}</td>
                                <td data-name="unite" contenteditable="false">
                                    <input type="text" required readonly class="form-control unites" name="unites[]" value="${data.articles[i].unite}">
                                </td>
                                <td><button class="btn btn-sm text_orange btn-sm bg-dark delete-row"><i class="bi bi-trash"></i></button></td>
                            </tr>`;
                            $('#editableTableAppro tbody').append(newRow);
                        }
                    }
                }
                // Attacher un gestionnaire d'événements sur les champs de quantité
                $('input[name="qte_cdes[]"]').on('change', function() {
                    var tr = $(this).closest('tr');
                    var qte = parseFloat($(this).val());
                    var prixUnit = parseFloat(tr.find('input[name="prixUnits[]"]').val());
                    var montant = qte * prixUnit;
                    tr.find('.montant').text(montant);
                });

                $('.delete-row').click(function() {
                    $(this).closest('tr').remove();
                });
            },
            error: function(error) {
                console.log('Erreur de la requête AJAX:', error);
            }
        });
    });


    // $('#commandeSelect').change(function() {
    //     var commandeId = $(this).val();

    //     $.ajax({
    //         url: apiUrl + '/lignesCommande/' + commandeId,
    //         type: 'GET',
    //         success: function(data) {
    //             console.log('Réponse de la requête AJAX:', data.articles);

    //             if (data.articles.length > 0) {
    //                 // Mettre à jour le champ #clientNom avec la valeur du premier article
    //                 $("#fournisseur").val(data.articles[0].fournisseur);

    //                 // Parcourir les articles récupérés
    //                 for (let i = 0; i < data.articles.length; i++) {
    //                     // Vérifier si une ligne avec le même identifiant d'article existe déjà
    //                     if (!$(`input[name='lignes[]'][value='${data.articles[i].id}']`).length) {
    //                         // Si la ligne n'existe pas, ajouter une nouvelle ligne pour l'article
    //                         const newRow = `
    //                         <tr>
    //                             <td data-name="article">
    //                                 <input type="hidden" class="lignes" name="commandes[]" value="${data.articles[i].commande_id}">
    //                                 <input type="hidden" class="lignes" name="lignes[]" value="${data.articles[i].id}">
    //                                 <input type="text" name="articles[]" required readonly value="${data.articles[i].nom}" class="form-control articles">
    //                             </td>
    //                             <td data-name="qte_cmde" contenteditable="true">
    //                                 <input type="number" step="0.0001" min="1" max="${data.articles[i].qte_cmde}" required class="form-control qtecmd" name="qte_cdes[]" value="${data.articles[i].qte_cmde}">
    //                             </td>
    //                             <td data-name="prix_unit" contenteditable="true">
    //                                 <input type="number" min="1" step="0.0001" readonly required class="form-control prixUnits" name="prixUnits[]" value="${data.articles[i].prix_unit}">
    //                             </td>
    //                             <td data-name="prix_unit" contenteditable="true">
    //                                 <input type="number" min="1" readonly step="0.0001" required class="form-control prixUnits" name="montants[]" value="${data.articles[i].qte_cmde * data.articles[i].prix_unit}">
    //                             </td>
    //                             <td data-name="unite" contenteditable="false">
    //                                 <input type="text" required readonly class="form-control unites" name="unites[]" value="${data.articles[i].unite}">
    //                             </td>
    //                             <td><button class="btn btn-danger btn-sm delete-row"><i class="bi bi-trash"></i></button></td>
    //                         </tr>`;
    //                         $('#editableTableAppro tbody').append(newRow);
    //                     }
    //                 }
    //             }
    //             // Attacher un gestionnaire d'événements sur les champs de quantité
    //         $('input[name="qte_cdes[]"]').on('change', function() {
    //             var tr = $(this).closest('tr');
    //             var qte = parseFloat($(this).val());
    //             var prixUnit = parseFloat(tr.find('input[name="prixUnits[]"]').val());
    //             var montant = qte * prixUnit;
    //             tr.find('.montant').text(montant);
    //         });
    //             $('.delete-row').click(function() {
    //                 $(this).closest('tr').remove();
    //             });
    //         },
    //         error: function(error) {
    //             console.log('Erreur de la requête AJAX:', error);
    //         }
    //     });
    // });


    // $('#commandeSelect').change(function() {
    //     var commandeId = $(this).val();

    //     $.ajax({
    //         url: apiUrl + '/lignesCommande/' + commandeId,
    //         type: 'GET',
    //         success: function(data) {
    //             console.log('Réponse de la requête AJAX:', data.articles);

    //             if (data.articles.length > 0) {
    //                 // Mettre à jour le champ #clientNom avec la valeur du premier article
    //                 $("#fournisseur").val(data.articles[0].fournisseur);

    //                 // Ensuite, ajouter les lignes pour tous les articles
    //                 for (let i = 0; i < data.articles.length; i++) {
    //                     const newRow = `
    //                     <tr>
    //                         <td data-name="article">
    //                             <input type="hidden" class="lignes" name="lignes[]" value="${data.articles[i].id}">
    //                             <input type="text" name="articles[]" required readonly value="${data.articles[i].nom}" class="form-control articles">
    //                         </td>
    //                         <td data-name="qte_cmde" contenteditable="true">
    //                             <input type="number" step="0.0001" min="1" max="${data.articles[i].qte_cmde}" required class="form-control qtecmd" name="qte_cdes[]" value="${data.articles[i].qte_cmde}">
    //                         </td>
    //                         <td data-name="prix_unit" contenteditable="true">
    //                             <input type="number" min="1" readonly required class="form-control prixUnits" name="prixUnits[]" value="${data.articles[i].prix_unit}">
    //                         </td>
    //                         <td data-name="unite" contenteditable="false">
    //                             <input type="text" required readonly class="form-control unites" name="unites[]" value="${data.articles[i].unite}">
    //                         </td>
    //                         <td><button class="btn btn-danger btn-sm delete-row"><i class="bi bi-trash"></i></button></td>
    //                     </tr>`;
    //                     $('#editableTableAppro tbody').append(newRow);
    //                 }
    //             }
    //             $('.delete-row').click(function() {
    //                 $(this).closest('tr').remove();
    //             });
    //         },
    //         error: function(error) {
    //             console.log('Erreur de la requête AJAX:', error);
    //         }
    //     });
    // });


    // $('#commandeSelect').change(function() {
    //     var commandeId = $(this).val();

    //     $.ajax({
    //         url: apiUrl + '/lignesCommande/' + commandeId,
    //         type: 'GET',
    //         success: function(data) {
    //             console.log('Réponse de la requête AJAX:', data.articles);

    //             $('#editableTableAppro tbody').empty();


    //             if (data.articles.length > 0) {
    //                 // Mettez à jour le champ #clientNom avec la valeur du premier article
    //                 $("#fournisseur").val(data.articles[0].fournisseur);

    //                 // Créez la première ligne avec les noms de champs comme attributs data
    //                 const firstRow = `
    //             <tr>
    //                 <td data-name="article">
    //                     <input type="hidden" class="lignes" name="lignes[]" value="${data.articles[0].id}">

    //                     <input type="text" name="articles[]" required readonly value="${data.articles[0].nom}" class="form-control articles">

    //                     </td>
    //                 <td data-name="qte_cmd" contenteditable="true">
    //                     <input type="number" step="0.0001" min="1" max="${data.articles[0].qte_cmde}" required class="form-control qtecmd" name="qte_cdes[]" value="${data.articles[0].qte_cmde}">
    //                     </td>
    //                 <td data-name="prix_unit" contenteditable="true">
    //                     <input type="number" min="1" readonly required class="form-control prixUnits" name="prixUnits[]" value="${data.articles[0].prix_unit}" >
    //                     </td>
    //                 <td data-name="unite" contenteditable="false">
    //                     <input type="text" required readonly class="form-control unites" name="unites[]" value="${data.articles[0].unite}">
    //                 </td>
    //                 <td><button class="btn btn-danger btn-sm delete-row"><i class="bi bi-trash"></i></button></td>
    //             </tr>`;

    //                 $('#editableTableAppro tbody').append(firstRow);

    //                 // Ensuite, ajoutez les lignes pour les autres articles
    //                 for (let i = 1; i < data.articles.length; i++) {
    //                     const newRow = `
    //                 <tr>
    //                     <td data-name="article">
    //                         <input type="hidden" class="lignes" name="lignes[]" value="${data.articles[i].id}">

    //                     <input type="text" name="articles[]" required readonly value="${data.articles[i].nom}" class="form-control articles">

    //                         </td>
    //                     <td data-name="qte_cmde" contenteditable="true">
    //                         <input type="number" step="0.0001" min="1" max="${data.articles[i].qte_cmde}" required class="form-control qtecmd" name="qte_cdes[]" value="${data.articles[i].qte_cmde}" ></td>
    //                     <td data-name="prix_unit" contenteditable="true">
    //                     <input type="number" min="1" readonly required class="form-control prixUnits" name="prixUnits[]" value="${data.articles[i].prix_unit}">
    //                         </td>
    //                     <td data-name="unite" contenteditable="false">
    //                     <input type="text" required readonly class="form-control unites" name="unites[]" value="${data.articles[i].unite}" >
    //                         </td>
    //                     <td><button class="btn btn-danger btn-sm delete-row"><i class="bi bi-trash"></i></button></td>
    //                 </tr>`;

    //                     $('#editableTableAppro tbody').append(newRow);
    //                 }
    //             }
    //             $('.delete-row').click(function() {
    //                 $(this).closest('tr').remove();
    //             });
    //         },
    //         error: function(error) {
    //             console.log('Erreur de la requête AJAX:', error);
    //         }
    //     });
    // });

    var defaultcommandeId = $('#commandeSelect').val();
    console.log('ID du devis initial:', defaultcommandeId);
    $('#commandeSelect').trigger('change'); // Déclencher l'événement de changement initial
</script>

<script>
    var apiUrl = "{{ config('app.url_ajax') }}";

    $('#cmdSelect').change(function() {
        var commandeId = $(this).val();

        $.ajax({
            url: apiUrl + '/lignesCommande/' + commandeId,
            type: 'GET',
            success: function(data) {
                console.log('Réponse de la requête AJAX:', data.articles);

                $('#editableTable tbody').empty();


                if (data.articles.length > 0) {
                    // Mettez à jour le champ #clientNom avec la valeur du premier article
                    $("#clientNom").val(data.articles[0].nom_client);

                    // Créez la première ligne avec les noms de champs comme attributs data
                    const firstRow = `
                <tr>
                    <td data-name="article">
                        <input type="hidden" name="ligne_id[]" value="${data.articles[0].id}">

                        <input type="text" name="article[]" readonly value="${data.articles[0].nom}" class="form-control">

                        </td>
                    <td data-name="qte_cmd" contenteditable="true">
                        <input type="number" step="0.0001" id="qte_liv" min="1" max="${data.articles[0].qte_cmde}" name="qte_cmde[]" value="${data.articles[0].qte_cmde}" class="form-control">
                        </td>
                    <td data-name="prix_achat" contenteditable="false">${data.articles[0].prix_unit}
                        <input type="hidden" name="prix_achat[]" value="${data.articles[0].prix_unit}" class="form-control readonly">
                        </td>

                        <td data-name="prix_unit" contenteditable="true">
                        <input type="number" min="${parseFloat(data.articles[0].prix_unit) + 1}" name="prix_unit[]" value="${data.articles[0].prix_unit}" class="form-control">
                        </td>
                        <td data-name="montant" contenteditable="true">
                        <input type="text" name="montant[]" readonly class="form-control">
                        </td>
                    <td data-name="unite" contenteditable="false">
                        <input type="text" name="unite[]" readonly value="${data.articles[0].unite}" class="form-control">
                    </td>
                    <td><button class="btn btn-danger btn-sm delete-row"><i class="bi bi-trash"></i></button></td>
                </tr>`;

                    $('#editableTable tbody').append(firstRow);

                    // Ensuite, ajoutez les lignes pour les autres articles
                    for (let i = 1; i < data.articles.length; i++) {
                        const newRow = `
                    <tr>
                        <td data-name="article">
                            <input type="hidden" name="ligne_id[]" value="${data.articles[i].id}">

                        <input type="text" name="article[]" readonly value="${data.articles[i].nom}" class="form-control">
                            </td>

                        <td data-name="qte_cmde" contenteditable="true">
                            <input type="number" step="0.0001" min="1" max="${data.articles[i].qte_cmde}" name="qte_cmde[]" value="${data.articles[i].qte_cmde}" class="form-control"></td>
                            <td data-name="prix_achat" contenteditable="false">${data.articles[i].prix_unit}
                        <input type="hidden" name="prix_achat[]" value="${data.articles[i].prix_unit}" class="form-control readonly">
                        </td>
                            <td data-name="prix_unit" contenteditable="true">
                        <input type="number" min="${parseFloat(data.articles[i].prix_unit) + 1}" name="prix_unit[]" value="${data.articles[i].prix_unit}" class="form-control">
                            </td>

                            <td data-name="montant" contenteditable="true">
                        <input type="text" name="montant[]" readonly class="form-control">
                        </td>
                        <td data-name="unite" contenteditable="false">
                        <input type="text" name="unite[]" readonly value="${data.articles[i].unite}" class="form-control">
                            </td>
                        <td><button class="btn btn-danger btn-sm delete-row"><i class="bi bi-trash"></i></button></td>
                    </tr>`;

                        $('#editableTable tbody').append(newRow);
                    }
                    calculateMontant();

                }
                $('.delete-row').click(function() {
                    $(this).closest('tr').remove();
                    calculateMontant();

                });

            },
            error: function(error) {
                console.log('Erreur de la requête AJAX:', error);
            }
        });

    });


    // Déclencher l'événement de changement initial avec la valeur par défaut
    var defaultcommandeId = $('#cmdSelect').val();
    console.log('ID du devis initial:', defaultcommandeId);
    $('#cmdSelect').trigger('change'); // Déclencher l'événement de changement initial

    function calculateMontant() {
        $('#editableTable tbody tr').each(function() {
            const qteCmde = parseFloat($(this).find('input[name="qte_cmde[]"]').val()) || 0;
            const prixUnit = parseFloat($(this).find('input[name="prix_unit[]"]').val()) || 0;
            const montant = qteCmde * prixUnit;
            $(this).find('input[name="montant[]"]').val(montant.toFixed(2));
        });
    }

    $('#editableTable tbody').on('input', 'input[name="qte_cmde[]"], input[name="prix_unit[]"]', function() {
        calculateMontant();
    });
</script>
@endsection
