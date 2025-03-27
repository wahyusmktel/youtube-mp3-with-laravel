<!DOCTYPE html>
<html>

<head>
    <title>YouTube to MP3 Converter</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        #loading {
            display: none;
            font-weight: bold;
            color: blue;
        }
    </style>
</head>

<body>
    <h2>Convert YouTube to MP3</h2>

    <div id="message"></div>

    <div id="preview" style="margin: 20px 0;"></div>

    <form id="convertForm">
        @csrf
        <input type="text" id="youtube_url" name="youtube_url" placeholder="Paste YouTube URL here" style="width: 300px;"
            required>
        <button type="submit" id="submitBtn">Convert</button>
    </form>

    <p id="loading">🔄 Sedang dikonversi... Mohon tunggu.</p>

    {{-- <script>
        const form = document.getElementById('convertForm');
        const loading = document.getElementById('loading');
        const submitBtn = document.getElementById('submitBtn');
        const message = document.getElementById('message');

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const youtubeUrl = document.getElementById('youtube_url').value;
            if (!youtubeUrl) return;

            loading.style.display = 'block';
            submitBtn.disabled = true;
            message.innerHTML = '';

            fetch("{{ route('convert') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        youtube_url: youtubeUrl
                    })
                })
                .then(response => response.json())
                .then(data => {
                    loading.style.display = 'none';
                    submitBtn.disabled = false;

                    if (data.success) {
                        message.innerHTML = `<p style="color: green">${data.success}</p>
                                         <a href="${data.download}" download>⬇️ Download MP3</a>`;
                    } else if (data.error) {
                        message.innerHTML = `<p style="color: red">${data.error}</p>`;
                    }
                })
                .catch(error => {
                    loading.style.display = 'none';
                    submitBtn.disabled = false;
                    message.innerHTML = `<p style="color: red">Terjadi kesalahan: ${error.message}</p>`;
                });
        });
    </script> --}}

    <script>
        const form = document.getElementById('convertForm');
        const loading = document.getElementById('loading');
        const submitBtn = document.getElementById('submitBtn');
        const message = document.getElementById('message');
        const previewDiv = document.getElementById('preview'); // bagian preview video

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const youtubeUrl = document.getElementById('youtube_url').value;
            if (!youtubeUrl) return;

            loading.style.display = 'block';
            submitBtn.disabled = true;
            message.innerHTML = '';
            previewDiv.innerHTML = '';

            fetch("{{ route('convert') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        youtube_url: youtubeUrl
                    })
                })
                .then(response => response.json())
                .then(data => {
                    loading.style.display = 'none';
                    submitBtn.disabled = false;

                    if (data.success) {
                        message.innerHTML = `<p style="color: green">${data.success}</p>
                                             <a href="${data.download}" download>⬇️ Download MP3</a>`;

                        // Tampilkan preview video
                        previewDiv.innerHTML = `
                            <h3>${data.title}</h3>
                            <iframe width="360" height="215" src="${data.embed}"
                                frameborder="0" allowfullscreen></iframe>
                        `;
                    } else if (data.error) {
                        message.innerHTML = `<p style="color: red">${data.error}</p>`;
                        previewDiv.innerHTML = '';
                    }
                })
                .catch(error => {
                    loading.style.display = 'none';
                    submitBtn.disabled = false;
                    message.innerHTML = `<p style="color: red">Terjadi kesalahan: ${error.message}</p>`;
                    previewDiv.innerHTML = '';
                });
        });
    </script>

</body>

</html>
