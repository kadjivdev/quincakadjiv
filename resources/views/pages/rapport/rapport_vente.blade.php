@extends('layout.template')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle d-flex">
            <div class="col-6">
                <h1 class="float-left">Ventes au comptant</h1>
            </div>

        </div>

        <section class="section">
            <div class="row col-12 mb-3">
                <form method="GET" action="{{ route('rap_fact_vte') }}">
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="start_date">Date début:</label>
                            <input type="date" class="form-control" name="start_date" id="start_date" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-4">
                            <label for="end_date">Date fin:</label>
                            <input type="date" class="form-control" name="end_date" id="end_date" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-3 mb-3">
                            <label class="form-label">Type de vente</label>
                            <select class="form-select mb-3" name="type_vente" id="type_vente">
                                <option value="">Choisir le type </option>

                                @foreach ($typeVentes as $typeVente)
                                    <option value="{{ $typeVente->id }}" data-donnee="{{ $typeVente->libelle }}">
                                        {{ $typeVente->libelle }} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-1 mt-4">
                            <button type="submit" class="btn btn-primary">Filtrer</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Liste des ventes</h5>

                            <!-- Table with stripped rows -->
                            <table id="example"
                                class=" table table-bordered border-warning  table-hover table-warning table-sm">
                                <thead>
                                    <tr>
                                        <th>N°</th>
                                        <th>Date Ecriture</th>
                                        <th>Date vente</th>
                                        <th>Type vente</th>
                                        <th>Montant </th>
                                        <th>Action </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($ventes as $vente)
                                        @php
                                            $date_ecriture = Carbon\Carbon::parse($vente->created_at);
                                            $date_vente = $vente->date_vente != null ? Carbon\Carbon::parse($vente->date_vente) : '';

                                            $formatted_date_ecriture = $date_ecriture->locale('fr_FR')->isoFormat('ll');
                                            $formatted_date_vente = $vente->date_vente != null ? $date_vente->locale('fr_FR')->isoFormat('ll') : '';
                                        @endphp
                                        <tr>
                                            <td>{{ $i++ }} </td>
                                            <td>{{ $formatted_date_ecriture }}</td>
                                            <td>{{ $formatted_date_vente }}</td>
                                            <td>{{ $vente->typeVente->libelle }} </td>
                                            <td>{{ $vente->montant }} </td>
                                            <td>
                                                @can('ventes.voir-vente')
                                                    <a href="{{ route('ventes.show', $vente->id) }}" class="btn btn-primary"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        data-bs-title="Voir détails"> <i class="bi bi-eye"></i> </a>
                                                </td>
                                            @endcan

                                        </tr>
                                    @empty
                                        <tr>Aucune vente enregistrée</tr>
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
