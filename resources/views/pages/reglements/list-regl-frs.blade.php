@extends('layout.template')
@section('content')

@php
    $typeOpDescriptions = [
        "FAC_AC" => "Facturation provenant d'un report à nouveau",
        "REG" => "Règlement Facture",
        "ACC" => "Accompte sur facture",
        "Acc" => "Accompte sur facture",
        "FAC_VP" => "Facture vente Proformat",
        "FAC_VC" => "Facture vente au comptant",
        "FAC" => "Facture",
        "REG_VC" => "Règlement vente au comptant",
        "REG_VP" => "Règlement vente Proforma",
        "REG_VPP" => "Règlement partiel à l'achat vente Proforma",
    ];
@endphp


<main id="main" class="main">

    <div class="pagetitle d-flex">
        <h1 class="float-left">Historique des opérations du fournisseur {{ $frs->nom }} </h1>

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

                <div class="row">


                    <div class="col-4">

                        <h5 class="card-title">Total des Facturations <br> <b style="font-size:30px; text-align:center; font-weight:bolder; background-color: rgba(255, 12, 12, 0.62);">{{ number_format($factures, 2, ',', ' ') }} FCFA</b></h5>

                    </div>

                    <div class="col-4">

                        <h5 class="card-title">Total des Règlements <br> <b style="font-size:30px; text-align:center; font-weight:bolder; background-color: rgba(13, 255, 97, 0.79);">{{ number_format($reglements, 2, ',', ' ') }} FCFA</b></h5>

                    </div>

                    <div class="col-4">
                        @if($solde > 0)
                        <h5 class="card-title">Solde des Opérations <br> <b style="font-size:30px; text-align:center; font-weight:bolder; background-color: rgba(13, 255, 97, 0.79);">{{ number_format($solde, 2, ',', ' ') }} FCFA</b></h5>
                    @else
                        <h5 class="card-title">Solde des Opérations <br> <b style="font-size:30px; text-align:center; font-weight:bolder; background-color: rgba(255, 12, 12, 0.62);">{{ number_format($solde, 2, ',', ' ') }} FCFA</b></h5>
                    @endif
                    </div>
                </div>

                <div class="card">

                    <div class="card-body">





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
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($compte as $op)
                                <tr>
                                    <td style="width: 5% !important;">{{ $op->id }} </td>
                                    <td style="width: 15% !important;">{{ $op->date_op->locale('fr_FR')->isoFormat('ll')}}</td>
                                    <td style="width: 40% !important; ">
                                        {{ $typeOpDescriptions[$op->type_op] ?? 'Autre' }}
                                    </td>
                                    <td style="width: 20% !important; font-size:18px; text-align:center; font-weight:bolder; background-color: rgba(255, 12, 12, 0.62);">
                                        {{
                                            ($op->type_op == "FAC_AC" || $op->type_op == "FAC_VP" || $op->type_op == "FAC_VC" || $op->type_op == "FAC")
                                            ? number_format($op->montant_op, 2, ',', ' ')
                                            : "-"
                                        }}

                                    </td>

                                    <td style="width: 20% !important; font-size:18px; text-align:center; font-weight:bolder; background-color: rgba(13, 255, 97, 0.79);">
                                        {{

                                            (($op->type_op == "REG") ? number_format($op->montant_op, 2, ',', ' ') :
                                                (($op->type_op == "ACC") ? number_format($op->montant_op, 2, ',', ' ') :

                                                            (($op->type_op == "REG_VC") ? number_format($op->montant_op, 2, ',', ' ') :
                                                            (($op->type_op == "REG_VP") ? number_format($op->montant_op, 2, ',', ' ') :
                                                            (($op->type_op == "REG_VPP") ? number_format($op->montant_op, 2, ',', ' ') :
                                                                "-")))));
                                         }}
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
