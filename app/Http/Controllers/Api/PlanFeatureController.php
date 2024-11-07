<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PlanFeature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Exception;

class PlanFeatureController extends Controller
{
    public function index()
    {
        return PlanFeature::all();
    }

    public function store(Request $request)
    {
        try {
            $validatedData = Validator::make($request->all(), [
                'plan_id' => 'required|exists:plans,id',
                'feature' => 'required|string|max:255',
            ]);

            $planFeature = PlanFeature::create($validatedData->validate());

            return response()->json(
                $planFeature,
                201
            );
        } catch (Exception $e) {
            // Handle other exceptions
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        }
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
