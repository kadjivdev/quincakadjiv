@extends('layout.template')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle d-flex">
            <div class="col-6">
                <h1 class="float-left">Factures devis</h1>
            </div>
            <div class="col-6 justify-content-end">
                @can('proforma.ajouter-facture-devis')
                    <div class="">
                        <a href="{{ route('factures.create') }}" class="btn btn-sm bg-dark text_orange float-end"> + Ajouter une facture</a>
                    </div>
                @endcan
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
                            <h5 class="card-title text-dark">Liste des factures</h5>

                            <table id="example"
                                class=" table table-bordered border-warning  table-hover table-striped table-sm">
                                <thead class="table-dark">
                                    <tr>
                                        <th>N°</th>
                                        <th>
                                            Date facture
                                        </th>
                                        <th>Référence</th>
                                        <th>Client</th>
                                        <th>Réduction</th>
                                        <th>Montant</th>
                                        <th>Montant Soldé</th>
                                        <th>Statut</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    @forelse ($factures as $item)
                                        <?php $i++; ?>
                                        <tr style="background-color: red;">
                                            <td>{{ $i }} </td>
                                            <td>{{ $item->date_facture->locale('fr_FR')->isoFormat('ll') }}</td>
                                            <td>{{ $item->num_facture }}</td>
                                            <td>{{ $item->client_facture }}</td>
                                            <td>{{ $item->taux_remise }} %</td>
                                            <td>{{ number_format($item->montant_total, 0, ',', ' ') }}</td>
                                            <td>{{ number_format($item->montant_regle, 0, ',', ' ') }}</td>
                                            <td>
                                                <span class="badge rounded-pill text-bg-warning">{{ $item->statut }}</span>
                                            </td>

                                            <td>

                                                <div class="dropdown">
                                                    <button class="btn btn-sm bg-dark text_orange w-100 dropdown-toggle" type="button"
                                                        id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        <i class="bi bi-gear"></i>
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">

                                                        <li>
                                                            @can('proforma.generer-facture-devis')
                                                                <a href="{{ route('facture-pdf', $item->id) }}"
                                                                    class="dropdown-item" data-bs-toggle="tooltip"
                                                                    data-bs-placement="left" data-bs-title="Générer la facture">
                                                                    <i class="bi bi-file-earmark-pdf"></i> Télécharger la Facture </a>
                                                            @endcan

                                                        </li>
                                                        <li>
                                                            <a href="{{ route('factures.show', $item->id) }}"
                                                                class="dropdown-item" data-bs-toggle="tooltip"
                                                                data-bs-placement="left" data-bs-title="Détail de la facture">
                                                                <i class="bi bi-check-circle"></i> Validation </a>
                                                        </li>

                                                        @if (is_null($item->validate_at))
                                                            {{-- <li>
                                                                <form action="{{ route('validate_facture', $item->id) }}"
                                                                    method="POST" class="col-3">
                                                                    @csrf
                                                                    @method('POST')
                                                                    <button type="submit" class="dropdown-item"
                                                                        onclick="return confirm('Voulez vous vraiment valider cette facture? Cette opération est irréversible')">Valider
                                                                        la Facture</button>
                                                                </form>
                                                            </li> --}}

                                                            <li>
                                                                <a href="{{route('factures.edit', $item->id )}}"  data-bs-toggle="tooltip" class="dropdown-item" data-bs-placement="left" data-bs-title="Modifier facture"><i class="bi bi-pencil"></i> Modifier </a>
                                                            </li>

                                                            <li>
                                                                <form action="{{ route('factures.destroy', $item->id) }}"
                                                                    method="POST" class="col-3">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="dropdown-item"
                                                                        onclick="return confirm('Voulez vous vraiment valider cette facture? Cette opération est irréversible')"><i class="bi bi-trash3"></i> Supprimer la Facture</button>
                                                                </form>
                                                            </li>                                                        
                                                        @endif

                                                    </ul>
                                                </div>






                                                {{-- <a class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#staticBackdrop{{ $item->id }}"> <i
                                                        class="bi bi-gear"></i> </a> --}}
                                            </td>
                                        </tr>

                                        <!-- Modal -->
                                        <div class="modal fade" id="staticBackdrop{{ $item->id }}"
                                            data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                                            aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('reglements.store') }}" method="post">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Ajouter un
                                                                règlement</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            @csrf
                                                            <div class="col-12">
                                                                <label for="" class="form-label">Numero de
                                                                    facture</label>
                                                                <input type="text" readonly
                                                                    value="{{ $item->num_facture }}" required
                                                                    class="form-control" name="facture">
                                                                <input type="hidden" value="{{ $item->id }}"
                                                                    name="facture_id">
                                                            </div>
                                                            <div class="col-12">
                                                                <label class="form-label">Montant payé</label>
                                                                <input type="number" min="0" required
                                                                    class="form-control" name="montant_regle">

                                                            </div>
                                                            <div class="col-12">
                                                                <label for="">Types de règlement </label>
                                                                <select name="type_reglement" id="type_reglement"
                                                                    class="form-control">
                                                                    <option value="Espèce">En espèce </option>
                                                                    <option value="Chèque">Par chèque </option>

                                                                </select>
                                                            </div>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Fermer</button>
                                                            <button type="submit"
                                                                class="btn btn-primary">Enregistrer</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <tr>Aucune facture enregistrée</tr>
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
