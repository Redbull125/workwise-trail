<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * @todo catch TypeErrors
 */
class ArticleController extends Controller
{
    /**
     * returns Article in a Collection
     */
    public function list(): JsonResponse {
        /** @todo columns to model filter for expiration date (all articles without and date lessandeq then expiration date) */
        $article = Article::all(['id', 'title', 'author', 'created_at', 'published']);

        return response()->json(['articles' => $article]);
    }

    /**
     * Adding an article
     * 
     * @todo Errors, Success, validation
     */
    public function add(Request $request): JsonResponse {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:60',
                'author' => 'required|string|max:40',
                'text'  =>  'nullable|string|max:1000',
                'published' => 'nullable|date',
                'expiration' => 'nullable|data'
            ]);
        } catch (ValidationException $validationExeption) {
            return response()->json([
                'success' => false,
                'message' => $validationExeption->getMessage()
            ], 400);
        }
    
        $article = new Article($validated);
        $article->save();

        return response()->json(['success' => true]);
    }

    /**
     * editing an article
     * 
     * @todo, validation, success, error
     */
    public function edit(Request $request, int $articleId): JsonResponse {
        $articleCollection = Article::where('id', $articleId)->get(['*']);
        if($articleCollection->count() === 0) return response()->json(['success' => false, 'message' => 'No Article found.'], 400);
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:60',
                'author' => 'required|string|max:40',
                'text'  =>  'nullable|string|max:1000',
                'published' => 'nullable|date',
                'expiration' => 'nullable|data'
            ]);
            /** @var Article $article */
            $article  = $articleCollection->first();
            $article->update($validated);
        } catch (ValidationException $validationExeption) {
            return response()->json([
                'success' => false,
                'messages' => $validationExeption->validator->errors()->getMessages()
            ], 400);
        }
        return response()->json([
            'success' => true
        ]);
    }

    /**
     * returns a single article by given id
     * 
     * @todo validation, Error
     */
    public function show(int $articleId): JsonResponse {
        $article = Article::where('id', $articleId)->get(['*']);
        if($article->count() === 0) return response()->json(['success' => false, 'message' => 'No Article found.'], 400);
        return response()->json(['article' => $article]);
    }

    /**
     * deletes a single article by given id
     * 
     * @todo validation, Error
     */
    public function delete(int $articleId): JsonResponse {
        $article = Article::where('id', $articleId);
        $article->delete();
        return response()->json(['success' => true]);
    }
}
