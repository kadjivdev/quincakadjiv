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
                        <div class="card-body pt-1">
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
                            <h5 class="card-title text-dark">Ajouter un client</h5>

                            <!-- Vertical Form -->
                            <form class="row g-3" action="{{ route('clients.store') }}" method="POST">
                                @csrf
                                <div class="col-12">
                                    <label class="form-label">Nom et prénoms</label>
                                    <input type="text" class="form-control" name="nom_client">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Téléphone</label>
                                    <input type="text" class="form-control" name="phone">
                                </div>
                                <div class="col-12">
                                    <label for="inputAddress" class="form-label">Adresse</label>
                                    <input type="text" class="form-control" name="address" id="inputAddress"
                                        placeholder="1234 Main St">
                                </div>

                                <div class="col-12">
                                    <label for="departement" class="form-label">Département</label>
                                    <select class="form-control" id="departement" name="departement" >
                                        <option></option>
                                        @foreach ($departements as $departement)
                                            <option value="{{$departement->id}}">{{$departement->libelle}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label for="departement" class="form-label">Agent</label>
                                    <select class="form-control" id="agent" name="agent" >
                                        <option></option>
                                        @foreach ($agents as $agent)
                                            <option value="{{$agent->id}}">{{$agent->nom}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Seuil limite (avance) </label>
                                    <div class="input-group mb-3">
                                        <input type="number" min="1" max="100" class="form-control" required name="seuil" aria-label="Recipient's username" aria-describedby="basic-addon2">
                                        <span class="input-group-text" id="basic-addon2">%</span>
                                      </div>
                                </div>

                                <div class="col-lg-12 d-flex flex-row align-items-center justify-content-between">
                                    <button type="submit" class="btn btn-sm btn-dark text_orange w-100 submitBtn"><i class="bi bi-check-circle"></i> Enregistrer</button>
                                    <button type="button" class="btn btn-sm btn-dark text_orange w-100 loadingBtn" hidden><span class="spinner-border spinner-border-sm text_orange loading"></span> En cours ...</button>
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
