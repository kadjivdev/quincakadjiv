@extends('layout.template')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle d-flex">
            <div class="col-6">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Tableau de Bord</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('fournisseurs.index') }}">Fournisseur</a></li>
                        <li class="breadcrumb-item active">Règlement</li>

                    </ol>
                </nav>

            </div>
            <div class="col-6 d-flex flex-row justify-content-end">

                <div class="">
                    @can('fournisseurs.ajouter-fournisseur')
                        <a href="{{ route('reglements.create') }}" class="btn btn-dark float-end petit_bouton text_orange"> <i
                                class="bi bi-plus-circle"></i> Ajouter un
                            Règlement</a>
                    @endcan

                </div>
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
                                <h5 class="card-title mb-0 text-dark">Liste des Règlements</h5>
                                <span class="badge rounded-pill bg-dark">{{ count($reglements) }} Règlements au
                                    total non validés</span>
                            </div>

                            <table id="example"
                                class=" table table-bordered border-warning  table-hover  table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>N°</th>
                                        <th>
                                            Code
                                        </th>
                                        <th>Date règlement</th>

                                        <th>Référence</th>
                                        <th>Montant règlement</th>
                                        <th>Fournisseur</th>
                                        <th>Type règlement</th>
                                        <th>Créé le</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>

                                    @forelse ($reglements as $reglement)
                                        <?php $i++; ?>
                                        <tr>
                                            <td>{{ $i }} </td>
                                            <td>{{ $reglement->code }}</td>
                                            <td>{{ $reglement->date_reglement->locale('fr_FR')->isoFormat('ll') }}</td>
                                            <td>{{ $reglement->reference }}</td>
                                            <td>{{ number_format($reglement->montant_regle, 2, ',', ' ') }}</td>
                                            <td>{{ $reglement->facture->fournisseur->name }}</td>
                                            <td>{{ $reglement->type_reglement }}</td>
                                            <td>{{ $reglement->created_at->locale('fr_FR')->isoFormat('lll') }}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-dark text_orange w-100 dropdown-toggle btn-small"
                                                        type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        <i class="bi bi-hand-index"></i>
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">

                                                        <li>
                                                            <a href="#" data-id="{{ $reglement->id }}"
                                                                class="dropdown-item text-dark details-button"
                                                                data-bs-toggle="modal" data-bs-placement="left"
                                                                data-bs-toggle="tooltip"
                                                                data-bs-title="Plus de détails sur le reglement"
                                                                data-bs-target="#detailsModal">
                                                                <small class="text-success" style="font-weight: bolder">Plus
                                                                    de Détails</small>
                                                            </a>


                                                        </li>

                                                        {{-- @can('fournisseurs.modifier-reglement-frs') --}}
                                                            @if (is_null($reglement->validated_at))
                                                                <li>
                                                                    <a href="{{ route('reglements.edit', $reglement->id) }}"
                                                                        class="dropdown-item text-warning"
                                                                        data-bs-toggle="tooltip" data-bs-placement="left"
                                                                        data-bs-title="Modifier reglement">
                                                                        <small class="text-warning"
                                                                            style="font-weight: bolder">Modifier
                                                                            Règlement</small>

                                                                    </a>
                                                                </li>
                                                            @endif
                                                        {{-- @endcan --}}

                                                        {{-- @can('fournisseurs.modifier-reglement-frs') --}}
                                                            @if (is_null($reglement->validated_at))
                                                                <li>
                                                                    <a href="#" data-id="{{ $reglement->id }}"
                                                                        class="dropdown-item text-dark valider-button"
                                                                        data-bs-toggle="modal" data-bs-placement="left"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-title="Plus de détails sur le reglement"
                                                                        data-bs-target="#validerModal">
                                                                        <small class="text-danger"
                                                                            style="font-weight: bolder">Valider le
                                                                            règlement</small>
                                                                    </a>
                                                                </li>
                                                            @endif
                                                        {{-- @endcan --}}
                                                    </ul>
                                                </div>

                                            </td>
                                        </tr>
                                    @empty
                                        <tr>Aucun reglement enregistré</tr>
                                    @endforelse
                                </tbody>
                            </table>

                        </div>
                    </div>

                </div>
            </div>
        </section>
    </main>


    <!-- Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalLabel">Détails du Règlement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="modalContent">
                        <!-- Loader -->
                        <div id="loader" class="text-center" style="display: none;">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Chargement...</span>
                            </div>
                            <p>Chargement des détails...</p>
                        </div>
                        <!-- Les détails de la facture seront insérés ici par AJAX -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

     <!-- Modal valider reglement-->
     <div class="modal fade" id="validerModal" tabindex="-1" aria-labelledby="validerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="validerModalLabel">Détails du Règlement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="modalValiderContent">
                        <!-- Loader -->
                        <div id="loader" class="text-center" style="display: none;">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Chargement...</span>
                            </div>
                            <p>Chargement des détails...</p>
                        </div>
                        <!-- Les détails de la facture seront insérés ici par AJAX -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Confirmer la validation</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <p>Voulez vous vraiment valider ce bon de commande?</p>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button>
                    <button type="button" class="btn btn-primary" id="confirmValidation">Oui</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            var apiUrl = "{{ config('app.url_ajax') }}";

            $('.details-button').on('click', function() {
                var reglementId = $(this).data('id'); // Obtenir l'ID de la facture

                // Afficher le loader
                $('#loader').show();
                $('#modalContent').empty(); // Vider le contenu précédent

                $.ajax({
                    url: apiUrl +  '/details-reglement/' + reglementId, // L'URL de votre fonction
                    type: 'GET',
                    success: function(response) {
                        // Masquer le loader
                        $('#loader').hide();
                        // Remplir le contenu du modal avec la réponse
                        $('#modalContent').html(response);
                    },
                    error: function(xhr) {
                        // Masquer le loader
                        $('#loader').hide();
                        // Gérer les erreurs
                        $('#modalContent').html(
                            '<p>Erreur lors du chargement des détails de la facture.</p>');
                    }
                });
            });

            $('.valider-button').on('click', function() {
                var reglementId = $(this).data('id'); // Obtenir l'ID de la facture

                // Afficher le loader
                $('#loader').show();
                $('#modalValiderContent').empty(); // Vider le contenu précédent

                $.ajax({
                    url: apiUrl +  '/valider-reglement/' + reglementId, // L'URL de votre fonction
                    type: 'GET',
                    success: function(response) {
                        // Masquer le loader
                        $('#loader').hide();
                        // Remplir le contenu du modal avec la réponse
                        $('#modalValiderContent').html(response);
                    },
                    error: function(xhr) {
                        // Masquer le loader
                        $('#loader').hide();
                        // Gérer les erreurs
                        $('#modalValiderContent').html(
                            '<p>Erreur lors du chargement des détails de la facture.</p>');
                    }
                });
            });

            $('#confirmValidation').click(function() {
        var regId = $('#confirmationbtn').data('reg-id');
        var token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: apiUrl + '/valider-post-reglement/' + regId,
            type: 'POST',
            data: {
                _token: token
            },
            success: function(response) {
                window.location.href = response.redirectUrl;
            },
            error: function(error) {
                console.error('Erreur lors de la validation du bon:', error);
            }
        });

        $('#exampleModal').modal('hide');
    });
        });
    </script>
@endsection
