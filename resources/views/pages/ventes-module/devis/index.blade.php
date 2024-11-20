@extends('layout.template')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle d-flex">
            <div class="col-6">
                <h1 class="float-left">Proforma </h1>
            </div>
            <div class="col-6 justify-content-end">
                @can('proforma.ajouter-devis')
                    <div class="">
                        <a href="{{ route('devis.create') }}" class="btn btn-primary float-end"> + Ajouter un proforma</a>
                    </div>
                @endcan
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
                            <h5 class="card-title">Liste des Proformas</h5>

                            <table id="example"
                                class=" table table-bordered border-warning  table-hover table-warning table-sm">
                                <thead>
                                    <tr>
                                        <th>N°</th>
                                        <th>
                                            Date proforma
                                        </th>
                                        <th>Référence</th>
                                        <th>Auteur</th>
                                        <th>Client</th>
                                        <th>Statut</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($devis as $item)
                                        <tr>
                                            <td>{{ $i++ }} </td>
                                            <td>{{ $item->date_devis->locale('fr_FR')->isoFormat('ll') }}</td>
                                            <td>{{ $item->reference }}</td>
                                            <td>{{ $item->redacteur->name }}</td>
                                            <td>{{ $item->client->nom_client }}</td>
                                            <td>
                                                <span class="badge rounded-pill text-bg-warning">{{ $item->statut }}</span>
                                            </td>

                                            <td>
                                                @can('proforma.detail-devis')

                                                <div class="dropdown">
                                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bi bi-gear"></i>
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                        <li>
                                                            <a href="{{ route('devis.show', $item->id) }}" class="dropdown-item"
                                                                data-bs-toggle="tooltip" data-bs-placement="left"
                                                                data-bs-title="Voir détails"> Détails du ProForma </a>
                                                        </li>

                                                        <li>
                                                            <a href="{{ route('devis.edit', $item->id) }}" class="dropdown-item"
                                                                data-bs-toggle="tooltip" data-bs-placement="left"
                                                                data-bs-title="Modifier le ProForma"> Modifier </a>
                                                        </li>

                                                        <li>
                                                            <a href="{{ url('generate-proforma', $item->id) }}" class="dropdown-item"
                                                                data-bs-toggle="tooltip" data-bs-placement="left"
                                                                data-bs-title="Voir détails"> Générer la ProForma </a>
                                                        </li>

                                                        <li>
                                                            <form
                                                                class="form-inline" method="POST"
                                                                action="{{ route('devis.destroy', $item->id) }}"
                                                                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette ProForma ?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item"
                                                                    data-bs-toggle="tooltip" data-bs-placement="left"
                                                                    data-bs-title="Supprimer">Supprimer la ProForma</button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>

                                                    {{-- <a href="{{ route('devis.show', $item->id) }}" class="btn btn-primary"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        data-bs-title="Voir détails"> <i class="bi bi-eye"></i> </a> --}}

                                                        @endcan
                                                        {{-- <a target="_blank" href="{{ url('generate-proforma', $item->id) }}" class="btn btn-primary"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            data-bs-title="Générer le devis"> <i class="bi bi-download"></i> </a> --}}


                                            </td>
                                        </tr>

                                    @empty
                                        <tr>Aucun Proforma enregistré</tr>
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
