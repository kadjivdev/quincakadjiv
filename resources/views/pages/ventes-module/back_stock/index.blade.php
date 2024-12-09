@extends('layout.template')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle d-flex">
            <div class="col-6">
                <h1 class="float-left text-dark">Retour de stock</h1>
            </div>
            <div class="col-6 justify-content-end">
                <!--   @can('ajouter-vente')
        <div class="">
                                <a href="{{ route('ventes.create') }}" class="btn btn-primary float-end"> + Nouvelle vente</a>
                            </div>
    @endcan -->
                <div class="">
                    <a href="{{ route('back_stock.create') }}" class="btn btn-sm bg-dark text_orange float-end"> + Ajouter</a>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12 py-2">
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
                            <h5 class="card-title text-dark">Liste des retours de stock</h5>

                            <!-- Table with stripped rows -->
                            <table id="example"
                                class=" table table-bordered border-warning  table-hover table-sm  table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>N°</th>
                                        <th>Référence</th>
                                        <th>Date Création</th>
                                        <th>Date Retour</th>
                                        <th>Provenance</th>
                                        <th>Destination</th>
                                        <th>Client</th>
                                        <th>Montant </th>
                                        <th>Action </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($backs as $back)
                                        @php
                                            $dateback = Carbon\Carbon::parse($back->created_at);
                                            $date_op = Carbon\Carbon::parse($back->date_op);
                                            $formattedDate = $dateback->locale('fr_FR')->isoFormat('LLLL');
                                            $formattedDateOp = $date_op->locale('fr_FR')->isoFormat('ll');
                                        @endphp
                                        @if (is_null($back->validated_at))
                                            <tr>
                                                <td>{{ $i++ }} </td>
                                                <td>{{ $back->reference }} </td>
                                                <td>{{ $formattedDate }}</td>
                                                <td>{{ $formattedDateOp }}</td>
                                                <td>{{ $back->provenance->nom }}</td>
                                                <td>{{ $back->destination->nom }}</td>
                                                <td>{{ $back->client?->nom_client ?? '-' }}</td>
                                                <td>{{ number_format($back->montant_total, 0, ',', ' ') }}</td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm bg-dark text_orange dropdown-toggle" type="button"
                                                            id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                            aria-expanded="false">
                                                            <i class="bi bi-gear"></i>
                                                        </button>
                                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                            @can('ventes.voir-vente')
                                                            @endcan
                                                            @if (!$back->validate_at)
                                                                @can('ventes.voir-vente')
                                                                    <li>
                                                                        <a href="{{ route('back-validation', $back->id) }}"
                                                                            data-bs-toggle="tooltip" class="dropdown-item"
                                                                            data-bs-placement="left"
                                                                            data-bs-title="Valider le retour de stock"><i class="bi bi-check-circle"></i> Validation </a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="{{ route('back_stock.edit', $back->id) }}"
                                                                            data-bs-toggle="tooltip" class="dropdown-item"
                                                                            data-bs-placement="left"
                                                                            data-bs-title="Modifier retour de stock"><i class="bi bi-pencil"></i> Modifier </a>
                                                                    </li>
                                                                    <li>
                                                                        <form action="{{ route('back_stock.destroy', $back->id) }}"
                                                                            method="POST" class="col-3">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" class="dropdown-item"
                                                                                data-bs-placement="left"
                                                                                data-bs-toggle="tooltip"
                                                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette ? Cette opération est irréversible')"
                                                                                data-bs-title="Supprimer la vente"><i class="bi bi-trash"></i> Supprimer</button>
                                                                        </form>
                                                                    </li>
                                                                @endcan
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @empty
                                        <tr>Aucun retour enregistré</tr>
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
