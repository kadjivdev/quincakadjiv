@extends('layout.template')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle d-flex">
            <div class="col-6">
                <h1 class="float-left">Approvisionnements </h1>
            </div>
            <div class="col-6 justify-content-end">

                @can('livraisons.ajouter-livraison-frs')
                <div class="">
                    <a href="{{ route('annulation-approvisionnement.index') }}" class="btn btn-primary float-end"> Demande d'annulation</a>
                </div>


                    <div class="">
                        <a href="{{ route('livraisons.create') }}" class="btn btn-primary float-end"> + Nouvelle livraison</a>
                    </div>
                @endcan
            </div>
        </div>

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
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Liste des approvisionnements</h5>

                            <!-- Table with stripped rows -->
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>N°</th>
                                        <th>Réf Commande</th>
                                        <th>Article</th>
                                        <th>Date livraison</th>
                                        <th>Qté livrée</th>
                                        {{-- <th>Prix unitaire</th> --}}
                                        <th>Lieu livraison </th>
                                        <th>Action </th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($appros as $appro)
                                        @php
                                            $dateLivraison = Carbon\Carbon::parse($appro->date_livraison);
                                            $formattedDate = $dateLivraison->locale('fr_FR')->isoFormat('ll');
                                        @endphp
                                        <tr>
                                            <td>{{ $i++ }} </td>
                                            <td>{{ $appro->ref_commande }}</td>
                                            <td>{{ $appro->article_nom }}</td>
                                            <td>{{ $formattedDate }}</td>
                                            <td>{{ $appro->qte_livre }} ({{ $appro->unite }})</td>
                                            {{-- <td>{{ $appro->prix_unit }}</td> --}}
                                            <td>{{ $appro->magasin }}</td>
                                            <td>
                                                @can('livraisons.modifier-appro')
                                                    @if (is_null($appro->validated_at))
                                                        <div class="dropdown">
                                                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="bi bi-gear"></i>
                                                            </button>
                                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                                <li>
                                                                    <a data-bs-target="#staticBackdrop{{ $appro->id }}" data-bs-toggle="modal" class="dropdown-item" data-bs-placement="left" data-bs-title="Valider la livraison">Valider la livraison </a>
                                                                </li>
                                                                <li>
                                                                    <form action="{{ route('livraisons.destroy', $appro->id) }}"
                                                                        class="form-inline" method="POST"
                                                                        onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette livraison ?');">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="dropdown-item"
                                                                            data-bs-toggle="tooltip" data-bs-placement="left"
                                                                            data-bs-title="Supprimer">Supprimer la livraison</button>
                                                                    </form>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    @endif
                                                @endcan
                                            </td>


                                        </tr>

                                        @include('pages.achats-module.approvisionnements.edit')
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
