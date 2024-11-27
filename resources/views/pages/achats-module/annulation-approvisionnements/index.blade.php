@extends('layout.template')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle d-flex">
            <div class="col-6">
                <h1 class="float-left">Rapport des Approvisionnements </h1>
            </div>

        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title text-dark">Liste des approvisionnements</h5>

                            <!-- Table with stripped rows -->
                            <table id="example" class="table border-warning table-hover table-sm table-striped" >
                                <thead class="table-dark">
                                    <tr>
                                        <th>N°</th>
                                        <th>Date</th>
                                        <th>Référence</th>
                                        <th>Cout de Revient</th>
                                        <th>Chauffeur</th>
                                        <th>Véhicule</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php $i = 0?>
                                @forelse ($livraisons as $liv)
                                <?php $i++ ?>

    <tr>
        <td>{{ $i }}</td>
        <td>{{ $liv->date_liv }}</td>
        <td>{{ $liv->ref_liv }}</td>
        <td>{{ $liv->cout_revient }}</td>
        <td>{{ $liv->nom_chauf }}</td>
        <td>{{ $liv->num_vehicule }}</td>
        <td>
            @can('livraisons.modifier-appro')
                <div class="dropdown">
                    <button class="btn btn-sm w-100 bg-dark text_orange dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-gear"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <li class="">
                            <a href="{{route('annulation-approvisionnement.show', $liv->id)  }}" class="btn btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Voir détails"><i class="bi bi-list"></i> Détails du Bon </a>
                        </li>
                        <li>
                            <form
                                class="form-inline" method="POST"
                                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette livraison ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item"
                                    data-bs-toggle="tooltip" data-bs-placement="left"
                                    data-bs-title="Supprimer"><i class="bi bi-trash3"></i> Supprimer la livraison</button>
                            </form>
                        </li>
                    </ul>
                </div>
        @endcan


                                                </td>
    </tr>
@empty
    <tr><td colspan="3">Aucune commande avec livraison enregistrée</td></tr>
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
