@extends('layout.template')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle d-flex">
            <div class="col-4">
                <small>Bon de Livraison</small>
                <h1 class="float-left">{{ $bon_data->code_bon }}</h1>
            </div>

            <div class="col-4">
                <small>Référence du Devis</small>
                <h1 class="float-left">{{ $bon_data->reference }}</h1>
            </div>

            <div class="col-4">
                <small>Adresse de livraison</small>
                <h1 class="float-left">{{ $bon_data->adr_livraison }}</h1>
            </div>

            <div class="col-6 justify-content-end">
                @can('livraisons.ajouter-livraison-client')
                    <div class="">
                        <a href="{{ route('deliveries.create') }}" class="btn btn-sm bg-dark text_orange float-end"> + Nouvelle livraison</a>
                    </div>
                @endcan
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

                            <div class="row">
                                <div class="col-6 float-left">
                                    <h5 class="card-title text-dark">
                                        Liste des articles du bon de livraisons</h5>
                                </div>

                                <div class="col-6 float-right">

                                    <a href="{{ route('deliveries.index') }}" class="btn btn-sm bg-dark text_orange float-end mt-2"> <i
                                            class="bi bi-arrow-left"></i> Retour</a>
                                </div>
                            </div>

                            <!-- Table with stripped rows -->
                            <table id="example"
                                class=" table table-bordered border-warning  table-hover table-striped table-sm">

                                <thead class="table-dark">
                                    <tr>
                                        <th>N°</th>
                                        <th>Article</th>
                                        <th>Date livraison</th>
                                        <th>Qté livrée</th>
                                        <th>Prix unitaire</th>
                                        <th>Client </th>
                                        <th>Chauffeur </th>
                                        <th>Camion </th>
                                        <th>Enregistrer le </th>
                                        <th>Raison Rejet </th>
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
                                            <td>{{ $formattedDate }}</td>
                                            <td>{{ $livraison->qte_livre }} ({{ $livraison->unite }})</td>
                                            <td>{{ $livraison->prix_unit }}</td>
                                            <td>{{ $livraison->nom_client }}</td>
                                            <td>{{ $livraison->nom_chauf }}</td>
                                            <td>{{ $livraison->num_vehicule }}</td>
                                            <td>{{ $livraison->created_at }}</td>
                                            <td style="background-color: lightcoral">{{ $livraison->comment }}</td>
                                            <td >
                                                @if (is_null($livraison->validated_at))
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm bg-dark w-100 text_orange dropdown-toggle" type="button"
                                                            id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                            aria-expanded="false">
                                                            <i class="bi bi-gear"></i>
                                                        </button>
                                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">

                                                            {{-- @can('livraison.valider-livraison-vente') --}}

                                                            <li>
                                                                <form
                                                                    action="{{ route('validation-liv-clt', $livraison->id) }}"
                                                                    class="form-inline" method="POST"
                                                                    onsubmit="return confirm('Êtes-vous sûr de vouloir valider cette livraison?');">
                                                                    @csrf
                                                                    @method('POST')
                                                                    <button type="submit"
                                                                        class="dropdown-item text-success"
                                                                        data-bs-toggle="tooltip" data-bs-placement="left"
                                                                        data-bs-title="Valider la livraison"><i class="bi bi-check-circle"></i> Valider</button>
                                                                </form>

                                                            </li>

                                                            <li>
                                                                <a class="dropdown-item text-danger" style="cursor: pointer" data-bs-toggle="modal"
                                                                    data-bs-target="#staticBackdrop{{ $livraison->id }}">
                                                                    <i class="bi bi-trash3"></i> Rejeter </a>
                                                            </li>

                                                            <li>
                                                                <form action="{{ route('delete-liv', $livraison->id) }}"
                                                                    class="form-inline" method="POST"
                                                                    onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette livraison client?');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="dropdown-item text-danger"
                                                                        data-bs-toggle="tooltip" data-bs-placement="left"
                                                                        data-bs-title="Supprimer le bon "><i class="bi bi-trash3"></i> Supprimer cette Livraison</button>
                                                                </form>
                                                            </li>




                                                            {{-- @endcan --}}

                                                        </ul>
                                                    </div>
                                                @endif

                                                @if (!is_null($livraison->validated_at))
                                                    <span class="badge rounded-pill text-bg-success">Validée</span>
                                                @endif
                                            </td>
                                        </tr>

                                        <div class="modal fade" id="staticBackdrop{{ $livraison->id }}" data-bs-keyboard="false" tabindex="-1"
                                            aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('rejeter-liv-clt', $livraison->id) }}"
                                                        method="post">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Ajouter un
                                                                Commentaire</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            @csrf
                                                            <div class="col-12">
                                                                <label for="" class="form-label text-danger">Raison de votre rejet</label>
                                                                <br>
                                                                <textarea class="form-control" style="text-align: left; padding:10px;" name="comment" id="" cols="30" rows="10">{{ trim($livraison->comment) }}</textarea>

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

                                    @empty
                                        <tr>Aucune Livraison enregistrée</tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <!-- End Table with stripped rows -->

                        </div>
                    </div>

                </div>
            </div>
        </section>
    </main>
@endsection
