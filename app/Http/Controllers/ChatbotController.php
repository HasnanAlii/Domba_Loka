<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $apiKey = config('services.gemini.key');
        
        if (!$apiKey) {
            return response()->json([
                'reply' => 'Maaf, fitur chatbot sedang dalam pemeliharaan. Silakan hubungi kami via WhatsApp.',
            ]);
        }

        $context = $this->getSystemContext();
        $userMessage = $request->input('message');

        try {
            $response = Http::post("https://generativelanguage.googleapis.com/v1/models/gemini-2.5-flash-lite:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'role' => 'user',
                        'parts' => [
                            ['text' => $context . "\n\nPertanyaan Pelanggan: " . $userMessage]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'maxOutputTokens' => 300,
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Maaf, saya tidak mengerti. Bisa diulangi?';
                return response()->json(['reply' => $reply]);
            }

            Log::error('Gemini API Error: ' . $response->body());
            return response()->json(['reply' => 'Maaf, terjadi gangguan teknis. Cobalah sesaat lagi.'], 500);

        } catch (\Exception $e) {
            Log::error('Chatbot Exception: ' . $e->getMessage());
            return response()->json(['reply' => 'Maaf, terjadi kesalahan pada server.'], 500);
        }
    }

    private function getSystemContext()
    {
        return <<<EOT
Anda adalah "LokaAI", asisten virtual profesional untuk peternakan "Domba Loka" yang berlokasi di Pacet, Cianjur, Jawa Barat.
Tugas Anda adalah menjawab pertanyaan pelanggan dengan ramah, informatif, dan persuasif dalam bahasa Indonesia yang sopan.

INFORMASI DOMBA LOKA:
1. Lokasi: Kecamatan Pacet, Cianjur, Jawa Barat. Dekat lereng Gunung Gede.
2. Produk & Layanan:
   - Domba Aqiqah & Qurban (siap potong dan paket masak).
   - Suplai Daging/Karkas untuk Restoran, Hotel, dan Katering.
   - Bibit Unggul (khususnya Domba Garut dan jenis lainnya).
3. Keunggulan:
   - 100% Domba Sehat (divaksin secara rutin).
   - Timbangan Akurat & Transparan.
   - Pakan Organik tanpa bahan kimia berbahaya.
   - Pengiriman cepat se-Jawa Barat.
4. Gaya Bahasa: Profesional, ramah, dan membantu. Jika ditanya harga spesifik, arahkan untuk melihat katalog atau hubungi tim via WhatsApp (+62 812-3456-7890).
5. Batasan: Jangan menjawab hal di luar peternakan atau Domba Loka. Jawablah secara singkat dan padat (max 3-4 kalimat).

Jawablah pertanyaan berikut dengan singkat:
EOT;
    }
}
