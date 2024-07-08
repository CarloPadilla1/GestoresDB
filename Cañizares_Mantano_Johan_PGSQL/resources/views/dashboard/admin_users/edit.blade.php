@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar Permisos de Usuario: {{ $user->rolname }}</h2>

    @foreach($databases as $database)
    <div class="card mb-3">
        <div class="card-header">
            Base de Datos: {{ $database->datname }}
            <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#modal-{{ $database->datname }}">
                Editar Permisos
            </button>
        </div>
        <div class="card-body">
            <p>Permisos actuales:</p>
            <ul>
                @foreach($users_permissions[$database->datname] as $permission)
                <li>{{ $permission['privilege'] }}: {{ $permission['has_privilege'] ? 'Sí' : 'No' }}</li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modal-{{ $database->datname }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel-{{ $database->datname }}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel-{{ $database->datname }}">Editar Permisos para {{ $database->datname }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin_users.updatePermissions', $user->oid) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="database" value="{{ $database->datname }}">

                        <div class="form-group">
                            <label for="permissions">Permisos Disponibles</label>
                            <div>
                                @foreach($available_privileges as $privilege)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $privilege }}"
                                        @if(in_array($privilege, array_column($users_permissions[$database->datname], 'privilege')) && $users_permissions[$database->datname][array_search($privilege, array_column($users_permissions[$database->datname], 'privilege'))]['has_privilege']) checked @endif>
                                    <label class="form-check-label">
                                        {{ $privilege }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
