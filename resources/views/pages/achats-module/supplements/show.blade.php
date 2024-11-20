@extends('layout.template')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle d-flex">
            <div class="col-6">
                <h1 class="float-left">Détails commande sup du {{Carbon\Carbon::parse($bon->date_cmd)->locale('fr_FR')->isoFormat('ll')}} </h1>
            </div>

        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Liste des articles de la commande sup</h5>

                            <table id="dataTable" class="table datatable">
                                <thead>
                                    <tr>
                                        <th>N°</th>
                                        <th>
                                           Article
                                        </th>
                                        <th>Quantité</th>
                                        <th>Unité de mesure</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($lignes as $article)
                                        <tr>
                                            <td>{{ $article->id }} </td>
                                            <td>{{ $article->nom }}</td>
                                            <td>{{ $article->qte_cmde }}</td>
                                            <td>{{ $article->unite }}</td>
                                        </tr>

                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>

                </div>
            </div>
        </section>
    </main>
@endsection
