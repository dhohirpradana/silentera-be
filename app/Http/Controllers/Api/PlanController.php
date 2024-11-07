<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        return Plan::with('category', 'features')->get();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:50',
            'price_weekly' => 'required|numeric',
            'price_monthly' => 'required|numeric',
            'price_annual' => 'nullable|numeric',
            'qty' => 'nullable|numeric',
            'description' => 'nullable|string',
        ]);

        return Plan::create($validatedData);
    }

    public function show($id)
    {
        return Plan::with('category', 'features')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $plan = Plan::findOrFail($id);
        $plan->update($request->only(['category_id', 'name', 'price_weekly', 'price_monthly', 'price_annual', 'description']));

        return $plan;
    }

    public function destroy($id)
    {
        $plan = Plan::findOrFail($id);
        $plan->delete();

        return response()->json(['message' => 'Plan deleted successfully']);
    }
}
