<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Symptom;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SymptomController extends Controller
{
    public function index(Request $request)
    {
        $query = Symptom::query()->with('category');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->integer('category_id'));
        }

        return response()->json($query->orderBy('name')->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:100'],
            'category_id' => ['required','integer','exists:categories,id'],
        ]);
        
        $exists = Symptom::where('category_id', $data['category_id'])
            ->where('name', $data['name'])
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Symptom already exists in that category'], 422);
        }

        $symptom = Symptom::create($data);
        return response()->json($symptom, 201);
    }

    public function show(Symptom $symptom)
    {
        return response()->json($symptom->load('category'));
    }

    public function update(Request $request, Symptom $symptom)
    {
        $data = $request->validate([
            'name' => ['required','string','max:100'],
            'category_id' => ['required','integer','exists:categories,id'],
        ]);

        $exists = Symptom::where('category_id', $data['category_id'])
            ->where('name', $data['name'])
            ->where('id', '!=', $symptom->id)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Symptom already exists in that category'], 422);
        }

        $symptom->update($data);
        return response()->json($symptom);
    }

    public function destroy(Symptom $symptom)
    {
        $symptom->delete();
        return response()->json(['message' => 'Deleted']);
    }
}