@extends('layout.template')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle d-flex">
            <div class="col-6">
                <h1 class="float-left">Utilisateurs</h1>
            </div>
            <div class="col-6 justify-content-end">
                @can('users.user-create')
                    <div class="">
                        <a href="{{ route('users.create') }}" class="btn btn-primary float-end"> + Ajouter un utilisateur</a>
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
                            <h5 class="card-title">Liste des utilisateurs</h5>

                            <!-- Table with stripped rows -->
                            <table class="table datatable" id="usersTable">
                                <thead>
                                    <tr>
                                        <th>N°</th>
                                        <th>
                                            Nom et Prénom(s)
                                        </th>
                                        <th>Email</th>
                                        <th>Contact</th>
                                        <th>Rôle</th>
                                        {{-- <th>Action</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($users as $user)
                                        <tr>
                                            <td>{{ $user->id }} </td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->phone }}</td>
                                            <td>{{ $user->roles[0]->name }}</td>
                                            {{-- <td>
                                                <a href="{{route('users.show', $user->id )}}" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Voir détails"> <i class="bi bi-eye"></i> </a>
                                                <a href="{{route('users.edit', $user->id )}}" class="btn btn-success" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Modifier utilisateur"> <i class="bi bi-pencil"></i> </a>
                                            </td> --}}
                                        </tr>
                                    @empty
                                        <tr>Aucun utilisateur</tr>
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

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.6/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.6/css/jquery.dataTables.min.css">
    <script>
        // $(document).ready(function() {
        //     $('#usersTable').DataTable({
        //         processing: true,
        //         serverSide: true,
        //         ajax: "{{ route('listUsers') }}",
        //         columns: [{
        //                 data: 'id'
        //             },
        //             {
        //                 data: 'name'
        //             },
        //             {
        //                 data: 'email'
        //             },
        //             {
        //                 data: 'phone'
        //             },
        //             {
        //                 data: 'role'
        //             }, // Use the 'role' column here
        //             {
        //                 data: 'phone'
        //             },
        //         ]
        //     });
        // });

        // $(document).ready(function() {
        //     // Fetch data using AJAX and populate the table
        //     $.ajax({
        //         url: '{{ route('listUsers') }}',
        //         type: 'GET',
        //         success: function(response) {
        //             const dataTable = $('#usersTable').DataTable({
        //                 data: response.data,
        //                 columns: [{
        //                         data: 'id'
        //                     },
        //                     {
        //                         data: 'name'
        //                     },
        //                     {
        //                         data: 'email'
        //                     },
        //                     {
        //                         data: 'phone'
        //                     },
        //                     {
        //                         data: 'role'
        //                     },
        //                     {
        //                         data: 'role'
        //                     },

        //                 ]
        //             });
        //         }
        //     });
        // });
    </script>
@endsection
