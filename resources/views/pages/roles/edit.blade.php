@extends('layout.template')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Rôles</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Tabelau de bord</a></li>
                    <li class="breadcrumb-item">Rôles</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section">
            <div class="row">

                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title text-dark">Modifier un Rôle</h5>

                            {!! Form::model($role, ['method' => 'PATCH', 'route' => ['roles.update', $role->id]]) !!}
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>Nom du rôle:</strong>
                                        {!! Form::text('name', null, ['placeholder' => 'Nom', 'class' => 'form-control']) !!}
                                    </div>
                                </div>
                                <br><br>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>Permissions:</strong>
                                        <div class="row">
                                            @foreach ($permission as $value)
                                                <div class="col-md-3">
                                                    <label>{{ Form::checkbox('permission[]', $value->name, in_array($value->id, $rolePermissions) ? true : false, array('class' => 'name')) }}
                                                        {{ $value->label }}</label>
                                                </div>
                                            @endforeach
                                        </div>

                                    </div>
                                </div>
                                <div class="col-lg-12 mt-5 d-flex flex-row align-items-center justify-content-between">
                                    <button type="submit" class="btn btn-sm btn-dark text_orange w-100 submitBtn" id="ajouterArticle"><i class="bi bi-check-circle"></i> Enregistrer</button>
                                    <button type="button" class="btn btn-sm btn-dark text_orange w-100 loadingBtn" hidden><span class="spinner-border spinner-border-sm text_orange loading"></span> En cours ...</button>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>
@endsection
