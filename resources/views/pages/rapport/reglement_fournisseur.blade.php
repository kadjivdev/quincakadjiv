@extends('layout.template')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle d-flex">
            <div class="col-6">
                <h1 class="float-left">Règlements</h1>
            </div>
        </div><!-- End Page +++ -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    
                    <div class="card">
                        <div class="card-body py-1">
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
                            <h5 class="card-title text-dark"><i class="bi bi-filter-circle"></i> Filtre</h5>
                            
                            <form class="row g-3" method="GET" action="{{ route('rap_reg_frs') }}">
                                <div class="col-3 mb-3">
                                    <label class="form-label">Début</label>
                                    <input type="date" class="form-control" name="start_date" id="start_date" required value="{{ request('start_date') }}">                                    
                                </div>
                                <div class="col-3 mb-3">
                                    <label class="form-label">Fin</label>
                                    <input type="date" class="form-control" name="end_date" id="end_date" value="{{ request('end_date') }}" required>                                    
                                </div>
                                <div class="col-4 mb-3">
                                    <label class="form-label">Type de règlement</label>
                                    <select name="type_reglement" class="form-control" id="type_reglement">
                                        <option value=""> </option>
                                        <option value="Espèce ">En espèce </option>
                                        <option value="Chèque">Chèque</option>
                                        <option value="Virement">Virement</option>
                                        <option value="Décharge">Décharge</option>
                                        <option value="Autres">Autres</option>
                                    </select>
                                </div>

                                <div class="text-center col-1 mt-5">
                                    <button type="submit" class="btn btn-sm bg-dark text_orange ">Filtrer</button>
                                </div>
                            </form>

                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title text-dark">Liste des règlements</h5>

                            <table id="example"
                                class=" table table-bordered border-warning  table-hover table-striped table-sm">
                                <thead class="table-dark">
                                    <tr>
                                        <th>N°</th>
                                        <th>
                                            Code
                                        </th>
                                        <th>Date règlement</th>

                                        <th>Référence</th>
                                        <th>Montant règlement</th>
                                        <th>Fournisseur</th>
                                        <th>Type règlement</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($reglements as $reglement)
                                        <tr>
                                            <td>{{ $reglement->id }} </td>
                                            <td>{{ $reglement->code }}</td>
                                            <td>{{ $reglement->date_reglement->locale('fr_FR')->isoFormat('ll') }}</td>
                                            <td>{{ $reglement->reference }}</td>
                                            <td>{{ number_format($reglement->montant_regle, 2, ',', ' ') }}</td>
                                            <td>{{ $reglement->facture->fournisseur->name }}</td>
                                            <td>{{ $reglement->type_reglement }}</td>
                                            <td>
                                                @if (is_null($reglement->validated_at))
                                                    @can('fournisseurs.modifier-reglement-frs')
                                                        <a href="{{ route('reglements.edit', $reglement->id) }}"
                                                            class="btn btn-sm bg-dark text_orange" data-bs-toggle="tooltip"
                                                            data-bs-placement="top" data-bs-title="Modifier reglement"> <i
                                                                class="bi bi-pencil"></i> </a>
                                                    @endcan
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>Aucun reglement enregistré</tr>
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