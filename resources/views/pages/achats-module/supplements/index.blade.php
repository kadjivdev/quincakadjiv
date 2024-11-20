@extends('layout.template')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle d-flex">
            <div class="col-6">
                <h1 class="float-left">Commandes supplémentaires </h1>
            </div>
            <div class="col-6 justify-content-end">
                {{-- <div class="">
                    <a href="{{ route('approsSuppl.create') }}" class="btn btn-primary float-end"> + Ajouter une livraison sup</a>
                </div> --}}
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
                            <h5 class="card-title">Liste des commandes supplémentaires</h5>

                            <!-- Table with stripped rows -->
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>N°</th>
                                        <th>Reférence commande</th>
                                        <th>Date commande sup</th>
                                        <th>Fournisseur</th>
                                        <th>Action</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($commandes as $commande)
                                        <tr>
                                            <td>{{ $i++ }} </td>
                                            <td>{{ $commande->ref }} </td>
                                            <td>{{ Carbon\Carbon::parse($commande->date_cmd)->locale('fr_FR')->isoFormat('ll') }}
                                            </td>
                                            <td>{{ $commande->name }}</td>
                                            <td>
                                                @can('bon-commandes.list-cmde-sup')
                                                    <a href="{{ route('supplements.show', $commande->id) }}"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        data-bs-title="Voir détails" class="btn btn-primary"> <i
                                                            class="bi bi-eye"></i> </a>
                                                @endcan
                                            </td>

                                        </tr>
                                    @empty
                                        <tr>Aucune commande supplémentaire enregistrée</tr>
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
