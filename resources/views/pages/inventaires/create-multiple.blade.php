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
                        <h5 class="card-title text-dark">Ajouter des inventaire</h5>

                        <form class="row g-3" id="programForm" action="{{ route('inventaire-multiple-store') }}" method="POST">
                            @csrf
                            <td><input style="width:150px" type="text" class="form-control datepicker" name="date_inventaire"></td>
                            <td><input style="width:150px" value="{{$magasin->id}}" type="hidden" class="form-control datepicker" name="magasin_id"></td>
                            <div class="table-responsive">
                                <table id="example" class="table border border-warning table-sm table-striped ">
                                    <thead>
                                        <tr>
                                            <th>N°</th>
                                            <th>Article</th>
                                            <th>Qté stock</th>
                                            <th>Qté réelle</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($articles as $article)
                                        <tr>
                                            <td>{{ $loop->iteration }} <input type="hidden" name="articles[]" value="{{ $article->id }}"></td>

                                            <td>{{ $article->nom }}</td>
                                            <td>{{ $article->qte_stock }}<input type="hidden" name="qte_stock[]" value="{{ $article->qte_stock }}"></td>
                                            <td><input type="number" min="0" value="{{ $article->qte_stock}}" name="qte_reel[]" class="form-control qte_reel"></td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6">Aucun détail enregistré</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div id="dynamic-fields-container">

                            </div>
                            <div class="col-lg-12 d-flex flex-row align-items-center justify-content-between">
                                <button type="submit" class="btn btn-sm btn-dark text_orange w-100 submitBtn" id="enregistrerVente"><i class="bi bi-check-circle"></i> Enregistrer</button>
                                <button type="button" class="btn btn-sm btn-dark text_orange w-100 loadingBtn" hidden><span class="spinner-border spinner-border-sm text_orange loading"></span> En cours ...</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="{{ asset('assets/js/jquery3.6.min.js') }}"></script>
<script src="{{ asset('assets/js/mindmup-editabletable.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(document).ready(function() {
        $(".datepicker").datepicker({
            beforeShowDay: function(date) {
                var currentDate = new Date();
                currentDate.setHours(0, 0, 0, 0);
                return [date <= currentDate];
            },
            dateFormat: 'dd-mm-yy'
        });

        $('#editableTable').editableTableWidget();

        /*  $(document).on('click', '.add-inventory', function() {
             var articleId = $(this).data('id');
             var magasinId = $(this).data('id');
             var articleName = $(this).closest('tr').find('td:eq(1)').text();
             var qteStock = $(this).closest('tr').find('td:eq(2)').text();
             var qteReel = $(this).closest('tr').find('.qte_reel').val();
             var newRow = `
                     <tr>
                         <td>${articleName}<input type="hidden" name="articles[]" value="${articleId}"></td>
                         <td>${qteStock}<input type="hidden" name="qte_stockselected[]" value="${qteStock}"></td>
                         <td>${qteReel}<input type="hidden" min="0" name="qte_reelselected[]" value="${qteReel}" class="form-control"></td>
                         <td><button type="button" class="btn btn-danger btn-sm delete-row">Supprimer</button></td>
                     </tr>`;
             $('#editableTable tbody').append(newRow);
         }); */

        /* $(document).on('click', '.delete-row', function() {
            $(this).closest('tr').remove();
        }); */
    });
</script>


<script>
    $(document).ready(function() {
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
                    console.log('Submited mulptiple')
                }
            });
        }

        // Call displayConfirmation function when the submit button is clicked
        $('#enregistrerVente').click(function(e) {
            e.preventDefault();
            displayConfirmation();
        });
    });
</script>


