@extends('layout.template')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle d-flex">
            <div class="col-6">
                <h1 class="float-left">Livraisons </h1>
            </div>
            <div class="col-6 justify-content-end">
                @can('livraisons.ajouter-livraison-client')
                    <div class="">
                        <a href="{{ route('deliveries.create') }}" class="btn btn-primary float-end"> + Nouvelle livraison</a>
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
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Liste des Bon de livraisons clients</h5>

                            <!-- Table with stripped rows -->
                            <table id="example" class=" table table-bordered border-warning  table-hover table-warning table-sm">

                                <thead>
                                    <tr>
                                        <th>N°</th>
                                        <th>Date</th>
                                        <th>Réf. Bon</th>
                                        <th>Rèf. Devis</th>
                                        <th>Chauffeur</th>
                                        <th>Véhicule </th>
                                        <th>Adr. Livraison </th>
                                        <th>Créer le </th>
                                        <th>Status</th>
                                        <th>Action</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($bon_liv_clt as $livraison)
                                        @php
                                            $dateLivraison = Carbon\Carbon::parse($livraison->date_livraison);
                                            $formattedDate = $dateLivraison->locale('fr_FR')->isoFormat('ll');
                                        @endphp
                                        <tr>
                                            <td>{{ $livraison->id }} </td>
                                            <td>{{ $formattedDate }}</td>
                                            <td>{{ $livraison->code_bon }}</td>
                                            <td>{{ $livraison->devis_reference }}</td>
                                            <td>{{ $livraison->nom_chauf }}</td>
                                            <td>{{ $livraison->num_vehicule }}</td>
                                            <td>{{ $livraison->adr_livraison }}</td>
                                            <td>{{ $livraison->created_at }}</td>
                                            <td>
                                                @if ($livraison->lignes_non_valides > 0 && $livraison->lignes_comment > 0)
                                                <span class="badge rounded-pill text-bg-danger">Rejeter</span>
                                                @endif

                                                @if ($livraison->lignes_non_valides > 0 && $livraison->lignes_comment == 0)
                                                <span class="badge rounded-pill text-bg-warning">En Cours</span>
                                                @endif
</td>
                                            <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                                            id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                            aria-expanded="false">
                                                            <i class="bi bi-gear"></i>
                                                        </button>
                                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">


                                                            {{-- @can('livraison.valider-livraison-vente') --}}

                                                            <li>
                                                                <a href="{{ route('deliveries.show', $livraison->id) }}"
                                                                    class="dropdown-item text-dark"> Détail du Bon</a>
                                                            </li>

                                                                @if ($livraison->lignes_non_valides == $livraison->lignes_non_valides + $livraison->lignes_valides)


                                                                    <li>
                                                                        <form action="{{ route('deliveries.destroy', $livraison->id) }}"
                                                                            class="form-inline" method="POST"
                                                                            onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette livraison client?');">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" class="dropdown-item text-danger"
                                                                                data-bs-toggle="tooltip" data-bs-placement="left"
                                                                                data-bs-title="Supprimer le bon ">Supprimer le Bon</button>
                                                                        </form>

                                                                    </li>
                                                                @endif
                                                            {{-- @endcan --}}

                                                        </ul>
                                                    </div>

                                                </td>
                                        </tr>
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
