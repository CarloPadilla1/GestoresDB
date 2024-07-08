@extends('layouts.app')

@section('title', 'User Permissions')

@section('content')
<div class="container">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (Session::has('success'))
        <div class="alert alert-success">
            <p>{{ Session::get('success') }}</p>
        </div>
    @endif

    <div class="card">
        <div class="card-header">User Permissions -- {{$user->name}}</div>
        <div>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#changePasswordModal">Change Password</button>
            <div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('users_db.update') }}">
                                @csrf
                                <input type="text" name="username" value="{{ $user->name }}">
                                <div class="form-group">
                                    <label for="new_password">New Password:</label>
                                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                                </div>
                                <div class="form-group">
                                    <label for="confirm_password">Confirm Password:</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col">
                    <h5>Databases and Permissions:</h5>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Database</th>
                                <th>Permission Name</th>
                                <th>Permission State</th>
                                <th>Permission Class</th>
                                <th>Object ID</th>
                                <th>Object Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($permissions as $database => $databasePermissions)
                                @if (count($databasePermissions) > 0)
                                    <tr>
                                        <td rowspan="{{ count($databasePermissions) }}">{{ $database }}</td>
                                        <td>{{ $databasePermissions[0]->permission_name }}</td>
                                        <td>{{ $databasePermissions[0]->permission_state }}</td>
                                        <td>{{ $databasePermissions[0]->permission_class }}</td>
                                        <td>{{ $databasePermissions[0]->object_id }}</td>
                                        <td>{{ $databasePermissions[0]->object_name }}</td>

                                            <td>
                                                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#editPermissionModal{{ $loop->index }}">Edit</a>
                                                <form action="{{route('users_db.deleteUM')}}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="text" name="database_name" value="{{$database}}" hidden>
                                                    <input type="text" name="username" value="{{$user->name}}" hidden>
                                                    <button type="submit" class="btn btn-danger">DesMap</button>
                                                </form>
                                            </td>

                                    </tr>
                                    @for ($i = 1; $i < count($databasePermissions); $i++)
                                        <tr>
                                            <td>{{ $databasePermissions[$i]->permission_name }}</td>
                                            <td>{{ $databasePermissions[$i]->permission_state }}</td>
                                            <td>{{ $databasePermissions[$i]->permission_class }}</td>
                                            <td>{{ $databasePermissions[$i]->object_id }}</td>
                                            <td>{{ $databasePermissions[$i]->object_name }}</td>
                                        </tr>
                                    @endfor


                                    <div class="modal fade" id="editPermissionModal{{ $loop->index }}" tabindex="-1" role="dialog" aria-labelledby="editPermissionModalLabel{{ $loop->index }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editPermissionModalLabel{{ $loop->index }}">Edit Permissions - {{ $database }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{route('users_db.editRolesUM',$user->principal_id)}}" method="POST">
                                                        @csrf
                                                        @method('PUT')

                                                        <input type="hidden" name="database_name" value="{{ $database }}">
                                                        <input type="hidden" name="username" value="{{ $user->name }}">
                                                        <div class="form-group">
                                                            <label for="permissions">Select Permissions:</label><br>
                                                            @foreach($all_permissions as $permission)
                                                                @php
                                                                    $isChecked = false;
                                                                    foreach ($databasePermissions as $dbPermission) {
                                                                        if ($dbPermission->object_name == $permission->object_name) {
                                                                            $isChecked = true;
                                                                            break;
                                                                        }
                                                                    }
                                                                @endphp
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="checkbox" id="permission_{{ $permission->object_name }}" name="permissions[]" value="{{ $permission->object_name }}" {{ $isChecked ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="permission_{{ $permission->object_name }}">{{ $permission->object_name }}</label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <tr>
                                        <td>{{ $database }}</td>
                                        <td colspan="5">No permissions assigned</td>
                                        <td>
                                            <form action="{{route('users_db.addUM')}}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="text" name="database_name" value="{{$database}}" hidden>
                                                <input type="text" name="username" value="{{$user->name}}" hidden>
                                                <button type="submit" class="btn btn-primary">Add</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endif

                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