<script>
    new DataTable('#example1', {
        layout: {
            topStart: {
                buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdfHtml5']
            }
        },
        paging: false,
        language: {
            "emptyTable": "Aucune donnée disponible dans le tableau",
            "loadingRecords": "Chargement...",
            "processing": "Traitement...",
            "select": {
                "rows": {
                    "_": "%d lignes sélectionnées",
                    "1": "1 ligne sélectionnée"
                },
                "cells": {
                    "1": "1 cellule sélectionnée",
                    "_": "%d cellules sélectionnées"
                },
                "columns": {
                    "1": "1 colonne sélectionnée",
                    "_": "%d colonnes sélectionnées"
                }
            },
            "autoFill": {
                "cancel": "Annuler",
                "fill": "Remplir toutes les cellules avec <i>%d<\/i>",
                "fillHorizontal": "Remplir les cellules horizontalement",
                "fillVertical": "Remplir les cellules verticalement"
            },
            "searchBuilder": {
                "conditions": {
                    "date": {
                        "after": "Après le",
                        "before": "Avant le",
                        "between": "Entre",
                        "empty": "Vide",
                        "not": "Différent de",
                        "notBetween": "Pas entre",
                        "notEmpty": "Non vide",
                        "equals": "Égal à"
                    },
                    "number": {
                        "between": "Entre",
                        "empty": "Vide",
                        "gt": "Supérieur à",
                        "gte": "Supérieur ou égal à",
                        "lt": "Inférieur à",
                        "lte": "Inférieur ou égal à",
                        "not": "Différent de",
                        "notBetween": "Pas entre",
                        "notEmpty": "Non vide",
                        "equals": "Égal à"
                    },
                    "string": {
                        "contains": "Contient",
                        "empty": "Vide",
                        "endsWith": "Se termine par",
                        "not": "Différent de",
                        "notEmpty": "Non vide",
                        "startsWith": "Commence par",
                        "equals": "Égal à",
                        "notContains": "Ne contient pas",
                        "notEndsWith": "Ne termine pas par",
                        "notStartsWith": "Ne commence pas par"
                    },
                    "array": {
                        "empty": "Vide",
                        "contains": "Contient",
                        "not": "Différent de",
                        "notEmpty": "Non vide",
                        "without": "Sans",
                        "equals": "Égal à"
                    }
                },
                "add": "Ajouter une condition",
                "button": {
                    "0": "Recherche avancée",
                    "_": "Recherche avancée (%d)"
                },
                "clearAll": "Effacer tout",
                "condition": "Condition",
                "data": "Donnée",
                "deleteTitle": "Supprimer la règle de filtrage",
                "logicAnd": "Et",
                "logicOr": "Ou",
                "title": {
                    "0": "Recherche avancée",
                    "_": "Recherche avancée (%d)"
                },
                "value": "Valeur",
                "leftTitle": "Désindenter le critère",
                "rightTitle": "Indenter le critère"
            },
            "searchPanes": {
                "clearMessage": "Effacer tout",
                "count": "{total}",
                "title": "Filtres actifs - %d",
                "collapse": {
                    "0": "Volet de recherche",
                    "_": "Volet de recherche (%d)"
                },
                "countFiltered": "{shown} ({total})",
                "emptyPanes": "Pas de volet de recherche",
                "loadMessage": "Chargement du volet de recherche...",
                "collapseMessage": "Réduire tout",
                "showMessage": "Montrer tout"
            },
            "buttons": {
                "collection": "Collection",
                "colvis": "Visibilité colonnes",
                "colvisRestore": "Rétablir visibilité",
                "copy": "Copier",
                "copySuccess": {
                    "1": "1 ligne copiée dans le presse-papier",
                    "_": "%d lignes copiées dans le presse-papier"
                },
                "copyTitle": "Copier dans le presse-papier",
                "csv": "CSV",
                "excel": "Excel",
                "pageLength": {
                    "-1": "Afficher toutes les lignes",
                    "_": "Afficher %d lignes",
                    "1": "Afficher 1 ligne"
                },
                "pdf": "PDF",
                "print": "Imprimer",
                "copyKeys": "Appuyez sur ctrl ou u2318 + C pour copier les données du tableau dans votre presse-papier.",
                "createState": "Créer un état",
                "removeAllStates": "Supprimer tous les états",
                "removeState": "Supprimer",
                "renameState": "Renommer",
                "savedStates": "États sauvegardés",
                "stateRestore": "État %d",
                "updateState": "Mettre à jour"
            },
            "decimal": ",",
            "datetime": {
                "previous": "Précédent",
                "next": "Suivant",
                "hours": "Heures",
                "minutes": "Minutes",
                "seconds": "Secondes",
                "unknown": "-",
                "amPm": [
                    "am",
                    "pm"
                ],
                "months": {
                    "0": "Janvier",
                    "2": "Mars",
                    "3": "Avril",
                    "4": "Mai",
                    "5": "Juin",
                    "6": "Juillet",
                    "8": "Septembre",
                    "9": "Octobre",
                    "10": "Novembre",
                    "1": "Février",
                    "11": "Décembre",
                    "7": "Août"
                },
                "weekdays": [
                    "Dim",
                    "Lun",
                    "Mar",
                    "Mer",
                    "Jeu",
                    "Ven",
                    "Sam"
                ]
            },
            "editor": {
                "close": "Fermer",
                "create": {
                    "title": "Créer une nouvelle entrée",
                    "button": "Nouveau",
                    "submit": "Créer"
                },
                "edit": {
                    "button": "Editer",
                    "title": "Editer Entrée",
                    "submit": "Mettre à jour"
                },
                "remove": {
                    "button": "Supprimer",
                    "title": "Supprimer",
                    "submit": "Supprimer",
                    "confirm": {
                        "_": "Êtes-vous sûr de vouloir supprimer %d lignes ?",
                        "1": "Êtes-vous sûr de vouloir supprimer 1 ligne ?"
                    }
                },
                "multi": {
                    "title": "Valeurs multiples",
                    "info": "Les éléments sélectionnés contiennent différentes valeurs pour cette entrée. Pour modifier et définir tous les éléments de cette entrée à la même valeur, cliquez ou tapez ici, sinon ils conserveront leurs valeurs individuelles.",
                    "restore": "Annuler les modifications",
                    "noMulti": "Ce champ peut être modifié individuellement, mais ne fait pas partie d'un groupe. "
                },
                "error": {
                    "system": "Une erreur système s'est produite (<a target=\"\\\" rel=\"nofollow\" href=\"\\\">Plus d'information<\/a>)."
                }
            },
            "stateRestore": {
                "removeSubmit": "Supprimer",
                "creationModal": {
                    "button": "Créer",
                    "order": "Tri",
                    "paging": "Pagination",
                    "scroller": "Position du défilement",
                    "search": "Recherche",
                    "select": "Sélection",
                    "columns": {
                        "search": "Recherche par colonne",
                        "visible": "Visibilité des colonnes"
                    },
                    "name": "Nom :",
                    "searchBuilder": "Recherche avancée",
                    "title": "Créer un nouvel état",
                    "toggleLabel": "Inclus :"
                },
                "renameButton": "Renommer",
                "duplicateError": "Il existe déjà un état avec ce nom.",
                "emptyError": "Le nom ne peut pas être vide.",
                "emptyStates": "Aucun état sauvegardé",
                "removeConfirm": "Voulez vous vraiment supprimer %s ?",
                "removeError": "Échec de la suppression de l'état.",
                "removeJoiner": "et",
                "removeTitle": "Supprimer l'état",
                "renameLabel": "Nouveau nom pour %s :",
                "renameTitle": "Renommer l'état"
            },
            "info": "Affichage de _START_ à _END_ sur _TOTAL_ entrées",
            "infoEmpty": "Affichage de 0 à 0 sur 0 entrées",
            "infoFiltered": "(filtrées depuis un total de _MAX_ entrées)",
            "lengthMenu": "Afficher _MENU_ entrées",
            "paginate": {
                "first": "Première",
                "last": "Dernière",
                "next": "Suivante",
                "previous": "Précédente"
            },
            "zeroRecords": "Aucune entrée correspondante trouvée",
            "aria": {
                "sortAscending": " : activer pour trier la colonne par ordre croissant",
                "sortDescending": " : activer pour trier la colonne par ordre décroissant"
            },
            "infoThousands": " ",
            "search": "Rechercher :",
            "thousands": " "
        }

    });
</script>

@endsection