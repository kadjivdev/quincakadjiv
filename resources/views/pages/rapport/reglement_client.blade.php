@extends('layout.template')
@section('content')
    <main id="main" class="main">

        @php
            $typeOpDescriptions = [
                "FAC_AC" => "Facturation provenant d'un report à nouveau",
                "REG" => "Règlement Facture",
                "ACC" => "Accompte sur facture",
                "Acc" => "Accompte sur facture",
                "FAC_VP" => "Facture vente Proformat",
                "FAC_VC" => "Facture vente au comptant",
                "FAC" => "Facture vente Proformat",
                "REG_VC" => "Règlement vente au comptant",
                "REG_VP" => "Règlement vente Proforma",
                "REG_VPP" => "Règlement partiel à l'achat vente Proforma",
            ];
        @endphp

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
                            
                            <form class="row g-3" method="GET" action="{{ route('rap_reg_clt') }}">
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
                                <div class="col-2 mb-3">
                                    <label class="form-label">Type</label>
                                    <select name="type_reglement" class="form-control" id="type_reglement" >
                                        <option value=""> </option>
                                        <option {{ request('type_reglement') == 'Accompte' ? 'selected' : '' }}  value="Accompte ">Accompte </option>
                                        <option {{ request('type_reglement') == 'Reglement' ? 'selected' : '' }} value="Reglement">Reglement</option>
                                    </select>
                                </div>

                                <div class="text-center col-1 mt-5">
                                    <button type="submit" class="btn btn-sm bg-dark text_orange "><i class="bi bi-filter-circle"></i> Filtrer</button>
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
                                        </th>
                                        <th>Date règlement</th>

                                        {{-- <th>Référence</th> --}}
                                        <th>Montant règlement</th>
                                        <th>Client</th>
                                        <th>Type règlement</th>
                                        {{-- <th>Action</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($reglements as $reglement)
                                        <tr>
                                            <td>{{ $reglement->id }} </td>
                                            <td>{{ $reglement->date_op->locale('fr_FR')->isoFormat('ll') }}</td>
                                            <td>{{ number_format($reglement->montant_op, 2, ',', ' ') }}</td>
                                            <td>{{ $reglement->client->nom_client }}</td>
                                            <td> {{ $typeOpDescriptions[$reglement->type_op] ?? 'Autre' }}</td>
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <script>
        $(".js-example-basic-single").select2();
    </script>
@endsection