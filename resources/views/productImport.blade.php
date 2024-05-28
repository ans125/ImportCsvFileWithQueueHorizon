<!DOCTYPE html>
{{-- <html lang="{{ str.replace('_', '', app().getLocale()) }}"> --}}
    <html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel Import Large CSV File Using Queue Webappfix</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Laravel Import Large CSV file</h1>
        <form id="importForm" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <strong>CSV File:</strong>
                <input type="file" name="csv" class="form-control" id="csvFile" />
            </div>
            <div class="form-group text-center">
                <button type="submit" class="btn btn-success btn-block">Import</button>
            </div>
        </form>
        <div class="progress mt-3" id="progressBar" style="display: none;">
            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" id="progressBarValue" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        <div id="progressText" class="mt-2" style="display: none;"></div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#importForm').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $('#progressBar').fadeIn('slow');
                $('#progressText').hide();
                $('#progressBarValue').css('width', '0%').attr('aria-valuenow', 0);

                $.ajax({
                    url: "{{ route('products.import.store') }}",
                    type: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    xhr: function() {
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function(evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = Math.round((evt.loaded / evt.total) * 100);
                                animateProgressBar(percentComplete);
                            }
                        }, false);
                        return xhr;
                    },
                    success: function(response) {
                        animateProgressBar(100, function() {
                            $('#progressBar').fadeOut('slow', function() {
                                $('#progressText').fadeIn('slow').html('CSV import completed successfully');
                            });
                        });
                    },
                    error: function(xhr, status, error) {
                        $('#progressBar').fadeOut('slow', function() {
                            $('#progressText').fadeIn('slow').html('An error occurred while processing the CSV file');
                        });
                    }
                });
            });

            function animateProgressBar(target, callback) {
                var current = $('#progressBarValue').attr('aria-valuenow');
                var interval = setInterval(function() {
                    current = Math.min(current + 1, target);
                    $('#progressBarValue').css('width', current + '%').attr('aria-valuenow', current);
                    if (current >= target) {
                        clearInterval(interval);
                        if (callback) callback();
                    }
                }, 10); // Adjust the interval time for smoother or faster animation
            }
        });
    </script>
</body>
</html>
