@extends('layout.template')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle d-flex">
            <div class="col-6">
                <h1 class="float-left">Factures fournisseurs </h1>
            </div>
            <div class="col-6 justify-content-end">
            </div>
        </div>

        <section class="section">
            <div class="row col-12 mb-3">
                <form method="GET" action="{{ route('rap_fact_frs') }}">
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="start_date">Date début:</label>
                            <input type="date" class="form-control" name="start_date" id="start_date" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-3">
                            <label for="end_date">Date fin:</label>
                            <input type="date" class="form-control" name="end_date" id="end_date" value="{{ request('end_date') }}">
                        </div>

                        <div class="col-3 mb-3">
                            <label class="form-label">Type de facture</label>
                            <select class="form-select mb-3" name="type_fact" id="type_fact">
                                <option value="">Choisir le type </option>

                                @foreach ($typeFacture as $type)
                                    <option value="{{ $type->id }}" data-donnee="{{ $type->libelle }}">
                                        {{ $type->libelle }} </option>
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
                            <h5 class="card-title">Liste des factures</h5>

                            <table id="example"
                                class="table table-bordered border-warning  table-hover table-warning table-sm">
                                <thead>
                                    <tr>
                                        <th>N°</th>
                                        <th>Référence</th>
                                        <th>Date facture</th>
                                        <th>Montant</th>
                                        <th>Fournisseur</th>
                                        <th>Type facture</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($factures as $facture)
                                        <tr>
                                            <td>{{ $facture->id }} </td>
                                            <td>{{ $facture->ref_facture }} </td>
                                            <td>{{ Carbon\Carbon::parse($facture->date_facture)->locale('fr_FR')->isoFormat('ll') }}
                                            </td>
                                            <td>{{ $facture->montant_total }}</td>
                                            <td>{{ $facture->fournisseur->name }}</td>
                                            <td>{{ $facture->typeFacture->libelle }}</td>
                                            <td>
                                                @can('bon-commandes.voir-commande')
                                                    <a href="{{ route('commandes.show', $facture->id) }}"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        data-bs-title="Voir détails" class="btn btn-primary"> <i
                                                            class="bi bi-eye"></i> </a>
                                                @endcan
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>Aucune facture enregistrée.</tr>
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
