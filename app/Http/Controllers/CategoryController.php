<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
  public function index(): Response|JsonResponse
  {
    $categories = Category::with('creator')
      ->orderBy('created_at', 'desc')
      ->get();

    if (request()->expectsJson()) {
      return response()->json(['data' => $categories]);
    }

    return Inertia::render('categories/index', [
      'categories' => $categories,
    ]);
  }

  public function create(): Response
  {
    return Inertia::render('categories/create');
  }

  public function store(StoreCategoryRequest $request): RedirectResponse|JsonResponse
  {
    $validated = $request->validated();

    $category = Category::create([
      'name' => $validated['name'],
      'created_by' => Auth::id(),
    ]);

    if (request()->expectsJson()) {
      return response()->json($category, 201);
    }

    return redirect()->route('categories.show', $category);
  }

  public function show(Category $category): Response
  {
    $category->load(['creator', 'records']);

    return Inertia::render('categories/show', [
      'category' => $category,
    ]);
  }

  public function edit(Category $category): Response
  {
    $category->load('creator');

    return Inertia::render('categories/edit', [
      'category' => $category,
    ]);
  }

  public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
  {
    $validated = $request->validated();

    $category->update($validated);

    return redirect()->route('categories.show', $category);
  }

  public function destroy(Category $category): RedirectResponse
  {
    if ($category->records()->exists()) {
      return redirect()->back()->withErrors([
        'category' => 'Não é possível deletar uma categoria que possui chamados associados.',
      ]);
    }

    $category->delete();

    return redirect()->route('categories.index');
  }
}

