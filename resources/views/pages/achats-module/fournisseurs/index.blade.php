@extends('layout.template')
@section('content')
<main id="main" class="main">

    <div class="pagetitle d-flex">
        <div class="col-6">
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Tableau de Bord</a></li>
                    <li class="breadcrumb-item active">Fournisseur</li>

                </ol>
            </nav>
        </div>
        <div class="col-6 d-flex flex-row justify-content-end">
            <div class="">
                @can('fournisseurs.ajouter-fournisseur')
                <a href="{{ route('fournisseurs.create') }}" class="btn bg_orange float-end petit_bouton"> <i
                        class="bi bi-plus-circle"></i> Ajouter un
                    Fournisseur</a>
                @endcan
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
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Liste des fournisseurs</h5>
                            <span class="badge rounded-pill bg-dark">{{ count($fournisseurs) }} Fournisseurs au
                                total</span>
                        </div>

                        <table id="example"
                            class="table table-bordered border-warning  table-hover table-sm table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th width="3%">N°</th>
                                    <th width="35%">
                                        Nom et Prénom(s)
                                    </th>

                                    <th width="30%">Contact</th>
                                    <th width="30%">Solde</th>
                                    <th width="2%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($fournisseurs as $fournisseur)
                                <tr>
                                    <td>{{ $fournisseur->id }} </td>
                                    <td>{{ $fournisseur->name }}</td>

                                    <td>{{ $fournisseur->phone }}</td>
                                    <td>{{ number_format(($total_restants[$fournisseur->id] ?? 0) + ($total_restants1[$fournisseur->id] ?? 0), 2, ',', ' ') }}
                                    </td>

                                    <td>
                                        <div class="dropdown">
                                            <button class="btn bg_dark w-100 text_orange dropdown-toggle btn-small"
                                                type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <i class="bi bi-hand-index"></i>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                <li>
                                                    @can('fournisseurs.editer-fournisseur')
                                                    <a href="{{ route('fournisseurs.edit', $fournisseur->id) }}"
                                                        class="dropdown-item" data-bs-toggle="tooltip"
                                                        data-bs-placement="left"
                                                        data-bs-title="Modifier fournisseur"> Modifier le
                                                        fournisseur </a>
                                                    @endcan
                                                </li>

                                                <li>
                                                    @can('fournisseurs.compte-fournisseur')
                                                    <a href="{{ route('fournisseurs.show', $fournisseur->id) }}"
                                                        class="dropdown-item" data-bs-toggle="tooltip"
                                                        data-bs-placement="left" data-bs-title="Voir détails">
                                                        Historique du Compte </a>
                                                    @endcan
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>Aucun Fournisseur enregistré</tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('frs-import') }} " method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Formulaire d'import</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="col-12 mb-3">
                            <label for="inputNanme4" class="form-label">Fichier excel</label>
                            <input type="file" class="form-control" name="upload_xls">
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
</main>

<script type="text/javascript">
    function submitForm() {
        $(".loading").removeAttr("hidden")
        $("#submit_icon").attr("hidden", "hidden")
    }
</script>
@endsection