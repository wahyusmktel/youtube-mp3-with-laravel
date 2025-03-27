{{-- <!DOCTYPE html>
<html>

<head>
    <title>YouTube to MP3 Converter</title>
</head>

<body>
    <h2>Convert YouTube to MP3</h2>

    @if (session('error'))
        <p style="color: red">{{ session('error') }}</p>
    @endif

    @if (session('success'))
        <p style="color: green">{{ session('success') }}</p>
        <a href="{{ session('mp3') }}" download>Download MP3</a>
    @endif

    <form action="{{ route('convert') }}" method="POST">
        @csrf
        <input type="text" name="youtube_url" placeholder="Paste YouTube URL here" style="width: 300px;" required>
        <button type="submit">Convert</button>
    </form>
</body>

</html> --}}


{{-- <!DOCTYPE html>
<html>

<head>
    <title>YouTube to MP3 Converter</title>
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

    @if (session('error'))
        <p style="color: red">{{ session('error') }}</p>
    @endif

    @if (session('success'))
        <p style="color: green">{{ session('success') }}</p>
        <a href="{{ session('mp3') }}" download>Download MP3</a>
    @endif

    <form id="convertForm" action="{{ route('convert') }}" method="POST">
        @csrf
        <input type="text" name="youtube_url" placeholder="Paste YouTube URL here" style="width: 300px;" required>
        <button type="submit" id="submitBtn">Convert</button>
    </form>

    <p id="loading">🔄 Sedang dikonversi... Mohon tunggu.</p>

    <script>
        const form = document.getElementById('convertForm');
        const loading = document.getElementById('loading');
        const submitBtn = document.getElementById('submitBtn');

        form.addEventListener('submit', function() {
            loading.style.display = 'block';
            submitBtn.disabled = true;
        });
    </script>
</body>

</html> --}}
