<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
  public function index(): JsonResponse
  {
    $categories = Category::with('creator:id,name')
      ->orderBy('created_at', 'desc')
      ->get();

    return response()->json($categories);
  }

  public function store(Request $request): JsonResponse
  {
    $validated = $request->validate([
      'name' => ['required', 'string', 'max:255'],
    ]);

    $category = Category::create([
      'name' => $validated['name'],
      'created_by' => Auth::id(),
    ]);

    $category->load('creator:id,name');

    return response()->json($category, 201);
  }

  public function update(Request $request, Category $category): JsonResponse
  {
    $validated = $request->validate([
      'name' => ['required', 'string', 'max:255'],
    ]);

    $category->update($validated);
    $category->load('creator:id,name');

    return response()->json($category);
  }

  public function destroy(Category $category): JsonResponse
  {
    if ($category->records()->exists()) {
      throw ValidationException::withMessages([
        'category' => ['Não é possível deletar uma categoria que possui chamados associados.'],
      ]);
    }

    $category->delete();

    return response()->json(null, 204);
  }
}

