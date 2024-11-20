@extends('layout.template')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Unités de mesure</h1>
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
                            <h5 class="card-title">Ajouter une unité </h5>

                            <!-- Vertical Form -->
                            <form class="row g-3" action="{{ route('unites.store') }}" method="POST">
                                @csrf

                                <div class="col-12">
                                    <label for="" class="form-label">Nom de l'unité</label>
                                    <input type="text" class="form-control" name="unite">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Abbréviation de l'unité</label>
                                    <input type="text" class="form-control" name="abbrev">
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                                    <div class="loader"></div>

                                    <button type="reset" class="btn btn-secondary">Annuler</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
