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
                        <h5 class="card-title text-dark">Ajouter un Rôle</h5>

                        @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        {!! Form::open(['route' => 'roles.store', 'method' => 'POST']) !!}

                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Nom du rôle:</strong>
                                    {!! Form::text('name', null, ['placeholder' => 'Nom', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                            <br>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <br><br>
                                <div class="form-group">
                                    <strong>Permissions:</strong>
                                    <br />
                                    <div class="row">
                                        @php
                                        $previousGroup = '';
                                        @endphp
                                        @foreach ($permissions as $permission)
                                        @if ($permission->group !== $previousGroup)
                                        <div class="col-md-12">
                                            <strong>{{ strtoupper($permission->group) }}</strong>
                                        </div>
                                        @php
                                        $previousGroup = $permission->group;
                                        @endphp
                                        @endif
                                        <div class="col-md-3">
                                            <label>
                                                {{ Form::checkbox('permission[]', $permission->name, false, ['class' => 'name']) }}
                                                {{ $permission->label }}
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <br>
                            <br>
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