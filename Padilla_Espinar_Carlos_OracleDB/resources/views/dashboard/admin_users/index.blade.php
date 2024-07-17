@extends('layouts.app')
@section('title', 'Dashboard Users')

@section('content')
    <h2>Admin Users</h2>
    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#createUserModal">
        Create User
    </button>
    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#createRoleModal">
        Create Role
    </button>
    <a href="{{ route('roles.viewRoles') }}" class="btn btn-primary mb-3">Admin Roles</a>


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
                    <form  method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="name" name="name" required autocomplete="false">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required autocomplete="false">
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required autocomplete="false">
                        </div>
                        <div class="form-group">
                            <label for="role">Role</label>
                            <select class="form-control" id="role" name="role">
                                @foreach ($roles as $rol)
                                    <option value="{{ $rol->role }}">{{ $rol->role }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tablespace">Tablespace</label>
                            <select class="form-control" id="tablespace" name="tablespace">
                                @foreach ($tablespaces as $tablespace)
                                    <option value="{{ $tablespace->tablespace_name }}">{{ $tablespace->tablespace_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="quota">Quota</label>
                            <select class="form-control" name="quota" id="qouta">
                                <option value="UNLIMITED">UNLIMITED</option>
                                <option value="100M">100M</option>
                                <option value="500M">500M</option>
                                <option value="1G">1G</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createRoleModal" tabindex="-1" role="dialog" aria-labelledby="createRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen" role="document"> <!-- Clase personalizada para un modal más ancho -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createRoleModalLabel">Create Role</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{route('roles.createRole')}}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="role">Role</label>
                            <input type="text" class="form-control" id="role" name="role" required>
                        </div>
                        <div class="form-group">
                            <label for="privileges">Privileges System</label>
                            <div class="row">
                                @php
                                    $chunks = array_chunk($privileges, 5); // Divide el array de privilegios en grupos de 10
                                    $count = 1; // Contador para los privilegios
                                @endphp
                                @foreach ($chunks as $chunk)
                                    <div class="col-md-4"> <!-- Cambiado a col-md-4 para más columnas -->
                                        @foreach ($chunk as $item)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="privilege{{ $count }}" name="privilege[]" value="{{ $item->privilege }}">
                                                <label class="form-check-label" for="privilege{{ $count }}">{{ $item->privilege }}</label>
                                            </div>
                                            @php $count += 1; @endphp
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Create Role</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <table class="table">
        <thead>
            <tr>
                <th>Username</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $item)
            <tr>
                <td>{{ $item->username }}</td>
                <td>
                <!-- Botón para modificar -->
                <form action="{{ route('users_db.edit', $item->user_id) }}" method="GET" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-primary">Modify</button>
                </form>

                <!-- Botón para eliminar -->
                <form action="{{ route('users_db.delete', $item->user_id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>

                <!-- Botón para asignar rol -->
                <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#assignRoleModal{{ $item->user_id }}">
                    Assign Role
                </button>

                <!-- Modal para asignar rol -->
                    <div class="modal fade" id="assignRoleModal{{ $item->user_id }}" tabindex="-1" role="dialog" aria-labelledby="assignRoleModalLabel{{ $item->user_id }}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title" id="assignRoleModalLabel{{ $item->user_id }}">Assign Role</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                            <div class="modal-body">
                            <form action="{{ route('users_db.assignRole', $item->user_id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="role">Role</label>
                                    @foreach ($roles as $rol)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="role{{ $rol->role_id }}" name="role[]" value="{{ $rol->role }}" {{ in_array($rol->role, $item->granted_roles) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="role{{ $rol->role_id }}">{{ $rol->role }}</label>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="submit" class="btn btn-primary">Assign</button>
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
@endsection
