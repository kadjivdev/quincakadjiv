@extends('layout.template')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle d-flex">
            <div class="col-6">
                <h1 class="float-left">Gestion des Agents</h1>
            </div>
            <div class="col-6 justify-content-end">
                <div class="">
                    {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop3">
                        Importer chauffeur
                    </button> --}}
                    <a href="{{ route('agents.create') }}" class="btn btn-sm bg-dark text_orange float-end"> + Ajouter un
                        Agent</a>
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
                            <h5 class="card-title text-dark">Liste des agents</h5>

                            <!-- Table with stripped rows -->
                            <table id="example" class="table table-bordered border-warning  table-hover table-striped table-sm">
                                <thead class="table-dark">
                                    <tr>
                                        <th>N°</th>
                                        <th>
                                            Nom et Prénom(s)
                                        </th>
                                        <th>Contact</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($agents as $agent)
                                        <tr>
                                            <td>{{ $i++ }} </td>
                                            <td>{{ $agent->nom }}</td>
                                            <td>{{ $agent->contact }}</td>
                                            <td class="text-center"><a href="{{ route('agents.edit', $agent->id) }}"
                                                class="btn btn-sm bg-dark text_orange" data-bs-toggle="tooltip" data-bs-placement="left"
                                                data-bs-title="Modifier agent"> <i class="bi bi-pencil"></i> </a></td>
                                        </tr>
                                    @empty
                                        <tr>Aucun agent</tr>
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
