<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class YouTubeConverterController extends Controller
{
    public function index()
    {
        return view('youtube.index');
    }

    // public function convert(Request $request)
    // {
    //     $request->validate([
    //         'youtube_url' => 'required|url',
    //     ]);

    //     $url = $request->youtube_url;
    //     $filename = Str::uuid()->toString(); // nama file unik
    //     $outputPath = public_path("mp3/{$filename}.mp3");

    //     $command = "yt-dlp -x --audio-format mp3 -o '{$outputPath}' {$url}";
    //     exec($command, $output, $returnVar);

    //     if ($returnVar !== 0) {
    //         return back()->with('error', 'Gagal mengonversi video.');
    //     }

    //     return back()->with('success', 'Berhasil dikonversi!')->with('mp3', asset("mp3/{$filename}.mp3"));
    // }

    // public function convert(Request $request)
    // {
    //     $request->validate([
    //         'youtube_url' => 'required|url',
    //     ]);

    //     $url = $request->youtube_url;
    //     $filename = \Illuminate\Support\Str::uuid()->toString();
    //     $outputFilename = "{$filename}.mp3";
    //     $outputDir = public_path('mp3');

    //     // Jalankan yt-dlp di folder public/mp3
    //     $command = "cd {$outputDir} && yt-dlp -x --audio-format mp3 -o \"{$filename}.%(ext)s\" {$url}";
    //     exec($command, $output, $returnVar);

    //     if ($returnVar !== 0) {
    //         return back()->with('error', 'Gagal mengonversi video.');
    //     }

    //     return back()->with('success', 'Berhasil dikonversi!')->with('mp3', asset("mp3/{$outputFilename}"));
    // }

    // Fix Oke 100%

    // public function convert(Request $request)
    // {
    //     $request->validate([
    //         'youtube_url' => 'required|url',
    //     ]);

    //     $url = $request->youtube_url;
    //     $filename = \Illuminate\Support\Str::uuid()->toString();
    //     $outputFilename = "{$filename}.mp3";
    //     $outputDir = public_path('mp3');

    //     // Jalankan yt-dlp di folder public/mp3
    //     $command = "cd {$outputDir} && yt-dlp -x --audio-format mp3 -o \"{$filename}.%(ext)s\" {$url}";
    //     exec($command, $output, $returnVar);

    //     // Validasi: cek apakah file beneran ada
    //     $fullPath = $outputDir . DIRECTORY_SEPARATOR . $outputFilename;

    //     if (!file_exists($fullPath)) {
    //         return back()->with('error', 'Gagal mengonversi video.');
    //     }

    //     return back()->with('success', 'Berhasil dikonversi!')->with('mp3', asset("mp3/{$outputFilename}"));
    // }

    // Versi Penyempurnaan Dengan AJax

    // public function convert(Request $request)
    // {
    //     $request->validate([
    //         'youtube_url' => 'required|url',
    //     ]);

    //     $url = $request->youtube_url;
    //     $filename = \Illuminate\Support\Str::uuid()->toString();
    //     $outputFilename = "{$filename}.mp3";
    //     $outputDir = public_path('mp3');

    //     // Jalankan yt-dlp di folder public/mp3
    //     $command = "cd {$outputDir} && yt-dlp -x --audio-format mp3 -o \"{$filename}.%(ext)s\" {$url}";
    //     exec($command, $output, $returnVar);

    //     $fullPath = $outputDir . DIRECTORY_SEPARATOR . $outputFilename;

    //     if (!file_exists($fullPath)) {
    //         return response()->json(['error' => 'Gagal mengonversi video.'], 500);
    //     }

    //     return response()->json([
    //         'success' => 'Berhasil dikonversi!',
    //         'download' => asset("mp3/{$outputFilename}")
    //     ]);
    // }

    public function convert(Request $request)
    {
        $request->validate([
            'youtube_url' => 'required|url',
        ]);

        $url = $request->youtube_url;
        $filename = \Illuminate\Support\Str::uuid()->toString();
        $outputFilename = "{$filename}.mp3";
        $outputDir = public_path('mp3');

        // STEP 1: Ambil info video
        $infoCommand = "yt-dlp --dump-json " . escapeshellarg($url);
        exec($infoCommand, $infoOutput, $infoReturn);
        if ($infoReturn !== 0 || empty($infoOutput)) {
            return response()->json(['error' => 'Gagal mengambil info video.']);
        }

        $json = json_decode(implode('', $infoOutput), true);

        $title = $json['title'] ?? 'Tanpa Judul';
        $thumbnail = $json['thumbnail'] ?? '';
        $videoId = $json['id'] ?? '';

        // STEP 2: Download mp3
        $command = "cd {$outputDir} && yt-dlp -x --audio-format mp3 -o \"{$filename}.%(ext)s\" " . escapeshellarg($url);
        exec($command, $output, $returnVar);

        $fullPath = $outputDir . DIRECTORY_SEPARATOR . $outputFilename;

        if (!file_exists($fullPath)) {
            return response()->json(['error' => 'Gagal mengonversi video.']);
        }

        return response()->json([
            'success' => 'Berhasil dikonversi!',
            'download' => asset("mp3/{$outputFilename}"),
            'title' => $title,
            'thumbnail' => $thumbnail,
            'video_id' => $videoId,
            'embed' => "https://www.youtube.com/embed/{$videoId}"
        ]);
    }
}
