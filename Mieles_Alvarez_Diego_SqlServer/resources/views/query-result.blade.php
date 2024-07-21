@extends('layouts.app')

@section('title', 'Executions de Query Results')

@section('content')
<div>
    <button type="button" class="btn btn-info mb-3" data-toggle="modal" data-target="#queryModal">
            Seleccionar hilos
    </button>
    <h2>Resultados de tiempo de espera de querys</h2>
    <div class="modal fade" id="queryModal" tabindex="-1" role="dialog" aria-labelledby="queryModalLabel" aria-hidden="true">
            <div class="modal-dialog  modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="queryModalLabel">Select Tables to Query</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="queryForm" method="POST" action="{{ route('execute_hilo') }}">
                            @csrf
                            <div class="form-group" style="height: 300px">
                                <label for="tables">Selecciones las tablas para realizar los hilos:</label>
                                <select style="height: 300px" multiple class="form-control" id="tables" name="tables[]">
                                    @foreach ($tables as $table)
                                        <option value="{{ $table->table_name }}">{{ $table->table_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" form="queryForm">Ejecutar</button>
                    </div>
                </div>
            </div>
    </div>

    <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Query Text</th>
                    <th>Execution Time (ms)</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($auditLogs as $log)
                    <tr>
                        <td>{{ $log->id }}</td>
                        <td>{{ $log->query_text }}</td>
                        <td>{{ $log->execution_time }}</td>
                        <td>{{ $log->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
    </table>
</div>
@endsection
