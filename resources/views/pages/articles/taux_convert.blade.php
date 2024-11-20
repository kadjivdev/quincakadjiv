@extends('layout.template')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle d-flex">
            <div class="col-2">
                <h1 class="float-left">Liste des taux de convertion</h1>
            </div>

        </div><!-- End Page +++ -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="alert alert-success d-none" id="tauxMsg">
                    </div>
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
                            <h5 class="card-title">Taux de conversion</h5>


                                <form action="{{ route('TauxSupplementMassUpdate') }}" method="post" id="myForm">
                                    @csrf
                                    <table  id="example" class=" table table-bordered border-warning  table-hover  table-sm table-striped">
                                        <thead class="table-dark">
                                            <tr>

                                                <th>Nom article</th>
                                                <th>Unité de base</th>
                                                <th>Unité Convertie</th>
                                                <th>Taux</th>
                                                {{-- <th>Action</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($taux as $article)
                                                <tr>
                                                    <td>{{ $article->article_nom }}</td>
                                                    {{-- <td>
                                                        <select name="unite_mesure_base[]" id="unite_mesure_base{{$article->article_id}}" class="form-control">
                                                            <option value="">Choisir l'unité à convertir</option>
                                                            @foreach ($unites as $unite)
                                                                <option value="{{ $unite->id }}" @if ($unite->id == $article->unite_mesure_id) selected @endif>{{ $unite->unite }}</option>
                                                            @endforeach
                                                        </select>        
                                                    </td> --}}
                                                    <td>{{ $article->article_unite_nom }}</td>
                                                    {{-- <td>{{ $article->unite_mesure_nom }}</td> --}}
                                                    <td>
                                                        <input type="hidden" id="article_{{$article->article_id}}" name="article_id[]" value="{{$article->article_id}}">
                                                        <input type="hidden" name="taux_id[]" value="{{$article->id}}">
                                                        <select name="unite_mesure[]" id="unite_mesure_{{$article->article_id}}" class="form-control">
                                                            <option value="">Choisir l'unité à convertir</option>
                                                            @foreach ($unites as $unite)
                                                                <option value="{{ $unite->id }}" @if ($unite->unite == $article->unite_mesure_nom) selected @endif>{{ $unite->unite }}</option>
                                                            @endforeach
                                                        </select>    
                                                    </td>
                                                    {{-- <td>{{ $article->taux_conversion }}</td> --}}
                                                    <td>
                                                        <input type="text" pattern="[0-9]+([,\.][0-9]+)?" class="form-control" id="taux{{$article->article_id}}" name="taux[]" placeholder="Ex: 1 ou 2.6 (Nombre entier ou nombre à virgule)" value="{{ $article->taux_conversion }}">
                                                    {{-- <td>
                                                        <a class="btn btn-primary edit-button" onclick="show_modal('{{ $article->article_id }}', '{{$article->article_unite_nom }}', '{{$article->taux_conversion }}')">
                                                            <i class="bi bi-gear"></i> 
                                                        </a>
                                                    </td> --}}
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4">Aucun taux de conversion enregistré.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>

                                    </table>
                                    <div class="col-lg-12 d-flex flex-row align-items-center justify-content-between">
                                        <button type="submit" class="btn btn-sm btn-dark text_orange w-100"><i class="bi bi-check-circle"></i> Valider</button>
                                        <button class="btn btn-dark button_loader w-100" id="myLoader" type="button" disabled>
                                            <span class="spinner-border spinner-border-sm text_orange" aria-hidden="true"></span>
                                            <span role="status text_orange">En cours...</span>
                                        </button>
                                    </div>
                                </form>

                            <!-- End Table with stripped rows -->
                            
                                <!-- Modal -->
                                <div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('tauxSupplements.store') }}" method="post">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Configurer
                                                        le taux</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    @csrf
                                                    <div class="col-12">
                                                        <label for="unite" class="form-label">Unité de base</label>
                                                        <input type="text" readonly required class="form-control" id="unite" name="unite">
                                                        <input type="hidden" id="article_id" name="article_id">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label">Taux de conversion</label>
                                                        <input type="text" pattern="[0-9]+([,\.][0-9]+)?" class="form-control" id="taux_conversion" name="taux_conversion" placeholder="Ex: 1 ou 2.6 (Nombre entier ou nombre à virgule)">
                                                        @if (old('taux_conversion') && !preg_match('/^[0-9]+(?:\.[0-9]+)?$/', old('taux_conversion')))
                                                            <div class="alert alert-danger">
                                                                Le champ doit contenir des chiffres ou un nombre à virgule.
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="col-12">
                                                        <label for="unite_mesure_id">Unité de mesure</label>
                                                        <select name="unite_mesure_id" id="unite_mesure_id" class="form-control">
                                                            <option value="">Choisir l'unité à convertir</option>
                                                            @foreach ($unites as $unite)
                                                                <option value="{{ $unite->id }}">{{ $unite->unite }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Fermer</button>
                                                    <button type="submit"
                                                        class="btn btn-primary">Enregistrer</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                        </div>
                    </div>

                </div>
            </div>
        </section>


    </main>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    {{-- <script src="{{ asset('assets/js/jquery3.6.min.js') }}"></script> --}}
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>


    <script>
        var apiUrl = "{{ config('app.url_ajax') }}";

        $(document).ready(function() {
            $('#tauxBtn').click(function() {
                console.log('jai cliqué');
                $.ajax({
                    url: apiUrl + '/taux-par-defaut',
                    type: 'GET',
                    success: function(response) {
                        // window.location.href = response.redirectUrl;
                        console.log('jai cliqué et succès', response);
                        $('#tauxMsg').removeClass('d-none');
                        $('#tauxMsg').html(response.message);
                    },
                    error: function(error) {
                        // La requête a échoué, vous pouvez gérer l'erreur ici
                        $('#tauxMsg').removeClass('d-none');
                        $('#tauxMsg').html('Erreur lors de la maj des taux de bases');

                    }
                });
            });
        });
    </script>

    <script>
        $('#id_art_sel').select2({
            width: 'resolve'
        });
    </script>

    <script>

        function show_modal(id, unite_nom, taux_conversion){
            document.getElementById('article_id').value = id;
            document.getElementById('unite').value = unite_nom;
            document.getElementById('taux_conversion').value = taux_conversion;

            $("#editModal").modal('toggle');
        }

    </script>

    <script>
        $(document).ready(function() {
            var table = $('#example').DataTable();

            // Avant de soumettre le formulaire
            $('#myForm').on('submit', function(e) {
                e.preventDefault();

                // Collecter toutes les entrées
                var data = $.merge(table.$('input').serializeArray(), table.$('select').serializeArray());

                // Ajouter les données collectées comme champs cachés au formulaire
                $.each(data, function(i, field) {
                    $('<input>').attr({
                        type: 'hidden',
                        name: field.name,
                        value: field.value
                    }).appendTo('#myForm');
                });

                // Soumettre le formulaire normalement
                this.submit();
            });
        });

    </script>
@endsection
