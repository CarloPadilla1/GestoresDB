<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title text-center">Login</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('login.post') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="db_host">Database Host</label>
                                <input type="text" class="form-control" id="db_host" name="db_host"  placeholder="localhost" required>
                            </div>
                            <div class="form-group">
                                <label for="db_port">Database Port</label>
                                <input type="text" class="form-control" id="db_port" name="db_port" placeholder="1521" required>
                            </div>
                            <div class="form-group">
                                <label for="db_database">Database Name</label>
                                <input type="text" class="form-control" id="db_database" name="db_database" required>
                            </div>
                            <div class="form-group">
                                <label for="db_username">Database Username</label>
                                <input type="text" class="form-control" id="db_username" name="db_username" required>
                            </div>
                            <div class="form-group">
                                <label for="db_password">Database Password</label>
                                <input type="password" class="form-control" id="db_password" name="db_password" >
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Login</button>
                        </form>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger mt-3">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

