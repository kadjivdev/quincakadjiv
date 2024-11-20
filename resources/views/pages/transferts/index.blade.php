@extends('layout.template')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle d-flex">
            <div class="col-6">
                <h1 class="float-left">Transferts entre magasins</h1>
            </div>
            <div class="col-6 justify-content-end">
                @can('ajouter-transfert')
                    <div class="">
                        <a href="{{ route('transferts.create') }}" class="btn btn-primary float-end"> + Nouveau transfert</a>
                    </div>
                @endcan
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
                            <h5 class="card-title">Liste des transferts</h5>

                            <!-- Table with stripped rows -->
                            <table id="example"
                                class=" table table-bordered border-warning  table-hover table-warning table-sm">
                                <thead>
                                    <tr>
                                        <th>N°</th>
                                        <th>Date transfert</th>
                                        <th>Magasin départ </th>
                                        <th>Quantité </th>
                                        <th>Magasin destinat° </th>
                                        <th>Action </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($transferts as $transfert)
                                        @php
                                            $datetransfert = Carbon\Carbon::parse($transfert->created_at);
                                            $formattedDate = $datetransfert->locale('fr_FR')->isoFormat('ll');
                                        @endphp
                                        <tr>
                                            <td>{{ $i++ }} </td>
                                            <td>{{ $formattedDate }}</td>
                                            <td>{{ $transfert->client }} </td>
                                            <td>{{ $transfert->client }} </td>
                                            <td>{{ $transfert->client }} </td>
                                            <td>{{ $transfert->montant }} </td>
                                            <td>
                                                @can('voir-transfert')
                                                    <a href="{{ route('transferts.show', $transfert->id) }}" class="btn btn-primary"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        data-bs-title="Voir détails"> <i class="bi bi-eye"></i> </a>
                                                </td>
                                            @endcan

                                        </tr>
                                    @empty
                                        <tr>Aucun transfert enregistré</tr>
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
