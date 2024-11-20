@extends('layout.template')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Utilisateurs</h1>
        </div><!-- End Page Title -->
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
                            <h5 class="card-title">Ajouter un agent</h5>

                            <!-- Vertical Form -->
                            <form class="row g-3" action="{{ route('users.update') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="col-12">
                                    <label for="inputNanme4" class="form-label">Nom et prénom(s)</label>
                                    <input type="text" class="form-control" value="{{ $user->name }}" name="name">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Email</label>
                                    <input type="email" value="{{ $user->email }} class="form-control" name="email">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Téléphone</label>
                                    <input type="text" value="{{ $user->phone }} class="form-control" name="phone">
                                </div>
                                <div class="col-12">
                                    <label for="inputAddress" class="form-label">Adresse</label>
                                    <input type="text" class="form-control" value="{{ $user->address }} name="address"
                                        id="inputAddress" placeholder="1234 Main St">
                                </div>
                                <div class="col-12">
                                    <label for="">Rôle</label>
                                    <select name="roles[]" id="roles" class="form-select" multiple>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role }}"
                                                @if (in_array($role, $userRoles)) selected @endif>
                                                {{ $role }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Modifier</button>
                                    <button type="reset" class="btn btn-secondary">Annuler</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>
@endsection
