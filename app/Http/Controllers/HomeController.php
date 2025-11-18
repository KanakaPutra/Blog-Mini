<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function index()
    {
        // 🔹 Ambil artikel
        $articles = Article::with('category', 'user')->latest()->take(5)->get();

        // 🔹 Ambil data BTC dari API GRATIS
        try {
            $response = Http::get('https://api.alternative.me/v2/ticker/1/');
            $data = $response->json();

            if (isset($data['data']['1'])) {

                $btcPrice  = $data['data']['1']['quotes']['USD']['price'];

                // 🔹 Format perubahan harga jadi max 3 digit desimal
                $rawChange = $data['data']['1']['quotes']['USD']['percent_change_24h'];

                // contoh: dari -3.330907048 → -3.331 (3 digit)
                $btcChange = number_format($rawChange, 3);

            } else {
                $btcPrice = null;
                $btcChange = null;
            }

        } catch (\Exception $e) {
            $btcPrice = null;
            $btcChange = null;
        }

        return view('home', compact('articles', 'btcPrice', 'btcChange'));
    }

    public function show(Article $article)
    {
        $article->load(['comments.user', 'category', 'user']);
        return view('articles.show', compact('article'));
    }
}
