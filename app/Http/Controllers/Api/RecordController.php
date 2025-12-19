<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Record;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecordController extends Controller
{
    public function index(): JsonResponse
    {
        $records = Record::with(['category:id,name', 'creator:id,name'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($records);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'status' => ['required', 'in:aberto,em progresso,resolvido'],
            'category_id' => ['required', 'exists:categories,id'],
        ]);

        $record = Record::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'status' => $validated['status'],
            'category_id' => $validated['category_id'],
            'created_by' => Auth::id(),
        ]);

        $record->load(['category:id,name', 'creator:id,name']);

        return response()->json($record, 201);
    }

    public function update(Request $request, Record $record): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'status' => ['required', 'in:aberto,em progresso,resolvido'],
            'category_id' => ['required', 'exists:categories,id'],
        ]);

        $record->update($validated);
        $record->load(['category:id,name', 'creator:id,name']);

        return response()->json($record);
    }

    public function destroy(Record $record): JsonResponse
    {
        $record->delete();

        return response()->json(null, 204);
    }
}

