<?php

namespace App\Http\Controllers;

use App\Services\GamificationService;
use App\Services\SenseiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SenseiController extends Controller
{
    public function __construct(
        public SenseiService $senseiService,
        public GamificationService $gamificationService,
    ) {
    }

    /**
     * Tampilkan halaman utama Tanya Sensei (AI Assistant Chatbot).
     */
    public function index(): View
    {
        $user = Auth::user();

        // Rekomendasi pertanyaan cepat untuk pemula
        $promptSuggestions = [
            [
                'title'   => 'Beda Partikel は dan が',
                'prompt'  => 'Apa perbedaan partikel は (wa) dan が (ga)? Berikan contoh kalimatnya!',
                'icon'    => 'help',
            ],
            [
                'title'   => 'Koreksi Kalimat Saya',
                'prompt'  => 'Tolong koreksi kalimat bahasa Jepang saya: 私は図書館に本を読みます。Apakah sudah tepat?',
                'icon'    => 'edit',
            ],
            [
                'title'   => 'Pola Kalimat ~たい (Ingin)',
                'prompt'  => 'Bagaimana cara menggunakan pola kalimat ~たい untuk menyatakan keinginan?',
                'icon'    => 'sparkles',
            ],
            [
                'title'   => 'Cara Membaca Jam & Menit',
                'prompt'  => 'Bagaimana cara membaca jam dan menit dalam bahasa Jepang?',
                'icon'    => 'clock',
            ],
            [
                'title'   => 'Tips Lolos JLPT N5',
                'prompt'  => 'Apa tips dan strategi terbaik untuk belajar dan lulus ujian JLPT N5?',
                'icon'    => 'target',
            ],
            [
                'title'   => 'Bentuk ~TE Kata Kerja',
                'prompt'  => 'Bagaimana rumus dan cara mengubah kata kerja ke bentuk ~TE (~て)?',
                'icon'    => 'book',
            ],
        ];

        return view('dashboard.sensei.index', [
            'user'              => $user,
            'promptSuggestions' => $promptSuggestions,
            'hasServerApiKey'   => !empty(config('services.gemini.key', env('GEMINI_API_KEY'))),
        ]);
    }

    /**
     * Endpoint API untuk memproses percakapan dengan Sensei.
     */
    public function chat(Request $request): JsonResponse
    {
        $request->validate([
            'message'  => 'required|string|max:1000',
            'api_key'  => 'nullable|string|max:200',
            'model'    => 'nullable|string|max:100',
            'version'  => 'nullable|string|max:30',
            'engine'   => 'nullable|string|in:gemini,builtin,auto',
            'history'  => 'nullable|array|max:20',
        ]);

        $message          = (string) $request->input('message');
        $userApiKey       = $request->input('api_key');
        $preferredModel   = $request->input('model');
        $preferredVersion = $request->input('version');
        $engine           = (string) $request->input('engine', 'auto');
        $history          = (array) $request->input('history', []);

        $result = $this->senseiService->respond($message, $userApiKey, $history, $preferredModel, $preferredVersion, $engine);

        $reply = mb_convert_encoding($result['reply'], 'UTF-8', 'UTF-8');
        $snippets = array_map(
            fn(string $s): string => mb_convert_encoding($s, 'UTF-8', 'UTF-8'),
            $result['japanese_snippets'] ?? []
        );

        return response()->json([
            'status'            => 'success',
            'reply'             => $reply,
            'engine'            => $result['engine'],
            'japanese_snippets' => $snippets,
        ]);
    }

    /**
     * Endpoint untuk menguji validitas API Key dari modal pengaturan.
     */
    public function testKey(Request $request): JsonResponse
    {
        $apiKey = (string) $request->input('api_key', '');
        $res = $this->senseiService->testApiKey($apiKey);

        return response()->json($res);
    }
}

