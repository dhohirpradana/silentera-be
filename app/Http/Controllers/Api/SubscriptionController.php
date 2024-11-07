<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Models\Location;
use Carbon\Carbon;
use App\Models\Plan;

class SubscriptionController extends Controller
{
    public function index()
    {
        return Subscription::with('plan', 'location')->get();
    }

    public function store(Request $request)
    {
        // Validate the input data
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'plan_id' => 'required|exists:plans,id',
            'location' => 'required|array', // Expect location data as an array
            'location.region' => 'required|string|max:100',
            'location.province' => 'required|string|max:100',
            'location.lon' => 'required|numeric',
            'location.lat' => 'required|numeric',
            'start_date' => 'required|date',
            'package' => 'required|string|max:100',
            'qty' => 'required|numeric|min:1',
        ]);

        // Extract location data
        $locationData = $validatedData['location'];

        // Create or find the location
        $location = Location::firstOrCreate(
            [
                'region' => $locationData['region'],
                'province' => $locationData['province'],
                'lon' => $locationData['lon'],
                'lat' => $locationData['lat'],
            ],
            $locationData
        );

        $qty = $validatedData['qty'] ?? 1;
        $plan = Plan::findOrFail($validatedData['plan_id']);
        $price = match ($validatedData['package']) {
            'weekly' => $plan->price_weekly,
            'monthly' => $plan->price_monthly,
            'annual' => $plan->price_annual,
            default => 0,
        };
        $grandPrice = $price * $qty;

        $startDate = Carbon::parse($validatedData['start_date']);
        if ($validatedData['package'] === 'monthly') {
            $endDate = $startDate->copy()->addMonths($qty);
        } else if ($validatedData['package'] === 'annual') {
            $endDate = $startDate->copy()->addYears($qty);
        } else if ($validatedData['package'] === 'weekly') {
            $endDate = $startDate->copy()->addWeeks($qty);
        } else {
            return response()->json("Invalid package!", 402);
        }

        $subscription = Subscription::create([
            'user_id' => $validatedData['user_id'],
            'plan_id' => $validatedData['plan_id'],
            'location_id' => $location->id,
            'start_date' => $validatedData['start_date'],
            'package' => $validatedData['package'],
            'qty' => $qty,
            'end_date' => $endDate,
            'price' => $price,
            'grand_price' => $grandPrice
        ]);

        return response()->json($subscription, 201);
    }

    public function show($id)
    {
        return Subscription::with('plan', 'location')->findOrFail($id);
    }

    // public function update(Request $request, $id)
    // {
    //     $subscription = Subscription::findOrFail($id);
    //     $subscription->update($request->only(['user_id', 'plan_id', 'location_id', 'start_date', 'end_date', 'price']));

    //     return $subscription;
    // }

    // public function destroy($id)
    // {
    //     $subscription = Subscription::findOrFail($id);
    //     $subscription->delete();

    //     return response()->json(['message' => 'Subscription deleted successfully']);
    // }
}
