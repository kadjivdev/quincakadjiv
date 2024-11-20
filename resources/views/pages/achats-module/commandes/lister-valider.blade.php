@extends('layout.template')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle d-flex">
            <div class="col-6">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Tableau de Bord</a></li>
                        <li class="breadcrumb-item active">Bon de Commande</li>

                    </ol>
                </nav>

            </div>

            <div class="col-3">

                    <a href="{{ route('commandes.index') }}" class="btn btn-warning float-end petit_bouton">
                        <i class="bi bi-check-circle-fill"></i>
                        Bon non Validés</a>

                </div>

            <div class="col-3">

                    @can('fournisseurs.ajouter-fournisseur')
                        <a href="{{ route('commandes.create') }}" class="btn btn-dark float-end petit_bouton"> <i
                                class="bi bi-plus-circle"></i> Ajouter une Bon</a>
                    @endcan

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
                                <h5 class="card-title mb-0">Liste des Bons de Commande non Validés</h5>
                                <span class="badge rounded-pill bg-dark">{{ count($commandes) }} Bon en attente au
                                    total</span>
                            </div>
                            <!-- Table with stripped rows -->
                            <table id="example"
                                class="table table-bordered border-warning  table-hover table-warning table-sm">
                                <thead>
                                    <tr>
                                        <th>N°</th>
                                        <th>Programmation</th>
                                        <th>Date bon</th>
                                        <th>Référence bon</th>
                                        <th>Montant</th>
                                        <th>Fournisseur</th>
                                        <th>Statut</th>
                                        <th>Date de Création</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    @forelse ($commandes as $commande)
                                        <?php $i++; ?>
                                        <tr>
                                            <td>{{ $i }} </td>
                                            <td>{{ $commande->bonRef }} </td>
                                            <td>{{ Carbon\Carbon::parse($commande->date_cmd)->locale('fr_FR')->isoFormat('ll') }}
                                            </td>
                                            <td>{{ $commande->reference }}</td>
                                            <td>{{ number_format($commande->total_montant, 2, ',', ' ') }}</td>
                                            <td>{{ $commande->name }}</td>
                                            <td>
                                                @if (is_null($commande->validated_at))
                                                    <span class="badge rounded-pill text-bg-warning">Non validée</span>
                                                @else
                                                    <span class="badge rounded-pill text-bg-success">Validée</span>
                                                @endif
                                            </td>
                                            <td>{{ $commande->created_at }} </td>

                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-dark dropdown-toggle btn-small" type="button"
                                                        id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        <i class="bi bi-gear"></i>
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                        @can('bon-commandes.voir-commande')
                                                            <li>
                                                                <a href="{{ route('commandes.show', $commande->id) }}"
                                                                    data-bs-toggle="tooltip" data-bs-placement="left"
                                                                    data-bs-title="Voir les détails du bon"
                                                                    class="dropdown-item"> Voir détail </a>
                                                            </li>
                                                            <li>
                                                                <a target="_blank"
                                                                    href="{{ url('generate_bon_cde', $commande->id) }}"
                                                                    class="dropdown-item" data-bs-toggle="tooltip"
                                                                    data-bs-placement="left"
                                                                    data-bs-title="Générer le pdf à imprimer"> Générer PDF </a>
                                                            </li>
                                                        @endcan

                                                        @can('bon-commandes.modifier-commande')
                                                            @if (is_null($commande->validated_at))
                                                                <li>
                                                                    <a href="{{ route('commandes.edit', $commande->id) }}"
                                                                        class="dropdown-item text-warning"> Modifier la
                                                                        Commande</a>
                                                                </li>
                                                            @endif

                                                            @if (is_null($commande->validated_at))
                                                                <li>
                                                                    <form
                                                                        action="{{ route('commandes.destroy', $commande->id) }}"
                                                                        class="form-inline" method="POST"
                                                                        onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette commande?');">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="dropdown-item text-danger"
                                                                            data-bs-toggle="tooltip" data-bs-placement="left"
                                                                            data-bs-title="Supprimer le bon ">Supprimer la
                                                                            Commande</button>
                                                                    </form>

                                                                </li>
                                                            @endif
                                                        @endcan

                                                    </ul>
                                                </div>
                                                <!-- @if (!is_null($commande->validated_at))
                                                        <a href="#"></a>
                                                    @else
                                                        @can('bon-commandes.modifier-commande')
                                                            <a href="{{ route('commandes.edit', $commande->id) }}" class="btn btn-success"> <i class="bi bi-pencil"></i> </a>
                                                        @endcan
                                                        @endif -->

                                            </td>
                                        </tr>
                                    @empty
                                        <tr>Aucun bon de commande enregistré</tr>
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
