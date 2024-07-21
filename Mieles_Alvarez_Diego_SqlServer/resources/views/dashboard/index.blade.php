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
    <a type="button" class="btn btn-info mt-2 mb-2" href="{{ route('execute-queries')}}">
        Show Query Results
    </a>
    <button type="button" class="btn btn-primary mt-2 mb-2" data-toggle="modal" data-target="#createUserModal">
        Execute Script SQL
    </button>
    <div class="modal fade" id="createUserModal" tabindex="-1" role="dialog" aria-labelledby="createUserModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createUserModalLabel">Execute Script SQL</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('execute') }}" method="POST" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label for="script">Script</label>
                            <textarea class="form-control" id="script" name="script" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="document_sql">Document SQL</label>
                            <input type="file" class="form-control" id="document_sql" name="document_sql" >
                        </div>
                        <button type="submit" class="btn btn-primary">Execute</button>
                        <button type="submit" class="btn btn-primary" name="action" value="pdf">Pdf de consulta</button>

                    </form>
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
                        <a href="{{ route('audits.show', $item->table_name) }}" class="btn btn-secondary btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard-fill" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M10 1.5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5zm-5 0A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5v1A1.5 1.5 0 0 1 9.5 4h-3A1.5 1.5 0 0 1 5 2.5zm-2 0h1v1A2.5 2.5 0 0 0 6.5 5h3A2.5 2.5 0 0 0 12 2.5v-1h1a2 2 0 0 1 2 2V14a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V3.5a2 2 0 0 1 2-2"/>
                        </svg>
                        </a>
                        <a href="{{ route('table.show', $item->table_name) }}" class="btn btn-info btn-sm">ver tabla</a>
                        {{-- <a href="{{ route('table.edit', $item->table_name) }}" class="btn btn-warning btn-sm">Edit</a>
                        <a href="{{ route('table.destroy', $item->table_name) }}" class="btn btn-danger btn-sm">Delete</a> --}}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
