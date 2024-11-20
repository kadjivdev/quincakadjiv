@extends('layout.template')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle d-flex">
            <div class="col-6">
                <h1 class="float-left">Inventaires </h1>
            </div>
            <div class="col-6 justify-content-end">
                {{-- @can('rapports.ajouter-inventaire')
                    <div class="">
                        <a href="{{ route('inventaires.create') }}" class="btn btn-primary float-end"> + Ajouter un inventaire
                            </a>
                    </div>
                @endcan --}}
            </div>
        </div><!-- End Page +++ -->

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
                            <h5 class="card-title">Liste des inventaires</h5>

                            <table id="example"
                                class="table table-bordered border-warning  table-hover table-warning table-sm">
                                <thead>
                                    <tr>
                                        <th>N°</th>
                                        <th>
                                            Date inventaire
                                        </th>
                                        <th>Auteur</th>
                                        <th>Magasin</th>
                                        <th>Statut</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($inventaires as $inventaire)
                                        <tr>
                                            <td>{{ $i++ }} </td>
                                            <td>{{ $inventaire->date_inventaire->locale('fr_FR')->isoFormat('ll') }}</td>
                                            <td>{{ $inventaire->auteur->name }}</td>
                                            <td>
                                                @if (is_null($inventaire->validated_at))
                                                <span class="badge rounded-pill text-bg-warning">Non validé</span>

                                                @else
                                                <span class="badge rounded-pill text-bg-warning">Validé</span>

                                                @endif
                                            </td>
                                            <td>{{ $inventaire->magasin->nom }}</td>
                                            <td>
                                                @can('rapports.acces-inventaire')
                                                    <a href="{{ route('inventaires.show', $inventaire->id) }}"
                                                        class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top"
                                                        data-bs-title="Voir détails"> <i class="bi bi-eye"></i> </a>
                                                @endcan

                                                @if (is_null($inventaire->validator_id) )
                                                    {{-- @can('rapports.modifier-inventaire')
                                                        <a href="{{ route('inventaires.edit', $inventaire->id) }}"
                                                            class="btn btn-warning" data-bs-toggle="tooltip"
                                                            data-bs-placement="top" data-bs-title="Modifier le inventaire"> <i
                                                                class="bi bi-pencil"></i> </a>
                                                    @endcan --}}
                                                @endif
                                            </td>
                                        </tr>

                                    @empty
                                        <tr>Aucun inventaire enregistré</tr>
                                    @endforelse
                                </tbody>
                            </table>

                        </div>
                    </div>

                </div>
            </div>
        </section>
    </main>
@endsection
