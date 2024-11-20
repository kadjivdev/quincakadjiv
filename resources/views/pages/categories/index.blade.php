@extends('layout.template')
@section('content')
<main id="main" class="main">
    <div class="pagetitle d-flex">
        <div class="col-6">
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item text-dark"><a href="/">Tableau de Bord</a></li>
                    <li class="breadcrumb-item text-dark active">Catégorie</li>
                </ol>
            </nav>
        </div>
        <div class="col-6 d-flex flex-row justify-content-end">
            <div class="">
                {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                        Importer
                    </button> --}}
                <span class="badge rounded-pill bg-dark">
                    {{ count($categories) }} Catégories au total
                </span>
            </div>
        </div>
    </div><!-- End Page +++ -->

    <section class="section">
        <div class="row">
            <div class="col-12">
                <!-- Afficher des messages de succès -->
                @if (session('success'))
                <div class="alert alert-success  d-flex flex-row align-items-center" role="alert">
                    <button type="button" class="btn btn-sm text-red float-right" data-bs-dismiss="alert"><i class="bi bi-x-circle"></i></button>
                    {{ session('success') }}
                </div>
                @endif

                <!-- Afficher des erreurs de validation -->
                @if ($errors->any())
                <div class="alert alert-danger  d-flex flex-row align-items-center" role="alert">
                    <button type="button" class="btn btn-sm text-red float-right" data-bs-dismiss="alert"><i class="bi bi-x-circle"></i></button>
                    @foreach ($errors->all() as $error)
                    {{ $error }}
                    @endforeach
                </div>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-dark">Liste des categories</h5>
                        <table id="example"
                            class=" table table-bordered border-warning  table-hover  table-sm table-striped"
                            style="width:100%">
                            <thead class="table-dark">
                                <tr>
                                    <th width="3%">N°</th>
                                    <th width="94%">Nom categorie</th>
                                    <th width="3%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($categories as $categorie)
                                <tr>
                                    <td>{{ $categorie->id }} </td>
                                    <td>{{ $categorie->libelle }}</td>
                                    <td>
                                        <div class="dropdown-center">
                                            <button class="btn bg_dark text_orange w-100 dropdown-toggle btn-small"
                                                type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <!-- <i class="bi bi-gear"></i> -->
                                                <i class="bi bi-hand-index-thumb"></i>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                @can('articles.edit-category')
                                                <li class="bg-light">
                                                    <!-- <a href="{{ route('categories.show', $categorie->id) }}" class="dropdown-item btn-sm "><i class="bi bi-pencil"></i> Modifier</a> -->
                                                    <a href="#" class="dropdown-item btn-sm" onclick="getCategory({{$categorie->id}})" data-bs-toggle="modal" data-bs-target="#updateCategory"><i class="bi bi-pencil"></i> Modifier</a>
                                                </li>
                                                @endcan

                                                <!-- can('articles.delete-category') -->
                                                <li>
                                                    <form action="{{ route('categories.destroy', $categorie->id) }}"
                                                        class="form-inline" method="POST"
                                                        onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text_orange"><i class="bi bi-trash3"></i> Supprimer</button>
                                                    </form>
                                                </li>
                                                <!-- endcan -->
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>Aucune categorie enregistré.</tr>
                                @endforelse

                            </tbody>
                        </table>

                    </div>
                </div>

            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-dark">Ajouter une Catégorie</h5>

                        <!-- Vertical Form -->
                        <form class="row g-3" action="{{ route('categories.store') }}" method="POST">
                            @csrf
                            <div class="col-12">
                                <label for="inputNanme4" class="form-label">Nom de catégorie</label>
                                <input type="text" class="form-control" name="libelle">
                            </div>

                            <div class="col-lg-12 d-flex flex-row align-items-center justify-content-between">
                                <button type="submit" class="btn btn-sm btn-dark text_orange w-100"><i class="bi bi-check-circle"></i> Enregistrer</button>
                                <div class="loader"></div>
                                <button class="btn btn-dark button_loader w-100" id="myLoader" type="button" disabled>
                                    <span class="spinner-border spinner-border-sm text_orange" aria-hidden="true"></span>
                                    <span role="status text_orange">En cours...</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('categorie-import') }} " method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Formulaire d'import</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="col-12 mb-3">
                            <label for="inputNanme4" class="form-label">Fichier excel</label>
                            <input type="file" class="form-control" name="upload">
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

    <!-- UPDATE CATEGORY -->
    <div class="modal fade" id="updateCategory" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Modifier :
                        <span class="spinner-border spinner-border-sm text_orange loading" hidden></span>
                        <em class="text_orange" id="category_name"></span> </em>
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updateCategoryForm" action="" method="post">
                    @csrf
                    @method("PATCH")
                    <div class="modal-body">
                        <input type="text" name="libelle" id="category" class="form-control">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-sm btn-dark text_orange w-100" onclick="submit()"><span class="spinner-border spinner-border-sm text_orange loading" hidden></span> <i class="bi bi-check-circle" id="submit_icon"></i> Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script type="text/javascript">
        function getCategory(Id) {
            $(".loading").removeAttr("hidden")
            $("#category").val()
            $("#category_name").empty()

            axios.get("/categories/" + Id + "/retrieve").then((response) => {
                var data = response.data
                $("#category").val(data.libelle)
                $("#category_name").html(data.libelle)
                $("#updateCategoryForm").attr("action", `/categories/${data.id}/update`)
                $(".loading").attr("hidden", "hidden")

            }).catch((error) => {
                console.log(error)
                alert("une erreure s'est produite")
            })
        }

        function submit() {
            $(".loading").removeAttr("hidden")
            $("#submit_icon").attr("hidden", "hidden")
        }
    </script>
</main>
@endsection