<!DOCTYPE html>
<html>
<head>
    <title>Proforma</title>
</head>
<style type="text/css">
    body{
        font-family: 'Roboto Condensed', sans-serif;
    }
    .m-0{
        margin: 0px;
    }
    .p-0{
        padding: 0px;
    }
    .pt-5{
        padding-top:5px;
    }
    .mt-10{
        margin-top:10px;
    }
    .text-center{
        text-align:center !important;
    }
    .w-100{
        width: 100%;
    }
    .w-50{
        width:50%;
    }
    .w-85{
        width:85%;
    }
    .w-15{
        width:15%;
    }
    .logo img{
        width:200px;
        height:60px;
    }
    .gray-color{
        color:#5D5D5D;
    }
    .text-bold{
        font-weight: bold;
    }
    .border{
        border:1px solid black;
    }
    table tr,th,td{
        border: 1px solid #d2d2d2;
        border-collapse:collapse;
        padding:7px 8px;
    }
    table tr th{
        background: #F4F4F4;
        font-size:15px;
    }
    table tr td{
        font-size:13px;
    }
    table{
        border-collapse:collapse;
    }
    .box-text p{
        line-height:10px;
    }
    .float-left{
        float:left;
    }
    .total-part{
        font-size:16px;
        line-height:12px;
    }
    .total-right p{
        padding-right:20px;
    }
</style>
<body>
<div class="head-title">
    <h1 class="text-center m-0 p-0"> Pro-forma</h1>
</div>
<div class="add-detail mt-10">
    <div class="w-50 float-left mt-10">
        <p class="m-0 pt-5 text-bold w-100">Distributeur Agréé de Ciment et de Matériaux de Construction</p>
        <p class="m-0 pt-5 text-bold w-100">AKPAKPA LOT 625 YAGBE</p>
        <p class="m-0 pt-5 text-bold w-100">Rue 1600, Cotonou – BENIN</p>
        <p class="m-0 pt-5 text-bold w-100">« BATIR C’EST NOTRE AFFAIRE »</p>
    </div>
    <div class="w-50 float-left logo mt-10">
        <img src="{{asset('assets/img/kadjiv.png')}}" alt="Logo">
    </div>
    <div style="clear: both;"></div>

    <div class="w-100 float-right mt-10">
        <p class="m-0 pt-5 text-bold w-100">Cotonou, le {{ now()->locale('fr_FR')->isoFormat('lll') }} </p>
    </div>
</div>
<div class="table-section w-100 mt-10">
    <table class="table w-100 mt-10">
        <tr>
            <td>
                <div class="box-text">
                    <strong> Pro-forma </strong>
                    <strong>Référence:  {{$data[0]->reference}} </strong>
                </div>
            </td>
            <td>
                <div class="box-text">
                   <span>Client : </span> <strong>{{$data[0]->nom_client}} </strong>
                </div>
            </td>
        </tr>
    </table>
</div>

<div class="table-section bill-tbl w-100 mt-10">
    <table class="table w-100 mt-10">
        <tr>
            <th class="w-50">#</th>
            <th class="w-50">Désignation</th>
            <th class="w-50">Unité</th>
            <th class="w-50">Qté</th>
            <th class="w-50">Prix U</th>
        </tr>
        @foreach ( $data as $item)
        <tr align="center">
            <td>{{ $item->id }}</td>
            <td>{{ $item->nom }}</td>
            <td>{{ $item->nom }}</td>
            <td>{{ $item->qte_cmd }}</td>
            <td>{{ $item->prix_unit }}</td>
        </tr>
        @endforeach

        <tr>
            <td colspan="7">
                <div class="total-part">
                    <div class="total-left w-85 float-left" align="right">
                        <p>Montant total</p>
                    </div>
                    <div class="total-right w-15 float-left text-bold" align="right">
                               <p>{{$data[0]->total_amount}}</p>
                    </div>
                    <div style="clear: both;"></div>
                </div>
            </td>
        </tr>
    </table>
</div>
</html>
