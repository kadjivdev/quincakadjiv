@extends('layout.template')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle d-flex">
            <div class="col-6">
                <h1 class="float-left text-dark">Livraisons non physiques</h1>
            </div>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body py-1">
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
                            <h5 class="card-title text-dark">Liste des livraisons directes</h5>

                            <!-- Table with stripped rows -->
                            <table id="example"
                                class=" table table-bordered border-warning  table-hover table-striped table-sm">
                                <thead class="table-dark">
                                    <tr>
                                        <th>N°</th>
                                        <th>Article</th>
                                        <th>Qté livrée</th>
                                        {{-- <th>Prix unitaire</th> --}}
                                        <th>Client </th>
                                        <th>Date livraison</th>
                                        <th>Action</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($livraisons as $livraison)
                                        @php
                                            $dateLivraison = Carbon\Carbon::parse($livraison->date_livraison);
                                            $formattedDate = $dateLivraison->locale('fr_FR')->isoFormat('ll');
                                        @endphp
                                        <tr>
                                            <td>{{ $i++ }} </td>
                                            <td>{{ $livraison->article_nom }}</td>
                                            <td>{{ $livraison->qte_livre }} ({{ $livraison->unite }})</td>
                                            {{-- <td>{{ $livraison->prix_vente }}</td> --}}
                                            <td>{{ $livraison->nom_client }}</td>
                                            <td>{{ $formattedDate }}</td>
                                            <td>
                                                @if (is_null($livraison->validated_at))
                                                    @can('livraisons.valider-livraison-directe')
                                                        <a class="btn btn-sm bg-dark text_orange" data-bs-toggle="modal"
                                                            data-bs-target="#validationModal{{ $livraison->id }}"> <i
                                                                class="bi bi-check"></i> </a>
                                                    @endcan
                                                @endif
                                            </td>

                                            <!-- Modal -->
                                            <div class="modal fade" id="validationModal{{ $livraison->id }}"
                                                data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                                                aria-labelledby="validationModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form action="{{ route('validerLivraison', $livraison->id) }}"
                                                            method="post">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-header">
                                                                <h1 class="modal-title text-dark fs-5" id="validationModalLabel">
                                                                    Valider une livraison</h1>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                @csrf
                                                                <div class="col-12 mb-3">
                                                                    <label class="form-label">Client</label>
                                                                    <select name="client_id" class="form-select"
                                                                        id="clientSelect">
                                                                        @foreach ($clients as $client)
                                                                            <option value="{{ $client->id }}"
                                                                                {{ $livraison->client_id == $client->id ? 'selected' : '' }}>
                                                                                {{ $client->nom_client }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                <div class="col-12 mb-3">
                                                                    <input type="hidden"
                                                                        value="{{ $livraison->ligne_commande_id }}"
                                                                        name="ligne_id">
                                                                    <label class="form-label">Article vendu</label>
                                                                    <input type="text" name=""
                                                                        value="{{ $livraison->article_nom }}"
                                                                        class="form-control" readonly id="">
                                                                </div>
                                                                <div class="col-12 mb-3">
                                                                    <label class="form-label">Quantité livrée</label>
                                                                    <input type="text" class="form-control"
                                                                        value="{{ $livraison->qte_livre }}"
                                                                        name="qte_livre" readonly>
                                                                </div>

                                                                <div class="col-12 mb-3">
                                                                    <label class="form-label">Prix de vente</label>
                                                                    <input type="hidden"
                                                                        value="{{ $livraison->qte_livre }}"
                                                                        name="qte_livre">
                                                                    <input type="number" min="{{ $livraison->prix_unit }}"
                                                                        max="{{ $livraison->prix_vente }}"
                                                                        name="prix_vente" class="form-control"
                                                                        value="{{ $livraison->prix_vente }}">
                                                                </div>

                                                                <div class="col-12 mb-3">
                                                                    <label class="form-label">Montant facture</label>
                                                                    <input type="text" readonly name="montant_facture"
                                                                        value="{{ $livraison->prix_vente * $livraison->qte_livre }} "
                                                                        class="form-control">
                                                                </div>
                                                                <div class="col-12 mb-3">
                                                                    <label class="form-label">Acompte</label>
                                                                    <input type="number" min="1"
                                                                        name="montant_regle" class="form-control"
                                                                        value="">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12 d-flex flex-row align-items-center justify-content-between">
                                                                <button type="submit" class="btn btn-sm btn-dark text_orange w-100 submitBtn"><i class="bi bi-check-circle" id="ajouterArticle"></i> Enregistrer</button>
                                                                <button type="button" class="btn btn-sm btn-dark text_orange w-100 loadingBtn" hidden><span class="spinner-border spinner-border-sm text_orange loading"></span> En cours ...</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </tr>
                                    @empty
                                        <tr>Aucune Livraison non physique enregistrée</tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <!-- End Table with stripped rows -->

                        </div>
                    </div>
                    <script src="{{ asset('assets/js/jquery3.6.min.js') }}"></script>
                    <script>
                        function calculateAndDisplayMontant() {
                            var prixVente = parseFloat($('input[name="prix_vente"]').val()) || 0;
                            var montantRegle = parseFloat($('input[name="qte_livre"]').val()) || 0;
                            var montantFacture = prixVente * montantRegle;
                            $('input[name="montant_facture"]').val(montantFacture.toFixed(2));
                        }

                        // Appeler la fonction après le chargement initial de la page
                        $(document).ready(function() {
                            calculateAndDisplayMontant();
                        });

                        // Détecter les changements dans les champs de quantité et de prix de vente
                        $('input[name="prix_vente"], input[name="qte_livre"]').on('input', function() {
                            calculateAndDisplayMontant();
                        });
                    </script>
                </div>
            </div>
        </section>

    </main>
@endsection
