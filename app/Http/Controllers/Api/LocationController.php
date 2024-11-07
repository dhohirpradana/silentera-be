<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Exception;

class LocationController extends Controller
{
    public function index()
    {
        return Location::all();
    }

    public function store(Request $request)
    {
        try {
            $validatedData = Validator::make($request->all(), [
                'region' => 'required|string|max:100',
                'province' => 'required|string|max:100',
                'lon' => 'required|numeric',
                'lat' => 'required|numeric',
            ]);

            if ($validatedData->fails()) {
                throw new ValidationException($validatedData);
            }

            return Location::create($validatedData->validated());
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
        return Location::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $location = Location::findOrFail($id);
        $location->update($request->only(['region', 'lon', 'lat', 'province']));

        return $location;
    }

    public function destroy($id)
    {
        $location = Location::findOrFail($id);
        $location->delete();

        return response()->json(['message' => 'Location deleted successfully']);
    }
}
