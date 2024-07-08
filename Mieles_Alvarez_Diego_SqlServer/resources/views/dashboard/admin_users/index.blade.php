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
                                <option value="" selected="true" disabled="disabled">Select a role</option>
                                @foreach ($roles as $rol)
                                    @if (!($rol->role === 'public'))
                                        <option value="{{ $rol->role }}">{{ $rol->role }}</option>
                                    @endif
                                @endforeach
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
                                    <div class="row" > <!-- Cambiado a col-md-4 para más columnas -->
                                        @foreach ($chunk as $item)
                                            <div class="col-6 p-4">
                                                <div class="form-check" >
                                                    <input class="form-check-input" type="checkbox" id="privilege{{ $count }}" name="privilege[]" value="{{ $item->privilege }}">
                                                    <label class="form-check-label" for="privilege{{ $count }}">{{ $item->privilege }}</label>
                                                </div>
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
                {{-- <form action="{{ route('users_db.edit', $item->user_id) }}" method="GET" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-primary">Modify</button>
                </form> --}}

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
                                            @if ($rol->role === 'public')
                                                <input class="form-check-input" type="checkbox" id="role{{ $rol->role_id }}" name="role[]" value="{{ $rol->role }}" disabled checked>
                                            @else
                                                <input class="form-check-input" type="checkbox" id="role{{ $rol->role_id }}" name="role[]" value="{{ $rol->role }}" {{ in_array($rol->role, $item->granted_roles) ? 'checked' : '' }}>
                                            @endif
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
