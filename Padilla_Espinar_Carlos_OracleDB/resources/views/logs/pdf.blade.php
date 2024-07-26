<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10px; /* Ajustar el tamaño de la fuente */
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            text-align: center;
        }
        td {
            text-align: center;
        }
        .container {
            margin: auto;
            width: 90%;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Logs Results</h1>
        <table>
            <thead>
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
</body>
</html>
