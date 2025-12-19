<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use App\Models\Record;
use App\Models\Category;
use App\Http\Requests\StoreRecordRequest;
use App\Http\Requests\UpdateRecordRequest;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;

class RecordsController extends Controller
{
    public function index(): Response|JsonResponse
    {
        $records = Record::with(['category', 'creator'])
            ->orderBy('created_at', 'desc')
            ->get();

        if (request()->expectsJson()) {
            return response()->json(['data' => $records]);
        }

        return Inertia::render('tickets/index', [
            'records' => $records,
        ]);
    }

    public function create(): Response
    {
        $categories = Category::all();

        return Inertia::render('tickets/create', [
            'categories' => $categories,
        ]);
    }

    public function store(StoreRecordRequest $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validated();

        $record = Record::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'status' => 'aberto',
            'category_id' => $validated['category_id'],
            'created_by' => Auth::id(),
        ]);

        if (request()->expectsJson()) {
            return response()->json($record, 201);
        }

        return redirect()->route('tickets.show', $record);
    }

    public function show(Record $record): Response
    {
        $record->load(['category', 'creator']);

        return Inertia::render('tickets/show', [
            'record' => $record,
        ]);
    }

    public function edit(Record $record): Response
    {
        $categories = Category::all();
        $record->load(['category', 'creator']);

        return Inertia::render('tickets/edit', [
            'record' => $record,
            'categories' => $categories,
        ]);
    }

    public function update(UpdateRecordRequest $request, Record $record): RedirectResponse
    {
        $validated = $request->validated();

        $record->update($validated);

        return redirect()->route('tickets.show', $record);
    }

    public function destroy(Record $record): RedirectResponse
    {
        $record->delete();

        return redirect()->route('tickets.index');
    }
}
