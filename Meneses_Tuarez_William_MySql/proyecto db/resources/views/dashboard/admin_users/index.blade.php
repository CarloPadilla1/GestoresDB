@extends('layouts.app')
@section('title', 'Dashboard Users')

@section('content')
    <div class="container">
        <h2>Admin Users</h2>
        <div class="mb-3">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createUserModal">
                Create User
            </button>
        </div>

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

        <!-- Modal para crear usuario -->
        <div class="modal fade" id="createUserModal" tabindex="-1" role="dialog" aria-labelledby="createUserModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createUserModalLabel">Create User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    <form action="{{ route('users_db.create') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="username">Nombre de Usuario</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Confirmar Contraseña</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>

                        <div class="form-group">
                            <label>Privilegios</label>
                            <div class="row">
                                @php
                                    $privileges = [
                                        'Select_priv', 'Insert_priv', 'Update_priv', 'Delete_priv',
                                        'Create_priv', 'Drop_priv', 'Reload_priv', 'Shutdown_priv',
                                        'Process_priv', 'File_priv', 'Grant_priv', 'References_priv',
                                        'Index_priv', 'Alter_priv', 'Show_db_priv', 'Super_priv',
                                        'Create_tmp_table_priv', 'Lock_tables_priv', 'Execute_priv',
                                        'Repl_slave_priv', 'Repl_client_priv', 'Create_view_priv',
                                        'Show_view_priv', 'Create_routine_priv', 'Alter_routine_priv',
                                        'Create_user_priv', 'Event_priv', 'Trigger_priv',
                                        'Create_tablespace_priv', 'Delete_history_priv'
                                    ];
                                @endphp

                                @foreach (array_chunk($privileges, ceil(count($privileges) / 3)) as $privilegeChunk)
                                    <div class="col-md-4">
                                        @foreach ($privilegeChunk as $privilege)
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="{{ $privilege }}" name="privileges[{{ $privilege }}]" value="Y">
                                                <label class="form-check-label" for="{{ $privilege }}">{{ str_replace('_', ' ', $privilege) }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Crear Usuario</button>
                    </form>


                    </div>
                </div>
            </div>
        </div>  
                                    
        <!-- Tabla de usuarios -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->username }}</td>
                        <td>
                            <!-- Botón para modificar -->
                            <form action="{{ route('users_db.edit', $user->user_id) }}" method="GET" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-primary">Modify</button>
                            </form>

                            <!-- Botón para eliminar -->
                            <form action="{{ route('users_db.delete', $user->user_id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>

                            <!-- Botón para asignar rol -->
                            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#assignRoleModal{{ $user->user_id }}">
                                Assign Role
                            </button>

                            <!-- Modal para asignar rol -->
                            <div class="modal fade" id="assignRoleModal{{ $user->user_id }}" tabindex="-1" role="dialog" aria-labelledby="assignRoleModalLabel{{ $user->user_id }}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="assignRoleModalLabel{{ $user->user_id }}">Assign Role</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('users_db.assignRole', $user->user_id) }}" method="POST">
                                                @csrf
                                                @method('POST')
                                                <div class="form-group">
                                                <label>Privilegios</label>
                                                <div class="row">
                                                    @php
                                                        $privileges = [
                                                            'Select_priv', 'Insert_priv', 'Update_priv', 'Delete_priv',
                                                            'Create_priv', 'Drop_priv', 'Reload_priv', 'Shutdown_priv',
                                                            'Process_priv', 'File_priv', 'Grant_priv', 'References_priv',
                                                            'Index_priv', 'Alter_priv', 'Show_db_priv', 'Super_priv',
                                                            'Create_tmp_table_priv', 'Lock_tables_priv', 'Execute_priv',
                                                            'Repl_slave_priv', 'Repl_client_priv', 'Create_view_priv',
                                                            'Show_view_priv', 'Create_routine_priv', 'Alter_routine_priv',
                                                            'Create_user_priv', 'Event_priv', 'Trigger_priv',
                                                            'Create_tablespace_priv', 'Delete_history_priv'
                                                        ];
                                                    @endphp

                                                    @foreach (array_chunk($privileges, ceil(count($privileges) / 3)) as $privilegeChunk)
                                                        <div class="col-md-4">
                                                            @foreach ($privilegeChunk as $privilege)
                                                                <div class="form-check">
                                                                    <input type="checkbox" class="form-check-input" id="{{ $privilege }}" name="privileges[{{ $privilege }}]" value="Y">
                                                                    <label class="form-check-label" for="{{ $privilege }}">{{ str_replace('_', ' ', $privilege) }}</label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <button type="submit" class="btn btn-primary">Asignar Roles</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
