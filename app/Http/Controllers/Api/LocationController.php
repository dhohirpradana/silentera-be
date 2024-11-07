<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        return Location::all();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'region' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'lon' => 'required|numeric',
            'lat' => 'required|numeric',
        ]);

        return Location::create($validatedData);
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
