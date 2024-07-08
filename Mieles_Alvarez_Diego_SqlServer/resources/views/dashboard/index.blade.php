@extends('layouts.app')

@section('title', 'Dashboard Table')

@section('content')
    <div>
        <h2>Dashboard</h2>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
            Create Backup
        </button>
        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#restoreBModal" >Restore Backup</button>
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modal Title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Backup
                        <form action="{{ route('backup') }}" method="POST">
                            @csrf
                            <label for="password">Password</label>
                                <input type="password" id="password"  name="password">
                            <label for="backupType">Backup Type</label>
                                <select id="backupType" name="backupType">
                                    <option value="full">Full Database Backup</option>
                                    <option value="differential">User Differential Backup</option>
                                </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create Backup</button>
                    </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="restoreBModal" tabindex="-1" role="dialog" aria-labelledby="restoreBModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="restoreBModalLabel">Modal Title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Restore
                        <form action="{{ route('restore') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <label for="password">Password</label>
                                <input type="password" id="password"  name="password">
                            <label for="backupFile">File Backup</label>
                                <input type="file" name="backupFile" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Restore Backup</button>
                    </form>
                    </div>
                </div>
            </div>
        </div>
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
    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>Table Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($nameTables as $item)
                <tr>
                    <td>{{ $item->table_name }}</td>
                    <td>
                        <a href="{{ route('table.show', $item->table_name) }}" class="btn btn-info btn-sm">Show</a>
                        {{-- <a href="{{ route('table.edit', $item->table_name) }}" class="btn btn-warning btn-sm">Edit</a>
                        <a href="{{ route('table.destroy', $item->table_name) }}" class="btn btn-danger btn-sm">Delete</a> --}}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
