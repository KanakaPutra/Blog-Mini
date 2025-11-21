namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleActionController extends Controller
{
    public function react(Request $request, Article $article)
    {
        $request->validate([
            'type' => 'required|in:like,dislike'
        ]);

        $existing = $article->likes()
            ->where('user_id', auth()->id())
            ->first();

        if ($existing) {
            $existing->update(['type' => $request->type]);
        } else {
            $article->likes()->create([
                'user_id' => auth()->id(),
                'type' => $request->type
            ]);
        }

        return back()->with('success', 'Aksi berhasil!');
    }

    public function report(Request $request, Article $article)
    {
        $request->validate(['reason' => 'required']);

        $article->reports()->create([
            'user_id' => auth()->id(),
            'reason' => $request->reason,
        ]);

        return back()->with('success', 'Artikel telah dilaporkan.');
    }
}
