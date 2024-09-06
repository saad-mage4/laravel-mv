{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Logs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .log-container {
            background-color: #fff;
            border-radius: 5px;
            padding: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            max-height: 500px;
        }
        pre {
            white-space: pre-wrap;
            word-wrap: break-word;
        }
    </style>
</head>
<body>

<div class="log-container">
    <h1 >Laravel Logs</h1>
    @if ($errors->any())
        <div style="color: red;">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    @if (isset($logLines))
        @foreach ($logLines as $line)
            <pre>{{ $line }}</pre>
        @endforeach
    @else
        <p>No logs available.</p>
    @endif
</div>

</body>
</html> --}}


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Logs</title>
    <!-- Load Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light font-sans leading-normal tracking-normal">

    <div class="container mt-5">
        <div class="row mb-4 justify-content-between align-items-center">
            <div class="col-md-6">
                <h1 class="text-2xl font-bold text-dark">Laravel Logs</h1>
            </div>
            <div class="col-md-6 text-end">
                <a href="/admin" class="btn btn-primary me-2">← Back to Dashboard</a>
                <!-- Clear Logs Button -->
                <form action="{{ route('admin.logs.clear') }}" method="POST" class="d-inline-block">
                    @csrf
                    <button type="submit" class="btn btn-danger">Clear Logs</button>
                </form>
            </div>
        </div>

        <!-- Show success message if logs were cleared -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card shadow-sm rounded-lg">
            <div class="card-header bg-dark text-white">
                <h2 class="h5 mb-0">Log Entries</h2>
                <p class="mb-0 text-sm">Showing recent logs from the system</p>
            </div>

            <div class="card-body overflow-auto" style="max-height: 600px;">
    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            @foreach ($errors->all() as $error)
                <span>{{ $error }}</span><br>
            @endforeach
        </div>
    @endif

    @if (isset($filteredLogs) && count($filteredLogs) > 0)
        <ul class="list-group">
            @foreach ($filteredLogs as $line)
                <li class="list-group-item bg-light border-0 text-dark font-monospace">{{ $line }}</li>
            @endforeach
        </ul>
    @else
        <p class="text-center text-muted">No logs available for the past week.</p>
    @endif
</div>

        </div>
    </div>

    <!-- Load Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

