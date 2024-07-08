@extends('layouts.app')

@section('title', 'Dashboard Roles')

@section('content')
    <h2>Admin Roles</h2>


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

    @foreach ($roles as $item)
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">{{ $item->RoleName }}</h5>
                <p class="card-text">{{ $item->Privileges }}</p>
                <p class="card-text">{{ $item->RoleId }}</p>
                {{-- <a href="{{ route('role.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a> --}}

                <form id="delete-form-{{ $item->RoleId }}" action="{{ route('role.deleteRole', $item->RoleId) }}" method="POST" >
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </div>
        </div>
    @endforeach
@endsection
