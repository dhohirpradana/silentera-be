<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PlanFeature;
use Illuminate\Http\Request;

class PlanFeatureController extends Controller
{
    public function index()
    {
        return PlanFeature::all();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'feature' => 'required|string|max:255',
        ]);

        return PlanFeature::create($validatedData);
    }

    public function show($id)
    {
        return PlanFeature::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $planFeature = PlanFeature::findOrFail($id);
        $planFeature->update($request->only(['plan_id', 'feature']));

        return $planFeature;
    }

    public function destroy($id)
    {
        $planFeature = PlanFeature::findOrFail($id);
        $planFeature->delete();

        return response()->json(['message' => 'Feature deleted successfully']);
    }
}
