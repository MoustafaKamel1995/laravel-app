@extends('layouts.dashboard')

@section('content')
    <h1 class="text-3xl font-bold mb-4">Upload Update</h1>
    @if (session('success'))
        <div class="bg-green-500 text-white p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-500 text-white p-4 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif
    <form id="fileupload" action="{{ route('update.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
            <label for="update_file" class="block text-sm font-bold mb-2">Select Update File:</label>
            <input type="file" name="update_file" id="file" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="version" class="block text-sm font-bold mb-2">Version:</label>
            <input type="text" name="version" id="version" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div id="progress" class="mt-4" style="display: none;">
            <div class="bg-gray-300">
                <div class="bg-blue-500 text-xs leading-none py-1 text-center text-white" style="width: 0%;" id="progress-bar">0%</div>
            </div>
        </div>
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4">Upload</button>
    </form>

    <div id="message" class="mt-4" style="display: none;"></div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/10.32.0/js/jquery.fileupload.min.js"></script>
    <script>
        $(function () {
            $('#fileupload').on('submit', function(e) {
                e.preventDefault(); // „‰⁄ «· ﬁœÌ„ «·«› —«÷Ì

                var formData = new FormData(this); // ≈‰‘«¡ FormData „‰ «·‰„Ê–Ã

                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: formData,
                    processData: false,
                    contentType: false,
                    xhr: function() {
                        var xhr = $.ajaxSettings.xhr();
                        xhr.upload.onprogress = function(e) {
                            var percentComplete = Math.round((e.loaded * 100) / e.total);
                            $('#progress').show();
                            $('#progress-bar').css('width', percentComplete + '%').text(percentComplete + '%');
                        };
                        return xhr;
                    },
                    success: function(response) {
                        $('#message').show().text(response.success).css('color', 'green');
                        $('#progress').hide();
                    },
                    error: function(response) {
                        var errorMsg = 'File upload failed';
                        if (response.responseJSON && response.responseJSON.error) {
                            errorMsg = response.responseJSON.error;
                        }
                        $('#message').show().text(errorMsg).css('color', 'red');
                        $('#progress').hide();
                    }
                });
            });
        });
    </script>
@endsection
