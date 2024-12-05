<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Article::all();
        return response()->json($articles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'detail' => 'required',
        ]);
        $article = Auth::user()->articles()->create($data);
        return response()->json($article);
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        return response()->json($article);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        if (Gate::allows('update', [Auth::user(), $article])) {
            $data = $request->validate([
                'detail' => 'required',
            ]);
            $article->update($data);
            return response()->json($article);
        } else {
            return response()->json(['message' => 'You are not authorized to update this article'], 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        if (Gate::allows('delete', [Auth::user(), $article])) {
            $article->delete();
            return response()->json(['message' => 'Article deleted successfully'], 200);
        } else {
            return response()->json(['message' => 'You are not authorized to delete this article'], 403);
        }
    }
}
