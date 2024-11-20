@extends('layout.template')
@section('content')

    @php

        $typeOpDescriptions = [
            'FAC_AC' => "Facturation provenant d'un report à nouveau",
            'REG' => 'Règlement Facture',
            'REG_AC' => 'Règlement Accompte',
            'ACC_REG' => 'Accompte sur restant de règlement',
            'ACC' => 'Accompte sur facture',
            'Acc' => 'Accompte sur facture',
            'FAC_VP' => 'Facture vente Proformat',
            'FAC-RAN' => 'Report à nouveau Débiteur',
            'FAC_VC' => 'Facture vente au comptant',
            'FAC' => 'Facture vente Proformat',
            'REG_VC' => 'Règlement vente au comptant',
            'REG_VP' => 'Règlement vente Proforma',
            'REG_RAN' => 'Reort à nouveau créditeur',
            'REG_VPP' => "Règlement partiel à l'achat vente Proforma",
            'REQ' => "Requête",
        ];
    @endphp


    <main id="main" class="main">

        <div class="pagetitle d-flex">
            <h1 class="float-left">Historique des opérations du client {{ $client->nom_client }} </h1>

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
                            @if ($solde > 0)
                                <h5 class="card-title">Solde des Opérations : <b
                                        style="font-size:30px; text-align:center; font-weight:bolder; background-color: rgba(13, 255, 97, 0.79);">{{ number_format($solde, 2, ',', ' ') }}
                                        FCFA</b></h5>
                            @else
                                <h5 class="card-title">Solde des Opérations : <b
                                        style="font-size:30px; text-align:center; font-weight:bolder; background-color: rgba(255, 12, 12, 0.62);">{{ number_format($solde, 2, ',', ' ') }}
                                        FCFA</b></h5>
                            @endif

                            <table id="example" class=" table table-bordered border-warning  table-hover table-warning table-sm">
                                <thead>
                                    <tr>
                                        <th style="width: 5% !important;">N°</th>
                                        <th style="width: 15% !important;">
                                            date Opération
                                        </th>
                                        <th style="width: 40% !important;">Opération</th>
                                        <th style="width: 20% !important;">Débit</th>
                                        <th style="width: 20% !important;">Crédit</th>
                                        <th style="width: 20% !important;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($compte as $op)
                                        <tr>
                                            <td style="width: 5% !important;">{{ $op->id }} </td>
                                            <td style="width: 15% !important;">
                                                {{ $op->date_op->locale('fr_FR')->isoFormat('ll') }}</td>
                                            <td style="width: 40% !important; ">
                                                {{ $typeOpDescriptions[$op->type_op] ?? 'Autre' }}
                                            </td>
                                            <td
                                                style="width: 20% !important; font-size:18px; text-align:center; font-weight:bolder; background-color: rgba(255, 12, 12, 0.62);">
                                                {{ $op->type_op == 'FAC_AC' ||
                                                $op->type_op == 'FAC_VP' ||
                                                $op->type_op == 'FAC_VC' ||
                                                $op->type_op == 'FAC' ||
                                                $op->type_op == 'FAC-RAN'
                                                    ? number_format($op->montant_op, 2, ',', ' ')
                                                    : '-' }}

                                            </td>

                                            <td
                                                style="width: 20% !important; font-size:18px; text-align:center; font-weight:bolder; background-color: rgba(13, 255, 97, 0.79);">
                                                {{ $op->type_op == 'REG'
                                                    ? number_format($op->montant_op, 2, ',', ' ')
                                                    : ($op->type_op == 'ACC' || $op->type_op == 'Acc'
                                                        ? number_format($op->montant_op, 2, ',', ' ')
                                                        : ($op->type_op == 'REG_VC'
                                                            ? number_format($op->montant_op, 2, ',', ' ')
                                                            : ($op->type_op == 'REG-RAN'
                                                                ? number_format($op->montant_op, 2, ',', ' ')
                                                                : ($op->type_op == 'REG_VP'
                                                                    ? number_format($op->montant_op, 2, ',', ' ')
                                                                    : ($op->type_op == 'REG_VPP'
                                                                        ? number_format($op->montant_op, 2, ',', ' ')
                                                                        : ($op->type_op == 'REG_AC'
                                                                            ? number_format($op->montant_op, 2, ',', ' ')
                                                                            : ($op->type_op == 'ACC_REG'
                                                                                ? number_format($op->montant_op, 2, ',', ' ')
                                                                                : ($op->type_op == 'REQ'
                                                                                    ? number_format($op->montant_op, 2, ',', ' ')
                                                                                        : '-')))))))) }}
                                            </td>
                                            <td>
                                                @if ($op->facture && $op->facture?->id != 15)
                                                    @if ($op->vente_id)
                                                        <a href="{{ route('ventes.show', $op->vente_id) }}" class="btn btn-primary"><i class="bi bi-eye"></i></a>
                                                    @else
                                                        <a href="{{ url('/devis', $op->facture?->devis_id) }}" class="btn btn-primary"><i class="bi bi-eye"></i></a>                                                        
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>Aucune opération enregistrée</tr>
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
