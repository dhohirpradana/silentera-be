<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Exception;

class PlanController extends Controller
{
    public function index()
    {
        return Plan::with('category', 'features')->get();
    }

    public function store(Request $request)
    {
        try {
            $validatedData = Validator::make($request->all(), [
                'category_id' => 'required|exists:categories,id',
                'name' => 'required|string|max:50',
                'price_weekly' => 'required|numeric',
                'price_monthly' => 'required|numeric',
                'price_annual' => 'required|numeric',
                'qty' => 'required|numeric',
                'description' => 'required|string',
            ]);

            if ($validatedData->fails()) {
                throw new ValidationException($validatedData);
            }

            $plan = Plan::create($validatedData->validate());

            return response()->json(
                $plan,
                201
            );
        } catch (Exception $e) {
            // Handle other exceptions
            return response()->json([
                // 'message' => 'An error occurred while processing your request.',
                'error' => $e->getMessage(),
            ], 422);
        }
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
