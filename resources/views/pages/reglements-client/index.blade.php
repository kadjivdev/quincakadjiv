@extends('layout.template')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle d-flex">
            <div class="col-6">
                <h1 class="float-left">Règlements</h1>
            </div>
            <div class="col-6 justify-content-end">
                @can('clients.ajouter-reglement-clt')
                <div class="">
                    <a href="{{ route('reglements-clt.create') }}" class="btn btn-primary float-end"> + Ajouter un
                        reglement</a>
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
                            <h5 class="card-title">Liste des règlements</h5>

                            <table id="example"
                                class=" table table-bordered border-warning  table-hover table-warning table-sm">
                                <thead>
                                    <tr>
                                        <th>N°</th>
                                        <th>
                                            Code
                                        </th>
                                        <th>Date règlement</th>

                                        <th>Référence</th>
                                        <th>Client</th>
                                        <th>Montant règlement</th>
                                        <th>Type règlement</th>
                                        <th>Date Insertion</th>
                                        {{-- <th>Action</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                <?php $i = 1?>
                                    @forelse ($reglements as $reglement)
                                    <?php 
                                        $i++;
                                        $montant = $reglement->montant_total_regle > 0 ? $reglement->montant_total_regle : $reglement->montant_regle;
                                    ?>
                                        <tr>
                                            <td>{{ $i }} </td>
                                            <td>{{ $reglement->code }}</td>
                                            <td>{{ $reglement->date_reglement->locale('fr_FR')->isoFormat('ll') }}</td>
                                            <td>{{ $reglement->reference }}</td>
                                            <td>{{ $reglement->client->nom_client }}</td>
                                            <td>{{ number_format($montant, 2, ',', ' ') }}</td>
                                            <td>{{ $reglement->type_reglement }}</td>
                                            <td>{{ $reglement->created_at }}</td>
                                            {{-- <td>
                                                @if (is_null($reglement->validated_at))
                                                <a href="{{route('reglements-clt.edit', $reglement->id )}}" class="btn btn-success" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Modifier reglement"> <i class="bi bi-pencil"></i> </a>

                                                @endif
                                            </td> --}}
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
