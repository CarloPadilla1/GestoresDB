@extends('layouts.app')

@section('content')
<div class="container mt-5">
    @if (count($nameTables) > 0)
    <form method="POST" action="{{ route('audit.filter') }}" class="mb-4">
        @csrf
        <div class="form-row align-items-end">
            <div class="col-md-6">
                <label for="tableSelect">Filtrar por Tabla:</label>
                <select id="tableSelect" name="table" class="form-control">
                    <option value="" {{ empty($selectedTable) ? 'selected' : '' }}>Seleccione una tabla</option>
                    @foreach ($nameTables as $table)
                        <option value="{{ $table->table_name }}">
                            {{ $table->table_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </div>
            <div class="col-md-3">
            <button type="button" class="btn btn-primary" onclick="window.print()">Print</button>
            </div>
        </div>
    </form>
    @else
        <p class="text-danger">No se encontraron tablas.</p>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
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

    <div class="table-responsive">
        <table class="table table-striped table-bordered mt-4">
            <thead class="thead-dark">
                <tr>
                    @foreach ($columns as $column)
                        <th>{{ $column }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $row)
                    <tr>
                        @foreach ($columns as $column)
                            <td>{{ $row->$column }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
