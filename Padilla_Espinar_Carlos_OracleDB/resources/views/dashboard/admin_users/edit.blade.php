@extends('layouts.app')
@section('title', 'Dashboard Users')

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
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <h2>Edit User</h2>
    <form action="{{ route('users_db.update', $data->user_id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="{{ $data->username }}" readonly>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>

        <div class="form-group">
            <label for="account_status">Account Status</label>
            <select class="form-control" id="account_status" name="account_status">
                <option value="OPEN" {{ $data->account_status == 'OPEN' ? 'selected' : '' }}>Open</option>
                <option value="LOCKED" {{ $data->account_status == 'LOCKED' ? 'selected' : '' }}>Locked</option>
            </select>
        </div>

        <div class="form-group">
            <label for="default_tablespace">Default Tablespace</label>
            <select class="form-control" id="default_tablespace" name="default_tablespace">
                @foreach($tablespaces as $tablespace)
                    <option value="{{ $tablespace->tablespace_name }}" {{ $data->default_tablespace == $tablespace->tablespace_name ? 'selected' : '' }}>{{ $tablespace->tablespace_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="temporary_tablespace">Temporary Tablespace</label>
            <select class="form-control" id="temporary_tablespace" name="temporary_tablespace">
                @foreach($tablespaces as $tablespace)
                    <option value="{{ $tablespace->tablespace_name }}" {{ $data->temporary_tablespace == $tablespace->tablespace_name ? 'selected' : '' }}>{{ $tablespace->tablespace_name }}</option>
                @endforeach
            </select>
        </div>

        {{-- <div class="form-group">
            <label for="role">Role</label>
            <select class="form-control" id="role" name="role">
                @foreach($roles as $role)
                    <option value="{{ $role->role }}">{{ $role->role }}</option>
                @endforeach
            </select>
        </div> --}}

        <button type="submit" class="btn btn-primary">Update User</button>
    </form>
</div>
@endsection
