<!DOCTYPE html>
<html lang="{{ str_replace('_', '', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel Import Large CSV File Using Queue Webappfix</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Laravel Import Large CSV file</h1>
        <form method="post" action="{{ route('products.import.store') }}" enctype="multipart/form-data">
            @csrf
            @if ($message = Session::get('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>{{ $message }}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="form-group">
                <strong>CSV File:</strong>
                <input type="file" name="csv" class="form-control" />
            </div>
            <div class="form-group text-center">
                <button type="submit" class="btn btn-success btn-block">Import</button>
            </div>
        </form>
    </div>
</body>
</html>
