@extends('layouts.app')

@section('content')
<!-- <a href="{{ route('generate.triggers.sql') }}" class="btn btn-primary">Generar SQL de Triggers</a> -->
    <div class="container">
        <div class="row justify-content-between">
            <h1>Audit Logs for {{ $table }}</h1>
            <div class="col-md-2 mt-2">
                <button type="button" class="btn btn-primary" onclick="window.print()">Print</button>
            </div>
        </div>
    <div>
    <table class="table table-striped table-bordered" border="1">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Table</th>
                <th>Accion</th>
                <th>Tiempo</th>
                <th>Usuario</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($audits as $audit)
                <tr>
                    <td>{{ $audit->ID }}</td>
                    <td>{{ $audit->TableName }}</td>
                    <td>{{ $audit->ActionType }}</td>
                    <td>{{ $audit->ActionTime }}</td>
                    <td>{{ $audit->UserName }}</td>
                    <td>{{ $audit->ActionDetails }}</td>

                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
@endsection