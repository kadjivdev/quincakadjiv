<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>QUINCA KADJIV</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicons -->
    <link href="{{ asset('assets/img/kadjiv.png') }}" rel="icon">
    <link href="{{ asset('assets/img/kadjiv.png') }}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"> --}}

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">


    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/css/dataTables/dataTables.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/dataTables/dataTables_buttons.css') }}" rel="stylesheet">


    <!-- Template Main CSS File -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    
    <style>
        /* Style pour le loader */
        .loader {
            display: none;
            border: 6px solid #f3f3f3;
            border-top: 6px solid #3498db;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }

        .button_loader {
            display: none;

        }

        .text_orange {
            color: #f8c714;
        }


        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* side bar style */

        .sidebar {
            /* background: linear-gradient(rgba(224, 155, 1, .5), rgba(224, 155, 1, .2)), url(../img/about.jpeg) center center no-repeat; */
            background: linear-gradient(135deg, #f8c714 0%, #080808 100%);
        }

        .global_container_left {
            margin-left: -15px;
        }

        .app_title {
            font-size: 2rem;
            color: #fff;
            font-weight: bolder;
            margin-bottom: -10px;
        }

        .app_date_heure {
            font-size: 0.9rem;
            color: #fff;
            font-weight: bold;
            padding-bottom: 10px;
        }

        .toogle_icon {
            color: #3498db;
            font-size: 50px;
        }

        /* style des tableau  */

        .entete {
            /* background-color: rgba(224, 155, 1, .7) !important; */
            background-color: #000 !important;
        }

        .ligne {
            background-color: rgba(224, 155, 1, .3) !important;
        }

        .small_bouton {
            --bs-btn-padding-y: .25rem;
            --bs-btn-padding-x: .5rem;
            --bs-btn-font-size: .75rem;
        }
    </style>
    @yield('styles')
</head>

<body>

    <!-- ======= Header ======= -->
    @include('partials.header')

    <!-- ======= Sidebar ======= -->
    @include('partials.sidebar')

    @yield('content')

    <!-- ======= Footer ======= -->
    @include('partials.footer')

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>
    {{-- <script src="{{ asset('assets/js/jquery3.6.min.js') }}"></script> --}}

    <!-- Vendor JS Files -->
    <script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('assets/vendor/echarts/echarts.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/quill/quill.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>

    <script src="{{ asset('assets/js/dataTables/dataTables.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables/dataTables_buttons.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables/buttons_dataTables.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables/jszip.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables/pdfmake.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables/html5.js') }}"></script>

    <!-- AXIOS -->
    <!-- <script src="{{asset('js/axios.min.js')}}"></script> -->
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                "order": [], // Désactive l'ordre initial
                "columnDefs": [{
                        "orderable": false,
                        "targets": 0
                    } // Désactive le tri sur la première colonne
                ],
                "drawCallback": function(settings) {
                    $('.custom-background').css('background-color', 'darkred').css('color', 'white');
                },
                pageLength: 10,
                layout: {
                    topStart: {
                        buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdfHtml5']
                    }
                },
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
        });
    </script>

    <!-- Template Main JS File -->
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/js/loader.js') }}"></script>
    @yield('scripts')

</body>

</html>