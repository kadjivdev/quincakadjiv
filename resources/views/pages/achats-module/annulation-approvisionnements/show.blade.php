@extends('layout.template')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle d-flex">
            <div class="col-10">
                <h1 class="float-left ">Détail du Bon de Livraison {{$liv->first()?$liv->first()->ref_liv:"---"}} </h1>
            </div>

            <div class="col-10">
            <a href="{{ url('/rapport_livraison_frs') }}" class="btn btn-sm bg-dark text_orange"
                                                        data-bs-toggle="tooltip" data-bs-placement="Retour a la liste"
                                                        data-bs-title="Voir détails"> Retour </a>
            </div>

        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title text-dark">Liste des articles livrés</h5>

                            <!-- Table with stripped rows -->
                            <table id="example" class="table table-bordered border-warning  table-hover table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>N°</th>
                                        <th>Article</th>
                                        <th>Qte Livrée</th>
                                        <th>Unité</th>
                                        <th>Réf Commande</th>
                                        <th>Validé le</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php $i = 0?>
                                @forelse ($liste_appro as $liv)
                                <?php $i++ ?>

    <tr>
        <td>{{ $i }}</td>
        <td>{{ $liv->nom }}</td>
        <td>{{ $liv->qte_livre }}</td>
        <td>{{ $liv->unite }}</td>
        <td>{{ $liv->ref_cmd }}</td>
        <td>{{ $liv->liv_at }}</td>

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
