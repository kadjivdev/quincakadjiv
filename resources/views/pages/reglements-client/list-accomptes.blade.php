@extends('layout.template')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle d-flex">
            <div class="col-6">
                <h1 class="float-left">Accomptes</h1>
            </div>
            <div class="col-6 justify-content-end">
                @can('clients.enregistrer-accompte')
                <div class="">
                    <a href="{{ route('acompte-create') }}" class="btn btn-sm bg-dark text_orange float-end"> + Ajouter un
                        accompte</a>
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
                            <h5 class="card-title text-dark">Liste des accomptes</h5>

                            <table id="example"
                                class=" table table-bordered border-warning  table-hover table-warning table-sm">
                                <thead class="table-dark">
                                    <tr>
                                        <th>N°</th>
                                        <th>
                                            Code
                                        </th>
                                        <th>Date accompte</th>
                                        <th>Référence</th>
                                        <th>Client</th>
                                        <th>Montant accompte</th>
                                        <th>Type versement</th>
                                        {{-- <th>Action</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($accomptes as $accompte)
                                        <tr>
                                            <td>{{ $accompte->id }} </td>
                                            <td>{{ $accompte->code }}</td>
                                            <td>{{ $accompte->created_at->locale('fr_FR')->isoFormat('ll') }}</td>
                                            <td>{{ $accompte->reference }}</td>
                                            <td>{{ $accompte->client->nom_client }}</td>
                                            <td>{{ number_format($accompte->montant_acompte, 2, ',', ' ') }}</td>
                                            <td>{{ $accompte->type_reglement }}</td>
                                            {{-- <td>
                                                @if (is_null($accompte->validated_at))
                                                <a href="{{route('accomptes-clt.edit', $accompte->id )}}" class="btn btn-success" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Modifier accompte"> <i class="bi bi-pencil"></i> </a>

                                                @endif
                                            </td> --}}
                                        </tr>
                                    @empty
                                        <tr>Aucun accompte enregistré</tr>
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
