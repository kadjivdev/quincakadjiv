@extends('layout.template')
@section('styles')
{{-- <meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/jquery-editable/css/jquery-editable.css" rel="stylesheet" />
<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/jquery-editable/js/jquery-editable.min.js"></script> --}}
@endsection
@section('content')
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Inventaires</h1>
    </div><!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body py-2">
                        <!-- Afficher des messages de succès -->
                        @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                        @endif

                        <!-- Afficher des erreurs de validation -->
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <h5 class="card-title text-dark">Ajouter un inventaire</h5>
                        <!-- Vertical Form -->

                        <div class="row g-3">
                            <form class="" id="programForm" action="{{ route('inventaires-bulk.store') }}" method="POST">
                                @csrf
                                <input type="hidden" id="allArticles" name="allArticles" value="{{ json_encode($articles) }}">
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <label class="form-label">Catégorie</label>
                                        <select id="categoryInput" class="form-select js-data-example-ajax" aria-label="Default select example">
                                            <option selected value="">Toute les catégories </option>
                                            @foreach($allCategories as $eachCategory)
                                            <option value="{{$eachCategory->libelle}}">{{$eachCategory->libelle }} </option>
                                            @endforeach

                                        </select>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label">Date inventaire</label>
                                        <input type="text" class="form-control" name="date_inventaire" id="dateReglement">
                                    </div>
                                </div>

                                <table id="example" class=" table table-bordered border-warning data-table table-hover table-striped table-sm">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>N°</th>
                                            <th>Désignations</th>
                                            <th>Catégorie</th>
                                            <th>Stock</th>
                                            <th>Stock réelle</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($articles as $article)
                                        <tr>
                                            <td>{{ $i++ }} </td>
                                            <td>
                                                <input type="hidden" name="magasin_id" value="{{ $article->magasin_id }}">
                                                <input type="hidden" name="stock_magasin[]" value="{{ $article->id }}">
                                                {{ $article->nom }}
                                            </td>
                                            <td>{{ $article->libelle }}</td>
                                            <td><input type="hidden" class="form-control" name="qte_stock[]" min="0" value="{{ $article->qte_stock }}">{{ $article->qte_stock }}</td>
                                            <td> <input type="text" class="form-control" name="qte_reel[]" id=""></td>
                                        </tr>
                                        @empty
                                        <tr>Aucun article disponible ici.</tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <br>
                                <div class="col-lg-12 d-flex flex-row align-items-center justify-content-between">
                                    <button type="submit" class="btn btn-sm btn-dark text_orange w-100 submitBtn" id="ajouterArticle"><i class="bi bi-check-circle"></i> Enregistrer</button>
                                    <button type="button" class="btn btn-sm btn-dark text_orange w-100 loadingBtn" hidden><span class="spinner-border spinner-border-sm text_orange loading"></span> En cours ...</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</main>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

<script src="{{ asset('assets/js/jquery3.6.min.js') }}"></script>

<script src="{{ asset('assets/js/mindmup-editabletable.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(document).ready(function() {
        $("#dateReglement").datepicker({
            beforeShowDay: function(date) {
                var currentDate = new Date();
                currentDate.setHours(0, 0, 0, 0);
                return [date <= currentDate];
            },
            dateFormat: 'dd-mm-yy'
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        var allArticles = JSON.parse($('#allArticles').val());
        $('#categoryInput').change(function() {

            console.log('allArticles : ' + allArticles)
            var filterText = $('#categoryInput').val().toLowerCase();
            var filteredArticles = [];
            console.log('filterText : ' + filterText)


            for (var i = 0; i < allArticles.length; i++) {
                var article = allArticles[i];
                var category = article.libelle.toLowerCase();
                if (category.indexOf(filterText) !== -1) {
                    filteredArticles.push(article);
                }
            }

            updateTable(filteredArticles);
        });

        function updateTable(articles) {
            $('#table tbody').html('');
            for (var i = 0; i < articles.length; i++) {
                var article = articles[i];
                var row = `
                                <tr>
                                    <td>${article.id}</td>
                                    <td>
                                    <input type="hidden" name="stock_magasin[]" min="0" value="${article.id}"> ${article.nom}
                                    </td>
                                    <td><input type="hidden" name="magasin_id" value="${article.magasin_id}">${article.libelle}</td>
                                    <td><input type="hidden" name="qte_stock[]" value="${article.qte_stock}">${article.qte_stock}</td>
                                    <td><input type="text" name="qte_reel[]" min="0" value="${article.qte_stock}"></td>
                                </tr>
                                `;

                $('#table tbody').append(row);
            }
        }

        updateTable(allArticles);
    });
</script>
</section>
</main>
@endsection