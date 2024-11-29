@extends('layout.template')
@section('content')
    <main id="main" class="main">
        <div class="pagetitle d-flex">
            <div class="col-6">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Tableau de Bord</a></li>
                        <li class="breadcrumb-item active">Détails du Bon du
                            {{ $bon->date_bon_cmd->locale('fr_FR')->isoFormat('ll') }}</li>
                    </ol>
                </nav>
            </div>


            <div class="col-2 ">
                <div class="">
                    @if ($bon->valideur_id)
                        @if ($nombre_commande == 0)
                            @can('programmations-achat.valider-bon-commande')
                                <button type="button" class="btn btn-dark text_orange float-end petit_bouton" id="cancelbtn"
                                    data-bon-id="{{ $bon->id }}" data-bs-toggle="modal" data-bs-target="#cancelV">
                                    <i class="bi bi-x-circle"></i>
                                    Annuler la validation
                                </button>
                            @endcan
                        @else
                            <span class="text_orange">Prog déjà passée en Commande</span>
                        @endif
                    @endif
                </div>
            </div>


            <div class="col-2 ">
                <div class="">
                    @if (is_null($bon->valideur_id))
                        @can('programmations-achat.valider-bon-commande')
                            <button type="button" class="btn btn-warning float-end petit_bouton" id="confirmationbtn"
                                data-bon-id="{{ $bon->id }}" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                <i class="bi bi-check-circle-fill"></i>
                                Valider programmation
                            </button>
                        @endcan
                    @endif

                </div>
            </div>

            <div class="col-2">
                <div class="">
                    <a href="{{ route('liste-valider') }}" class="btn btn-dark text_orange petit_bouton float-end mx-2">
                        <i class="bi bi-arrow-left"></i> Retour</a>
                </div>
            </div>
        </div><!-- End Page +++ -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Liste des articles de la programmation</h5>

                            <table id="example"
                                class="table table-bordered border-warning  table-hover table-striped table-sm">
                                <thead class="table-dark">
                                    <tr>
                                        <th width="3%">N°</th>
                                        <th width="60%">
                                            Article
                                        </th>
                                        <th width="17%">Quantité</th>
                                        <th width="20%">Unité de mesure</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($lignes as $article)
                                        <tr>
                                            <td>{{ $i++ }} </td>
                                            <td>{{ $article->nom }}</td>
                                            <td>{{ $article->qte_cmde }}</td>
                                            <td>{{ $article->unite }}</td>
                                        </tr>
                                    @empty
                                        <tr>Aucun bon de commande enregistré</tr>
                                    @endforelse
                                </tbody>
                            </table>

                        </div>
                    </div>

                    {{-- modal de confirmation de validation  --}}
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Confirmer la validation</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">

                                    <p>Voulez vous vraiment valider cette programmation d'achat?</p>

                                </div>
                                <div class="modal-footer">
                                    <div class="col-lg-12 d-flex flex-row align-items-center justify-content-between">
                                        <button type="submit" class="btn btn-sm btn-dark text_orange w-100 submitBtn" id="confirmValidation"><i class="bi bi-check-circle"></i> Enregistrer</button>
                                        <button type="button" class="btn btn-sm btn-dark text_orange w-100 loadingBtn" hidden><span class="spinner-border spinner-border-sm text_orange loading"></span> En cours ...</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- modal d'annulation de la validation --}}

                    <div class="modal fade" id="cancelV" tabindex="-1" aria-labelledby="cancelVLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Confirmer l'annulation de la
                                        validation</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">

                                    <p>Voulez vous vraiment annuler la validation de cette programmation d'achat?</p>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button>
                                    <button type="button" class="btn btn-primary" id="cancelValidation">Oui</button>
                                </div>
                            </div>
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
            $('#confirmValidation').click(function() {
                var bonId = $('#confirmationbtn').data('bon-id');

                $.ajax({
                    url: apiUrl + '/valider/' + bonId,
                    type: 'GET',
                    success: function(response) {
                        window.location.href = response.redirectUrl;
                        $('#exampleModal').modal('hide');
                    },
                    error: function(error) {
                        // La requête a échoué, vous pouvez gérer l'erreur ici
                        console.error('Erreur lors de la validation de la programmation:',
                            error);
                    }
                });
                $('#exampleModal').modal('hide');
            });

            $('#cancelValidation').click(function() {
                var bonId = $('#cancelbtn').data('bon-id');

                $.ajax({
                    url: apiUrl + '/cancel-valider/' + bonId,
                    type: 'GET',
                    success: function(response) {
                        window.location.href = response.redirectUrl;
                        $('#exampleModal').modal('hide');
                    },
                    error: function(error) {
                        // La requête a échoué, vous pouvez gérer l'erreur ici
                        console.error('Erreur lors de la validation de la programmation:',
                            error);
                    }
                });
                $('#cancelV').modal('hide');
            });
        });
    </script>
@endsection
