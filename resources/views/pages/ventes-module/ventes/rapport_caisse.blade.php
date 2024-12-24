@extends('layout.template')
@section('content')
<main id="main" class="main">

    <div class="pagetitle d-flex">
        <div class="col-9">
            <h1 class="float-left">Rapport de caisse de {{Carbon\Carbon::parse()->locale('fr_FR')->isoFormat('ll')}} </h1>
        </div>
        <div class="col-3 justify-content-end" >
            <a href="{{ route('ventes.index')}}" class="btn btn-sm bg-dark text_orange float-end mx-2"> <i class="bi bi-arrow-left"></i> Retour</a>

        </div>
    </div>

    <div class="pagetitle d-flex">
        <div class="col-6">
            {{-- <h1 class="float-left">Ventes au comptant</h1> --}}
        </div>
        <div class="col-6 justify-content-end">
            <!--   @can('ajouter-vente')
                    <div class="">
                        <a href="{{ route('ventes.create') }}" class="btn btn-primary float-end"> + Nouvelle vente</a>
                    </div>
                @endcan -->
            {{-- <div class="">
                <a href="{{ route('ventes.create') }}" class="btn btn-sm bg-dark text_orange float-end"> + Nouvelle vente</a>
            </div> --}}
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                
                <div class="card">
                    <div class="card-body py-2">
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
                        <h5 class="card-title text-dark">Filtre</h5>
                        
                        <form class="row g-3" method="GET" action="{{ route('rapport-caisse') }}">
                            <div class="col-3 mb-3">
                                <label class="form-label">Début</label>
                                <input type="date" class="form-control" name="start_date" id="start_date" value="{{ request('start_date') }}">                                    
                            </div>
                            <div class="col-3 mb-3">
                                <label class="form-label">Fin</label>
                                <input type="date" class="form-control" name="end_date" id="end_date" value="{{ request('end_date') }}" >                                    
                            </div>
                            <div class="col-3 mb-3">
                                <label class="form-label">Client</label>
                                <select name="client" class="js-example-basic-single form-control" id="client" >
                                    <option value=""> </option>
                                    @foreach ($clients as $client)
                                        <option {{ request('client') == $client->id ? 'selected' : '' }} value="{{$client->id}}">{{$client->nom_client}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="text-center col-1 mt-5">
                                <button type="submit" class="btn btn-sm bg-dark text_orange "><i class="bi bi-filtre"></i> Filtrer</button>
                            </div>
                        </form>

                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-dark">Rapport</h5>

                        <!-- Table with stripped rows -->
                        <table id="example" class=" table table-bordered border-warning  table-hover table-striped table-sm">
                            <thead class="table-dark">
                                <tr>
                                    <th>N°</th>
                                    <th>Date</th>
                                    <th class="text-center">Client</th>
                                    <th class="text-center">Montant</th>
                                    <th class="text-center">Observation</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($encaissements as $encaissement)
                                <tr>
                                    <td>{{ $i++ }} </td>
                                    <td>{{ Carbon\Carbon::parse($encaissement->created_at)->format('d-m-Y') }} </td>
                                    <td class="text-center">{{ $getClient($encaissement->encaisseable?->client_id)?->nom_client }} </td>
                                    <td class="text-center">{{ number_format($encaissement->encaisseable->montant ?? $encaissement->encaisseable?->montant_total_regle ?? $encaissement->encaisseable?->montant_acompte, 0, ',', ' ') }} </td>
                                    <td class="text-center">
                                        @if ($encaissement->encaisseable instanceof \App\Models\ReglementClient)
                                            REMBOURSEMENT
                                        @elseif ($encaissement->encaisseable instanceof \App\Models\Vente)
                                            ACHAT
                                        @elseif ($encaissement->encaisseable instanceof \App\Models\AcompteClient)
                                            AVANCE
                                        @endif
                                    </td>

                                </tr>
                                @empty
                                <tr>Aucune vente enregistrée</tr>
                                @endforelse
                            </tbody>
                        </table>
                        <br>

                        <table class="table table-bordered border-warning  table-hover table-striped table-lg">
                            <thead class="text-dark">
                                <tr>
                                    <th>Achat</th>
                                    <th>Remboursement</th>
                                    <th>Avance</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{number_format($achat, '0', ',', ' ')}}</td>
                                    <td>{{number_format($remboursement, '0', ',', ' ')}}</td>
                                    <td>{{number_format($avance, '0', ',', ' ')}}</td>
                                    <td>{{number_format($montant_total, '0', ',', ' ')}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </section>

</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <script>
        $(".js-example-basic-single").select2();
    </script>
@endsection
