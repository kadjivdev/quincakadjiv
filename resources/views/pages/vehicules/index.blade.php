@extends('layout.template')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle d-flex">
            <div class="col-6">
                <h1 class="float-left">Chauffeurs</h1>
            </div>
            <div class="col-6 justify-content-end">
                <div class="">
                    {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop3">
                        Importer chauffeur
                    </button> --}}
                    <a href="{{ route('vehicules.create') }}" class="btn btn-sm bg-dark text_orange float-end"> + Ajouter un
                        Véhicule</a>
                </div>
            </div>
        </div><!-- End Page +++ -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body py-2">
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
                            <h5 class="card-title text-dark">Liste des Véhicules</h5>

                            <!-- Table with stripped rows -->
                            <table id="example" class="table table-bordered border-warning  table-hover table-striped table-sm">
                                <thead class="table-dark">
                                    <tr>
                                        <th>N°</th>
                                        <th>Immatriculation</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($vehicules as $vehicule)
                                        <tr>
                                            <td>{{ $i++ }} </td>
                                            <td>{{ $vehicule->num_vehicule }}</td>
                                            <td class="text-center"><a href="{{ route('vehicules.edit', $vehicule->id) }}"
                                                class="btn btn-sm bg-dark text_orange" data-bs-toggle="tooltip" data-bs-placement="left"
                                                data-bs-title="Modifier vehicule"> <i class="bi bi-pencil"></i> </a></td>
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

            <div class="modal fade" id="staticBackdrop3" data-bs-keyboard="false" tabindex="-1"
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
                                <div class="col-lg-12 d-flex flex-row align-items-center justify-content-between">
                                    <button type="submit" class="btn btn-sm btn-dark text_orange w-100 submitBtn"><i class="bi bi-check-circle"></i> Enregistrer</button>
                                    <button type="button" class="btn btn-sm btn-dark text_orange w-100 loadingBtn" hidden><span class="spinner-border spinner-border-sm text_orange loading"></span> En cours ...</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
