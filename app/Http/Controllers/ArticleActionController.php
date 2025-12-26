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
$existing = $article->likes()->create([
'user_id' => auth()->id(),
'type' => $request->type
]);
}

// Trigger notification if it's a like and not from the author
if ($request->type === 'like' && $article->user_id !== auth()->id()) {
$article->user->notify(new \App\Notifications\ArticleLiked(auth()->user(), $article));
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