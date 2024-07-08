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
    <h2>Edit User</h2>
    <form action="{{ route('users_db.update', $data->User) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="{{ $data->User }}" readonly>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password">
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
