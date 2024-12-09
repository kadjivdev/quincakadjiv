@extends('layout.template')
@section('content')
<main id="main" class="main">
    <style>
        /* CSS for the selectable div */
        .selectable-div {
            position: relative;
            border: none;
            border-radius: 3px;
            padding: 10px;
            margin: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        /* CSS for the overlay */
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.1);
            /* Dark semi-transparent overlay */
            opacity: 0;
            /* Initially hidden */
            transition: opacity 0.3s ease;
            pointer-events: none;
            /* Allows clicks to go through the overlay to the underlying div */
        }

        /* Show the overlay on hover */
        .selectable-div:hover .overlay {
            opacity: 1;
        }
    </style>

    <div class="pagetitle d-flex">
        <div class="col-6">
            <h1 class="float-left">Compte du client {{ $client->nom_client }} </h1>
        </div>
        <div class="col-4 mb-3">
            <button type="button" class="btn btn-sm bg-dark text_orange float-end mt-3" id="confirmationbtn" data-bs-toggle="modal" data-bs-target="#clientListModal">
                Tout les clients
            </button>
        </div>


        <div class="modal fade" id="clientListModal" tabindex="-1" role="dialog" aria-labelledby="clientModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="clientModalLabel">Selectionner un client</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        @forelse ($clients as $client)
                        <a href="{{route('clients.show', $client->id)}}">
                            <div class="selectable-div text-dark">
                                {{ $client->nom_client }}
                                <div class="overlay"></div>
                            </div>
                        </a>
                        @empty
                        <div>Aucun client enregistré</div>
                        @endforelse

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <h5 class="text-dark"> Rapports des Factures Simples</h5>
        <div class="col-4">
            <div class="card border_orange shadow shadow-lg" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title ">{{ number_format($total_du, 2, ',', ' ') }} </h5>
                    <h6 class="card-subtitle mb-2 text-body-secondary">Montant total</h6>
                </div>
            </div>
        </div>

        <div class="col-4">
            <div class="card border_orange shadow shadow-lg" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title">{{ number_format($total_solde, 2, ',', ' ') }} </h5>
                    <h6 class="card-subtitle mb-2 text-body-secondary">Montant total règlement</h6>
                </div>
            </div>
        </div>

        <div class="col-4">
            <div class="card border_orange shadow shadow-lg" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title text-danger">{{ number_format($total_restant, 2, ',', ' ') }} </h5>
                    <h6 class="card-subtitle mb-2 text-body-secondary">Montant restant</h6>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <h5 class="text-dark"> Rapports des Factures Normalisées</h5>
        <div class="col-4">
            <div class="card border_orange shadow shadow-lg" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title ">{{ number_format($total_du1, 2, ',', ' ') }} </h5>
                    <h6 class="card-subtitle mb-2 text-body-secondary">Montant total</h6>
                </div>
            </div>
        </div>

        <div class="col-4">
            <div class="card border_orange shadow shadow-lg" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title">{{ number_format($total_solde1, 2, ',', ' ') }} </h5>
                    <h6 class="card-subtitle mb-2 text-body-secondary">Montant total règlement</h6>
                </div>
            </div>
        </div>

        <div class="col-4">
            <div class="card border_orange shadow shadow-lg" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title text-danger">{{ number_format($total_restant1, 2, ',', ' ') }} </h5>
                    <h6 class="card-subtitle mb-2 text-body-secondary">Montant restant</h6>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <h5 class="text-dark"> Solde et accompte</h5>
        <div class="col-6">
            <div class="card border border-success" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title ">{{ number_format($avance, 2, ',', ' ') }} </h5>
                    <h6 class="card-subtitle mb-2 text-body-secondary">Accompte client</h6>
                </div>
            </div>
        </div>

        <div class="col-6">
            <div class="card border border-warning" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title">{{ number_format($solde, 2, ',', ' ') }} </h5>
                    <h6 class="card-subtitle mb-2 text-body-secondary">Solde client</h6>
                </div>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <h5 class="card-title text-dark">Liste des factures
                                </h5>
                            </div>
                            <div class="col-6 float-end py-3">
                                <button type="button" class="btn btn-sm bg-dark text_orange" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                <i class="bi bi-list"></i> Report à nouveau
                                </button>
                                @can('clients.reglements-d-un-client')
                                <a href="{{route('real-reglements-clt', $id )}}" class="btn btn-sm bg-dark text_orange float-end">
                                <i class="bi bi-eye"></i> Voir ses règlements</a>
                                @endcan
                            </div>
                        </div>

                        @if (count($factures) > 0)
                            <table id="example" class="table table-bordered border-warning  table-hover table-striped table-sm">
                                <thead class="table-dark">
                                    <tr>
                                        <th>N°</th>
                                        <th>
                                            Ref facture
                                        </th>
                                        <th>Date Facture</th>
                                        <th>Montant facture</th>
                                        <th>Montant soldé</th>
                                        <th>Observation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($factures as $facture)
                                    <tr>
                                        <td>{{ $i++ }} </td>
                                        <td>{{ $facture->num_facture }} {{ $facture->ref_livraison }} </td>
                                        <td>{{ $facture->date_facture }} {{ $facture->date_livraison }}</td>
                                        <td> {{ number_format($facture->montant_total, 2, ',', ' ') }}</td>
                                        <td>{{ number_format($facture->montant_regle, 2, ',', ' ') }}</td>
                                        <td>
                                            @if (substr($facture->num_facture, 0, 2) == 'FO')
                                            Report à nouveau
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <div class="alert alert-danger text-center py-3 " role="alert">
                                            Aucune facture disponible actuellement
                                        </div>
                                    </tr>
                                    @endforelse

                                </tbody>
                            </table>
                        @else
                            <div class="alert alert-danger text-center py-3 " role="alert">
                                Aucune facture disponible actuellement
                            </div>
                        @endif

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('report-nouveau') }} " method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Formulaire de report</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="col-12 mb-3">
                            <label for="inputNanme4" class="form-label">Fichier excel</label>
                            <input type="file" class="form-control" name="upload">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col-lg-12 d-flex flex-row align-items-center justify-content-between">
                            <button type="submit" class="btn btn-sm btn-dark text_orange w-100 submitBtn"><i class="bi bi-check-circle"></i> Enregistrer</button>
                            <button type="button" class="btn btn-sm btn-dark text_orange w-100 loadingBtn" hidden><span class="spinner-border spinner-border-sm text_orange loading"></span> En cours ...</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
<script>
    var apiUrl = "{{ config('app.url_ajax') }}";
    $(document).ready(function() {
        $('.js-client-select').select2({
            placeholder: 'Selectionner client',
            minimumInputLength: 2,
            allowAdd: true,
            ajax: {
                url: apiUrl + '/cltListAjax',
                dataType: 'json',
                data: function(params) {
                    console.log(params);
                    return {
                        term: params.term // search term
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.clients.map(function(clt) {
                            return {
                                id: clt.id,
                                text: clt.nom_client
                            };
                        })
                    };
                }
            },

        });
    });
</script>
@endsection
