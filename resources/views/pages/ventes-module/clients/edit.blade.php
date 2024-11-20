@extends('layout.template')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Clients</h1>
        </div><!-- End Page Title -->
        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
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
                        <div class="card-body">
                            <h5 class="card-title">Modifier un client</h5>

                            <!-- Vertical Form -->
                            <form class="row g-3" action="{{ route('clients.update', $client->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="col-12">
                                    <label class="form-label">Nom et prénoms</label>
                                    <input type="text" class="form-control" value="{{$client->nom_client}}" name="nom_client">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" value="{{$client->email}}" name="email">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Téléphone</label>
                                    <input type="text" class="form-control" value="{{$client->phone}}" name="phone">
                                </div>
                                <div class="col-12">
                                    <label for="inputAddress" class="form-label">Adresse</label>
                                    <input type="text" class="form-control" value="{{$client->address}}" name="address" id="inputAddress"
                                        placeholder="1234 Main St">
                                </div>

                                <div class="col-12">
                                    <label for="departement" class="form-label">Département</label>
                                    <select class="form-control" id="departement" name="departement" >
                                        <option value=""></option>
                                        @foreach ($departements as $departement)
                                            <option @if ($departement->id == $client->departement_id) selected @endif value="{{$departement->id}}">{{$departement->libelle}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label for="departement" class="form-label">Agent</label>
                                    <select class="form-control" id="agent" name="agent" >
                                        <option value=""></option>
                                        @foreach ($agents as $agent)
                                            <option  @if ($agent->id == $client->agent_id) selected @endif value="{{$agent->id}}">{{$agent->nom}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Seuil limite (crédit) </label>
                                    <input type="number" class="form-control" value="{{$client->seuil}}" name="seuil"
                                        placeholder="2 000 000">
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Modifier</button>
                                    <div class="loader"></div>

                                    <button type="reset" class="btn btn-secondary">Annuler</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </section>

        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script>
            $(document).ready(function() {
                $("#connectBtn").on("click", function() {
                    // Afficher le loader
                    $(".myLoader").show();
                    setTimeout(function() {
                        $(".myLoader").hide();
                    }, 2000);
                });
            });
        </script>

    </main>
@endsection
