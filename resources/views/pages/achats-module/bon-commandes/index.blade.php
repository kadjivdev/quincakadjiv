@extends('layout.template')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle d-flex">
            <div class="col-6">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Tableau de Bord</a></li>
                        <li class="breadcrumb-item active">Programmation d'achat</li>

                    </ol>
                </nav>

            </div>

            <div class="col-3">

                    <a href="{{ route('liste-valider') }}" class="btn btn-success float-end petit_bouton">
                        <i class="bi bi-check-circle-fill"></i>
                        Prog Validés et Classés</a>

                </div>

            <div class="col-3">

                    @can('fournisseurs.ajouter-fournisseur')
                        <a href="{{ route('bon-commandes.create') }}" class="btn btn-dark float-end petit_bouton"> <i
                                class="bi bi-plus-circle"></i> Ajouter une
                            programmation</a>
                    @endcan

            </div>
        </div><!-- End Page +++ -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
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
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">Liste des programmations d'achats non Validées</h5>
                                <span class="badge rounded-pill bg-dark">{{ count($bons) }} Prog en attente au
                                    total</span>
                            </div>
                            <table id="example"
                                class="table table-bordered border-warning  table-hover table-warning table-sm">
                                <thead>
                                    <tr>
                                        <th>N°</th>
                                        <th>
                                            Date de programmation
                                        </th>
                                        <th>Référence</th>
                                        <th>Auteur</th>
                                        <th>Statut</th>
                                        <th>Date de Création</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    @forelse ($bons as $bon)
                                        <?php $i++; ?>
                                        <tr>
                                            <td>{{ $i }} </td>
                                            <td>{{ $bon->date_bon_cmd->locale('fr_FR')->isoFormat('ll') }}</td>
                                            <td>{{ $bon->reference }}</td>
                                            <td>{{ $bon->createur->name ?? '' }}</td>
                                            <td>
                                                @if (is_null($bon->valideur_id))
                                                    <span
                                                        class="badge rounded-pill text-bg-warning">{{ $bon->statut }}</span>
                                                @else
                                                    <span
                                                        class="badge rounded-pill text-bg-success">{{ $bon->statut }}</span>
                                                @endif
                                                <!-- <span class="badge rounded-pill text-bg-warning">{{ $bon->statut }}</span> -->
                                            </td>
                                            <td>{{ $bon->created_at ?? '' }}</td>

                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-dark dropdown-toggle btn-small" type="button"
                                                        id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        <i class="bi bi-gear"></i>
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                        @can('programmations-achat.voir-bon-commande')
                                                            <li>
                                                                <a href="{{ route('bon-commandes.show', $bon->id) }}"
                                                                    class="dropdown-item" data-bs-toggle="tooltip"
                                                                    data-bs-placement="left" data-bs-title="Voir détails">
                                                                    Détails du Bon de Commande</a>
                                                            </li>
                                                        @endcan

                                                        @if (is_null($bon->valideur_id))
                                                            <li>
                                                                @can('programmations-achat.modifier-bon-commande')
                                                                    <a href="{{ route('bon-commandes.edit', $bon->id) }}"
                                                                        class="dropdown-item text-warning"
                                                                        data-bs-toggle="tooltip" data-bs-placement="left"
                                                                        data-bs-title="Modifier le bon"> Modifier le Bon de
                                                                        Commande</a>
                                                                @endcan
                                                            </li>
                                                            <li>
                                                                @can('programmations-achat.delete-bon-commande')
                                                                    <form
                                                                        action="{{ route('bon-commandes.destroy', $bon->id) }}"
                                                                        class="form-inline" method="POST"
                                                                        onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet bon de commande?');">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="dropdown-item text-danger"
                                                                            data-bs-toggle="tooltip" data-bs-placement="left"
                                                                            data-bs-title="Supprimer le bon ">Supprimer le Bon
                                                                            de Commande</button>
                                                                    </form>
                                                                @endcan
                                                            </li>
                                                        @endif

                                                    </ul>
                                                </div>

                                                {{-- @if (is_null($bon->valideur_id) || !in_array($bon->id, $arrayIds)) --}}
                                                <!-- @if (is_null($bon->valideur_id))
    @can('programmations-achat.modifier-bon-commande')
        <a href="{{ route('bon-commandes.edit', $bon->id) }}"
                                                                    class="btn btn-warning" data-bs-toggle="tooltip"
                                                                    data-bs-placement="top" data-bs-title="Modifier le bon"> <i
                                                                        class="bi bi-pencil"></i> </a>
    @endcan

                                                        @can('programmations-achat.delete-bon-commande')
        <form action="{{ route('bon-commandes.destroy', $bon->id) }}"
                                                                    class="form-inline" method="POST"
                                                                    onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet bon de commande?');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger"
                                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                                        data-bs-title="Supprimer bon de commande"><i
                                                                            class="bi bi-trash"></i></button>
                                                                </form>
    @endcan
    @endif -->

                                            </td>

                                        </tr>

                                    @empty
                                        <tr>Aucun bon de commande enregistré</tr>
                                    @endforelse
                                </tbody>
                            </table>

                        </div>
                    </div>

                </div>
            </div>
        </section>
        <script>
            new DataTable('#tableBonCommande', {
                sorting: [
                    [2, 'asc'],
                    [1, 'asc']
                ],
                order: [
                    [2, 'asc'],
                    [1, 'asc']
                ],
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
        </script>
    </main>

@endsection
