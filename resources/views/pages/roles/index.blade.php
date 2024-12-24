@extends('layout.template')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle d-flex">
            <div class="col-6">
                <h1 class="float-left">Rôles</h1>
            </div>
            <div class="col-6 justify-content-end">
                @can('roles.creer-role')
                    <div class="">
                        <a href="{{ route('roles.create') }}" class="btn btn-sm bg-dark text_orange float-end"> + Ajouter un role</a>
                    </div>
                @endcan
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
                            <h5 class="card-title text-dark">Liste des Rôles</h5>

                            <!-- Table with stripped rows -->
                            <table id="example" class=" table table-bordered border-warning  table-hover table-striped table-sm">
                                <thead class="table-dark">
                                    <tr>
                                        <th>N°</th>
                                        <th>
                                            Rôle
                                        </th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($roles as $key => $role)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $role->name }}</td>
                                            <td class="text-center">
                                                @can('roles.role-show')
                                                    <a class="btn btn-sm bg-dark text_orange" href="{{ route('roles.show', $role->id) }}"><i class="bi bi-list"></i> Show</a>
                                                @endcan
                                                @can('roles.modifier-role')
                                                    <a class="btn btn-sm btn-light"
                                                        href="{{ route('roles.edit', $role->id) }}"><i class="bi bi-pencil"></i> Edit</a>
                                                @endcan
                                                @can('roles.supprimer-role')
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['roles.destroy', $role->id], 'style' => 'display:inline']) !!}
                                                    {!! Form::submit('Delete', ['class' => 'btn btn-sm bg-dark text_orange']) !!}
                                                    {!! Form::close() !!}
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title text-dark">UPDATE STOCK</h5>

                            <div class="row">
                                <form action="{{ route('update_art_point_vte') }}" method="POST" class="col-3">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm bg-dark text-light" onclick="return confirm('Êtes-vous sûr de vouloir mettre à jour les articles point de vente ?')"><i class="bi bi-pencil"></i> MAJ Article Point de vente</button>
                                </form>

                                <form action="{{ route('update_stk_point_vte') }}" method="POST" class="col-3">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm bg-dark text-light" onclick="return confirm('Êtes-vous sûr de vouloir mettre à jour le stock des articles point de vente ?')"><i class="bi bi-trash3"></i> MAJ Stock Point de vente</button>
                                </form>

                                <form action="{{ route('update_unite_mesure') }}" method="POST" class="col-3">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm bg-dark text-light" onclick="return confirm('Êtes-vous sûr de vouloir mettre à jour les unités de mésures ?')"><i class="bi bi-pencil"></i> MAJ Unités Mésures</button>
                                </form>

                                <form action="{{ route('update_cpt_clt') }}" method="POST" class="col-3">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm bg-dark text-light" onclick="return confirm('Êtes-vous sûr de vouloir mettre à jour les comptes clients ?')"><i class="bi bi-pencil"></i> MAJ Compte Client</button>
                                </form>

                                <form action="{{ route('update_cpt_frs') }}" method="POST" class="col-3">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm my-1 bg-dark text-light" onclick="return confirm('Êtes-vous sûr de vouloir mettre à jour les comptes fournisseurs ?')"><i class="bi bi-pencil"></i> MAJ Compte Frs</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>
@endsection
