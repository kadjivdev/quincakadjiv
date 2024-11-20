@extends('layout.template')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle d-flex">
            <div class="col-6">
                <h1 class="float-left">Détails de l'inventaire du
                    {{ $invent->date_inventaire->locale('fr_FR')->isoFormat('ll') }} </h1>
            </div>
            <div class="col-6 justify-content-end">
                @if (is_null($invent->validator_id))
                    @can('rapports.valider-inventaire')
                        <button type="button" class="btn btn-primary float-end" id="confirmationbtn"
                            data-invent-id="{{ $invent->id }}" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            Valider inventaire
                        </button>
                    @endcan
                @endif
                <a href="{{ route('inventaires.index') }}" class="btn btn-success float-end mx-2"> <i
                        class="bi bi-arrow-left"></i> Retour</a>

            </div>
        </div><!-- End Page +++ -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Liste des articles du détail</h5>

                            <table id="dataTable" class="table datatable">
                                <thead>
                                    <tr>
                                        <th>N°</th>
                                        <th>
                                            Article
                                        </th>
                                        <th>Qté stock</th>
                                        <th>Qté réelle</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($lignes as $article)
                                        <tr>
                                            <td>{{ $i++ }} </td>
                                            <td>{{ $article->nom }}</td>
                                            <td>{{ $article->qte_stock }}</td>
                                            <td>{{ $article->qte_reel }}</td>
                                        </tr>
                                    @empty
                                        <tr>Aucun detail enregistré</tr>
                                    @endforelse
                                </tbody>
                            </table>

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

                                    <p>Voulez vous vraiment valider cet inventaire?</p>

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
        console.log('on est ici');
        $(document).ready(function() {
            $('#confirmValidation').click(function() {
                console.log('ok');
                var inventId = $('#confirmationbtn').data('invent-id');
                console.log(inventId, 'id inventaire');

                $.ajax({
                    url: apiUrl + '/valider-inventaire/' + inventId,
                    type: 'GET',
                    success: function(response) {
                        window.location.href = response.redirectUrl;
                        $('#exampleModal').modal('hide');
                    },
                    error: function(error) {
                        console.error('Erreur lors de la validation de l\'inventaire :',
                            error);
                        window.location.href = response.redirectUrl;
                        $('.alert-danger').text(error.message);
                        $('#exampleModal').modal('hide');

                    }
                });
                $('#exampleModal').modal('hide');
            });
        });
    </script>
@endsection
