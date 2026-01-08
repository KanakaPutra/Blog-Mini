<?php

use App\Models\User;
use App\Models\Article;
use App\Models\Comment;
use App\Models\Category;

test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

test('article author can pin a comment', function () {
    $author = User::create([
        'name' => 'Author',
        'email' => 'author@test.com',
        'password' => bcrypt('password')
    ]);

    $category = Category::create(['name' => 'Test Category']);

    $article = Article::create([
        'user_id' => $author->id,
        'category_id' => $category->id,
        'title' => 'Test Article',
        'content' => 'Test Content'
    ]);

    $comment = Comment::create([
        'user_id' => User::create(['name' => 'User', 'email' => 'user@test.com', 'password' => bcrypt('password')])->id,
        'article_id' => $article->id,
        'content' => 'This is a comment'
    ]);

    $response = $this->actingAs($author)
        ->post(route('comments.pin', $comment->id));

    $response->assertStatus(200);
    expect($comment->fresh()->is_pinned)->toBeTrue();
});

test('non-author cannot pin a comment', function () {
    $author = User::create([
        'name' => 'Author',
        'email' => 'author2@test.com',
        'password' => bcrypt('password')
    ]);
    $otherUser = User::create([
        'name' => 'Other',
        'email' => 'other@test.com',
        'password' => bcrypt('password')
    ]);

    $category = Category::create(['name' => 'Test Category 2']);

    $article = Article::create([
        'user_id' => $author->id,
        'category_id' => $category->id,
        'title' => 'Test Article',
        'content' => 'Test Content'
    ]);

    $comment = Comment::create([
        'user_id' => User::create(['name' => 'User2', 'email' => 'user2@test.com', 'password' => bcrypt('password')])->id,
        'article_id' => $article->id,
        'content' => 'This is a comment'
    ]);

    $response = $this->actingAs($otherUser)
        ->post(route('comments.pin', $comment->id));

    $response->assertStatus(403);
    expect($comment->fresh()->is_pinned)->toBeFalse();
});

test('pinning a new comment unpins the previous one', function () {
    $author = User::create([
        'name' => 'Author',
        'email' => 'author3@test.com',
        'password' => bcrypt('password')
    ]);

    $category = Category::create(['name' => 'Test Category 3']);

    $article = Article::create([
        'user_id' => $author->id,
        'category_id' => $category->id,
        'title' => 'Test Article',
        'content' => 'Test Content'
    ]);

    $comment1 = Comment::create([
        'user_id' => User::create(['name' => 'User3', 'email' => 'user3@test.com', 'password' => bcrypt('password')])->id,
        'article_id' => $article->id,
        'content' => 'Comment 1',
        'is_pinned' => true
    ]);

    $comment2 = Comment::create([
        'user_id' => User::create(['name' => 'User4', 'email' => 'user4@test.com', 'password' => bcrypt('password')])->id,
        'article_id' => $article->id,
        'content' => 'Comment 2'
    ]);

    $response = $this->actingAs($author)
        ->post(route('comments.pin', $comment2->id));

    $response->assertStatus(200);
    expect($comment1->fresh()->is_pinned)->toBeFalse();
    expect($comment2->fresh()->is_pinned)->toBeTrue();
});
