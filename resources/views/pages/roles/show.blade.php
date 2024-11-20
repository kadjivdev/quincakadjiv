@extends('layout.template')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle d-flex">
            <div class="col-6">
                <h1 class="float-left">Rôles</h1>
            </div>
            <div class="col-6 justify-content-end">
                @can('modifier-role')
                    <div class="">
                        <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-primary"> Modifier role</a>
                    </div>
                @endcan
            </div>
        </div><!-- End Page +++ -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Détail du Role {{ $role->name }}</h5>

                            <!-- Table with stripped rows -->
                            <div class="row">
                                <div class="col-md-12">
                                    <strong>Les Permissions:</strong>
                                    @if (!empty($rolePermissions))
                                        @foreach ($rolePermissions as $v)
                                            <label class="label label-success">{{ $v->label }},</label>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

    </main>
@endsection
