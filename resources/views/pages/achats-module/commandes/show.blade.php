@extends('layout.template')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle d-flex">

            <div class="col-6">

                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Tableau de Bord</a></li>
                        <li class="breadcrumb-item active">Détails du bon de commande du
                            {{ Carbon\Carbon::parse($bon->date_cmd)->locale('fr_FR')->isoFormat('ll') }}</li>
                    </ol>
                </nav>

            </div>


            <div class="col-2 ">

                <div class="">
                    @if ($bon->valideur_id)
                        @if ($nombre_commande == 0)
                            @can('bon-commandes.valider-commande')
                                <button type="button" class="btn btn-danger float-end petit_bouton" id="cancelbtn"
                                    data-bon-id="{{ $bon->id }}" data-bs-toggle="modal" data-bs-target="#cancelV">
                                    <i class="bi bi-x-circle"></i>
                                    Annuler la validation
                                </button>
                            @endcan
                        @else
                            <span class="text-danger">Bon déjà utilisé ailleur</span>
                        @endif
                    @endif
                </div>
            </div>


            <div class="col-2 ">
                <div class="">

                    @if (is_null($bon->validator_id))
                        @can('bon-commandes.valider-commande')
                            <button type="button" class="btn btn-sm btn-light float-end petit_bouton" id="confirmationbtn"
                                data-bon-id="{{ $bon->id }}" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                <i class="bi bi-check-circle-fill"></i>
                                Valider la Commande
                            </button>
                        @endcan
                    @endif

                </div>
            </div>


            <div class="col-2">
                <div class="">
                    <a href="{{ route('commandes.index') }}" class="btn btn-sm btn-dark text_orange petit_bouton float-end mx-2">
                        <i class="bi bi-arrow-left"></i> Retour</a>
                </div>

            </div>
        </div><!-- End Page +++ -->



        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">


                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title text-dark">Liste des articles du bon <span
                                        class="badge bg-dark text-white">{{ $bon->reference }}</span>
                                </h5>

                                @can('bon-commandes.ajouter-cmde-sup')
                                    <div class="">
                                        <a href="{{ route('supplement-create', $bon->id) }}"
                                            class="btn btn-sm btn-dark text_orange float-end petit_bouton">
                                            <i class="bi bi-plus-circle"></i>
                                            Ajouter un supplément</a>
                                    </div>
                                @endcan
                            </div>


                            <table id="dataTable"
                                class="table table-bordered border-warning  table-hover table-striped table-sm">

                                <thead class="table-dark">
                                    <tr>
                                        <th>N°</th>
                                        <th>
                                            Article
                                        </th>
                                        <th>Quantité</th>
                                        <th>PU</th>
                                        <th>Unité de mesure</th>
                                        <th>Montant</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($lignes as $article)
                                        <tr>
                                            <td>{{ $article->id }} </td>
                                            <td>{{ $article->nom }}</td>
                                            <td>{{ $article->quantity }}</td>
                                            <td>{{ $article->prix_unit }}</td>
                                            <td>{{ $article->unite }}</td>
                                            <td>{{ number_format((float) $article->prix_unit * (float) $article->quantity, 2, ',', ' ') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{-- <h5 class="card-title">Total HT du Bon de commande : <b style="font-size:30px; text-align:center; font-weight:bolder; background-color: rgba(13, 255, 97, 0.79);"> <?php echo number_format($total_ht, 2, ',', ' '); ?>  </b> FCFA</h5>
                        <h5 class="card-title">Total AIB du Bon de commande : <b style="font-size:30px; text-align:center; font-weight:bolder; background-color: rgba(13, 255, 97, 0.79);"> <?php echo number_format($aib, 2, ',', ' '); ?>  </b> FCFA</h5>
                        <h5 class="card-title text-dark">Total TVA du Bon de commande : <b style="font-size:30px; text-align:center; font-weight:bolder; background-color: rgba(13, 255, 97, 0.79);"> <?php echo number_format($tva, 2, ',', ' '); ?>  </b> FCFA</h5> --}}
                            <h5 class="card-title">Total TTC du Bon de commande : <b class="bg-dark text_orange"
                                    style="font-size:30px; text-align:center; font-weight:bolder;">
                                    <?php echo number_format($total_ttc, 2, ',', ' '); ?> </b> FCFA</h5>

                        </div>
                    </div>
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

                                    <p>Voulez vous vraiment valider ce bon de commande?</p>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button>
                                    <button type="button" class="btn btn-primary" id="confirmValidation">Oui</button>
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
                    url: apiUrl + '/valider-bon/' + bonId,
                    type: 'GET',
                    success: function(response) {
                        window.location.href = response.redirectUrl;
                        $('#exampleModal').modal('hide');
                    },
                    error: function(error) {
                        // La requête a échoué, vous pouvez gérer l'erreur ici
                        console.error('Erreur lors de la validation du bon:', error);
                    }
                });
                $('#exampleModal').modal('hide');
            });
        });
    </script>
@endsection
