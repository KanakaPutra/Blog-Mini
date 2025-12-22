<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Category;
use App\Models\Article;
use App\Models\Comment;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class AiChat extends Component
{
    public $messages = [];
    public $userMessage = '';
    public $isAdmin = false;
    public $suggestions = [
        'Apa itu The Archipelago Times?',
        'Tampilkan artikel terbaru',
        'Buatkan ringkasan berita hari ini',
        'Bagaimana cara menjadi penulis?',
        'Daftar kategori yang ada'
    ];

    // Rate Limiting
    public $dailyLimit = 25;
    public $remainingMessages = 0;

    public function mount()
    {
        $this->messages = [
            ['role' => 'assistant', 'content' => 'Halo! Saya siap membantu apa pun yang kamu butuhkan.']
        ];

        $this->updateRemainingMessages();
    }

    public function updateRemainingMessages()
    {
        if (Auth::check()) {
            // Admin & Super Admin unlimited (is_admin > 0)
            if ((int) Auth::user()->is_admin > 0) {
                $this->remainingMessages = 9999; // Indikator unlimited
                $this->isAdmin = true;
                return;
            }

            $this->isAdmin = false;
            $userId = Auth::id();
            $today = now()->format('Y-m-d');
            $cacheKey = "ai_chat_limit:{$userId}:{$today}";

            $used = Cache::get($cacheKey, 0);
            $this->remainingMessages = max(0, $this->dailyLimit - $used);
        } else {
            $this->remainingMessages = 0;
            $this->isAdmin = false;
        }
    }

    public function toggleChat()
    {
        $this->isOpen = !$this->isOpen;
    }

    public function selectSuggestion($suggestion)
    {
        $this->userMessage = $suggestion;
        $this->sendMessage();
        $this->dispatch('messageSent');
    }

    public function clearChat()
    {
        $this->messages = [
            ['role' => 'assistant', 'content' => 'Halo! Riwayat chat telah dibersihkan. Ada yang bisa saya bantu lagi?']
        ];
    }


    public function sendMessage()
    {
        if (!Auth::check()) {
            $this->messages[] = ['role' => 'assistant', 'content' => 'Maaf, kamu harus login terlebih dahulu untuk menggunakan fitur ini.'];
            return;
        }

        // Auth Checks
        $userLevel = (int) Auth::user()->is_admin;
        $isAdmin = $userLevel > 0;      // 1 or 2 (Regular Admin or Super Admin)
        $isSuperAdmin = $userLevel === 2; // Only Super Admin

        // Cek limit: Admin (level > 0) unlimited, User biasa dicek limit only if not admin
        if (!$isAdmin && $this->remainingMessages <= 0) {
            $this->messages[] = ['role' => 'assistant', 'content' => 'Maaf, kuota harian kamu sudah habis. Silakan kembali lagi besok!'];
            return;
        }

        if (trim($this->userMessage) === '') {
            return;
        }

        // Add user message
        $this->messages[] = ['role' => 'user', 'content' => $this->userMessage];
        $input = $this->userMessage;
        $this->userMessage = '';
        $this->dispatch('messageSent');

        // Increment usage only for normal users
        if (!$isAdmin) {
            $this->incrementUsage();
        }

        // Call Gemini API - Pass $isSuperAdmin for command capability
        $response = $this->generateResponse($input, $isSuperAdmin);

        // Check for JSON command (create_category) - ONLY Super Admin
        if ($isSuperAdmin && $this->processAiCommand($response)) {
            return;
        }

        $this->messages[] = ['role' => 'assistant', 'content' => $response];
    }

    private function processAiCommand($response)
    {
        try {
            // Coba cari JSON di dalam response
            if (preg_match('/\{.*"action":\s*"(create|delete|update)_category".*\}/s', $response, $matches)) {
                $json = json_decode($matches[0], true);

                if (isset($json['action']) && !empty($json['name'])) {
                    $categoryName = $json['name'];
                    $action = $json['action'];

                    if ($action === 'create_category') {
                        // Cek apakah kategori sudah ada
                        if (Category::where('name', $categoryName)->exists()) {
                            $this->messages[] = ['role' => 'assistant', 'content' => "Kategori **'$categoryName'** sudah ada."];
                        } else {
                            Category::create(['name' => $categoryName]);
                            $this->messages[] = ['role' => 'assistant', 'content' => "Siap! Kategori **'$categoryName'** berhasil dibuat."];
                        }
                        return true;
                    } elseif ($action === 'delete_category') {
                        // Cek apakah kategori ada
                        $category = Category::where('name', $categoryName)->first();

                        if ($category) {
                            $category->delete();
                            $this->messages[] = ['role' => 'assistant', 'content' => "Siap! Kategori **'$categoryName'** berhasil dihapus."];
                        } else {
                            $this->messages[] = ['role' => 'assistant', 'content' => "Maaf, kategori **'$categoryName'** tidak ditemukan."];
                        }
                        return true;
                    } elseif ($action === 'update_category' && !empty($json['new_name'])) {
                        $newName = $json['new_name'];

                        // Cek apakah kategori ada
                        $category = Category::where('name', $categoryName)->first();

                        if (!$category) {
                            $this->messages[] = ['role' => 'assistant', 'content' => "Maaf, kategori **'$categoryName'** tidak ditemukan."];
                            return true;
                        }

                        // Cek conflict nama baru
                        if (Category::where('name', $newName)->exists()) {
                            $this->messages[] = ['role' => 'assistant', 'content' => "Gagal ubah nama. Kategori **'$newName'** sudah ada."];
                            return true;
                        }

                        $category->update(['name' => $newName]);
                        $this->messages[] = ['role' => 'assistant', 'content' => "Siap! Kategori **'$categoryName'** berhasil diubah menjadi **'$newName'**."];
                        return true;
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('AI Command Parsing Error: ' . $e->getMessage());
        }
        return false;
    }

    private function incrementUsage()
    {
        if (Auth::check()) {
            // Double check to prevent admin usage increment
            if ((int) Auth::user()->is_admin > 0) {
                return;
            }

            $userId = Auth::id();
            $today = now()->format('Y-m-d');
            $cacheKey = "ai_chat_limit:{$userId}:{$today}";

            $used = Cache::get($cacheKey, 0);
            Cache::put($cacheKey, $used + 1, now()->endOfDay());

            $this->updateRemainingMessages();
        }
    }

    private function getDatabaseContext()
    {
        try {
            // Ambil semua kategori
            $categories = Category::all()->pluck('name')->toArray();

            // Ambil 5 artikel terbaru dengan relasi
            $recentArticles = Article::with(['user', 'category'])
                ->where('suspended', false)
                ->latest()
                ->take(5)
                ->get()
                ->map(function ($article) {
                    // Ambil snippet konten (500 karakter pertama)
                    $contentSnippet = strip_tags($article->content);
                    $contentSnippet = mb_substr($contentSnippet, 0, 500);

                    return [
                        'title' => $article->title,
                        'category' => $article->category->name ?? 'Uncategorized',
                        'author' => $article->user->name ?? 'Unknown',
                        'content_snippet' => $contentSnippet
                    ];
                });

            // Statistik blog
            $stats = [
                'total_articles' => Article::where('suspended', false)->count(),
                'total_categories' => Category::count(),
                'total_comments' => Comment::count()
            ];

            return [
                'categories' => $categories,
                'recent_articles' => $recentArticles,
                'stats' => $stats
            ];
        } catch (\Exception $e) {
            Log::error('Database Context Error: ' . $e->getMessage());
            return null;
        }
    }

    private function generateResponse($input, $isSuperAdmin = false)
    {
        $apiKey = env('GEMINI_API_KEY');

        if (empty($apiKey)) {
            return 'Fitur AI belum dikonfigurasi. Tambahkan GEMINI_API_KEY di file .env.';
        }

        try {
            // Ambil konteks database
            $dbContext = $this->getDatabaseContext();

            // Build context string
            $contextInfo = "";
            if ($dbContext) {
                $contextInfo = "\n\nINFORMASI BLOG:\n";
                $contextInfo .= "Kategori tersedia: " . implode(', ', $dbContext['categories']) . "\n";
                $contextInfo .= "Total artikel: " . $dbContext['stats']['total_articles'] . "\n";
                $contextInfo .= "Total kategori: " . $dbContext['stats']['total_categories'] . "\n";
                $contextInfo .= "Total komentar: " . $dbContext['stats']['total_comments'] . "\n\n";

                if ($dbContext['recent_articles']->isNotEmpty()) {
                    $contextInfo .= "Artikel terbaru:\n";
                    foreach ($dbContext['recent_articles'] as $article) {
                        $contextInfo .= "- Judul: {$article['title']}\n";
                        $contextInfo .= "  Kategori: {$article['category']}, Penulis: {$article['author']}\n";
                        $contextInfo .= "  Ringkasan: {$article['content_snippet']}...\n\n";
                    }
                }
            }

            // Admin Logic for Commands
            $adminInstructions = "";
            if ($isSuperAdmin) {
                $adminInstructions = "\n\nKHUSUS SUPER ADMIN:\n";
                $adminInstructions .= "KAMU MEMILIKI OTORITAS UNTUK MEMBUAT KATEGORI BARU.\n";
                $adminInstructions .= "Jika user meminta (baik secara eksplisit maupun implisit) untuk membuat/menambahkan kategori baru, contoh:\n";
                $adminInstructions .= "- 'buatkan kategori teknologi'\n";
                $adminInstructions .= "- 'tambah category mesin'\n";
                $adminInstructions .= "- 'bikin kategori baru namanya gaming'\n";
                $adminInstructions .= "  - 'Category Mesin'\n";
                $adminInstructions .= "2. MENGHAPUS KATEGORI:\n";
                $adminInstructions .= "Jika user meminta untuk menghapus kategori, contoh: 'hapus kategori teknologi', 'delete category gaming'.\n";
                $adminInstructions .= "3. MENGUBAH / RENAME KATEGORI:\n";
                $adminInstructions .= "Jika user meminta mengubah nama kategori, contoh:\n";
                $adminInstructions .= "  - 'ganti nama kategori teknologi jadi sains'\n";
                $adminInstructions .= "  - 'ubah kategori A ke B'\n";

                $adminInstructions .= "JANGAN membalas dengan teks biasa atau pertanyaan konfirmasi.\n";
                $adminInstructions .= "Berikan respon HANYA dalam format JSON. Pilih salah satu:\n";
                $adminInstructions .= "{\"action\": \"create_category\", \"name\": \"Nama Kategori\"}\n";
                $adminInstructions .= "{\"action\": \"delete_category\", \"name\": \"Nama Kategori\"}\n";
                $adminInstructions .= "{\"action\": \"update_category\", \"name\": \"Nama Lama\", \"new_name\": \"Nama Baru\"}\n";
                $adminInstructions .= "Ambil nama kategori dari permintaan user. Pastikan diawali huruf kapital.\n";
            }

            // Correct and updated Gemini endpoint
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . $apiKey, [
                        'contents' => [
                            [
                                'parts' => [
                                    [
                                        'text' =>
                                            "Kamu adalah asisten AI serbaguna dan cerdas yang membantu pengunjung blog 'The Archipelago Times'. 
Kamu dapat menjawab berbagai pertanyaan: teknologi, umum, edukasi, hiburan, sains, dan lainnya. 
Jawablah dengan bahasa yang ramah, jelas, akurat, dan mudah dipahami. 
Jika topik sangat sensitif atau berbahaya, alihkan dengan sopan. 
Berikan penjelasan yang bermanfaat, tidak terlalu panjang, dan tetap komunikatif.

INFORMASI KOTAK DEVELOPER BLOG:
email: kanakawicaksono@gmail.com
instagram: @kaaaiiiyy
{$contextInfo}
{$adminInstructions}

Gunakan informasi blog di atas untuk menjawab pertanyaan yang relevan tentang konten blog.
Jika ditanya tentang ringkasan artikel tertentu, gunakan informasi ringkasan yang tersedia di atas.
Jika user meminta ringkasan artikel yang lebih detail, berikan ringkasan berdasarkan snippet konten yang ada.

PENTING - FORMAT JAWABAN:
Saat menyebutkan beberapa artikel/berita, WAJIB gunakan format markdown numbered list seperti ini:
1. **Judul Artikel**: Ringkasan singkat...
2. **Judul Artikel**: Ringkasan singkat...

Pertanyaan pengguna: " . $input
                                    ]
                                ]
                            ]
                        ]
                    ]);

            if ($response->successful()) {
                $data = $response->json();

                // Safe fallback parsing
                return $data['candidates'][0]['content']['parts'][0]['text']
                    ?? $data['candidates'][0]['content']['parts'][0]['content']
                    ?? 'Maaf, saya tidak dapat memproses respon saat ini.';
            } else {
                Log::error('Gemini API Error: ' . $response->body());
                return 'Maaf, terjadi kesalahan saat menghubungi AI.';
            }

        } catch (\Exception $e) {
            Log::error('Gemini Exception: ' . $e->getMessage());
            return 'Maaf, ada gangguan teknis pada asisten AI.';
        }
    }

    public function render()
    {
        return view('livewire.ai-chat');
    }
}
