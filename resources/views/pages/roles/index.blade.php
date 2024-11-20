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
                        <a href="{{ route('roles.create') }}" class="btn btn-primary float-end"> + Ajouter un role</a>
                    </div>
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
                            <h5 class="card-title">Liste des Rôles</h5>

                            <!-- Table with stripped rows -->
                            <table id="example" class=" table table-bordered border-warning  table-hover table-warning table-sm">
                                <thead>
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
                                            <td>
                                                @can('roles.role-show')
                                                    <a class="btn btn-info" href="{{ route('roles.show', $role->id) }}">Show</a>
                                                @endcan
                                                @can('roles.modifier-role')
                                                    <a class="btn btn-primary"
                                                        href="{{ route('roles.edit', $role->id) }}">Edit</a>
                                                @endcan
                                                @can('roles.supprimer-role')
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['roles.destroy', $role->id], 'style' => 'display:inline']) !!}
                                                    {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
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
                            <h5 class="card-title">UPDATE STOCK</h5>

                            <div class="row">
                                <form action="{{ route('update_art_point_vte') }}" method="POST" class="col-3">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success" onclick="return confirm('Êtes-vous sûr de vouloir mettre à jour les articles point de vente ?')">MAJ Article Point de vente</button>
                                </form>

                                <form action="{{ route('update_stk_point_vte') }}" method="POST" class="col-3">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir mettre à jour le stock des articles point de vente ?')">MAJ Stock Point de vente</button>
                                </form>

                                <form action="{{ route('update_unite_mesure') }}" method="POST" class="col-3">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-primary" onclick="return confirm('Êtes-vous sûr de vouloir mettre à jour les unités de mésures ?')">MAJ Unités Mésures</button>
                                </form>

                                <form action="{{ route('update_cpt_clt') }}" method="POST" class="col-3">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-warning" onclick="return confirm('Êtes-vous sûr de vouloir mettre à jour les comptes clients ?')">MAJ Compte Client</button>
                                </form>

                                <form action="{{ route('update_cpt_frs') }}" method="POST" class="col-3">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-warning" onclick="return confirm('Êtes-vous sûr de vouloir mettre à jour les comptes fournisseurs ?')">MAJ Compte Frs</button>
                                </form>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </section>

    </main>
@endsection
