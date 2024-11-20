@extends('layout.template')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle d-flex">
            <div class="col-6">
                <h1 class="float-left">Unités de mesure</h1>
            </div>
            <div class="col-6 justify-content-end">
                <div class="">
                    <a href="{{ route('unites.create') }}" class="btn btn-primary float-end"> + Ajouter une unité</a>
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
                            <h5 class="card-title">Liste des unités mesures</h5>

                            <!-- Table with stripped rows -->
                            <table id="dataTable" class="table datatable">
                                <thead>
                                    <tr>
                                        <th>N°</th>
                                        <th>Unité de mesure</th>
                                        <th>Abbréviation</th>
                                        {{-- <th>Action</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($unites as $mesure)
                                        <tr>
                                            <td>{{ $i++ }} </td>
                                            <td>{{ $mesure->unite }} ({{ $mesure->abbrev }}) </td>
                                            <td>{{ $mesure->abbrev }}</td>
                                            {{-- <td>{{ $mesure->taux->parentUnite }}</td> --}}
                                            {{-- <td>
                                                <a class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#staticBackdrop{{ $mesure->id }}"> <i
                                                        class="bi bi-eye"></i> </a>
                                            </td> --}}
                                        </tr>

                                        <!-- Modal -->
                                        <div class="modal fade" id="staticBackdrop{{ $mesure->id }}"
                                            data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                                            aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('tauxSupplements.store') }}" method="post">
                                                        @csrf

                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Ajouter
                                                                les taux de conversion</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            @csrf
                                                            <div class="col-12">
                                                                <label for="" class="form-label">Unité de
                                                                    base</label>
                                                                <input type="text" readonly value="{{ $mesure->unite }}"
                                                                    required class="form-control" name="unite_mesure">
                                                                <input type="hidden" value="{{ $mesure->id }}"
                                                                    name="unite_mesure_id">
                                                            </div>
                                                            <div class="col-12">
                                                                <label class="form-label">Taux de conversion</label>
                                                                <input type="text" pattern="[0-9]+([,\.][0-9]+)?"
                                                                    class="form-control" name="taux_conversion"
                                                                    value="{{ old('taux_conversion') }}"
                                                                    placeholder="Ex: 1 ou 2.6 (Nombre entier ou nombre à virgule)">
                                                                @if (old('taux_conversion') && !preg_match('/^[0-9]+(?:\.[0-9]+)?$/', old('taux_conversion')))
                                                                    <div class="alert alert-danger">
                                                                        Le champ doit contenir des chiffres ou un nombre à
                                                                        virgule.
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="col-12">
                                                                <label for="">Unité de mesure </label>
                                                                <select name="parent_id" id="parent_id" class="form-select">
                                                                    <option value="">Choisir l'unité de conversion
                                                                    </option>
                                                                    @foreach ($unites as $unite)
                                                                        <option value="{{ $unite->id }}">
                                                                            {{ $unite->unite }}</option>
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
                                    @empty
                                        <tr>Aucune unité de mesure enregistré.</tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <!-- End Table with stripped rows -->

                        </div>
                    </div>

                </div>
            </div>
        </section>
    </main>
@endsection
