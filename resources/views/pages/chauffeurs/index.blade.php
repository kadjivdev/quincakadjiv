@extends('layout.template')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle d-flex">
            <div class="col-6">
                <h1 class="float-left">Chauffeurs</h1>
            </div>
            <div class="col-6 justify-content-end">
                <div class="">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop3">
                        Importer chauffeur
                    </button>
                    <a href="{{ route('chauffeurs.create') }}" class="btn btn-primary float-end"> + Ajouter un
                        chauffeur</a>
                </div>
            </div>
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
                            <h5 class="card-title">Liste des chauffeurs</h5>

                            <!-- Table with stripped rows -->
                            <table id="example" class="table table-bordered border-warning  table-hover table-warning table-sm">
                                <thead>
                                    <tr>
                                        <th>N°</th>
                                        <th>
                                            Nom et Prénom(s)
                                        </th>
                                        <th>Contact</th>
                                        <th>Permis</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($chauffeurs as $chauffeur)
                                        <tr>
                                            <td>{{ $i++ }} </td>
                                            <td>{{ $chauffeur->nom_chauf }}</td>
                                            <td>{{ $chauffeur->tel_chauf }}</td>
                                            <td>{{ $chauffeur->permis }}</td>
                                            <td><a href="{{ route('chauffeurs.edit', $chauffeur->id) }}"
                                                class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="left"
                                                data-bs-title="Modifier chauffeur"> <i class="bi bi-pencil"></i> </a></td>
                                        </tr>
                                    @empty
                                        <tr>Aucun chauffeur</tr>
                                    @endforelse

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="staticBackdrop3" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="staticBackdropLabel3" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="importForm" action="{{ route('chauffeur-import') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="staticBackdropLabel3">Formulaire d'import</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="col-12 mb-3">
                                    <label for="inputNanme4" class="form-label">Fichier excel</label>
                                    <input type="file" class="form-control" id="upload_xls" name="upload_xls">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
