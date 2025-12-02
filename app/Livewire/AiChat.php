<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Category;
use App\Models\Article;
use App\Models\Comment;

class AiChat extends Component
{
    public $isOpen = false;
    public $messages = [];
    public $userMessage = '';
    public $isLoading = false;

    public function mount()
    {
        $this->messages = [
            ['role' => 'assistant', 'content' => 'Halo! Saya siap membantu apa pun yang kamu butuhkan.']
        ];
    }

    public function toggleChat()
    {
        $this->isOpen = !$this->isOpen;
    }

    public function sendMessage()
    {
        if (trim($this->userMessage) === '') {
            return;
        }

        // Add user message
        $this->messages[] = ['role' => 'user', 'content' => $this->userMessage];
        $input = $this->userMessage;
        $this->userMessage = '';
        $this->isLoading = true;

        // Call Gemini API
        $response = $this->generateResponse($input);

        $this->messages[] = ['role' => 'assistant', 'content' => $response];
        $this->isLoading = false;
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

    private function generateResponse($input)
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

            // Correct and updated Gemini endpoint
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . $apiKey, [
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
Gunakan informasi blog di atas untuk menjawab pertanyaan yang relevan tentang konten blog.
Jika ditanya tentang ringkasan artikel tertentu, gunakan informasi ringkasan yang tersedia di atas.
Jika user meminta ringkasan artikel yang lebih detail, berikan ringkasan berdasarkan snippet konten yang ada.

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
