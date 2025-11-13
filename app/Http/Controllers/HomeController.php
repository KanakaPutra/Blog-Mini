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

        // 🔹 Panggil API Binance (via RapidAPI)
        try {
            $response = Http::withHeaders([
                'x-rapidapi-key' => 'd243538abamsh53f12e467468e89p13d16fjsn9c6bead9a415',
                'x-rapidapi-host' => 'binance43.p.rapidapi.com',
            ])->get('https://binance43.p.rapidapi.com/ticker/24hr', [
                'symbol' => 'BTCUSDT',
            ]);

            $data = $response->json();

            if (isset($data['lastPrice'])) {
                $btcPrice = $data['lastPrice'];
                $btcChange = $data['priceChangePercent'];
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
