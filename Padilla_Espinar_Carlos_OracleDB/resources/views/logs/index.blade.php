<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Log</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Audit Log</h2>
    <form method="GET" action="{{ route('logs.view') }}" class="mb-4">
        @csrf
        @method('GET')
        <div class="form-row align-items-end">
            <div class="col-md-6">
                <label for="tableSelect">Filtrar por Tabla:</label>
                <select id="tableSelect" name="object_name" class="form-control">
                    <option value="" {{ empty($selectedTable) ? 'selected' : '' }}>Seleccione una tabla</option>
                    @foreach ($nameTables as $table)
                        <option value="{{ $table->table_name }}">
                            {{ $table->table_name }}
                        </option>
                    @endforeach
                </select>
                <label for="tableSelect">Filtrar por Action Name:</label>
                <select id="actionSelect" name="action_name" class="form-control">
                    <option value="" {{ empty($selectedAction) ? 'selected' : '' }}>Seleccione una acción</option>
                    @foreach ($actions as $action)
                        <option value="{{ $action->action_name }}">
                            {{ $action->action_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </div>
        </div>
    </form>
    <div class="text-right mb-4">
        <a href="{{ route('logs.pdf') }}" class="btn btn-primary">Download</a>
    </div>
    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
        <tr>
            <th>OS Username</th>
            <th>Username</th>
            <th>Userhost</th>
            <th>Terminal</th>
            <th>Timestamp</th>
            <th>Owner</th>
            <th>Object Name</th>
            <th>Action</th>
            <th>Action Name</th>
            <th>Comment Text</th>
            <th>Session ID</th>
            <th>Entry ID</th>
            <th>Statement ID</th>
            <th>Return Code</th>
        </tr>
        </thead>
        <tbody>
        @foreach($items as $item)
            <tr>
                <td>{{ $item->os_username }}</td>
                <td>{{ $item->username }}</td>
                <td>{{ $item->userhost }}</td>
                <td>{{ $item->terminal }}</td>
                <td>{{ $item->timestamp }}</td>
                <td>{{ $item->owner }}</td>
                <td>{{ $item->obj_name }}</td>
                <td>{{ $item->action }}</td>
                <td>{{ $item->action_name }}</td>
                <td>{{ $item->comment_text }}</td>
                <td>{{ $item->sessionid }}</td>
                <td>{{ $item->entryid }}</td>
                <td>{{ $item->statementid }}</td>
                <td>{{ $item->returncode }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
