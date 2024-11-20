@extends('layout.template')
@section('content')
<main id="main" class="main">

    <div class="pagetitle d-flex">
        <h1 class="float-left">Liste des acomptes du client {{ $client->nom_client }} </h1>

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
                        <h5 class="card-title">Liste des acomptes</h5>

                        <table id="example" class=" table table-bordered border-warning  table-hover table-warning table-sm">
                            <thead>
                                <tr>
                                    <th>N°</th>

                                    <th>Date acompte</th>

                                    <th>Référence</th>
                                    <th>Montant acompte</th>
                                    <th>Client</th>
                                    <th>Type règlement</th>
                                    <th>Statut</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($accomptes as $acompte)
                                @php
                                    if ($acompte->validated_at) {
                                        $statut = 'Validé';
                                        $class = 'text-success';
                                    }else{
                                        $statut = 'Non Validé';
                                        $class = 'text-danger';
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $acompte->id }} </td>

                                    {{-- <td> {{$getDateReglementByRegId($acompte->reglement_client_id )?->date_reglement}}</td> --}}
                                    <td> {{$acompte->date_op}}</td>
                                    <td>{{ $acompte->reference }}</td>
                                    <td>{{ $acompte->montant_acompte }}</td>
                                    <td>{{ $acompte->client->nom_client }}</td>
                                    <td>{{ $acompte->type_reglement }}</td>
                                    <td><strong class="{{$class}}">{{ $statut }}</strong></td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-gear"></i>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                @if (!$acompte->validated_at)
                                                    <li>
                                                        <a href="{{route('validate-accompte' , $acompte->id)}}" data-bs-toggle="tooltip" class="dropdown-item" data-bs-placement="left" data-bs-title="Valider l'accompte">Valider </a>
                                                    </li>  
                                                    <li>
                                                        <a href="{{route('update-accompte' , $acompte->id)}}" data-bs-toggle="tooltip" class="dropdown-item" data-bs-placement="left" data-bs-title="Modifier l'accompte">Modifier </a>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('delete-accompte', $acompte->id) }}" method="POST" class="col-3">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"  class="dropdown-item" data-bs-placement="left" data-bs-toggle="tooltip" onclick="return confirm('Êtes-vous sûr de vouloir supprimer l\'accompte ?')" data-bs-title="Supprimer l'accompte">Supprimer</button>
                                                        </form>
                                                    </li>                                                  
                                                @endif
                                            </ul>
                                        </div>
                                </tr>
                                @empty
                                <tr>Aucun acompte enregistré</tr>
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